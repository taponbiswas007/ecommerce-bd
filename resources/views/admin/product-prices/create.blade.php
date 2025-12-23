@extends('admin.layouts.master')

@section('title', 'Add Price Tier')
@section('page-title', 'Add Price Tier')
@section('page-subtitle', 'Create new tiered pricing for: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.prices.index', $product->id) }}">Prices</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@push('styles')
    <style>
        .price-preview {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .preview-value {
            font-size: 2rem;
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
                    <h5 class="card-title mb-0">Add New Price Tier</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.prices.store', $product->id) }}" method="POST" id="priceForm">
                        @csrf

                        <div class="row">
                            <!-- Quantity Range -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_quantity" class="form-label">Minimum Quantity *</label>
                                    <input type="number" class="form-control @error('min_quantity') is-invalid @enderror"
                                        id="min_quantity" name="min_quantity"
                                        value="{{ old('min_quantity', $product->min_order_quantity) }}"
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
                                        id="max_quantity" name="max_quantity" value="{{ old('max_quantity') }}"
                                        min="1">
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
                                    id="price" name="price" step="0.01" min="0" value="{{ old('price') }}"
                                    required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Price for each unit in this quantity range
                            </small>
                        </div>

                        <!-- Auto-calculate discount -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="auto_calculate" name="auto_calculate"
                                    value="1">
                                <label class="form-check-label" for="auto_calculate">
                                    Auto-calculate discount percentage
                                </label>
                            </div>
                            <small class="text-muted">
                                When checked, system will suggest optimal price based on base price
                            </small>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Price Tier
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

            <!-- Price Preview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Price Preview</h6>
                </div>
                <div class="card-body">
                    <div class="price-preview text-center">
                        <div class="mb-2">
                            <small class="text-muted">Quantity Range</small>
                            <h4 id="previewQuantity">1-10</h4>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Price per Unit</small>
                            <div class="preview-value" id="previewPrice">
                                {{ config('app.currency_symbol') }}0.00
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Total Price</small>
                            <h5 id="previewTotal">{{ config('app.currency_symbol') }}0.00</h5>
                        </div>

                        <div class="mt-4">
                            <small class="text-muted">Compared to Base Price</small>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between">
                                    <span>Base:</span>
                                    <span>{{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Your Price:</span>
                                    <span id="previewComparison">{{ config('app.currency_symbol') }}0.00</span>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between">
                                    <strong>Saving:</strong>
                                    <strong class="text-success"
                                        id="previewSaving">{{ config('app.currency_symbol') }}0.00</strong>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted" id="previewPercentage">0% discount</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Existing Price Tiers -->
            @if ($product->prices->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Existing Price Tiers</h6>
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
                                    @foreach ($product->prices as $price)
                                        <tr>
                                            <td>{{ $price->quantity_range }}</td>
                                            <td>{{ $price->formatted_price }}</td>
                                            <td>
                                                @php
                                                    $saving = $product->base_price - $price->price;
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info mt-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Ensure your new price tier doesn't overlap with existing ranges.
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const minQtyInput = document.getElementById('min_quantity');
            const maxQtyInput = document.getElementById('max_quantity');
            const priceInput = document.getElementById('price');
            const basePrice = {{ $product->base_price }};

            // Auto-calculate checkbox
            const autoCalculateCheckbox = document.getElementById('auto_calculate');

            // Update preview function
            function updatePreview() {
                const minQty = parseInt(minQtyInput.value) || 1;
                const maxQty = maxQtyInput.value ? parseInt(maxQtyInput.value) : null;
                const price = parseFloat(priceInput.value) || 0;

                // Update quantity preview
                if (maxQty) {
                    document.getElementById('previewQuantity').textContent = `${minQty}-${maxQty}`;
                    document.getElementById('previewTotal').textContent =
                        `{{ config('app.currency_symbol') }}${(price * maxQty).toFixed(2)}`;
                } else {
                    document.getElementById('previewQuantity').textContent = `${minQty}+`;
                    document.getElementById('previewTotal').textContent = 'Unlimited';
                }

                // Update price preview
                document.getElementById('previewPrice').textContent =
                    `{{ config('app.currency_symbol') }}${price.toFixed(2)}`;
                document.getElementById('previewComparison').textContent =
                    `{{ config('app.currency_symbol') }}${price.toFixed(2)}`;

                // Calculate savings
                if (price > 0 && basePrice > 0) {
                    const saving = basePrice - price;
                    const savingPercentage = (saving / basePrice) * 100;

                    document.getElementById('previewSaving').textContent =
                        `{{ config('app.currency_symbol') }}${saving.toFixed(2)}`;
                    document.getElementById('previewPercentage').textContent =
                        `${savingPercentage.toFixed(1)}% discount`;

                    if (saving > 0) {
                        document.getElementById('previewSaving').className = 'text-success';
                        document.getElementById('previewPercentage').className = 'text-success';
                    } else if (saving < 0) {
                        document.getElementById('previewSaving').className = 'text-danger';
                        document.getElementById('previewPercentage').className = 'text-danger';
                        document.getElementById('previewPercentage').textContent =
                            `${Math.abs(savingPercentage).toFixed(1)}% increase`;
                    } else {
                        document.getElementById('previewSaving').className = 'text-muted';
                        document.getElementById('previewPercentage').className = 'text-muted';
                        document.getElementById('previewPercentage').textContent = 'No change';
                    }
                }
            }

            // Auto-calculate price based on quantity
            function autoCalculatePrice() {
                if (!autoCalculateCheckbox.checked) return;

                const minQty = parseInt(minQtyInput.value) || 1;

                // Simple discount formula: more quantity = more discount
                // You can adjust this formula as needed
                let discountPercentage = 0;

                if (minQty >= 100) {
                    discountPercentage = 25;
                } else if (minQty >= 50) {
                    discountPercentage = 20;
                } else if (minQty >= 25) {
                    discountPercentage = 15;
                } else if (minQty >= 10) {
                    discountPercentage = 10;
                } else if (minQty >= 5) {
                    discountPercentage = 5;
                }

                const calculatedPrice = basePrice * (1 - discountPercentage / 100);
                priceInput.value = calculatedPrice.toFixed(2);

                updatePreview();
            }

            // Event listeners
            [minQtyInput, maxQtyInput, priceInput].forEach(input => {
                input.addEventListener('input', updatePreview);
            });

            minQtyInput.addEventListener('change', autoCalculatePrice);
            autoCalculateCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    autoCalculatePrice();
                }
            });

            // Initial preview
            updatePreview();

            // Form validation
            document.getElementById('priceForm').addEventListener('submit', function(e) {
                const minQty = parseInt(minQtyInput.value);
                const maxQty = maxQtyInput.value ? parseInt(maxQtyInput.value) : null;
                const price = parseFloat(priceInput.value);

                // Validate min quantity
                if (minQty < {{ $product->min_order_quantity }}) {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'error',
                        title: `Minimum quantity must be at least {{ $product->min_order_quantity }}`
                    });
                    minQtyInput.focus();
                    return;
                }

                // Validate max quantity
                if (maxQty && maxQty <= minQty) {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'error',
                        title: 'Maximum quantity must be greater than minimum quantity'
                    });
                    maxQtyInput.focus();
                    return;
                }

                // Validate price
                if (price <= 0) {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'error',
                        title: 'Price must be greater than 0'
                    });
                    priceInput.focus();
                    return;
                }
            });
        });
    </script>
@endpush
