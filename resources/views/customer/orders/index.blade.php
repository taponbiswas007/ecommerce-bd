@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .stats-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .order-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08) !important;
            border-color: #dee2e6;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
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

        .status-shipped {
            background-color: #e2d9f3;
            color: #4a2b8c;
            border: 1px solid #d6c8ed;
        }

        .help-card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .help-card.bg-blue {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .help-card.bg-green {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }

        .help-card.bg-purple {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
        }

        .quick-action-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .quick-action-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(248, 249, 250, 0.8) !important;
        }

        .order-id {
            font-weight: 600;
            color: #2c3e50;
        }

        .order-date {
            font-size: 12px;
            color: #6c757d;
        }

        .amount {
            font-weight: 700;
            font-size: 18px;
            color: #2c3e50;
        }

        .payment-method {
            font-size: 12px;
            color: #6c757d;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-view {
            background-color: #e8eaf6;
            color: #3f51b5;
            border: 1px solid #c5cae9;
        }

        .btn-view:hover {
            background-color: #c5cae9;
            color: #303f9f;
        }

        .btn-reorder {
            background-color: #e8f5e8;
            color: #388e3c;
            border: 1px solid #c8e6c9;
        }

        .btn-reorder:hover {
            background-color: #c8e6c9;
            color: #2e7d32;
        }

        .btn-invoice {
            background-color: #f5f5f5;
            color: #616161;
            border: 1px solid #e0e0e0;
        }

        .btn-invoice:hover {
            background-color: #e0e0e0;
            color: #424242;
        }

        .btn-track {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid #bbdefb;
        }

        .btn-track:hover {
            background-color: #bbdefb;
            color: #1565c0;
        }

        .search-box {
            position: relative;
        }

        .search-box .form-control {
            padding-left: 40px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .search-box .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .pagination .page-item.active .page-link {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .pagination .page-link {
            color: #4f46e5;
            border-radius: 6px;
            margin: 0 3px;
            border: 1px solid #dee2e6;
        }

        .pagination .page-link:hover {
            background-color: #f8f9fa;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 80px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 15px;
            }

            .table-responsive {
                border: none;
            }

            .action-btn span {
                display: none;
            }

            .action-btn {
                padding: 8px;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h2 fw-bold text-dark mb-2">
                            <i class="fas fa-shopping-bag text-primary me-2"></i>
                            My Orders
                        </h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-history text-secondary me-2"></i>
                            View your order history and track your purchases
                        </p>
                    </div>
                    <div class="search-box w-100 w-md-auto">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" placeholder="Search orders by ID, product, or status...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                <i class="fas fa-clipboard-list text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Orders</h6>
                                <h3 class="fw-bold mb-0">{{ $orders->total() }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-arrow-up text-success me-1"></i>
                                Last 30 days: {{ $orders->where('created_at', '>=', now()->subDays(30))->count() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Completed</h6>
                                <h3 class="fw-bold mb-0">{{ $orders->where('order_status', 'completed')->count() }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-success fw-medium">
                                <i class="fas fa-chart-line me-1"></i>
                                {{ $orders->total() > 0 ? round(($orders->where('order_status', 'completed')->count() / $orders->total()) * 100, 1) : 0 }}%
                                completion rate
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">In Progress</h6>
                                <h3 class="fw-bold mb-0">
                                    {{ $orders->whereIn('order_status', ['pending', 'processing', 'shipped'])->count() }}
                                </h3>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            @php
                                $pendingOrders = $orders->whereIn('order_status', ['pending', 'processing', 'shipped']);
                                $needsAttention = $pendingOrders->where('created_at', '<=', now()->subDays(3))->count();
                            @endphp
                            @if ($needsAttention > 0)
                                <small class="text-danger fw-medium">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $needsAttention }} need attention
                                </small>
                            @else
                                <small class="text-muted">All on track</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-purple bg-opacity-10 rounded p-3 me-3">
                                <i class="fas fa-money-bill-wave text-purple fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Spent</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($orders->sum('total_amount'), 2) }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-credit-card me-1"></i>
                                Avg. order:
                                ৳{{ $orders->count() > 0 ? number_format($orders->avg('total_amount'), 2) : '0.00' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <div class="row g-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-medium">Status Filter</label>
                                        <select class="form-select">
                                            <option value="all">All Orders</option>
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="form-label fw-medium">Date Range</label>
                                        <select class="form-select">
                                            <option value="all">All Time</option>
                                            <option value="last30">Last 30 Days</option>
                                            <option value="last90">Last 90 Days</option>
                                            <option value="2024">2024</option>
                                            <option value="2023">2023</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button class="btn btn-primary w-100">
                                            <i class="fas fa-filter me-2"></i>Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                                    <button class="btn btn-outline-secondary">
                                        <i class="fas fa-file-export me-2"></i>Export
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="fas fa-sort me-2"></i>Sort By
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Newest First</a></li>
                                            <li><a class="dropdown-item" href="#">Oldest First</a></li>
                                            <li><a class="dropdown-item" href="#">Highest Amount</a></li>
                                            <li><a class="dropdown-item" href="#">Lowest Amount</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-list-alt me-2"></i>
                                    Order History
                                </h5>
                                <small class="opacity-75">Showing {{ $orders->count() }} of {{ $orders->total() }}
                                    orders</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if ($orders->count())
                            <!-- Mobile View (Cards) -->
                            <div class="d-md-none">
                                @foreach ($orders as $order)
                                    <div class="order-card p-3 m-3 bg-white rounded border">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fas fa-box text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="order-id mb-1">#{{ $order->order_number }}</h6>
                                                    <small class="order-date">
                                                        {{ $order->created_at->format('d M Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div>
                                                @php
                                                    $statusClass = 'status-' . $order->order_status;
                                                @endphp
                                                <span class="status-badge {{ $statusClass }}">
                                                    <i class="fas fa-circle" style="font-size: 8px;"></i>
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Items</small>
                                                <span
                                                    class="fw-medium">{{ $order->items_count ?? $order->items->count() }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Amount</small>
                                                <div class="amount">৳{{ number_format($order->total_amount, 2) }}</div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    {{ ucfirst($order->payment_method ?? 'Card') }}
                                                </small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('customer.orders.show', $order->id) }}"
                                                    class="action-btn btn-view">
                                                    <i class="fas fa-eye"></i>
                                                    <span>View</span>
                                                </a>
                                                <button class="action-btn btn-invoice">
                                                    <i class="fas fa-file-invoice"></i>
                                                    <span>Invoice</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Desktop View (Table) -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 py-3">
                                                <i class="fas fa-hashtag me-2"></i>Order #
                                            </th>
                                            <th class="py-3">
                                                <i class="fas fa-calendar-alt me-2"></i>Date
                                            </th>
                                            <th class="py-3">
                                                <i class="fas fa-tag me-2"></i>Status
                                            </th>
                                            <th class="py-3">
                                                <i class="fas fa-cube me-2"></i>Items
                                            </th>
                                            <th class="py-3">
                                                <i class="fas fa-money-bill me-2"></i>Amount
                                            </th>
                                            <th class="pe-4 py-3 text-end">
                                                <i class="fas fa-cogs me-2"></i>Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr class="align-middle">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                            <i class="fas fa-box-open text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="order-id">#{{ $order->order_number }}</div>
                                                            @if ($order->is_urgent ?? false)
                                                                <small class="badge bg-danger">
                                                                    <i class="fas fa-exclamation-circle me-1"></i>Urgent
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                                    <small class="order-date">
                                                        <i
                                                            class="far fa-clock me-1"></i>{{ $order->created_at->format('h:i A') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusConfig = [
                                                            'pending' => [
                                                                'class' => 'status-pending',
                                                                'icon' => 'fas fa-clock',
                                                            ],
                                                            'processing' => [
                                                                'class' => 'status-processing',
                                                                'icon' => 'fas fa-cogs',
                                                            ],
                                                            'completed' => [
                                                                'class' => 'status-completed',
                                                                'icon' => 'fas fa-check-circle',
                                                            ],
                                                            'cancelled' => [
                                                                'class' => 'status-cancelled',
                                                                'icon' => 'fas fa-times-circle',
                                                            ],
                                                            'shipped' => [
                                                                'class' => 'status-shipped',
                                                                'icon' => 'fas fa-shipping-fast',
                                                            ],
                                                        ];
                                                        $status = $statusConfig[$order->order_status] ?? [
                                                            'class' => 'status-pending',
                                                            'icon' => 'fas fa-question-circle',
                                                        ];
                                                    @endphp
                                                    <span class="status-badge {{ $status['class'] }}">
                                                        <i class="{{ $status['icon'] }} me-1"></i>
                                                        {{ ucfirst($order->order_status) }}
                                                    </span>
                                                    @if ($order->order_status == 'shipped' && $order->tracking_number)
                                                        <div class="mt-1">
                                                            <small class="text-muted">
                                                                <i class="fas fa-truck me-1"></i>
                                                                {{ $order->tracking_number }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-medium">
                                                            {{ $order->items_count ?? $order->items->count() }} items
                                                        </span>
                                                        @if (($order->items_count ?? $order->items->count()) > 5)
                                                            <span class="badge bg-secondary ms-2">
                                                                +{{ ($order->items_count ?? $order->items->count()) - 5 }}
                                                                more
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="amount">৳{{ number_format($order->total_amount, 2) }}
                                                    </div>
                                                    <div class="payment-method">
                                                        <i class="fas fa-credit-card me-1"></i>
                                                        {{ ucfirst($order->payment_method ?? 'Card') }}
                                                        @if ($order->payment_status == 'paid')
                                                            <span class="badge bg-success ms-2">
                                                                <i class="fas fa-check me-1"></i>Paid
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('customer.orders.show', $order->id) }}"
                                                            class="action-btn btn-view" title="View Order Details">
                                                            <i class="fas fa-eye"></i>
                                                            <span>View</span>
                                                        </a>
                                                        @if (in_array($order->order_status, ['confirmed', 'processing', 'ready_to_ship', 'shipped', 'delivered', 'completed']))
                                                            <a href="{{ route('customer.orders.tracking', $order->id) }}"
                                                                class="action-btn btn-track" title="Track Order">
                                                                <i class="fas fa-map-marked-alt"></i>
                                                                <span>Track</span>
                                                            </a>
                                                        @endif
                                                        @if ($order->order_status == 'completed')
                                                            <button class="action-btn btn-reorder" title="Reorder Items">
                                                                <i class="fas fa-redo"></i>
                                                                <span>Reorder</span>
                                                            </button>
                                                        @endif
                                                        <button class="action-btn btn-invoice" title="Download Invoice">
                                                            <i class="fas fa-download"></i>
                                                            <span>Invoice</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="card-footer bg-white border-top">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                    <div class="mb-3 mb-md-0">
                                        <p class="mb-1 text-muted">
                                            Showing <strong>{{ $orders->firstItem() }}</strong> to
                                            <strong>{{ $orders->lastItem() }}</strong> of
                                            <strong>{{ $orders->total() }}</strong> orders
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}
                                        </small>
                                    </div>
                                    <div>
                                        @if ($orders->hasPages())
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination mb-0">
                                                    {{-- Previous Page Link --}}
                                                    @if ($orders->onFirstPage())
                                                        <li class="page-item disabled">
                                                            <span class="page-link">
                                                                <i class="fas fa-chevron-left"></i>
                                                            </span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $orders->previousPageUrl() }}">
                                                                <i class="fas fa-chevron-left"></i>
                                                            </a>
                                                        </li>
                                                    @endif

                                                    {{-- Pagination Elements --}}
                                                    @foreach (range(1, $orders->lastPage()) as $i)
                                                        @if ($i == $orders->currentPage())
                                                            <li class="page-item active">
                                                                <span class="page-link">{{ $i }}</span>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="{{ $orders->url($i) }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach

                                                    {{-- Next Page Link --}}
                                                    @if ($orders->hasMorePages())
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $orders->nextPageUrl() }}">
                                                                <i class="fas fa-chevron-right"></i>
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li class="page-item disabled">
                                                            <span class="page-link">
                                                                <i class="fas fa-chevron-right"></i>
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </nav>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h3 class="h4 fw-bold text-muted mb-3">No Orders Yet</h3>
                                <p class="text-muted mb-4 max-w-md mx-auto">
                                    You haven't placed any orders yet. Browse our products and make your first purchase!
                                </p>
                                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-store me-2"></i>
                                        Start Shopping
                                    </a>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg px-4">
                                        <i class="fas fa-boxes me-2"></i>
                                        Browse Products
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Help & Support Section -->
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card help-card bg-blue h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary bg-opacity-25 rounded p-3 me-3">
                                <i class="fas fa-headset fs-4 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-primary mb-2">Need Help?</h5>
                                <p class="card-text text-primary text-opacity-75 mb-3">
                                    Our customer support team is here to help you with any questions about your orders.
                                </p>
                                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card help-card bg-green h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-25 rounded p-3 me-3">
                                <i class="fas fa-undo-alt fs-4 text-success"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-success mb-2">Return Policy</h5>
                                <p class="card-text text-success text-opacity-75 mb-3">
                                    Not satisfied with a purchase? Check our easy return and refund policy.
                                </p>
                                <a href="#" class="btn btn-outline-success">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    View Return Policy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card help-card bg-purple h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="bg-purple bg-opacity-25 rounded p-3 me-3">
                                <i class="fas fa-shipping-fast fs-4 text-purple"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-purple mb-2">Track Delivery</h5>
                                <p class="card-text text-purple text-opacity-75 mb-3">
                                    Track your shipped orders in real-time and get delivery updates.
                                </p>
                                <a href="#" class="btn btn-outline-purple">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Track Package
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-gradient bg-dark border-0 shadow-lg overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="mb-4 mb-md-0 text-center text-md-start">
                                <h3 class="h4 text-white mb-2">Quick Actions</h3>
                                <p class="text-white text-opacity-75 mb-0">Manage your orders and account settings</p>
                            </div>
                            <div class="row g-3 w-100 w-md-auto">
                                <div class="col-6 col-md-3">
                                    <a href="{{ route('profile.edit') }}"
                                        class="quick-action-card text-center text-decoration-none">
                                        <i class="fas fa-user-cog fs-3 mb-2 text-info"></i>
                                        <div class="text-white fw-medium">Profile</div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="{{ route('wishlist.index') }}"
                                        class="quick-action-card text-center text-decoration-none">
                                        <i class="fas fa-heart fs-3 mb-2 text-danger"></i>
                                        <div class="text-white fw-medium">Wishlist</div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="#" class="quick-action-card text-center text-decoration-none">
                                        <i class="fas fa-map-marker-alt fs-3 mb-2 text-success"></i>
                                        <div class="text-white fw-medium">Addresses</div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="#" class="quick-action-card text-center text-decoration-none">
                                        <i class="fas fa-star fs-3 mb-2 text-warning"></i>
                                        <div class="text-white fw-medium">Reviews</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const applyFiltersBtn = document.querySelector('.btn-primary:contains("Apply Filters")');
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', function() {
                    const statusFilter = document.querySelector('select:first-of-type');
                    const dateFilter = document.querySelectorAll('select')[1];

                    const status = statusFilter ? statusFilter.value : 'all';
                    const dateRange = dateFilter ? dateFilter.value : 'all';

                    // Implement filter logic here
                    console.log('Applying filters:', {
                        status,
                        dateRange
                    });
                    // You would typically make an AJAX request or redirect with query parameters
                });
            }

            // Search functionality
            const searchInput = document.querySelector('.search-box input');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const searchTerm = e.target.value.trim();
                        if (searchTerm.length >= 2 || searchTerm.length === 0) {
                            // Implement search logic here
                            console.log('Searching for:', searchTerm);
                            // You would typically make an AJAX request
                        }
                    }, 500);
                });
            }

            // Order actions
            document.querySelectorAll('.action-btn').forEach(element => {
                element.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#' || !this.getAttribute('href')) {
                        e.preventDefault();
                        const btnClass = this.classList;

                        if (btnClass.contains('btn-reorder')) {
                            alert('Reorder functionality coming soon!');
                        } else if (btnClass.contains('btn-invoice')) {
                            alert('Invoice download coming soon!');
                        } else if (btnClass.contains('btn-track')) {
                            alert('Package tracking coming soon!');
                        }
                    }
                });
            });

            // Export button
            const exportBtn = document.querySelector('.btn-outline-secondary:contains("Export")');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    alert('Export functionality coming soon!');
                });
            }

            // Add Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
