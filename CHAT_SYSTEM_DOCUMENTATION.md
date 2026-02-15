# Real-Time Chat System - Documentation

## Overview

This is a complete real-time chat system for communication between Admin and Customers. The system includes:

- ✅ **Customer → Admin** messaging
- ✅ **Admin → Customer** messaging
- ✅ **Real-time notifications** (mobile and desktop)
- ✅ **Unread message counters**
- ✅ **Multiple customer chat management** for admin
- ✅ **Circular chat button** (bottom-right corner)
- ✅ **Responsive design** (mobile & desktop)

---

## Features

### For Customers:

1. Circular chat button on bottom-right corner
2. Click to open chat dialog
3. Send messages to admin instantly
4. Receive real-time responses from admin
5. Unread message counter badge
6. Chat history preserved

### For Admin:

1. Circular chat button with total unread count
2. See list of all customer chats
3. Separate dialogs for each customer
4. Switch between different customer conversations
5. Real-time message updates
6. Individual unread counters per customer

---

## Database Structure

### `chats` Table

- `id` - Primary key
- `customer_id` - Foreign key to users table
- `admin_id` - Foreign key to users table (nullable)
- `status` - Chat status (active/closed)
- `last_message_at` - Timestamp of last message
- `created_at`, `updated_at` - Timestamps

### `chat_messages` Table

- `id` - Primary key
- `chat_id` - Foreign key to chats table
- `user_id` - Foreign key to users table (sender)
- `message` - Message content (text)
- `is_read` - Boolean flag
- `read_at` - Timestamp when read
- `created_at`, `updated_at` - Timestamps

---

## Installation & Setup

### Step 1: Database Migration ✅

Database tables have been created successfully.

```bash
php artisan migrate
```

### Step 2: Install Pusher Package (For Real-Time Broadcasting)

You need to install Pusher PHP SDK:

```bash
composer require pusher/pusher-php-server
```

### Step 3: Install Laravel Echo & Pusher JS (Frontend)

Add to your `package.json`:

```bash
npm install --save laravel-echo pusher-js
```

Or add via CDN in your blade files (already configured):

```html
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
```

### Step 4: Configure Pusher Credentials

Add these to your `.env` file:

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

**Get Free Pusher Credentials:**

1. Go to https://pusher.com/
2. Sign up for free account
3. Create a new app (Channels)
4. Copy your credentials to `.env`

### Step 5: Enable Broadcasting in Laravel

Uncomment in `config/app.php`:

```php
// providers array
App\Providers\BroadcastServiceProvider::class,
```

If `BroadcastServiceProvider.php` doesn't exist, create it:

```bash
php artisan make:provider BroadcastServiceProvider
```

Then add this content:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['web', 'auth']]);
        require base_path('routes/channels.php');
    }
}
```

### Step 6: Configure Laravel Echo (JavaScript)

Add to your main JavaScript file or blade template:

```javascript
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
});
```

Or via CDN (add to your blade layouts):

```html
<script>
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env('PUSHER_APP_KEY') }}',
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        forceTLS: true
    });
</script>
```

---

## How to Use

### Customer Side:

1. **Login** to the website
2. Look for the **circular blue chat button** at bottom-right
3. **Click** to open chat dialog
4. **Type message** and press send
5. **Receive responses** from admin in real-time
6. **Unread badge** shows when new messages arrive

### Admin Side:

1. **Login** to admin panel
2. Look for the **circular blue chat button** at bottom-right
3. **Badge** shows total unread messages count
4. **Click** to see list of all customer chats
5. **Click on a customer** to open their conversation
6. **Send messages** to specific customer
7. **Switch** between different customers easily
8. **Badge** updates automatically with new messages

---

## API Endpoints

All endpoints require authentication (`auth` middleware):

| Method | Endpoint                 | Description                    | Access   |
| ------ | ------------------------ | ------------------------------ | -------- |
| GET    | `/chat/get-or-create`    | Get or create customer's chat  | Customer |
| GET    | `/chat/all`              | Get all customer chats         | Admin    |
| GET    | `/chat/{chat}/messages`  | Get messages for specific chat | Both     |
| POST   | `/chat/{chat}/send`      | Send a message                 | Both     |
| GET    | `/chat/unread-count`     | Get unread messages count      | Both     |
| POST   | `/chat/{chat}/mark-read` | Mark messages as read          | Both     |

---

## Real-Time Broadcasting

### Events:

- `MessageSent` - Fired when a new message is created
- Broadcasts to:
    - `chat.{chatId}` - Specific chat channel
    - `user.{customerId}` - Customer's private channel
    - `user.admin` - Admin notification channel

### Listening to Events:

```javascript
// Listen to specific chat
Echo.private(`chat.${chatId}`).listen(".message.sent", (e) => {
    console.log("New message received:", e);
    // Update UI with new message
    appendMessage(e);
    playNotificationSound();
});

// Listen to user notifications
Echo.private(`user.${userId}`).listen(".message.sent", (e) => {
    console.log("You have a new message");
    updateUnreadBadge();
    showNotification(e);
});
```

---

## Testing Without Pusher (Development Mode)

If you want to test without Pusher setup, you can use **polling** instead (already implemented):

The chat widgets automatically poll for new messages every 10 seconds:

- Checks for unread messages
- Updates badge counter
- Loads new messages

**Note:** This works but doesn't provide instant real-time updates like Pusher.

---

## Mobile Responsiveness

The chat widgets are fully responsive:

- **Desktop:** Fixed position at bottom-right
- **Mobile:** Adapts to screen size (fullscreen on small devices)
- **Touch-friendly:** Large buttons and touch areas
- **Auto-scroll:** Messages scroll to bottom automatically

---

## Notification Support

### Browser Notifications (Optional Enhancement)

To add browser push notifications, add this JavaScript:

```javascript
// Request notification permission
if ("Notification" in window && Notification.permission === "default") {
    Notification.requestPermission();
}

// Show notification when new message arrives
function showBrowserNotification(message) {
    if (Notification.permission === "granted") {
        new Notification("New Message", {
            body: message.message,
            icon: "/path/to/icon.png",
            badge: "/path/to/badge.png",
        });
    }
}
```

---

## Customization

### Change Colors:

**Customer Chat (app.blade.php):**

```css
/* Line ~2580 in app.blade.php */
background: linear-gradient(
    135deg,
    var(--primary-blue, #0d6efd),
    var(--electric-blue, #0096ff)
);
```

**Admin Chat (admin.blade.php):**

```css
/* Line ~200 in admin.blade.php */
background: linear-gradient(135deg, #3b82f6, #1d4ed8);
```

### Change Position:

```css
.chat-widget {
    bottom: 20px; /* Change vertical position */
    right: 20px; /* Change horizontal position (use left: for left side) */
}
```

### Change Size:

```css
.chat-toggle-btn {
    width: 60px; /* Change button size */
    height: 60px;
}

.chat-dialog {
    width: 380px; /* Change dialog width */
    height: 500px; /* Change dialog height */
}
```

---

## Troubleshooting

### Issue: Chat button not showing

**Solution:**

- Make sure you're logged in (`@auth` directive)
- Check browser console for JavaScript errors
- Clear browser cache

### Issue: Messages not sending

**Solution:**

- Check CSRF token is present in meta tags
- Verify user is authenticated
- Check network tab in browser dev tools for API errors

### Issue: Real-time not working

**Solution:**

- Verify Pusher credentials in `.env`
- Check Laravel Echo is initialized
- Make sure broadcasting is enabled in `config/app.php`
- Check broadcast driver in `.env`: `BROADCAST_CONNECTION=pusher`
- Run: `php artisan config:clear`

### Issue: Unread count not updating

**Solution:**

- The system polls every 10 seconds automatically
- For instant updates, set up Pusher broadcasting
- Check browser console for errors

---

## Security Features

✅ **CSRF Protection** - All POST requests protected  
✅ **Authentication Required** - Only logged-in users can chat  
✅ **Authorization Checks** - Users can only access their own chats  
✅ **Admin Role Check** - Only admins can see all chats  
✅ **XSS Prevention** - All messages are escaped before display  
✅ **Private Channels** - Broadcasting uses private channels

---

## Performance Optimization

1. **Lazy Loading:** Messages load only when chat is opened
2. **Pagination:** Consider adding pagination for old messages
3. **Caching:** Cache user data to reduce queries
4. **Queue Jobs:** Use queues for sending notifications
5. **Throttling:** Add rate limiting to prevent spam

To add rate limiting, update routes:

```php
Route::middleware(['auth', 'throttle:60,1'])->prefix('chat')->group(function () {
    // chat routes
});
```

---

## Future Enhancements (Optional)

- [ ] File/Image sharing in chat
- [ ] Typing indicators ("Admin is typing...")
- [ ] Read receipts (seen/delivered status)
- [ ] Chat history export
- [ ] Chat search functionality
- [ ] Emoji picker
- [ ] Voice messages
- [ ] Video call integration
- [ ] AI chatbot for automated responses
- [ ] Multi-language support
- [ ] Dark mode theme

---

## File Structure

```
app/
├── Events/
│   └── MessageSent.php              # Broadcasting event
├── Http/Controllers/
│   └── ChatController.php           # Chat API controller
└── Models/
    ├── Chat.php                     # Chat model
    └── ChatMessage.php              # Message model

config/
└── broadcasting.php                 # Broadcasting configuration

database/migrations/
├── 2026_02_15_000001_create_chats_table.php
└── 2026_02_15_000002_create_chat_messages_table.php

resources/views/layouts/
├── app.blade.php                    # Customer chat widget
└── admin.blade.php                  # Admin chat widget

routes/
├── web.php                          # Chat routes
└── channels.php                     # Broadcasting channels
```

---

## Support

For any issues or questions:

1. Check this documentation first
2. Review Laravel Broadcasting docs: https://laravel.com/docs/broadcasting
3. Check Pusher documentation: https://pusher.com/docs
4. Review browser console for errors

---

## Credits

**Developed by:** GitHub Copilot  
**Technology Stack:**

- Laravel 12
- Pusher (for real-time)
- Bootstrap 5 (frontend)
- Tailwind CSS (admin)
- Font Awesome icons
- SweetAlert2 (notifications)

---

## License

This chat system is part of your Laravel eCommerce application.

---

**Last Updated:** February 15, 2026
