<?php

namespace App\Http\Controllers;

use App\Models\DonationAnalytic;
use App\Services\LigdicashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LigdicashController extends Controller
{
    protected LigdicashService $ligdicash;

    public function __construct(LigdicashService $ligdicashService)
    {
        $this->ligdicash = $ligdicashService;
    }

    /**
     * Initiate a mobile money payment (sends OTP to customer)
     */
    public function initiate(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|string',
            'operator_code' => 'required|string',
            'country_code' => 'required|string|size:2',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'campaign_id' => 'nullable|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Get country config
        $countryConfig = $this->ligdicash->getCountryConfig($data['country_code']);
        if (!$countryConfig) {
            return response()->json([
                'success' => false,
                'error' => 'Country not supported',
            ], 400);
        }

        // Convert amount to local currency
        $currency = $countryConfig['currency'];
        $localAmount = $this->ligdicash->convertCurrency($data['amount'], $currency);

        // Prepare payment data
        $paymentData = [
            'amount' => $localAmount,
            'currency' => $currency,
            'phone' => $data['phone'],
            'operator_code' => $data['operator_code'],
            'country_code' => $data['country_code'],
        ];

        // Initiate payment with Ligdicash (sends OTP)
        $result = $this->ligdicash->initiatePayment($paymentData);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to initiate payment',
            ], 500);
        }

        // Create pending donation record
        try {
            $donation = DonationAnalytic::create([
                'donor_name' => $data['donor_name'] ?? 'Anonymous',
                'email' => $data['donor_email'] ?? null,
                'phone' => $data['phone'],
                'amount' => $localAmount,
                'currency' => $currency,
                'donation_type' => 'one_time',
                'campaign_id' => $data['campaign_id'] ?? null,
                'payment_method' => 'mobile_money',
                'payment_reference' => null, // Will be set after confirmation
                'transaction_id' => $result['invoice_id'],
                'status' => 'pending',
                'donor_metadata' => [
                    'operator_code' => $data['operator_code'],
                    'country_code' => $data['country_code'],
                    'original_amount_usd' => $data['amount'],
                    'payment_data' => $result['data'] ?? [], // Store for confirmation
                ],
            ]);

            Log::info('Mobile money donation initiated (Ligdicash)', [
                'donation_id' => $donation->id,
                'invoice_id' => $result['invoice_id'],
                'amount' => $localAmount,
                'currency' => $currency,
            ]);

            return response()->json([
                'success' => true,
                'invoice_id' => $result['invoice_id'],
                'donation_id' => $donation->id,
                'status' => 'pending',
                'message' => 'OTP sent to your phone. Please enter it to confirm payment.',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create donation record', [
                'error' => $e->getMessage(),
                'invoice_id' => $result['invoice_id'] ?? null,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create donation record',
            ], 500);
        }
    }

    /**
     * Confirm payment with OTP
     */
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
            'donation_id' => 'required|exists:donation_analytics,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Find donation
        $donation = DonationAnalytic::find($data['donation_id']);

        // Get payment data from donation metadata
        $paymentData = [
            'phone' => $donation->phone,
            'amount' => $donation->amount,
            'currency' => $donation->currency,
            'country_code' => $donation->donor_metadata['country_code'] ?? 'BF',
            'donor_name' => $donation->donor_name,
            'donor_email' => $donation->email,
            'invoice_id' => $donation->transaction_id,
            'donation_id' => $donation->id,
            'description' => 'Donation to GiveMeALift',
        ];

        // Confirm payment with Ligdicash
        $result = $this->ligdicash->confirmPayment($data['otp'], $paymentData);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Invalid OTP or payment confirmation failed',
            ], 400);
        }

        // Update donation status and store token
        $donation->update([
            'status' => 'completed',
            'payment_reference' => $result['token'] ?? null,
            'donor_metadata' => array_merge($donation->donor_metadata ?? [], [
                'ligdicash_status' => $result['status'],
                'ligdicash_token' => $result['token'] ?? null,
                'confirmed_at' => now()->toIso8601String(),
            ]),
        ]);

        Log::info('Mobile money donation confirmed (Ligdicash)', [
            'donation_id' => $donation->id,
            'token' => $result['token'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'status' => 'completed',
            'token' => $result['token'] ?? null,
            'donation' => [
                'id' => $donation->id,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'status' => 'completed',
            ],
        ]);
    }

    /**
     * Check the status of a payment
     */
    public function status(string $token)
    {
        // Find donation by payment_reference (token)
        $donation = DonationAnalytic::where('payment_reference', $token)->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'error' => 'Donation not found',
            ], 404);
        }

        // Get status from Ligdicash
        $result = $this->ligdicash->getPaymentStatus($token);

        if ($result['success']) {
            $ligdicashStatus = $result['status'];
            $internalStatus = $this->ligdicash->mapStatus($ligdicashStatus);

            // Update donation if status changed
            if ($donation->status !== $internalStatus) {
                $donation->update([
                    'status' => $internalStatus,
                    'donor_metadata' => array_merge($donation->donor_metadata ?? [], [
                        'ligdicash_status' => $ligdicashStatus,
                        'last_status_check' => now()->toIso8601String(),
                    ]),
                ]);

                Log::info('Donation status updated (Ligdicash)', [
                    'donation_id' => $donation->id,
                    'old_status' => $donation->status,
                    'new_status' => $internalStatus,
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => $internalStatus,
                'ligdicash_status' => $ligdicashStatus,
                'donation' => [
                    'id' => $donation->id,
                    'amount' => $donation->amount,
                    'currency' => $donation->currency,
                    'status' => $internalStatus,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Failed to check payment status',
        ], 500);
    }

    /**
     * Get available mobile money operators for a country
     */
    public function operators(Request $request)
    {
        $countryCode = $request->input('country', config('ligdicash.default_country'));
        $operators = $this->ligdicash->getOperators($countryCode);
        $countryConfig = $this->ligdicash->getCountryConfig($countryCode);

        return response()->json([
            'success' => true,
            'country' => $countryConfig,
            'operators' => $operators,
        ]);
    }
}
