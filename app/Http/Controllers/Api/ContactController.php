<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Submit a new contact message.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:20',
            'priority' => 'nullable|in:low,normal,high,urgent',
        ]);

        $validated['status'] = 'unread';
        $validated['priority'] = $validated['priority'] ?? 'normal';

        $message = ContactMessage::create($validated);

        // TODO: Send notification email to admin

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $message->id,
                'message' => 'Your message has been sent successfully. We will get back to you soon!'
            ],
            'message' => 'Contact message submitted successfully'
        ], 201);
    }
}
