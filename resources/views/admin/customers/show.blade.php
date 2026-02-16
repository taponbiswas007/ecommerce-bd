@extends('admin.layouts.master')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')
@section('page-subtitle', $customer->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning btn-sm me-2">
        <i class="fas fa-edit me-1"></i> Edit Customer
    </a>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
@endsection

@push('styles')
    <style>
        .customer-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .customer-avatar-large {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            background: #fff;
        }

        .stat-card {
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="customer-header">
        <div class="d-flex align-items-center gap-3">
            @if ($customer->user_image)
                <img src="{{ asset('storage/' . $customer->user_image) }}" alt="{{ $customer->name }}"
                    class="customer-avatar-large">
            @else
                <div class="customer-avatar-large d-flex align-items-center justify-content-center text-dark">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h4 class="mb-1">{{ $customer->name }}</h4>
                <div class="small">{{ $customer->email }}</div>
                <div class="small">{{ $customer->phone ?? 'N/A' }}</div>
            </div>
            <div class="ms-auto">
                <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-primary text-white">
                <div class="fw-bold">{{ $customer->orders->count() }}</div>
                <div class="small">Total Orders</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-info text-white">
                <div class="fw-bold">{{ $customer->created_at->format('M d, Y') }}</div>
                <div class="small">Joined</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-warning text-white">
                <div class="fw-bold">{{ $customer->district ?? 'N/A' }}</div>
                <div class="small">District</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-secondary text-white">
                <div class="fw-bold">{{ $customer->upazila ?? 'N/A' }}</div>
                <div class="small">Upazila</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border shadow-sm rounded mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact & Address</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2"><strong>Email:</strong> {{ $customer->email }}</div>
                    <div class="mb-2"><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>District:</strong> {{ $customer->district ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Upazila:</strong> {{ $customer->upazila ?? 'N/A' }}</div>
                    <div class="mb-0"><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if ($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->orders->take(5) as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status_color }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.orders.index', ['customer' => $customer->id]) }}"
                                class="btn btn-sm btn-outline-primary">
                                View All Orders
                            </a>
                        </div>
                    @else
                        <p class="text-muted mb-0">No orders found for this customer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
