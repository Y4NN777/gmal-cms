<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Testimonial;
use App\Models\DonationAnalytic;
use App\Models\VisitorAnalytic;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get featured events (upcoming, published, featured)
        $featuredEvents = Event::with(['category', 'featuredImage'])
            ->published()
            ->upcoming()
            ->where('is_featured', true)
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'slug' => $event->slug,
                    'excerpt' => $event->excerpt,
                    'event_date' => $event->event_date,
                    'featured_image' => $event->featuredImage ? [
                        'url' => $event->featuredImage->url,
                        'alt' => $event->featuredImage->alt_text
                    ] : null,
                    'category' => $event->category ? [
                        'name' => $event->category->name,
                        'slug' => $event->category->slug
                    ] : null
                ];
            });

        // Get approved and featured testimonials
        $testimonials = Testimonial::with('avatar')
            ->approved()
            ->featured()
            ->ordered()
            ->take(3)
            ->get()
            ->map(function ($testimonial) {
                return [
                    'id' => $testimonial->id,
                    'name' => $testimonial->name,
                    'position' => $testimonial->position,
                    'organization' => $testimonial->organization,
                    'content' => $testimonial->content,
                    'rating' => $testimonial->rating,
                    'avatar' => $testimonial->avatar ? [
                        'url' => $testimonial->avatar->url,
                        'alt' => $testimonial->avatar->alt_text
                    ] : null
                ];
            });

        // Get recent gallery images (8 most recent)
        $recentGalleryImages = GalleryImage::with(['media', 'album'])
            ->whereHas('media')
            ->ordered()
            ->take(8)
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => $image->image_url,
                    'caption' => $image->caption,
                    'alt_text' => $image->alt_text,
                    'album' => $image->album ? [
                        'title' => $image->album->title,
                        'slug' => $image->album->slug
                    ] : null
                ];
            });

        // Calculate statistics
        $stats = [
            'totalDonations' => DonationAnalytic::completed()->count(),
            'eventsHosted' => Event::where('status', 'completed')->count(),
            'volunteersEngaged' => 500, // This would come from a volunteers table if implemented
            'communitiesReached' => 25  // This would come from a communities table if implemented
        ];

        return Inertia::render('Home/Index', [
            'featuredEvents' => $featuredEvents,
            'testimonials' => $testimonials,
            'recentGalleryImages' => $recentGalleryImages,
            'stats' => $stats
        ]);
    }
}
