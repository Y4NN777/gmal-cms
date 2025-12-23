<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LigdicashService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $apiToken;
    protected string $callbackUrl;
    protected int $timeout;

    public function __construct()
    {
        $mode = config('ligdicash.mode', 'test');
        $this->apiUrl = config("ligdicash.api_url.{$mode}");
        $this->apiKey = config('ligdicash.api_key');
        $this->apiToken = config('ligdicash.api_token');
        $this->callbackUrl = config('ligdicash.callback_url');
        $this->timeout = config('ligdicash.timeout', 30);

        if (empty($this->apiKey) || empty($this->apiToken)) {
            Log::warning('Ligdicash API credentials are not configured');
        }
    }

    /**
     * Initiate a mobile money payment (sends OTP to customer)
     * Step 1: GET /pay/v02/debitotp/{phone}/{amount}
     *
     * @param array $data
     * @return array|null
     */
    public function initiatePayment(array $data): ?array
    {
        try {
            $phone = $this->formatPhoneNumber($data['phone'], $data['country_code'] ?? 'BF');
            $amount = (int) $data['amount']; // Ligdicash uses integers for XOF
            
            // Generate unique invoice ID
            $invoiceId = $this->generateInvoiceId();
            
            Log::info('Ligdicash: Initiating payment (debit OTP)', [
                'invoice_id' => $invoiceId,
                'phone' => $phone,
                'amount' => $amount,
            ]);

            // Step 1: Initiate OTP debit
            $response = Http::withHeaders([
                'Apikey' => $this->apiKey,
                'Authorization' => "Bearer {$this->apiToken}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->get("{$this->apiUrl}/pay/v02/debitotp/{$phone}/{$amount}");

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('Ligdicash: OTP sent successfully', [
                    'invoice_id' => $invoiceId,
                    'response' => $result,
                ]);
                
                return [
                    'success' => true,
                    'invoice_id' => $invoiceId,
                    'phone' => $phone,
                    'amount' => $amount,
                    'status' => 'pending',
                    'message' => $result['message'] ?? 'OTP sent to customer phone',
                    'data' => $data, // Store original data for confirmation step
                ];
            }

            Log::error('Ligdicash: Payment initiation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Failed to initiate payment',
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('Ligdicash: Exception during payment initiation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm payment with OTP
     * Step 2: POST /pay/v02/debitwallet/withotp
     *
     * @param string $otp
     * @param array $paymentData Original payment data from initiation
     * @return array|null
     */
    public function confirmPayment(string $otp, array $paymentData): ?array
    {
        try {
            $phone = $this->formatPhoneNumber($paymentData['phone'], $paymentData['country_code'] ?? 'BF');
            $amount = (int) $paymentData['amount'];
            
            Log::info('Ligdicash: Confirming payment with OTP', [
                'phone' => $phone,
                'amount' => $amount,
            ]);

            // Build invoice payload according to Ligdicash docs
            $payload = [
                'commande' => [
                    'invoice' => [
                        'items' => [
                            [
                                'name' => 'Donation',
                                'description' => $paymentData['description'] ?? 'Donation to GiveMeALift',
                                'quantity' => 1,
                                'unit_price' => $amount,
                                'total_price' => $amount,
                            ],
                        ],
                        'total_amount' => $amount,
                        'devise' => $paymentData['currency'] ?? 'XOF',
                        'description' => $paymentData['description'] ?? 'Donation to GiveMeALift',
                        'customer' => $phone,
                        'customer_firstname' => $paymentData['donor_name'] ?? 'Anonymous',
                        'customer_lastname' => '',
                        'customer_email' => $paymentData['donor_email'] ?? '',
                        'external_id' => $paymentData['invoice_id'] ?? '',
                        'otp' => $otp,
                    ],
                    'store' => [
                        'name' => 'GiveMeALift',
                        'website_url' => config('app.url'),
                    ],
                    'actions' => [
                        'cancel_url' => '',
                        'return_url' => '',
                        'callback_url' => $this->callbackUrl,
                    ],
                    'custom_data' => [
                        'invoice_id' => $paymentData['invoice_id'] ?? '',
                        'donation_id' => $paymentData['donation_id'] ?? '',
                    ],
                ],
            ];

            $response = Http::withHeaders([
                'Apikey' => $this->apiKey,
                'Authorization' => "Bearer {$this->apiToken}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->post("{$this->apiUrl}/pay/v02/debitwallet/withotp", $payload);

            if ($response->successful()) {
                $result = $response->json();
                
                // Check response_code
                if (($result['response_code'] ?? '') === '00') {
                    Log::info('Ligdicash: Payment confirmed successfully', [
                        'token' => $result['token'] ?? null,
                    ]);
                    
                    return [
                        'success' => true,
                        'token' => $result['token'] ?? null,
                        'status' => 'completed',
                        'data' => $result,
                    ];
                }
                
                Log::warning('Ligdicash: Payment confirmation returned non-success code', [
                    'response_code' => $result['response_code'] ?? 'unknown',
                    'response' => $result,
                ]);
                
                return [
                    'success' => false,
                    'error' => $result['response_text'] ?? 'Payment confirmation failed',
                    'data' => $result,
                ];
            }

            Log::error('Ligdicash: Payment confirmation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to confirm payment',
            ];

        } catch (\Exception $e) {
            Log::error('Ligdicash: Exception while confirming payment', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check the status of a payment using the invoice token
     * GET /pay/v01/redirect/checkout-invoice/confirm/?invoiceToken={token}
     *
     * @param string $token
     * @return array|null
     */
    public function getPaymentStatus(string $token): ?array
    {
        try {
            Log::info('Ligdicash: Checking payment status', ['token' => $token]);

            $response = Http::withHeaders([
                'Apikey' => $this->apiKey,
                'Authorization' => "Bearer {$this->apiToken}",
            ])
            ->timeout($this->timeout)
            ->post("{$this->apiUrl}/pay/v01/redirect/checkout-invoice/confirm/", [
                'invoiceToken' => $token,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // Check if payment is completed
                $isCompleted = ($result['response_code'] ?? '') === '00' && 
                               ($result['status'] ?? '') === 'completed';
                
                return [
                    'success' => true,
                    'status' => $isCompleted ? 'completed' : 'pending',
                    'ligdicash_status' => $result['status'] ?? 'unknown',
                    'data' => $result,
                ];
            }

            Log::error('Ligdicash: Failed to get payment status', [
                'token' => $token,
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get payment status',
            ];

        } catch (\Exception $e) {
            Log::error('Ligdicash: Exception while checking payment status', [
                'token' => $token,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate a unique invoice ID
     *
     * @return string
     */
    protected function generateInvoiceId(): string
    {
        return 'INV-' . strtoupper(Str::random(15)) . '-' . time();
    }

    /**
     * Format phone number for Ligdicash API
     * Ligdicash expects: country_code + number (e.g., 22670123456 for Burkina Faso)
     *
     * @param string $phone
     * @param string $countryCode
     * @return string
     */
    protected function formatPhoneNumber(string $phone, string $countryCode = 'BF'): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Get country phone code from config (without +)
        $phoneCode = str_replace('+', '', config("ligdicash.countries.{$countryCode}.phone_code", '226'));
        
        // If phone doesn't start with country code, add it
        if (!str_starts_with($phone, $phoneCode)) {
            // Remove leading zeros
            $phone = ltrim($phone, '0');
            $phone = $phoneCode . $phone;
        }
        
        return $phone;
    }

    /**
     * Map Ligdicash status to our internal status
     *
     * @param string $ligdicashStatus
     * @return string
     */
    public function mapStatus(string $ligdicashStatus): string
    {
        return config("ligdicash.status_mapping.{$ligdicashStatus}", 'pending');
    }

    /**
     * Get supported operators for a country
     *
     * @param string $countryCode
     * @return array
     */
    public function getOperators(string $countryCode = 'BF'): array
    {
        return config("ligdicash.countries.{$countryCode}.operators", []);
    }

    /**
     * Get country configuration
     *
     * @param string $countryCode
     * @return array|null
     */
    public function getCountryConfig(string $countryCode): ?array
    {
        return config("ligdicash.countries.{$countryCode}");
    }

    /**
     * Convert USD to local currency
     *
     * @param float $usdAmount
     * @param string $currency
     * @return float
     */
    public function convertCurrency(float $usdAmount, string $currency = 'XOF'): float
    {
        $rate = config("ligdicash.exchange_rates.{$currency}", 600);
        return round($usdAmount * $rate, 0); // XOF doesn't use decimals
    }
}
