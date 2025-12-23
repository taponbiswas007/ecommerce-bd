@extends('admin.layouts.master')

@section('title', 'Edit Price Tier')
@section('page-title', 'Edit Price Tier')
@section('page-subtitle', 'Update tiered pricing for: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.prices.index', $product->id) }}">Prices</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@push('styles')
    <style>
        .price-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Price Tier</h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ route('admin.products.prices.update', ['product' => $product->id, 'price' => $price->id]) }}"
                        method="POST" id="priceForm">
                        @csrf
                        @method('PUT')

                        <!-- Current Price Summary -->
                        <div class="price-summary">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <small class="text-muted d-block">Quantity Range</small>
                                    <h4>{{ $price->quantity_range }}</h4>
                                </div>
                                <div class="col-md-4 text-center">
                                    <small class="text-muted d-block">Current Price</small>
                                    <div class="summary-value">
                                        {{ $price->formatted_price }}
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <small class="text-muted d-block">Discount</small>
                                    @php
                                        $saving = $product->base_price - $price->price;
                                        $savingPercentage =
                                            $product->base_price > 0 ? ($saving / $product->base_price) * 100 : 0;
                                    @endphp
                                    @if ($saving > 0)
                                        <h4 class="text-success">{{ number_format($savingPercentage, 1) }}%</h4>
                                    @else
                                        <h4 class="text-muted">-</h4>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity Range -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_quantity" class="form-label">Minimum Quantity *</label>
                                    <input type="number" class="form-control @error('min_quantity') is-invalid @enderror"
                                        id="min_quantity" name="min_quantity"
                                        value="{{ old('min_quantity', $price->min_quantity) }}"
                                        min="{{ $product->min_order_quantity }}" required>
                                    <small class="text-muted">
                                        Must be at least {{ $product->min_order_quantity }} (product's minimum order)
                                    </small>
                                    @error('min_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_quantity" class="form-label">Maximum Quantity</label>
                                    <input type="number" class="form-control @error('max_quantity') is-invalid @enderror"
                                        id="max_quantity" name="max_quantity"
                                        value="{{ old('max_quantity', $price->max_quantity) }}" min="1">
                                    <small class="text-muted">
                                        Leave empty for unlimited (e.g., "100+")
                                    </small>
                                    @error('max_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label for="price" class="form-label">Price per Unit *</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ config('app.currency_symbol') }}</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" step="0.01" min="0"
                                    value="{{ old('price', $price->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Price for each unit in this quantity range
                            </small>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Price Tier
                            </button>
                            <a href="{{ route('admin.products.prices.index', $product->id) }}" class="btn btn-light">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Product Info -->
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
                </div>
            </div>

            <!-- Other Price Tiers -->
            @if ($product->prices->count() > 1)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Other Price Tiers</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->prices as $otherPrice)
                                        @if ($otherPrice->id != $price->id)
                                            <tr class="{{ $otherPrice->id == $price->id ? 'table-active' : '' }}">
                                                <td>{{ $otherPrice->quantity_range }}</td>
                                                <td>{{ $otherPrice->formatted_price }}</td>
                                                <td>
                                                    @php
                                                        $saving = $product->base_price - $otherPrice->price;
                                                        $savingPercentage =
                                                            $product->base_price > 0
                                                                ? ($saving / $product->base_price) * 100
                                                                : 0;
                                                    @endphp
                                                    @if ($saving > 0)
                                                        <span class="text-success">
                                                            {{ number_format($savingPercentage, 1) }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info mt-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Ensure your updated price tier doesn't overlap with other ranges.
                            </small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Delete Option -->
            <div class="card mt-4">
                <div class="card-body text-center">
                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i> Delete This Price Tier
                    </button>
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

        // Form validation
        document.getElementById('priceForm').addEventListener('submit', function(e) {
            const minQty = parseInt(document.getElementById('min_quantity').value);
            const maxQty = document.getElementById('max_quantity').value ?
                parseInt(document.getElementById('max_quantity').value) : null;
            const price = parseFloat(document.getElementById('price').value);

            // Validate min quantity
            if (minQty < {{ $product->min_order_quantity }}) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: `Minimum quantity must be at least {{ $product->min_order_quantity }}`
                });
                return;
            }

            // Validate max quantity
            if (maxQty && maxQty <= minQty) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Maximum quantity must be greater than minimum quantity'
                });
                return;
            }

            // Validate price
            if (price <= 0) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Price must be greater than 0'
                });
                return;
            }
        });
    </script>
@endpush
