<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class PublicTestimonialController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $avatarId = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/testimonials', $filename, 'public');

            // Create Media record
            $media = \App\Models\Media::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'storage_path' => $path,
                'alt_text' => $validated['name'] . ' Avatar',
            ]);

            $avatarId = $media->id;
        }

        Testimonial::create([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'avatar_id' => $avatarId,
            'status' => 'pending',
            'is_featured' => false,
        ]);

        return back()->with('success', 'Thank you for your testimonial! It has been submitted for review.');
    }
}
