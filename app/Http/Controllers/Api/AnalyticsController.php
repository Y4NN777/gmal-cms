<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorAnalytic;
use App\Models\DonationAnalytic;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get visitor analytics with time range filtering.
     */
    public function visitors(Request $request): JsonResponse
    {
        $range = $request->get('range', 'week'); // day, week, month, year

        $query = VisitorAnalytic::query();

        switch ($range) {
            case 'day':
                $query->today();
                break;
            case 'week':
                $query->thisWeek();
                break;
            case 'month':
                $query->thisMonth();
                break;
            case 'year':
                $query->thisYear();
                break;
        }

        $stats = [
            'total_visits' => $query->count(),
            'unique_visitors' => $query->distinct('session_id')->count('session_id'),
            'average_duration' => $query->avg('visit_duration'),
            'bounce_rate' => $query->avg('bounce_rate'),
            'by_device' => VisitorAnalytic::byDevice()
                ->selectRaw('device_type, COUNT(*) as count')
                ->groupBy('device_type')
                ->pluck('count', 'device_type'),
            'by_country' => VisitorAnalytic::thisMonth()
                ->selectRaw('country_code, COUNT(*) as count')
                ->whereNotNull('country_code')
                ->groupBy('country_code')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'country_code'),
            'top_pages' => VisitorAnalytic::thisMonth()
                ->selectRaw('page_url, COUNT(*) as count')
                ->groupBy('page_url')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'meta' => [
                'range' => $range,
                'generated_at' => now()->toIso8601String(),
            ],
            'message' => 'Visitor analytics retrieved successfully'
        ]);
    }

    /**
     * Get donor/donation analytics.
     */
    public function donors(Request $request): JsonResponse
    {
        $range = $request->get('range', 'month');

        $query = DonationAnalytic::completed();

        switch ($range) {
            case 'month':
                $query->thisMonth();
                break;
            case 'year':
                $query->thisYear();
                break;
        }

        $stats = [
            'total_donations' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'average_donation' => $query->avg('amount'),
            'by_type' => DonationAnalytic::completed()
                ->selectRaw('donation_type, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('donation_type')
                ->get()
                ->mapWithKeys(fn($item) => [
                    $item->donation_type => [
                        'count' => $item->count,
                        'total' => $item->total,
                    ]
                ]),
            'recurring_donors' => DonationAnalytic::recurring()->count(),
            'top_campaigns' => DonationAnalytic::completed()
                ->with('campaign:id,title')
                ->selectRaw('campaign_id, COUNT(*) as donations, SUM(amount) as total')
                ->whereNotNull('campaign_id')
                ->groupBy('campaign_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
            'monthly_trend' => DonationAnalytic::completed()
                ->selectRaw('DATE_TRUNC(\'month\', created_at) as month, COUNT(*) as count, SUM(amount) as total')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'meta' => [
                'range' => $range,
                'generated_at' => now()->toIso8601String(),
            ],
            'message' => 'Donor analytics retrieved successfully'
        ]);
    }

    /**
     * Get dashboard summary with key metrics.
     */
    public function dashboard(): JsonResponse
    {
        $stats = [
            'overview' => [
                'total_events' => Event::count(),
                'published_events' => Event::where('status', 'published')->count(),
                'upcoming_events' => Event::published()->upcoming()->count(),
                'total_donations' => DonationAnalytic::completed()->count(),
                'donation_amount_total' => DonationAnalytic::completed()->sum('amount'),
                'visitors_today' => VisitorAnalytic::today()->count(),
                'visitors_month' => VisitorAnalytic::thisMonth()->count(),
            ],
            'recent_activity' => [
                'donations_this_week' => DonationAnalytic::completed()
                    ->where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->count(),
                'donations_amount_this_week' => DonationAnalytic::completed()
                    ->where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->sum('amount'),
            ],
            'quick_stats' => [
                'average_donation' => DonationAnalytic::completed()->avg('amount'),
                'recurring_donors_count' => DonationAnalytic::recurring()
                    ->distinct('email')
                    ->count('email'),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
            ],
            'message' => 'Dashboard summary retrieved successfully'
        ]);
    }

    /**
     * Generate custom reports with date range.
     */
    public function reports(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after:from_date',
            'type' => 'required|in:visitors,donations,events,combined',
        ]);

        $fromDate = Carbon::parse($validated['from_date']);
        $toDate = Carbon::parse($validated['to_date']);

        $report = [];

        switch ($validated['type']) {
            case 'visitors':
                $report = [
                    'total_visits' => VisitorAnalytic::whereBetween('visited_at', [$fromDate, $toDate])->count(),
                    'unique_visitors' => VisitorAnalytic::whereBetween('visited_at', [$fromDate, $toDate])
                        ->distinct('session_id')->count('session_id'),
                    'by_device' => VisitorAnalytic::whereBetween('visited_at', [$fromDate, $toDate])
                        ->selectRaw('device_type, COUNT(*) as count')
                        ->groupBy('device_type')
                        ->pluck('count', 'device_type'),
                ];
                break;

            case 'donations':
                $report = [
                    'total_count' => DonationAnalytic::whereBetween('created_at', [$fromDate, $toDate])->count(),
                    'total_amount' => DonationAnalytic::whereBetween('created_at', [$fromDate, $toDate])->sum('amount'),
                    'by_type' => DonationAnalytic::whereBetween('created_at', [$fromDate, $toDate])
                        ->selectRaw('donation_type, COUNT(*) as count, SUM(amount) as total')
                        ->groupBy('donation_type')
                        ->get(),
                ];
                break;

            case 'events':
                $report = [
                    'total_events' => Event::whereBetween('event_date', [$fromDate, $toDate])->count(),
                    'by_status' => Event::whereBetween('event_date', [$fromDate, $toDate])
                        ->selectRaw('status, COUNT(*) as count')
                        ->groupBy('status')
                        ->pluck('count', 'status'),
                ];
                break;

            case 'combined':
                $report = [
                    'visitors' => VisitorAnalytic::whereBetween('visited_at', [$fromDate, $toDate])->count(),
                    'donations' => DonationAnalytic::whereBetween('created_at', [$fromDate, $toDate])->count(),
                    'donation_amount' => DonationAnalytic::whereBetween('created_at', [$fromDate, $toDate])->sum('amount'),
                    'events' => Event::whereBetween('event_date', [$fromDate, $toDate])->count(),
                ];
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $report,
            'meta' => [
                'from_date' => $fromDate->toDateString(),
                'to_date' => $toDate->toDateString(),
                'type' => $validated['type'],
                'generated_at' => now()->toIso8601String(),
            ],
            'message' => 'Report generated successfully'
        ]);
    }
}
