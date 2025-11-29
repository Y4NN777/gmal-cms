<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Testimonial;
use App\Models\DonationAnalytic;
use App\Models\VisitorAnalytic;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with analytics.
     */
    public function index()
    {
        // Overview Statistics
        $stats = [
            'total_events' => Event::count(),
            'published_events' => Event::where('status', 'published')->count(),
            'upcoming_events' => Event::published()->upcoming()->count(),
            'total_donations' => DonationAnalytic::completed()->count(),
            'total_donation_amount' => DonationAnalytic::completed()->sum('amount'),
            'pending_testimonials' => Testimonial::where('status', 'pending')->count(),
            'approved_testimonials' => Testimonial::approved()->count(),
            'unread_messages' => ContactMessage::unread()->count(),
        ];

        // Recent Events (last 5)
        $recentEvents = Event::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'status' => $event->status,
                'event_date' => $event->event_date,
                'category' => $event->category ? [
                    'id' => $event->category->id,
                    'name' => $event->category->name,
                    'slug' => $event->category->slug,
                    'color' => $event->category->color,
                ] : null,
            ]);

        // Pending Testimonials (last 5)
        $pendingTestimonials = Testimonial::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($testimonial) => [
                'id' => $testimonial->id,
                'name' => $testimonial->name,
                'rating' => $testimonial->rating,
                'content' => \Illuminate\Support\Str::limit($testimonial->content, 100),
                'created_at' => $testimonial->created_at,
            ]);

        // Recent Donations (last 10)
        $recentDonations = DonationAnalytic::with('campaign')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(fn($donation) => [
                'donor_name' => $donation->donor_name,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'campaign' => $donation->campaign?->title,
                'status' => $donation->status,
                'created_at' => $donation->created_at,
            ]);

        // Visitor Analytics - Last 7 Days
        $visitorStats = [
            'today' => VisitorAnalytic::today()->count(),
            'this_week' => VisitorAnalytic::thisWeek()->count(),
            'this_month' => VisitorAnalytic::thisMonth()->count(),
            'by_device' => VisitorAnalytic::thisMonth()
                ->selectRaw('device_type, COUNT(*) as count')
                ->groupBy('device_type')
                ->pluck('count', 'device_type'),
        ];

        // Donation Analytics - Current Month
        $donationStats = [
            'this_month_count' => DonationAnalytic::thisMonth()->count(),
            'this_month_amount' => DonationAnalytic::thisMonth()->sum('amount'),
            'by_type' => DonationAnalytic::thisMonth()
                ->selectRaw('donation_type, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('donation_type')
                ->get()
                ->mapWithKeys(fn($item) => [
                    $item->donation_type => [
                        'count' => $item->count,
                        'total' => $item->total,
                    ]
                ]),
        ];

        // Unread Messages
        $unreadMessages = ContactMessage::unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($message) => [
                'id' => $message->id,
                'name' => $message->name,
                'subject' => $message->subject,
                'priority' => $message->priority,
                'created_at' => $message->created_at,
            ]);

        return Inertia::render('Admin/Dashboard/Index', [
            'user' => request()->user(),
            'stats' => $stats,
            'recentEvents' => $recentEvents,
            'pendingTestimonials' => $pendingTestimonials,
            'recentDonations' => $recentDonations,
            'visitorStats' => $visitorStats,
            'donationStats' => $donationStats,
            'unreadMessages' => $unreadMessages,
        ]);
    }
}
