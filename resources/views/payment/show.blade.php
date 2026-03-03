@extends('layouts.app')

@section('content')
    @php
        $supportEmail = config('mail.from.address', 'support@ecommercebd.com');
        $supportPhoneRaw = '+8801234567890';
        $supportPhoneDisplay = '+880 1234-567890';
        $whatsAppNumber = preg_replace('/\D+/', '', $supportPhoneRaw);
    @endphp

    <div class="container py-4 py-md-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong class="d-block mb-1">Please fix the following:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <div>
                        <h4 class="mb-2">Payment Submission</h4>
                        <div class="text-muted">Order #{{ $order->order_number }}</div>
                    </div>
                    <div class="text-md-end">
                        <div class="small text-muted">Final Payable</div>
                        <div class="h4 mb-0 fw-bold text-primary">৳{{ number_format($order->payable_amount, 2) }}</div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="small text-muted">Requested Total</div>
                        <div class="fw-semibold">৳{{ number_format($order->total_amount, 2) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Payment Method</div>
                        <div class="fw-semibold">{{ strtoupper($order->payment_method) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Payment Status</div>
                        <div class="fw-semibold">{{ ucfirst($order->payment_status) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Negotiation Status</div>
                        <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $order->negotiation_status ?? 'open')) }}
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4 mb-0 d-flex align-items-start">
                    <i class="fas fa-info-circle mt-1 me-2"></i>
                    <div>
                        <strong>Attention:</strong> Cash on Delivery is unavailable. Please transfer using the assigned
                        account, then submit Transaction ID and proof for faster confirmation.
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="fas fa-university me-2 text-primary"></i>Assigned Payment Account</h5>

                        @if ($order->paymentAccount)
                            <div class="bg-light border rounded p-3 mb-3">
                                <div><strong>{{ $order->paymentAccount->account_name }}</strong></div>
                                <div><strong>Account Number:</strong> {{ $order->paymentAccount->account_number }}</div>
                                <div><strong>Holder:</strong> {{ $order->paymentAccount->account_holder ?: 'N/A' }}</div>
                                <div><strong>Branch:</strong> {{ $order->paymentAccount->branch ?: 'N/A' }}</div>
                                @if ($order->paymentAccount->instructions)
                                    <hr class="my-2">
                                    <div><strong>Instructions:</strong></div>
                                    <div class="small text-muted">{!! nl2br(e($order->paymentAccount->instructions)) !!}</div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-danger mb-3">
                                Admin has not assigned a payment account for this order yet. Please contact support.
                            </div>
                        @endif

                        @if ($order->payment_instructions)
                            <div class="alert alert-warning">
                                <strong>Admin Notes for Payment:</strong><br>
                                {!! nl2br(e($order->payment_instructions)) !!}
                            </div>
                        @endif

                        @if (
                            $order->negotiation_status === 'awaiting_customer_payment' ||
                                $order->negotiated_total_amount ||
                                $order->payment_proof_path)
                            <hr>
                            <h5 class="mb-3"><i class="fas fa-receipt me-2 text-success"></i>Submit Transfer Proof</h5>

                            <form action="{{ route('payment.process', $order) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Transaction / Reference ID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="payment_reference"
                                        value="{{ old('payment_reference', $order->payment_reference) }}"
                                        class="form-control form-control-lg @error('payment_reference') is-invalid @enderror"
                                        placeholder="e.g. TXN123456789" required>
                                    @error('payment_reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Payment Proof (Optional)</label>
                                    <input type="file" name="payment_proof"
                                        class="form-control @error('payment_proof') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="text-muted">Accepted: JPG, JPEG, PNG, PDF (Max 5MB)</small>
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button class="btn btn-primary btn-lg" type="submit"
                                    {{ $order->paymentAccount ? '' : 'disabled' }}>
                                    <i class="fas fa-paper-plane me-2"></i>Submit Payment Proof
                                </button>
                            </form>
                        @endif

                        @if ($order->payment_proof_path)
                            <div class="mt-4">
                                <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Submitted Proof
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="fas fa-headset me-2 text-danger"></i>Need Quick Help?</h5>
                        <p class="text-muted mb-3">
                            Payment submit এর সময় কোনো সমস্যা হলে নিচের যেকোনো অপশন দিয়ে সাথে সাথে যোগাযোগ করুন।
                        </p>

                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $supportEmail }}?subject=Payment%20Help%20for%20Order%20{{ $order->order_number }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Email Support
                            </a>

                            <a href="https://wa.me/{{ $whatsAppNumber }}?text={{ urlencode('Hello, I need payment support for order #' . $order->order_number) }}"
                                class="btn btn-outline-success" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>

                            <a href="tel:{{ $supportPhoneRaw }}" class="btn btn-outline-dark">
                                <i class="fas fa-phone-alt me-2"></i>Call {{ $supportPhoneDisplay }}
                            </a>

                            <button type="button" class="btn btn-outline-secondary"
                                onclick="if (typeof toggleChat === 'function') { toggleChat(); }">
                                <i class="fas fa-comments me-2"></i>Message Support (Popup)
                            </button>
                        </div>

                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-bell me-2"></i>
                            <strong>Attention:</strong> Message popup active থাকলে admin reply দ্রুত দেখতে পারবেন।
                        </div>
                    </div>
                </div>

                <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100 mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Go to My Orders
                </a>
            </div>
        </div>
    </div>
@endsection
