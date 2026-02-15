# Real-Time Chat Debugging Guide

## কিভাবে চেক করবেন Real-Time কাজ করছে কিনা

### ১. Browser Console খুলুন (F12)

#### Customer Side (Frontend):

```javascript
// Console এ এই লিখে এন্টার চাপুন
console.clear();
console.log("Chat ID:", chatId);
console.log("Dialog Open:", isDialogOpen);
console.log("Messages Count:", chatMessages.length);
console.log("Echo Available:", typeof window.Echo !== "undefined");
console.log("Active Channel:", window.activeChatChannel);

if (window.Echo && window.Echo.connector) {
    console.log("Pusher State:", window.Echo.connector.pusher.connection.state);
}
```

**Expected Output যদি সব ঠিক থাকে:**

```
Chat ID: 1
Dialog Open: true
Messages Count: 5
Echo Available: true
Active Channel: chat.1
Pusher State: connected
```

#### Admin Side (Backend):

```javascript
// Console এ এই লিখে এন্টার চাপুন
console.clear();
console.log("[Admin] Current Chat ID:", currentAdminChatId);
console.log("[Admin] Dialog Open:", isAdminDialogOpen);
console.log("[Admin] Messages Count:", adminChatMessages.length);
console.log("[Admin] Echo Available:", typeof window.Echo !== "undefined");
console.log("[Admin] Active Channel:", window.activeAdminChatChannel);

if (window.Echo && window.Echo.connector) {
    console.log(
        "[Admin] Pusher State:",
        window.Echo.connector.pusher.connection.state,
    );
}
```

---

## ২. Pusher Configuration Check

### .env File Check করুন:

```bash
# Terminal এ run করুন
cat .env | grep PUSHER
```

**Expected Output:**

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=ap2
```

**যদি PUSHER credentials না থাকে:**

1. [Pusher.com](https://pusher.com) এ account তৈরি করুন
2. New channel app তৈরি করুন
3. Credentials copy করে `.env` file এ paste করুন
4. Cache clear করুন:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## ৩. Real-Time Message Test

### Test Setup:

1. **Browser 1:** Customer হিসেবে login করুন
2. **Browser 2:** Admin হিসেবে login করুন
3. উভয় browser এর **Console (F12)** খুলুন

### Test Case: Customer Message পাঠান

**Browser 1 (Customer):**

1. Chat dialog open করুন
2. "Hello from customer" লিখে send করুন
3. **Console চেক করুন:**

**Expected Console Output:**

```
Message sent successfully
[Pusher] Event sent on channel: chat.1
```

**Browser 2 (Admin):**

1. Customer এর chat open রাখুন
2. **Console automatically দেখাবে:**

**Expected Console Output:**

```
✅ [Admin] New message received via Pusher: {message: "Hello from customer", ...}
[Admin] Adding message to current chat
[Admin] Messages re-rendered
```

**যদি এই output না আসে:**

- ❌ Pusher connected নয়
- ❌ Listener setup হয়নি
- ❌ Channel authorization failed

---

## ৪. সমস্যা ও সমাধান

### Problem 1: "Pusher State: unavailable"

**সমাধান:**

```bash
# 1. Pusher credentials check করুন
cat .env | grep PUSHER

# 2. Config cache clear করুন
php artisan config:clear

# 3. Server restart করুন
php artisan serve
```

### Problem 2: "Echo Available: false"

**Reason:** Laravel Echo script load হয়নি

**সমাধান:**

1. Browser hard refresh করুন: `Ctrl + Shift + R`
2. Network tab check করুন - echo.iife.js load হচ্ছে কিনা

### Problem 3: "Active Channel: undefined"

**Reason:** `setupChatListener()` call হয়নি

**সমাধান:**

```javascript
// Console এ manually call করুন (test করতে)
if (chatId) {
    window.setupChatListener(chatId);
}
```

### Problem 4: Dialog open থাকলেও message আসছে না

**Debug Steps:**

```javascript
// Console এ run করুন
console.log("Dialog Open:", isDialogOpen);
console.log("Active Channel:", window.activeChatChannel);
console.log("Last Message:", chatMessages[chatMessages.length - 1]);
```

**যদি `isDialogOpen: false` দেখায়:**

- Dialog variable ঠিকমত set হয়নি
- `toggleChat()` function check করুন

### Problem 5: Scroll কাজ করছে না

**Debug Steps:**

```javascript
// Console এ run করুন
const container = document.getElementById("chatMessages");
console.log("Container Height:", container.clientHeight);
console.log("Scroll Height:", container.scrollHeight);
console.log("Scroll Top:", container.scrollTop);
console.log("Overflow:", getComputedStyle(container).overflowY);
```

**Expected Output:**

```
Container Height: 400
Scroll Height: 850
Scroll Top: 450
Overflow: auto
```

**যদি Overflow: visible দেখায়:**

- CSS এ `overflow-y: auto` add করুন

### Problem 6: Duplicate Messages

**Check:**

```javascript
// Console এ run করুন
console.log(
    "Messages:",
    chatMessages.map((m) => m.id),
);
// Output: [1, 2, 3, 3, 4]  ← 3 duplicate
```

**সমাধান:** Already implement করা আছে, কিন্তু যদি still হয়:

```bash
# Cache clear করুন
php artisan view:clear
# Browser hard refresh
Ctrl + Shift + R
```

---

## ৫. Network Tab Analysis

### Check করুন:

1. **F12** → **Network** tab open করুন
2. Filter: **WS** (WebSocket) select করুন

**Expected:**

- `ws://ws-ap2.pusher.com` connected
- Status: **101 Switching Protocols**

**যদি WebSocket connection না দেখায়:**

- Pusher credentials wrong
- Firewall blocking WebSocket
- Browser extension (ad blocker) blocking

---

## ৬. Real Data Flow Visualization

### Customer পাঠায় → Admin পায়

```
Customer Browser
    ↓
[1] sendMessage() function
    ↓
[2] POST /chat/1/send
    ↓
[3] ChatMessage::create()
    ↓
[4] broadcast(new MessageSent())  ← ChatMessage model boot()
    ↓
[5] Pusher sends to channels:
    - chat.1
    - user.{admin_id}
    ↓
[6] Admin Browser Echo listener receives
    ↓
[7] chatMessages.push(new message)
    ↓
[8] renderMessages()
    ↓
[9] setTimeout() → scrollTop
    ↓
[10] ✅ Message displayed + scrolled
```

**Console এ step by step দেখতে পারবেন:**

```
Message sent successfully          ← Step 1-2
Broadcasting...                    ← Step 4
✅ [Admin] New message received    ← Step 6
[Admin] Adding message             ← Step 7
[Admin] Messages re-rendered       ← Step 8
```

---

## ৭. Manual Broadcast Test

### Test করুন Broadcasting কাজ করছে কিনা:

```bash
# Terminal এ run করুন
php artisan tinker
```

```php
// Tinker console এ
$chat = \App\Models\Chat::first();
$message = \App\Models\ChatMessage::create([
    'chat_id' => $chat->id,
    'user_id' => 1,
    'message' => 'Test broadcast message'
]);

// Console এ দেখুন broadcast হচ্ছে কিনা
```

**Browser console এ expected:**

```
✅ New message received via Pusher: {message: "Test broadcast message"}
```

---

## ৮. Quick Fixes Checklist

যদি কিছুই কাজ না করে, এই order এ try করুন:

```bash
# 1. Clear all cache
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 2. Verify Pusher credentials
php artisan config:cache

# 3. Restart server
php artisan serve

# 4. Browser
- Hard refresh: Ctrl + Shift + R
- Clear browser cache
- Open in incognito mode
```

---

## ৯. Final Verification Script

### Copy-paste করুন Browser Console এ:

```javascript
// Complete Chat System Check
(function () {
    console.clear();
    console.log("=== CHAT SYSTEM DIAGNOSTIC ===\n");

    // 1. Variables Check
    console.log("1️⃣ Variables:");
    console.log(
        "   chatId:",
        typeof chatId !== "undefined" ? chatId : "NOT DEFINED",
    );
    console.log(
        "   isDialogOpen:",
        typeof isDialogOpen !== "undefined" ? isDialogOpen : "NOT DEFINED",
    );
    console.log(
        "   chatMessages count:",
        typeof chatMessages !== "undefined"
            ? chatMessages.length
            : "NOT DEFINED",
    );

    // 2. Echo Check
    console.log("\n2️⃣ Laravel Echo:");
    console.log("   Echo available:", typeof window.Echo !== "undefined");
    console.log(
        "   Active channel:",
        typeof window.activeChatChannel !== "undefined"
            ? window.activeChatChannel
            : "NONE",
    );

    // 3. Pusher Check
    console.log("\n3️⃣ Pusher:");
    if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        const pusher = window.Echo.connector.pusher;
        console.log("   State:", pusher.connection.state);
        console.log(
            "   Socket ID:",
            pusher.connection.socket_id || "NOT CONNECTED",
        );
    } else {
        console.log("   ❌ Pusher not initialized");
    }

    // 4. DOM Elements
    console.log("\n4️⃣ DOM Elements:");
    console.log(
        "   Chat container:",
        document.getElementById("chatMessages") ? "✅ Found" : "❌ Not found",
    );
    console.log(
        "   Chat dialog:",
        document.getElementById("chatDialog") ? "✅ Found" : "❌ Not found",
    );
    console.log(
        "   Input field:",
        document.getElementById("chatMessageInput")
            ? "✅ Found"
            : "❌ Not found",
    );

    // 5. Functions
    console.log("\n5️⃣ Functions:");
    console.log(
        "   toggleChat:",
        typeof toggleChat !== "undefined" ? "✅ Available" : "❌ Missing",
    );
    console.log(
        "   renderMessages:",
        typeof renderMessages !== "undefined" ? "✅ Available" : "❌ Missing",
    );
    console.log(
        "   sendMessage:",
        typeof sendMessage !== "undefined" ? "✅ Available" : "❌ Missing",
    );
    console.log(
        "   setupChatListener:",
        typeof window.setupChatListener !== "undefined"
            ? "✅ Available"
            : "❌ Missing",
    );

    console.log("\n=== END DIAGNOSTIC ===");

    // Test scroll
    if (document.getElementById("chatMessages")) {
        const container = document.getElementById("chatMessages");
        console.log("\n📏 Scroll Info:");
        console.log("   clientHeight:", container.clientHeight);
        console.log("   scrollHeight:", container.scrollHeight);
        console.log("   scrollTop:", container.scrollTop);
        console.log(
            "   Can scroll:",
            container.scrollHeight > container.clientHeight,
        );
    }
})();
```

**Expected Output যদি সব ঠিক থাকে:**

```
=== CHAT SYSTEM DIAGNOSTIC ===

1️⃣ Variables:
   chatId: 1
   isDialogOpen: true
   chatMessages count: 5

2️⃣ Laravel Echo:
   Echo available: true
   Active channel: chat.1

3️⃣ Pusher:
   State: connected
   Socket ID: 123456.789012

4️⃣ DOM Elements:
   Chat container: ✅ Found
   Chat dialog: ✅ Found
   Input field: ✅ Found

5️⃣ Functions:
   toggleChat: ✅ Available
   renderMessages: ✅ Available
   sendMessage: ✅ Available
   setupChatListener: ✅ Available

=== END DIAGNOSTIC ===

📏 Scroll Info:
   clientHeight: 400
   scrollHeight: 850
   scrollTop: 450
   Can scroll: true
```

---

## ১০. Support

যদি এই সব check করার পরও কাজ না করে:

1. **.env file এর screenshot** পাঠান (credentials hide করে)
2. **Browser console এর screenshot** পাঠান
3. **Network tab এর screenshot** পাঠান (WS filter দিয়ে)

এতে exact problem identify করা সহজ হবে।

---

**Happy Debugging! 🐛**
