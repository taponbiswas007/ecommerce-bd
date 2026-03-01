<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get unread message count
     */
    public static function unreadCount()
    {
        return self::where('is_read', false)->count();
    }

    /**
     * Get recent unread messages
     */
    public static function recentUnread($limit = 5)
    {
        return self::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get initials for avatar
     */
    public function getInitials()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return substr($initials, 0, 2) ?: 'U';
    }
}
