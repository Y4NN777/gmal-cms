<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationAnalytic;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonationController extends Controller
{
    /**
     * Display a listing of donations.
     */
    public function index(Request $request)
    {
        $query = DonationAnalytic::with('campaign')
            ->orderBy('created_at', 'desc');

        // Optional search by donor name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $donations = $query->paginate(30)
            ->through(fn($donation) => [
                'id' => $donation->id,
                'donor_name' => $donation->donor_name,
                'email' => $donation->email,
                'phone' => $donation->phone,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'campaign' => $donation->campaign ? [
                    'id' => $donation->campaign->id,
                    'title' => $donation->campaign->title,
                ] : null,
                'payment_method' => $donation->payment_method,
                'payment_reference' => $donation->payment_reference,
                'status' => $donation->status,
                'donation_type' => $donation->donation_type,
                'donor_metadata' => $donation->donor_metadata,
                'created_at' => $donation->created_at,
                'is_new' => $donation->created_at->greaterThan(now()->subDay()),
            ]);

        // Statistics for current month
        $stats = [
            'this_month_count' => DonationAnalytic::thisMonth()->count(),
            'this_month_amount' => DonationAnalytic::thisMonth()->sum('amount'),
            'average_donation' => DonationAnalytic::thisMonth()->count() > 0
                ? DonationAnalytic::thisMonth()->avg('amount')
                : 0,
            'total_all_time' => DonationAnalytic::completed()->sum('amount'),
        ];

        return Inertia::render('Admin/Donations/Index', [
            'donations' => $donations,
            'stats' => $stats,
            'filters' => [
                'search' => $request->input('search'),
            ],
        ]);
    }
}
