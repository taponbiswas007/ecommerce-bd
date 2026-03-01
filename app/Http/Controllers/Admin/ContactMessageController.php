<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display all contact messages.
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contact-messages.index', compact('messages'));
    }

    /**
     * Display a specific message.
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Mark as read if not already read
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Get recent unread messages (for header dropdown via AJAX).
     */
    public function getRecentMessages()
    {
        $messages = ContactMessage::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadCount = ContactMessage::where('is_read', false)->count();

        return response()->json([
            'messages' => $messages,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a message.
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Delete multiple messages.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        ContactMessage::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }
}
