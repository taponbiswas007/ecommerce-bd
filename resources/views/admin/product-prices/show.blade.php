@extends('admin.layouts.master')

@section('title', 'View Price Tier')
@section('page-title', 'Price Tier Details')
@section('page-subtitle', 'Product: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.prices.index', $product->id) }}">Prices</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@push('styles')
    <style>
        .detail-card {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
        }

        .price-display {
            font-size: 2.5rem;
            font-weight: bold;
            color: #28a745;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Price Tier Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.products.prices.edit', ['product' => $product->id, 'price' => $price->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.products.prices.index', $product->id) }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Price Display -->
                    <div class="text-center mb-4">
                        <div class="price-display">
                            {{ $price->formatted_price }}
                        </div>
                        <p class="text-muted">per unit</p>
                    </div>

                    <div class="row">
                        <!-- Quantity Information -->
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="detail-label mb-3">
                                    <i class="fas fa-box me-2"></i> Quantity Information
                                </h6>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Quantity Range</small>
                                    <h4>{{ $price->quantity_range }}</h4>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Minimum Quantity</small>
                                        <h5>{{ $price->min_quantity }}</h5>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Maximum Quantity</small>
                                        <h5>{{ $price->max_quantity ?: 'Unlimited' }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Comparison -->
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="detail-label mb-3">
                                    <i class="fas fa-chart-line me-2"></i> Price Comparison
                                </h6>
                                @php
                                    $basePrice = $product->base_price;
                                    $saving = $basePrice - $price->price;
                                    $savingPercentage = $basePrice > 0 ? ($saving / $basePrice) * 100 : 0;
                                    $totalSaving = $saving * ($price->max_quantity ?: $price->min_quantity);
                                @endphp

                                <div class="mb-3">
                                    <small class="text-muted d-block">Base Price</small>
                                    <h5 class="text-muted">
                                        {{ config('app.currency_symbol') }}{{ number_format($basePrice, 2) }}
                                    </h5>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted d-block">Saving per Unit</small>
                                    <h5
                                        class="{{ $saving > 0 ? 'text-success' : ($saving < 0 ? 'text-danger' : 'text-muted') }}">
                                        {{ config('app.currency_symbol') }}{{ number_format(abs($saving), 2) }}
                                        @if ($saving > 0)
                                            ({{ number_format($savingPercentage, 1) }}% discount)
                                        @elseif($saving < 0)
                                            ({{ number_format(abs($savingPercentage), 1) }}% increase)
                                        @endif
                                    </h5>
                                </div>

                                @if ($price->max_quantity)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Total Saving at Max Quantity</small>
                                        <h5 class="text-success">
                                            {{ config('app.currency_symbol') }}{{ number_format($totalSaving, 2) }}
                                        </h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Calculation Examples -->
                    <div class="detail-card">
                        <h6 class="detail-label mb-3">
                            <i class="fas fa-calculator me-2"></i> Calculation Examples
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>Compared to Base</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $examples = [
                                            $price->min_quantity,
                                            $price->max_quantity
                                                ? min($price->min_quantity + 10, $price->max_quantity)
                                                : $price->min_quantity + 10,
                                            $price->max_quantity ?: 100,
                                        ];
                                        $examples = array_unique($examples);
                                    @endphp

                                    @foreach ($examples as $qty)
                                        @if ($qty >= $price->min_quantity && (!$price->max_quantity || $qty <= $price->max_quantity))
                                            <tr>
                                                <td>{{ $qty }}</td>
                                                <td>{{ $price->formatted_price }}</td>
                                                <td>{{ config('app.currency_symbol') }}{{ number_format($price->price * $qty, 2) }}
                                                </td>
                                                <td>
                                                    @php
                                                        $baseTotal = $basePrice * $qty;
                                                        $tierTotal = $price->price * $qty;
                                                        $exampleSaving = $baseTotal - $tierTotal;
                                                    @endphp
                                                    <span
                                                        class="{{ $exampleSaving > 0 ? 'text-success' : ($exampleSaving < 0 ? 'text-danger' : 'text-muted') }}">
                                                        {{ config('app.currency_symbol') }}{{ number_format(abs($exampleSaving), 2) }}
                                                        {{ $exampleSaving > 0 ? 'saved' : ($exampleSaving < 0 ? 'extra' : '') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Product Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Product Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if ($product->featured_image)
                            <img src="{{ $product->featured_image->image_url }}" alt="{{ $product->name }}"
                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; margin-right: 15px;">
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted d-block">Base Price</small>
                        <h4 class="text-primary">
                            {{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}
                        </h4>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Minimum Order</small>
                        <h5>{{ $product->min_order_quantity }}</h5>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Stock Quantity</small>
                        <h5 class="{{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $product->stock_quantity }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.prices.edit', ['product' => $product->id, 'price' => $price->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Edit This Tier
                        </a>
                        <a href="{{ route('admin.products.prices.create', $product->id) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i> Add New Tier
                        </a>
                        <a href="{{ route('admin.products.prices.index', $product->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i> View All Tiers
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i> Delete This Tier
                        </button>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Timestamps</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Created At</small>
                        <strong>{{ $price->created_at->format('M d, Y h:i A') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Updated At</small>
                        <strong>{{ $price->updated_at->format('M d, Y h:i A') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm"
        action="{{ route('admin.products.prices.destroy', ['product' => $product->id, 'price' => $price->id]) }}"
        method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this price tier. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@endpush
