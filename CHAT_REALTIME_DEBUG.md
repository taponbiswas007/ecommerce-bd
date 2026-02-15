# Chat Real-Time Debug Script

## Console এ এই script run করুন:

```javascript
// COMPLETE DIAGNOSTIC SCRIPT
(function () {
    console.clear();
    console.log(
        "%c=== 🔍 CHAT SYSTEM FULL DIAGNOSTIC ===",
        "font-size:16px; font-weight:bold; color:#00ff00;",
    );

    // 1. Variables
    console.log("\n%c1️⃣ VARIABLES:", "font-weight:bold; color:#3498db;");
    console.log("  chatId:", chatId);
    console.log("  isDialogOpen:", isDialogOpen);
    console.log("  chatMessages.length:", chatMessages.length);
    console.log("  Last 3 messages:", chatMessages.slice(-3));

    // 2. DOM Elements
    console.log("\n%c2️⃣ DOM ELEMENTS:", "font-weight:bold; color:#3498db;");
    const dialog = document.getElementById("chatDialog");
    const container = document.getElementById("chatMessages");
    const btn = document.getElementById("chatToggleBtn");
    const input = document.getElementById("chatMessageInput");

    console.log("  chatDialog:", dialog ? "✅ Found" : "❌ Not found");
    console.log("  chatDialog display:", dialog ? dialog.style.display : "N/A");
    console.log("  chatMessages:", container ? "✅ Found" : "❌ Not found");
    console.log("  chatToggleBtn:", btn ? "✅ Found" : "❌ Not found");
    console.log("  chatMessageInput:", input ? "✅ Found" : "❌ Not found");

    if (container) {
        console.log("  Container children:", container.children.length);
        console.log("  Container scrollHeight:", container.scrollHeight);
        console.log("  Container scrollTop:", container.scrollTop);
        console.log("  Container clientHeight:", container.clientHeight);
    }

    // 3. Echo & Pusher
    console.log("\n%c3️⃣ REAL-TIME STATUS:", "font-weight:bold; color:#3498db;");
    console.log(
        "  Echo available:",
        typeof window.Echo !== "undefined" ? "✅" : "❌",
    );
    console.log("  Active channel:", window.activeChatChannel);

    if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        const pusher = window.Echo.connector.pusher;
        console.log("  Pusher state:", pusher.connection.state);
        console.log("  Socket ID:", pusher.connection.socket_id);

        if (pusher.connection.state === "connected") {
            console.log(
                "%c  🎉 PUSHER CONNECTED!",
                "color:green; font-weight:bold;",
            );
        } else {
            console.log(
                "%c  ⚠️ PUSHER NOT CONNECTED",
                "color:orange; font-weight:bold;",
            );
        }
    }

    // 4. Functions
    console.log("\n%c4️⃣ FUNCTIONS:", "font-weight:bold; color:#3498db;");
    console.log(
        "  toggleChat:",
        typeof toggleChat === "function" ? "✅" : "❌",
    );
    console.log(
        "  renderMessages:",
        typeof renderMessages === "function" ? "✅" : "❌",
    );
    console.log(
        "  sendMessage:",
        typeof sendMessage === "function" ? "✅" : "❌",
    );
    console.log(
        "  setupChatListener:",
        typeof window.setupChatListener === "function" ? "✅" : "❌",
    );

    // 5. Test Functions
    console.log("\n%c5️⃣ TEST COMMANDS:", "font-weight:bold; color:#e74c3c;");
    console.log("%cকোনো সমস্যা থাকলে এগুলো try করুন:", "font-style:italic;");
    console.log("");
    console.log("// Force render messages:");
    console.log("renderMessages();");
    console.log("");
    console.log("// Check current state:");
    console.log(
        'console.log("Messages:", chatMessages.length, "Dialog:", document.getElementById("chatDialog").style.display);',
    );
    console.log("");
    console.log("// Manual scroll:");
    console.log(
        'document.getElementById("chatMessages").scrollTop = document.getElementById("chatMessages").scrollHeight;',
    );
    console.log("");
    console.log("// Re-setup listener:");
    console.log("if(chatId) window.setupChatListener(chatId);");

    console.log(
        "\n%c=== ✅ DIAGNOSTIC COMPLETE ===",
        "font-size:16px; font-weight:bold; color:#00ff00;",
    );
})();
```

## যদি message আসছে না dialog open থাকলে:

```javascript
// Test listener manually
console.clear();
console.log("Testing real-time listener...");
console.log("Current chatId:", chatId);
console.log(
    "Dialog display:",
    document.getElementById("chatDialog").style.display,
);
console.log("isDialogOpen:", isDialogOpen);

// Wait for message and check
setTimeout(() => {
    console.log("After 5 seconds:");
    console.log("Messages count:", chatMessages.length);
    console.log("Last message:", chatMessages[chatMessages.length - 1]);
}, 5000);
```

## যদি scroll কাজ না করে:

```javascript
// Force scroll test
const container = document.getElementById("chatMessages");
console.log("Before scroll:");
console.log("  scrollTop:", container.scrollTop);
console.log("  scrollHeight:", container.scrollHeight);
console.log("  clientHeight:", container.clientHeight);

// Scroll to bottom
container.scrollTop = container.scrollHeight;

console.log("After scroll:");
console.log("  scrollTop:", container.scrollTop);
console.log("  scrollHeight:", container.scrollHeight);

// Check if scrolled
if (
    container.scrollTop + container.clientHeight >=
    container.scrollHeight - 10
) {
    console.log("✅ Scroll successful!");
} else {
    console.log("❌ Scroll failed!");
    console.log("Try adding more CSS:");
    console.log("  overflow-y: auto !important;");
    console.log("  height: 400px !important;");
}
```

## Manual Fix Commands:

### যদি dialog open থাকলেও message না আসে:

```javascript
// Force re-render on every message
window.originalRenderMessages = renderMessages;
window.renderMessages = function () {
    console.log("🔧 FORCE RENDERING");
    window.originalRenderMessages();
    // Force scroll
    const c = document.getElementById("chatMessages");
    if (c) {
        c.scrollTop = c.scrollHeight;
        console.log("✅ Force scroll done");
    }
};

// Re-setup listener
if (chatId && window.setupChatListener) {
    window.setupChatListener(chatId);
    console.log("✅ Listener re-setup complete");
}
```

### যদি customer send করলে scroll না হয়:

```javascript
// Wrap sendMessage function
const originalSendMessage = sendMessage;
window.sendMessage = async function (event) {
    console.log("📤 Sending message...");
    await originalSendMessage(event);

    // Force scroll after send
    setTimeout(() => {
        const container = document.getElementById("chatMessages");
        if (container) {
            container.scrollTop = container.scrollHeight;
            console.log("✅ Forced scroll after send");
        }
    }, 200);
};
```

## Real-Time Test:

1. **Console clear করুন:** `console.clear()`
2. **Dialog open করুন:** Chat button click করুন
3. **Watch mode enable করুন:**

```javascript
// Watch for changes
setInterval(() => {
    const d = document.getElementById("chatDialog");
    const c = document.getElementById("chatMessages");
    if (d && d.style.display !== "none") {
        console.log("📊 Status:", {
            messages: chatMessages.length,
            children: c.children.length,
            scrollTop: c.scrollTop,
            scrollHeight: c.scrollHeight,
        });
    }
}, 2000);
```

4. **অন্য browser থেকে message পাঠান**
5. **Console check করুন** - 2 second interval এ status দেখাবে

---

## Expected Console Output (যখন message আসবে):

```
✅ New message received via Pusher: {id: 20, message: "Test"}
Current dialog state: true
Current messages count: 18
✅ Message added! New count: 19
🔄 Rendering messages...
📝 Rendering 19 messages...
⬇️ Scrolling to bottom...
✅ Scroll complete. Height: 850
✅ Messages re-rendered
```

## যদি এই output না আসে:

### Case 1: "New message received" আসে কিন্তু "Rendering messages" না:

**Problem:** Dialog check fail করছে
**Solution:**

```javascript
// Force render without check
Echo.private(`chat.${chatId}`).stopListening(".message.sent");
Echo.private(`chat.${chatId}`).listen(".message.sent", (e) => {
    console.log("✅ Message:", e.message);
    chatMessages.push({
        id: e.id,
        chat_id: e.chat_id,
        user_id: e.user_id,
        message: e.message,
        created_at: new Date().toISOString(),
        user: { name: e.user_name },
    });
    renderMessages(); // Always render
});
```

### Case 2: "Rendering messages" আসে কিন্তু UI update হয় না:

**Problem:** DOM update issue
**Solution:**

```javascript
// Force DOM update
function forceRenderMessages() {
    const container = document.getElementById('chatMessages');
    const html = chatMessages.map(msg => {
        const isSent = msg.user_id === {{ auth()->id() }};
        return `<div class="chat-message ${isSent ? 'sent' : ''}">${msg.message}</div>`;
    }).join('');
    container.innerHTML = html;
    container.scrollTop = 99999; // Force scroll
}

// Use this instead
forceRenderMessages();
```

### Case 3: Customer send করলে scroll হয় না:

**Problem:** Scroll timing issue
**Solution:**

```javascript
// After line: input.value = '';
// Add:
setTimeout(() => {
    const c = document.getElementById("chatMessages");
    c.scrollTop = c.scrollHeight;
}, 100);
setTimeout(() => {
    const c = document.getElementById("chatMessages");
    c.scrollTop = c.scrollHeight;
}, 300);
```

---

## Ultimate Nuclear Option (যদি কিছুই কাজ না করে):

```javascript
// Complete override
(function () {
    console.log("🚀 Applying nuclear fix...");

    // Force render every 2 seconds if dialog open
    setInterval(() => {
        const dialog = document.getElementById("chatDialog");
        if (dialog && dialog.style.display !== "none") {
            const container = document.getElementById("chatMessages");
            const lastChild = container.lastElementChild;
            const lastMsgId = lastChild
                ? parseInt(lastChild.dataset.id || "0")
                : 0;
            const latestMsgId = chatMessages[chatMessages.length - 1]?.id || 0;

            if (latestMsgId > lastMsgId) {
                console.log("🔄 Auto-refreshing messages...");
                renderMessages();
            }
        }
    }, 2000);

    console.log("✅ Nuclear fix applied! Messages will auto-refresh every 2s");
})();
```

এটা run করলে প্রতি 2 second এ automatically check করবে এবং নতুন message থাকলে render করবে।
