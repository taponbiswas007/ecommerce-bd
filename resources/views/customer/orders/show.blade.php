@extends('layouts.app')

@section('styles')
    <style>
        .order-header-card {
            border-radius: 15px;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow: hidden;
        }

        .order-info-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .order-info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product-card {
            border-radius: 10px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: #dee2e6;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .status-shipped {
            background-color: #e2d9f3;
            color: #4a2b8c;
            border: 1px solid #d6c8ed;
        }

        .status-delivered,
        .status-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -36px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #6c757d;
            border: 3px solid white;
            z-index: 1;
        }

        .timeline-item.active::before {
            background-color: #4f46e5;
            box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.2);
        }

        .timeline-item.completed::before {
            background-color: #10b981;
        }

        .price-cell {
            font-weight: 600;
            color: #2c3e50;
        }

        .subtotal-cell {
            font-weight: 700;
            color: #2c3e50;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }

        .order-actions .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #2c3e50;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f1f1;
        }

        .section-title i {
            color: #4f46e5;
        }

        @media (max-width: 768px) {
            .product-image {
                width: 60px;
                height: 60px;
            }

            .total-amount {
                font-size: 20px;
            }

            .order-actions .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <!-- Order Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card order-header-card">
                    <div class="card-body">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <div class="mb-3 mb-md-0">
                                <div class="d-flex align-items-center mb-2">
                                    <h1 class="h3 fw-bold text-white mb-0">
                                        <i class="fas fa-receipt me-3"></i>
                                        Order Details
                                    </h1>
                                    @php
                                        $statusClass = 'status-' . $order->order_status;
                                    @endphp
                                    <span class="status-badge {{ $statusClass }} ms-3">
                                        @switch($order->order_status)
                                            @case('pending')
                                                <i class="fas fa-clock"></i>
                                            @break

                                            @case('processing')
                                                <i class="fas fa-cogs"></i>
                                            @break

                                            @case('shipped')
                                                <i class="fas fa-shipping-fast"></i>
                                            @break

                                            @case('completed')
                                                <i class="fas fa-check-circle"></i>
                                            @break

                                            @case('cancelled')
                                                <i class="fas fa-times-circle"></i>
                                            @break

                                            @default
                                                <i class="fas fa-question-circle"></i>
                                        @endswitch
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </div>
                                <p class="text-white-50 mb-0">
                                    <i class="far fa-calendar-alt me-2"></i>
                                    Ordered on {{ $order->created_at->format('F d, Y') }} at
                                    {{ $order->created_at->format('h:i A') }}
                                </p>
                            </div>
                            <div class="text-white text-md-end">
                                <div class="h2 fw-bold mb-1">#{{ $order->order_number }}</div>
                                <small class="text-white-50">Order ID</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Information & Actions -->
        <div class="row mb-4">
            <!-- Left Column: Order Info -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Order Summary -->
                    <div class="col-md-6 mb-4">
                        <div class="card order-info-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                        <i class="fas fa-info-circle text-primary fs-4"></i>
                                    </div>
                                    <h5 class="card-title mb-0">Order Summary</h5>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <div class="info-label">Order Date</div>
                                        <div class="info-value">
                                            <i class="far fa-calendar me-2 text-muted"></i>
                                            {{ $order->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="info-label">Order Time</div>
                                        <div class="info-value">
                                            <i class="far fa-clock me-2 text-muted"></i>
                                            {{ $order->created_at->format('h:i A') }}
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="info-label">Payment Method</div>
                                        <div class="info-value">
                                            <i class="fas fa-credit-card me-2 text-muted"></i>
                                            {{ ucfirst($order->payment_method ?? 'Card') }}
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="info-label">Payment Status</div>
                                        <div class="info-value">
                                            @if ($order->payment_status == 'paid')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Paid
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i> Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($order->tracking_number)
                                        <div class="col-12">
                                            <div class="info-label">Tracking Number</div>
                                            <div class="info-value">
                                                <i class="fas fa-truck me-2 text-muted"></i>
                                                {{ $order->tracking_number }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="col-md-6 mb-4">
                        <div class="card order-info-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                        <i class="fas fa-user text-success fs-4"></i>
                                    </div>
                                    <h5 class="card-title mb-0">Customer Information</h5>
                                </div>
                                <div class="mb-3">
                                    <div class="info-label">Customer Name</div>
                                    <div class="info-value">
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        {{ $order->user->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        {{ $order->user->email ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        {{ $order->user->phone ?? 'N/A' }}
                                    </div>
                                </div>
                                @if ($order->shipping_address)
                                    <div>
                                        <div class="info-label">Shipping Address</div>
                                        <div class="info-value small">
                                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                            {{ $order->shipping_address }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card order-info-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                <i class="fas fa-cogs text-warning fs-4"></i>
                            </div>
                            <h5 class="card-title mb-0">Order Actions</h5>
                        </div>

                        <div class="order-actions mb-4">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary mb-2">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>

                            <a href="{{ route('customer.orders.tracking', $order->id) }}" class="btn btn-primary mb-2">
                                <i class="fas fa-map-marked-alt"></i> Track Order
                            </a>

                            @if (in_array($order->order_status, ['pending', 'processing']))
                                <button class="btn btn-outline-danger mb-2" data-bs-toggle="modal"
                                    data-bs-target="#cancelOrderModal">
                                    <i class="fas fa-times-circle"></i> Cancel Order
                                </button>
                            @endif

                            @if ($order->order_status == 'completed')
                                <button class="btn btn-outline-success mb-2">
                                    <i class="fas fa-redo"></i> Reorder Items
                                </button>
                            @endif

                            <button class="btn btn-outline-secondary mb-2">
                                <i class="fas fa-file-invoice"></i> Download Invoice
                            </button>

                            @if ($order->order_status == 'shipped' && $order->tracking_number)
                                <a href="{{ route('tracking.show', $order->tracking_number) }}"
                                    class="btn btn-outline-info">
                                    <i class="fas fa-truck"></i> Track Package
                                </a>
                            @endif
                            @if ($order->order_status == 'shipped' && $order->delivery_document)
                                <a href="{{ asset('storage/' . $order->delivery_document) }}"
                                    class="btn btn-outline-success mt-2" download>
                                    <i class="fas fa-file-download"></i> Download Delivery Document
                                </a>
                            @endif
                        </div>

                        <!-- Order Timeline -->
                        <div>
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-history me-2"></i> Order Timeline
                            </h6>
                            <div class="timeline">
                                @if ($order->statusHistories->count() > 0)
                                    @foreach ($order->statusHistories->take(4) as $history)
                                        <div class="timeline-item {{ $loop->first ? 'active' : 'completed' }}">
                                            <div class="small text-muted">{{ $history->status_display }}</div>
                                            <div class="small">{{ $history->status_date->format('M d, h:i A') }}</div>
                                            @if ($history->location)
                                                <div class="small text-primary">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $history->location }}
                                                </div>
                                            @endif
                                            @if ($history->document_path && $loop->first)
                                                <div class="mt-2">
                                                    <a href="{{ route('customer.orders.download-document', [$order->id, $history->id]) }}"
                                                        class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-download me-1"></i>Document
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if ($order->statusHistories->count() > 4)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('customer.orders.tracking', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>View Full Timeline
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="timeline-item completed">
                                        <div class="small text-muted">Order Placed</div>
                                        <div class="small">{{ $order->created_at->format('M d, h:i A') }}</div>
                                    </div>

                                    <div
                                        class="timeline-item {{ $order->order_status != 'pending' ? 'completed' : 'active' }}">
                                        <div class="small text-muted">Order Confirmed</div>
                                        @if ($order->order_status != 'pending')
                                            <div class="small">{{ $order->updated_at->format('M d, h:i A') }}</div>
                                        @endif
                                    </div>

                                    @if (in_array($order->order_status, ['processing', 'shipped', 'completed']))
                                        <div
                                            class="timeline-item {{ in_array($order->order_status, ['shipped', 'completed']) ? 'completed' : 'active' }}">
                                            <div class="small text-muted">Processing</div>
                                            @if (in_array($order->order_status, ['shipped', 'completed']))
                                                <div class="small">Completed</div>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($order->order_status == 'shipped')
                                        <div class="timeline-item active">
                                            <div class="small text-muted">Shipped</div>
                                            <div class="small">
                                                {{ $order->shipped_at ? $order->shipped_at->format('M d, h:i A') : 'In transit' }}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($order->order_status == 'completed')
                                        <div class="timeline-item completed">
                                            <div class="small text-muted">Delivered</div>
                                            <div class="small">
                                                {{ $order->delivered_at ? $order->delivered_at->format('M d, h:i A') : 'Completed' }}
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-bag text-primary me-2"></i>
                            Order Items ({{ $order->items->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 50px;">#</th>
                                        <th class="py-3">Product</th>
                                        <th class="py-3 text-center">Quantity</th>
                                        <th class="py-3 text-end">Unit Price</th>
                                        <th class="pe-4 py-3 text-end">Subtotal</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $index => $item)
                                        <tr class="align-middle">
                                            <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product && $item->product->images->count())
                                                        <img src="{{ $item->product->images->first()->image_url }}"
                                                            alt="{{ $item->product->name }}" class="product-image me-3">
                                                    @else
                                                        <div
                                                            class="product-image bg-light d-flex align-items-center justify-content-center me-3">
                                                            <i class="fas fa-box-open text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->product->name ?? 'Product' }}</h6>
                                                        <small class="text-muted">SKU:
                                                            {{ $item->product->sku ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    {{ $item->quantity }}
                                                    @if ($item->product && $item->product->unit)
                                                        <span
                                                            class="text-muted small">{{ $item->product->unit->name }}</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-end price-cell">
                                                ৳{{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="text-end">
                                                ৳{{ number_format($item->total_price, 2) }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Total -->
        <div class="row">
            <!-- Order Notes -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-sticky-note text-warning me-2"></i>
                            Order Notes
                        </h5>
                        @if ($order->notes)
                            <div class="alert alert-light border">
                                <i class="fas fa-quote-left text-muted me-2"></i>
                                {{ $order->notes }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-comment-slash fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No notes for this order</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Total -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-receipt text-success me-2"></i>
                            Order Summary
                        </h5>
                        <div class="row mb-2">
                            <div class="col-6">
                                <span class="text-muted">Subtotal:</span>
                            </div>
                            <div class="col-6 text-end">
                                <span
                                    class="fw-semibold">৳{{ number_format($order->subtotal ??$order->items->sum(function ($item) {return $item->price * $item->quantity;}),2) }}</span>
                            </div>
                        </div>

                        @if ($order->shipping_cost > 0)
                            <div class="row mb-2">
                                <div class="col-6">
                                    <span class="text-muted">Shipping:</span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="fw-semibold">৳{{ number_format($order->shipping_cost, 2) }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($order->tax_amount > 0)
                            <div class="row mb-2">
                                <div class="col-6">
                                    <span class="text-muted">Tax:</span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="fw-semibold">৳{{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($order->discount_amount > 0)
                            <div class="row mb-2">
                                <div class="col-6">
                                    <span class="text-muted">Discount:</span>
                                </div>
                                <div class="col-6 text-end">
                                    <span
                                        class="fw-semibold text-success">-৳{{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            </div>
                        @endif

                        <hr>
                        <div class="row mt-3">
                            <div class="col-6">
                                <span class="fw-bold">Total Amount:</span>
                            </div>
                            <div class="col-6 text-end">
                                <div class="total-amount">৳{{ number_format($order->total_amount, 2) }}</div>
                                <small class="text-muted">Including all charges</small>
                            </div>
                        </div>

                        @if ($order->payment_status == 'paid')
                            <div class="alert alert-success mt-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Payment Successful</h6>
                                        <p class="mb-0">This order has been paid in full.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-2">
                                    <i class="fas fa-headset text-primary me-2"></i>
                                    Need Help With This Order?
                                </h5>
                                <p class="text-muted mb-0">
                                    Contact our customer support team for any questions about this order.
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('contact') }}" class="btn btn-primary">
                                    <i class="fas fa-envelope me-2"></i> Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        Cancel Order
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this order?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        This action cannot be undone. Any payments will be refunded according to our policy.
                    </div>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for cancellation:</label>
                        <select class="form-select" id="cancelReason">
                            <option selected>Select a reason</option>
                            <option value="change_mind">Changed my mind</option>
                            <option value="found_cheaper">Found cheaper elsewhere</option>
                            <option value="shipping_time">Shipping time too long</option>
                            <option value="other">Other reason</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Cancel order modal
            const cancelModal = document.getElementById('cancelOrderModal');
            if (cancelModal) {
                cancelModal.addEventListener('shown.bs.modal', function() {
                    document.getElementById('cancelReason').focus();
                });
            }

            // Print invoice function
            function printInvoice() {
                window.print();
            }

            // Add print button event listener
            const printBtn = document.querySelector('.btn[title*="Invoice"]');
            if (printBtn) {
                printBtn.addEventListener('click', printInvoice);
            }

            // Reorder functionality
            const reorderBtn = document.querySelector('.btn-outline-success');
            if (reorderBtn) {
                reorderBtn.addEventListener('click', function() {
                    if (confirm('Add all items from this order to your cart?')) {
                        // Implement reorder logic here
                        alert('Reorder functionality coming soon!');
                    }
                });
            }
        });
    </script>
@endsection
