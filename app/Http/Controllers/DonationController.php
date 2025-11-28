<?php

namespace App\Http\Controllers;

use App\Models\DonationAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class DonationController extends Controller
{
    /**
     * PayPal IPN (Instant Payment Notification) webhook endpoint
     * This endpoint receives notifications from PayPal after a donation is completed
     */
    public function handlePayPalIPN(Request $request)
    {
        // Log incoming IPN for debugging
        Log::info('PayPal IPN Received', $request->all());

        // Step 1: Read POST data from PayPal
        $ipnData = $request->all();

        // Step 2: Validate IPN with PayPal
        if (!$this->validateIPN($ipnData)) {
            Log::error('PayPal IPN Validation Failed', $ipnData);
            return response('INVALID', 400);
        }

        // Step 3: Check payment status
        $paymentStatus = $ipnData['payment_status'] ?? '';
        
        if ($paymentStatus !== 'Completed') {
            Log::info('PayPal IPN: Payment not completed', [
                'status' => $paymentStatus,
                'txn_id' => $ipnData['txn_id'] ?? 'N/A'
            ]);
            return response('OK', 200);
        }

        // Step 4: Extract donation data
        $transactionId = $ipnData['txn_id'] ?? null;
        $amount = floatval($ipnData['mc_gross'] ?? 0);
        $currency = $ipnData['mc_currency'] ?? 'USD';
        $payerEmail = $ipnData['payer_email'] ?? null;
        $payerName = $ipnData['first_name'] . ' ' . $ipnData['last_name'];
        
        // Extract custom data if provided
        $customData = json_decode($ipnData['custom'] ?? '{}', true);

        // Step 5: Check if transaction already recorded (prevent duplicates)
        $existingDonation = DonationAnalytic::where('transaction_id', $transactionId)->first();
        
        if ($existingDonation) {
            Log::info('PayPal IPN: Duplicate transaction ignored', [
                'txn_id' => $transactionId
            ]);
            return response('OK', 200);
        }

        // Step 6: Create donation record
        try {
            DonationAnalytic::create([
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'currency' => $currency,
                'donor_name' => $customData['donor_name'] ?? $payerName,
                'donor_email' => $customData['donor_email'] ?? $payerEmail,
                'payment_method' => 'paypal',
                'status' => 'completed',
                'metadata' => json_encode([
                    'payer_id' => $ipnData['payer_id'] ?? null,
                    'payment_date' => $ipnData['payment_date'] ?? now(),
                    'item_name' => $ipnData['item_name'] ?? 'Donation',
                    'custom_data' => $customData,
                ]),
            ]);

            Log::info('PayPal IPN: Donation recorded successfully', [
                'txn_id' => $transactionId,
                'amount' => $amount,
                'donor' => $payerName
            ]);

            // TODO: Send thank you email to donor
            // $this->sendThankYouEmail($payerEmail, $amount);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('PayPal IPN: Failed to record donation', [
                'error' => $e->getMessage(),
                'txn_id' => $transactionId
            ]);
            return response('ERROR', 500);
        }
    }

    /**
     * Validate IPN with PayPal to ensure it's legitimate
     */
    private function validateIPN(array $ipnData): bool
    {
        // Add cmd parameter for validation
        $validationData = array_merge(['cmd' => '_notify-validate'], $ipnData);

        // Determine PayPal URL (sandbox vs live)
        $paypalUrl = config('services.paypal.mode') === 'live'
            ? 'https://ipnpb.paypal.com/cgi-bin/webscr'
            : 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

        try {
            // Post back to PayPal for validation
            $response = Http::asForm()->post($paypalUrl, $validationData);

            $verificationResponse = $response->body();

            // Log validation response
            Log::info('PayPal IPN Validation Response', [
                'response' => $verificationResponse
            ]);

            // Check if PayPal verified the IPN
            return str_contains($verificationResponse, 'VERIFIED');

        } catch (\Exception $e) {
            Log::error('PayPal IPN Validation Exception', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Optional: Send thank you email to donor
     */
    private function sendThankYouEmail(string $email, float $amount): void
    {
        // TODO: Implement email sending using Laravel Mail
        // Mail::to($email)->send(new DonationThankYou($amount));
    }
}
