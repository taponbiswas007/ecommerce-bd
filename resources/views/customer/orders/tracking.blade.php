@extends('layouts.app')

@section('title', 'Track Order #' . $order->order_number)

@section('styles')
    <style>
        .tracking-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .tracking-timeline {
            position: relative;
            padding: 30px 0;
        }

        .tracking-timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #10b981, #3b82f6, #8b5cf6);
        }

        .timeline-step {
            position: relative;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
        }

        .timeline-step:nth-child(odd) {
            flex-direction: row;
        }

        .timeline-step:nth-child(even) {
            flex-direction: row-reverse;
        }

        .timeline-content {
            width: 45%;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .timeline-step:nth-child(odd) .timeline-content {
            margin-right: auto;
        }

        .timeline-step:nth-child(even) .timeline-content {
            margin-left: auto;
        }

        .timeline-icon {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .timeline-icon.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .timeline-icon.current {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            animation: pulse 2s infinite;
        }

        .timeline-icon.pending {
            background: #f3f4f6;
            color: #9ca3af;
            border: 3px dashed #d1d5db;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }

            50% {
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0);
            }
        }

        .status-badge-large {
            font-size: 18px;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
        }

        .document-card {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .document-card:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .info-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .tracking-timeline::before {
                left: 30px;
            }

            .timeline-step {
                flex-direction: column !important;
                align-items: flex-start;
                padding-left: 60px;
            }

            .timeline-content {
                width: 100%;
                margin: 0 !important;
            }

            .timeline-icon {
                left: 30px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <!-- Tracking Header -->
        <div class="tracking-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="h2 fw-bold mb-2">
                        <i class="fas fa-truck me-2"></i>Track Your Order
                    </h1>
                    <p class="mb-0 opacity-75">Order #{{ $order->order_number }}</p>
                </div>
                <div class="text-end mt-3 mt-md-0">
                    @php
                        $statusClass = 'status-' . $order->order_status;
                        $statusColors = [
                            'pending' => 'warning',
                            'confirmed' => 'info',
                            'processing' => 'primary',
                            'ready_to_ship' => 'secondary',
                            'shipped' => 'info',
                            'delivered' => 'success',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            'refunded' => 'dark',
                        ];
                        $bgColor = $statusColors[$order->order_status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $bgColor }} status-badge-large">
                        <i class="fas fa-circle-notch me-2"></i>
                        {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <h6 class="text-muted mb-1">Order Date</h6>
                        <p class="fw-bold mb-0">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                        <h6 class="text-muted mb-1">Total Items</h6>
                        <p class="fw-bold mb-0">{{ $order->items->count() }} Items</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                        <h6 class="text-muted mb-1">Total Amount</h6>
                        <p class="fw-bold mb-0">৳{{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">
                    <i class="fas fa-route text-primary me-2"></i>Delivery Timeline
                </h4>

                @if ($order->statusHistories->count() > 0)
                    <div class="tracking-timeline">
                        @foreach ($order->statusHistories as $index => $history)
                            @php
                                $isLatest = $index === 0;
                                $iconClass = $isLatest ? 'current' : 'completed';
                            @endphp
                            <div class="timeline-step">
                                <div class="timeline-icon {{ $iconClass }}">
                                    <i class="{{ $history->status_icon }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="fw-bold mb-0">{{ $history->status_display }}</h5>
                                        @if ($isLatest)
                                            <span class="badge bg-primary">Current</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $history->status_date->format('d M Y, h:i A') }}
                                    </p>

                                    @if ($history->notes)
                                        <div class="alert alert-light border mb-2">
                                            <i class="fas fa-info-circle me-2 text-info"></i>
                                            {{ $history->notes }}
                                        </div>
                                    @endif

                                    @if ($history->location)
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                            <strong>Current Location:</strong> {{ $history->location }}
                                        </p>
                                    @endif

                                    @if ($history->document_path)
                                        <div class="document-card">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-file-alt me-2 text-primary"></i>
                                                    <strong>{{ $history->document_name ?? 'Document' }}</strong>
                                                </div>
                                                <a href="{{ route('customer.orders.download-document', [$order->id, $history->id]) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($history->previous_status)
                                        <small class="text-muted">
                                            <i class="fas fa-exchange-alt me-1"></i>
                                            Changed from: {{ ucwords(str_replace('_', ' ', $history->previous_status)) }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="info-box text-center">
                        <i class="fas fa-info-circle fa-3x text-primary mb-3"></i>
                        <h5>No Tracking History Available</h5>
                        <p class="mb-0">Your order tracking information will appear here once processing begins.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Items Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-bag text-primary me-2"></i>
                    Order Items ({{ $order->items->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end pe-4">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if ($item->product && $item->product->images->count())
                                                <img src="{{ $item->product->images->first()->image_url }}"
                                                    alt="{{ $item->product_name }}"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                                                    class="me-3">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end pe-4 fw-bold">
                                        ৳{{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="text-center mb-4">
            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Order Details
            </a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-2"></i>View All Orders
            </a>
        </div>
    </div>
@endsection
