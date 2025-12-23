<?php

namespace App\Http\Controllers;

use App\Models\DonationAnalytic;
use App\Services\LigdicashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LigdicashWebhookController extends Controller
{
    protected LigdicashService $ligdicash;

    public function __construct(LigdicashService $ligdicashService)
    {
        $this->ligdicash = $ligdicashService;
    }

    /**
     * Handle Ligdicash webhook callbacks
     */
    public function handle(Request $request)
    {
        // Log incoming webhook
        Log::info('Ligdicash webhook received', $request->all());

        try {
            $data = $request->all();

            // Ligdicash sends transaction details in callback
            $token = $data['token'] ?? null;
            $status = $data['status'] ?? null;

            if (!$token) {
                Log::error('Ligdicash webhook: Missing token');
                return response('Invalid payload', 400);
            }

            $this->processPayment($data);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Ligdicash webhook: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('ERROR', 500);
        }
    }

    /**
     * Process a payment update from callback
     */
    protected function processPayment(array $data): void
    {
        $token = $data['token'] ?? null;
        $status = $data['status'] ?? 'unknown';

        if (!$token) {
            Log::warning('Ligdicash webhook: Missing token', $data);
            return;
        }

        // Find donation by payment_reference (token)
        $donation = DonationAnalytic::where('payment_reference', $token)->first();

        if (!$donation) {
            Log::warning('Ligdicash webhook: Donation not found', [
                'token' => $token,
            ]);
            return;
        }

        // Map Ligdicash status to internal status
        $internalStatus = $this->ligdicash->mapStatus($status);

        // Check if status changed
        if ($donation->status === $internalStatus) {
            Log::info('Ligdicash webhook: Status unchanged', [
                'token' => $token,
                'status' => $status,
            ]);
            return;
        }

        // Update donation
        $donation->update([
            'status' => $internalStatus,
            'donor_metadata' => array_merge($donation->donor_metadata ?? [], [
                'ligdicash_status' => $status,
                'webhook_received_at' => now()->toIso8601String(),
                'callback_data' => $data,
            ]),
        ]);

        Log::info('Ligdicash webhook: Donation updated', [
            'donation_id' => $donation->id,
            'token' => $token,
            'old_status' => $donation->status,
            'new_status' => $internalStatus,
            'ligdicash_status' => $status,
        ]);

        // Send thank you email if completed
        if ($internalStatus === 'completed' && $donation->email) {
            $this->sendThankYouEmail($donation);
        }
    }

    /**
     * Send thank you email to donor
     */
    protected function sendThankYouEmail(DonationAnalytic $donation): void
    {
        try {
            // TODO: Implement email sending using Laravel Mail
            // Mail::to($donation->email)->send(new DonationThankYou($donation));
            
            Log::info('Thank you email queued (Ligdicash)', [
                'donation_id' => $donation->id,
                'email' => $donation->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send thank you email', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
