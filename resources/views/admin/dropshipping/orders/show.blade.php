@extends('admin.layouts.master')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', 'CJ Order: ' . $dropshippingOrder->cj_order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.dropshipping.orders.index') }}">Dropshipping Orders</a></li>
    <li class="breadcrumb-item active">Order Details</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.dropshipping.orders.sync-status', $dropshippingOrder->id) }}" class="btn btn-info">
        <i class="fas fa-sync me-2"></i> Sync Status
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Status & Timeline -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Order Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Current Status</h6>
                        @switch($dropshippingOrder->cj_order_status)
                            @case('pending')
                                <span class="badge bg-warning text-dark" style="font-size: 1em;">⏳ Pending</span>
                            @break

                            @case('confirmed')
                                <span class="badge bg-info" style="font-size: 1em;">✓ Confirmed</span>
                            @break

                            @case('shipped')
                                <span class="badge bg-primary" style="font-size: 1em;">🚚 Shipped</span>
                            @break

                            @case('delivered')
                                <span class="badge bg-success" style="font-size: 1em;">✓ Delivered</span>
                            @break

                            @case('cancelled')
                                <span class="badge bg-danger" style="font-size: 1em;">✗ Cancelled</span>
                            @break

                            @default
                                <span class="badge bg-secondary">{{ ucfirst($dropshippingOrder->cj_order_status) }}</span>
                        @endswitch
                    </div>

                    <div class="timeline mt-4">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div>
                                <small class="text-muted">Submitted to CJ</small><br>
                                {{ $dropshippingOrder->submitted_to_cj_at?->format('M d, Y H:i') ?? 'Pending' }}
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div
                                class="timeline-marker {{ $dropshippingOrder->confirmed_by_cj_at ? 'bg-success' : 'bg-secondary' }}">
                            </div>
                            <div>
                                <small class="text-muted">Confirmed by CJ</small><br>
                                {{ $dropshippingOrder->confirmed_by_cj_at?->format('M d, Y H:i') ?? 'Pending' }}
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div
                                class="timeline-marker {{ $dropshippingOrder->shipped_by_cj_at ? 'bg-success' : 'bg-secondary' }}">
                            </div>
                            <div>
                                <small class="text-muted">Shipped by CJ</small><br>
                                {{ $dropshippingOrder->shipped_by_cj_at?->format('M d, Y H:i') ?? 'Pending' }}
                                @if ($dropshippingOrder->tracking_number)
                                    <br><small class="text-muted">Tracking:
                                        {{ $dropshippingOrder->tracking_number }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div
                                class="timeline-marker {{ $dropshippingOrder->delivered_at ? 'bg-success' : 'bg-secondary' }}">
                            </div>
                            <div>
                                <small class="text-muted">Delivered</small><br>
                                {{ $dropshippingOrder->delivered_at?->format('M d, Y H:i') ?? 'Pending' }}
                            </div>
                        </div>
                    </div>

                    @if ($dropshippingOrder->cj_order_status !== 'cancelled')
                        <form method="POST"
                            action="{{ route('admin.dropshipping.orders.cancel', $dropshippingOrder->id) }}" class="mt-4"
                            onsubmit="return confirm('Cancel this CJ order?');">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Reason (optional)</label>
                                <textarea name="reason" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times"></i> Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="col-md-8">
            <!-- Original Order Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Original Order Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Number:</strong><br>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                {{ $order->order_number }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <strong>Customer:</strong><br>
                            {{ $order->user->name }}<br>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Shipping Name:</strong><br>
                            {{ $order->shipping_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Shipping Phone:</strong><br>
                            {{ $order->shipping_phone }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Shipping Address:</strong><br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_upazila }}, {{ $order->shipping_district }}
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Financial Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted">Cost Price (paid to CJ)</small>
                                <h5>{{ number_format($dropshippingOrder->cost_price, 2) }} ৳</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted">Selling Price (from customer)</small>
                                <h5 class="text-primary">{{ number_format($dropshippingOrder->selling_price, 2) }} ৳
                                </h5>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-success">Profit: {{ number_format($dropshippingOrder->profit, 2) }}
                                ৳</strong>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Margin:
                                {{ $dropshippingOrder->selling_price > 0 ? number_format(($dropshippingOrder->profit / $dropshippingOrder->selling_price) * 100, 1) : 0 }}%</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Order Items</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Unit Price</th>
                                <th>Total Cost</th>
                                <th>Total Price</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dropshippingOrder->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->product->name ?? 'Unknown' }}<br>
                                        <small class="text-muted">SKU: {{ $item->sku }}</small>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_cost_price, 2) }} ৳</td>
                                    <td>{{ number_format($item->unit_selling_price, 2) }} ৳</td>
                                    <td>{{ number_format($item->total_cost_price, 2) }} ৳</td>
                                    <td class="fw-bold">{{ number_format($item->total_selling_price, 2) }} ৳</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format($item->getProfit(), 2) }} ৳
                                        </span>
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

    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 40px;
            width: 2px;
            height: 40px;
            background: #dee2e6;
        }

        .timeline-marker {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-right: 15px;
            margin-top: 2px;
            flex-shrink: 0;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-item div:last-child {
            flex: 1;
        }
    </style>
@endsection
