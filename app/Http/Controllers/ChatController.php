<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Get or create a chat for the authenticated customer.
     */
    public function getOrCreateChat()
    {
        $user = Auth::user();

        // If user is customer, find or create their chat
        if (!$user->hasRole('admin')) {
            $chat = Chat::firstOrCreate(
                ['customer_id' => $user->id],
                ['status' => 'active']
            );
        } else {
            return response()->json(['error' => 'Admin cannot create customer chat'], 403);
        }

        $chat->load(['messages.user', 'customer', 'admin']);

        return response()->json([
            'chat' => $chat,
            'unread_count' => $chat->unreadMessagesCount($user->id),
        ]);
    }

    /**
     * Get all chats (for admin).
     */
    public function getAllChats()
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chats = Chat::with(['customer', 'latestMessage.user'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($chat) use ($user) {
                return [
                    'id' => $chat->id,
                    'customer' => $chat->customer,
                    'latest_message' => $chat->latestMessage,
                    'unread_count' => $chat->unreadMessagesCount($user->id),
                    'last_message_at' => $chat->last_message_at,
                ];
            });

        return response()->json(['chats' => $chats]);
    }

    /**
     * Get messages for a specific chat.
     */
    public function getMessages($chatId)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        // Check if user has access to this chat
        if (!$user->hasRole('admin') && $chat->customer_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $chat->markAsRead($user->id);

        return response()->json([
            'messages' => $messages,
            'chat' => $chat->load(['customer', 'admin']),
        ]);
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        // Check if user has access to this chat
        if (!$user->hasRole('admin') && $chat->customer_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If admin is sending message, assign them to the chat
        if ($user->hasRole('admin') && !$chat->admin_id) {
            $chat->update(['admin_id' => $user->id]);
        }

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        $message->load('user');

        return response()->json([
            'message' => $message,
            'success' => true,
        ]);
    }

    /**
     * Get unread messages count.
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $unreadCount = 0;

        if ($user->hasRole('admin')) {
            // Count unread messages across all chats for admin
            $unreadCount = ChatMessage::whereHas('chat')
                ->where('user_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();
        } else {
            // Count unread messages in customer's chat
            $chat = Chat::where('customer_id', $user->id)->first();
            if ($chat) {
                $unreadCount = $chat->unreadMessagesCount($user->id);
            }
        }

        return response()->json(['unread_count' => $unreadCount]);
    }

    /**
     * Mark chat messages as read.
     */
    public function markAsRead($chatId)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        // Check if user has access to this chat
        if (!$user->hasRole('admin') && $chat->customer_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat->markAsRead($user->id);

        return response()->json(['success' => true]);
    }
}
