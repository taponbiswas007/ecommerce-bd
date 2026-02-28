@extends('admin.layouts.master')

@section('title', 'AI Chat Assistant')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai.index') }}">AI Assistant</a></li>
                        <li class="breadcrumb-item active">Chat</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">💬 AI Chat Assistant</h1>
            </div>
        </div>

        <div class="row">
            <!-- Chat Area -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="height: 600px;">
                    <div class="card-header bg-gradient text-white"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                    <i class="fas fa-robot text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white">AI Business Assistant</h6>
                                    <small class="text-white-50">Powered by <span id="currentProvider">Gemini</span></small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="includeContext" checked>
                                <label class="form-check-label text-white-50" for="includeContext">Include Business
                                    Context</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body overflow-auto" id="chatMessages"
                        style="height: calc(100% - 140px); background: #f8f9fa;">
                        <!-- Welcome Message -->
                        <div class="text-center py-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-robot text-primary fa-2x"></i>
                            </div>
                            <h5>Welcome to AI Assistant!</h5>
                            <p class="text-muted mb-0">Ask me anything about your business - products, sales, marketing,
                                etc.</p>
                            <p class="text-muted small">আমাকে বাংলায়ও প্রশ্ন করতে পারেন!</p>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0">
                        <form id="chatForm">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg border-0 bg-light" id="chatInput"
                                    placeholder="Type your message... / আপনার প্রশ্ন লিখুন..." autocomplete="off">
                                <button type="submit" class="btn btn-primary px-4" id="sendBtn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Provider Selection -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-cog me-2"></i>AI Provider</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="provider" id="providerGemini"
                                value="gemini" checked>
                            <label class="form-check-label" for="providerGemini">
                                <strong>Google Gemini</strong>
                                <small class="d-block text-muted">Fast and reliable</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="provider" id="providerGroq" value="groq">
                            <label class="form-check-label" for="providerGroq">
                                <strong>Groq (Llama)</strong>
                                <small class="d-block text-muted">Super fast responses</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Today's Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Today's Orders</span>
                            <strong>{{ $recentStats['today_orders'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Today's Revenue</span>
                            <strong>৳{{ number_format($recentStats['today_revenue']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pending Orders</span>
                            <strong class="text-warning">{{ $recentStats['pending_orders'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Low Stock Items</span>
                            <strong class="text-danger">{{ $recentStats['low_stock_count'] }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Quick Prompts -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Quick Prompts</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm text-start quick-prompt"
                                data-prompt="আজকের সেলস কেমন হলো? বিশ্লেষণ করো।">
                                <i class="fas fa-chart-line me-2"></i>আজকের সেলস বিশ্লেষণ
                            </button>
                            <button class="btn btn-outline-primary btn-sm text-start quick-prompt"
                                data-prompt="কোন প্রোডাক্টগুলো বেশি প্রমোট করা উচিত এবং কেন?">
                                <i class="fas fa-bullhorn me-2"></i>প্রোডাক্ট প্রমোশন সাজেশন
                            </button>
                            <button class="btn btn-outline-primary btn-sm text-start quick-prompt"
                                data-prompt="Low stock products নিয়ে কী করা উচিত?">
                                <i class="fas fa-boxes me-2"></i>স্টক ম্যানেজমেন্ট টিপস
                            </button>
                            <button class="btn btn-outline-primary btn-sm text-start quick-prompt"
                                data-prompt="এই মাসের মার্কেটিং স্ট্র্যাটেজি কী হওয়া উচিত?">
                                <i class="fas fa-bullseye me-2"></i>মার্কেটিং স্ট্র্যাটেজি
                            </button>
                            <button class="btn btn-outline-primary btn-sm text-start quick-prompt"
                                data-prompt="How can I increase my sales this month?">
                                <i class="fas fa-arrow-up me-2"></i>Sales Growth Tips
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chat-message {
            margin-bottom: 1rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-message.user {
            display: flex;
            justify-content: flex-end;
        }

        .chat-message.ai {
            display: flex;
            justify-content: flex-start;
        }

        .chat-bubble {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 16px;
            position: relative;
        }

        .chat-message.user .chat-bubble {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-message.ai .chat-bubble {
            background: white;
            color: #333;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .chat-bubble p {
            margin-bottom: 0;
            white-space: pre-wrap;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 8px 0;
        }

        .typing-indicator span {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-8px);
            }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatForm = document.getElementById('chatForm');
                const chatInput = document.getElementById('chatInput');
                const chatMessages = document.getElementById('chatMessages');
                const sendBtn = document.getElementById('sendBtn');

                // Provider change
                document.querySelectorAll('input[name="provider"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        document.getElementById('currentProvider').textContent =
                            this.value === 'gemini' ? 'Gemini' : 'Groq';
                    });
                });

                // Quick prompts
                document.querySelectorAll('.quick-prompt').forEach(btn => {
                    btn.addEventListener('click', function() {
                        chatInput.value = this.dataset.prompt;
                        chatForm.dispatchEvent(new Event('submit'));
                    });
                });

                // Send message
                chatForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const message = chatInput.value.trim();
                    if (!message) return;

                    const provider = document.querySelector('input[name="provider"]:checked').value;
                    const includeContext = document.getElementById('includeContext').checked;

                    // Add user message
                    addMessage(message, 'user');
                    chatInput.value = '';
                    sendBtn.disabled = true;

                    // Add typing indicator
                    const typingId = addTypingIndicator();

                    try {
                        const response = await fetch('{{ route('admin.ai.chat.send') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message: message,
                                provider: provider,
                                include_context: includeContext
                            })
                        });

                        const data = await response.json();

                        // Remove typing indicator
                        removeTypingIndicator(typingId);

                        if (data.success) {
                            addMessage(data.message, 'ai');
                        } else {
                            addMessage('Sorry, something went wrong. Please try again.', 'ai');
                        }
                    } catch (error) {
                        removeTypingIndicator(typingId);
                        addMessage('Connection error. Please check your internet connection.', 'ai');
                    }

                    sendBtn.disabled = false;
                });

                function addMessage(text, type) {
                    const div = document.createElement('div');
                    div.className = `chat-message ${type}`;
                    div.innerHTML = `
            <div class="chat-bubble">
                <p>${escapeHtml(text)}</p>
            </div>
        `;
                    chatMessages.appendChild(div);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }

                function addTypingIndicator() {
                    const id = 'typing-' + Date.now();
                    const div = document.createElement('div');
                    div.className = 'chat-message ai';
                    div.id = id;
                    div.innerHTML = `
            <div class="chat-bubble">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
                    chatMessages.appendChild(div);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    return id;
                }

                function removeTypingIndicator(id) {
                    const element = document.getElementById(id);
                    if (element) element.remove();
                }

                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML.replace(/\n/g, '<br>');
                }
            });
        </script>
    @endpush
@endsection
