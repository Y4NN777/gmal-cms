<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(20);

        return Inertia::render('Admin/Contact/Index', [
            'messages' => $messages,
            'filters' => [
                'status' => $request->status,
                'search' => $request->search,
            ],
            'stats' => [
                'total' => ContactMessage::count(),
                'unread' => ContactMessage::whereNull('read_at')->count(),
                'read' => ContactMessage::whereNotNull('read_at')->count(),
            ],
            'user' => auth()->user(),
        ]);
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        if (!$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    /**
     * Mark a message as unread.
     */
    public function markAsUnread($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $message->update(['read_at' => null]);

        return redirect()->back()->with('success', 'Message marked as unread.');
    }

    /**
     * Delete a contact message.
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }

    /**
     * Bulk delete messages.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id',
        ]);

        ContactMessage::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' message(s) deleted successfully.');
    }
}
