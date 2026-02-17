@extends('admin.layouts.master')

@section('title', 'Product Details')
@section('page-title', 'Product Details')
@section('page-subtitle', $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.dropshipping.products.index') }}">Dropshipping Products</a></li>
    <li class="breadcrumb-item active">{{ $product->name }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.dropshipping.products.edit', $product->id) }}" class="btn btn-primary">
        <i class="fas fa-edit me-2"></i> Edit
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" class="img-fluid rounded mb-3" alt="{{ $product->name }}">
                    @else
                        <div class="bg-light rounded mb-3"
                            style="height: 300px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Pricing Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Cost Price (CJ)</small>
                        <div class="h5 mb-0">{{ number_format($product->unit_price, 2) }} ৳</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Selling Price</small>
                        <div class="h5 mb-0 text-primary">{{ number_format($product->selling_price, 2) }} ৳</div>
                    </div>
                    <div>
                        <small class="text-muted">Profit Margin</small>
                        <div class="h5 mb-0 text-success">{{ number_format($product->profit_margin, 2) }} ৳</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Product Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>CJ Product ID:</strong><br>
                            <code>{{ $product->cj_product_id }}</code>
                        </div>
                        <div class="col-md-6">
                            <strong>SKU:</strong><br>
                            {{ $product->sku ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Category:</strong><br>
                            {{ $product->category ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Sub Category:</strong><br>
                            {{ $product->sub_category ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Description:</strong><br>
                        <p class="text-muted">{{ $product->description ?? 'No description' }}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Stock:</strong><br>
                            @if ($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }} in stock</span>
                            @else
                                <span class="badge bg-danger">Out of stock</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Min Order Qty:</strong><br>
                            {{ $product->minimum_order_quantity }}
                        </div>
                        <div class="col-md-4">
                            <strong>Status:</strong><br>
                            @if ($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Orders -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Orders Using This Product</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><a
                                            href="{{ route('admin.dropshipping.orders.show', $order->dropshippingOrder->id) }}">
                                            {{ $order->dropshippingOrder->order->order_number }}</a></td>
                                    <td>{{ $order->dropshippingOrder->order->user->name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ number_format($order->total_selling_price, 2) }} ৳</td>
                                    <td><span
                                            class="badge bg-info">{{ ucfirst($order->dropshippingOrder->cj_order_status) }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No orders yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="card-footer bg-light">
                        {{ $orders->render() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
