@extends('admin.layouts.master')

@section('title', 'Product Prices')
@section('page-title', 'Product Prices')
@section('page-subtitle', 'Manage tiered pricing for: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item active">Prices</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.products.prices.create', $product->id) }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add Price Tier
    </a>
    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-light ms-2">
        <i class="fas fa-arrow-left me-2"></i> Back to Product
    </a>
@endsection

@push('styles')
    <style>
        .price-card {
            border-left: 4px solid #28a745;
            transition: all 0.3s;
        }

        .price-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .price-badge {
            font-size: 0.9em;
            padding: 5px 10px;
        }

        .discount-percentage {
            font-size: 0.8em;
            padding: 2px 6px;
            border-radius: 3px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Price Tiers for {{ $product->name }}</h5>
                    <div class="card-tools">
                        <div class="badge bg-info">
                            Base Price: {{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bulk Actions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                    Bulk Actions
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-danger" onclick="bulkAction('delete')">
                                        <i class="fas fa-trash me-2"></i> Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">Select All</label>
                            </div>
                        </div>
                    </div>

                    @if ($prices->count() > 0)
                        <div class="row">
                            @foreach ($prices as $price)
                                <div class="col-md-6 mb-4">
                                    <div class="card price-card h-100">
                                        <div class="card-body">
                                            <!-- Checkbox -->
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input price-checkbox"
                                                    value="{{ $price->id }}">
                                            </div>

                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="mb-1">{{ $price->formatted_price }}</h5>
                                                    <p class="text-muted mb-0">
                                                        <i class="fas fa-box me-1"></i>
                                                        Quantity:
                                                        <span class="badge bg-primary price-badge">
                                                            {{ $price->quantity_range }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.products.prices.show', ['product' => $product->id, 'price' => $price->id]) }}">
                                                                <i class="fas fa-eye me-2"></i> View
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.products.prices.edit', ['product' => $product->id, 'price' => $price->id]) }}">
                                                                <i class="fas fa-edit me-2"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item text-danger confirm-delete"
                                                                data-id="{{ $price->id }}"
                                                                data-name="Price tier for {{ $price->quantity_range }}">
                                                                <i class="fas fa-trash me-2"></i> Delete
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- Price Comparison -->
                                            @php
                                                $basePrice = $product->base_price;
                                                $saving = $basePrice - $price->price;
                                                $savingPercentage = $basePrice > 0 ? ($saving / $basePrice) * 100 : 0;
                                            @endphp

                                            <div class="mt-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">Compared to base price:</small>
                                                    @if ($saving > 0)
                                                        <span class="badge bg-success discount-percentage">
                                                            Save {{ number_format($savingPercentage, 1) }}%
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <small class="text-muted">
                                                        Base:
                                                        {{ config('app.currency_symbol') }}{{ number_format($basePrice, 2) }}
                                                    </small>
                                                    <small class="text-muted">
                                                        Saving:
                                                        {{ config('app.currency_symbol') }}{{ number_format($saving, 2) }}
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Stats -->
                                            <div class="row mt-3 text-center">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Per Unit</small>
                                                    <strong>{{ config('app.currency_symbol') }}{{ number_format($price->price, 2) }}</strong>
                                                </div>
                                                <div class="col-6">
                                                    @if ($price->max_quantity)
                                                        <small class="text-muted d-block">Total for Max</small>
                                                        <strong>{{ config('app.currency_symbol') }}{{ number_format($price->price * $price->max_quantity, 2) }}</strong>
                                                    @else
                                                        <small class="text-muted d-block">Bulk Pricing</small>
                                                        <strong>Unlimited</strong>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                            <h4>No Price Tiers Found</h4>
                            <p class="text-muted">This product doesn't have any tiered pricing yet.</p>
                            <p class="text-muted small">
                                Base price:
                                <strong>{{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}</strong>
                            </p>
                            <a href="{{ route('admin.products.prices.create', $product->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Add First Price Tier
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Product Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Product Summary</h6>
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
                        <small class="text-muted d-block">Minimum Order Quantity</small>
                        <h5>{{ $product->min_order_quantity }}</h5>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Current Stock</small>
                        <h5 class="{{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $product->stock_quantity }}
                        </h5>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>
                            Price tiers apply when customers order in specified quantities.
                            Minimum quantity for any tier must be at least {{ $product->min_order_quantity }}.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Pricing Guide -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Pricing Guide</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-lightbulb me-2"></i> Best Practices</h6>
                        <ul class="mb-0 small">
                            <li>Start with your base price (1 unit)</li>
                            <li>Add tiers for bulk discounts</li>
                            <li>Ensure ranges don't overlap</li>
                            <li>Lower price for higher quantities</li>
                            <li>Last tier can have unlimited max quantity</li>
                        </ul>
                    </div>

                    @if ($prices->count() > 0)
                        <div class="mt-3">
                            <h6>Current Price Structure</h6>
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
                                        @foreach ($prices as $price)
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deletePriceName"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Form -->
    <form id="bulkActionForm" method="POST" action="{{ route('admin.products.prices.bulk-action', $product->id) }}">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="ids" id="bulkIds">
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkboxes
            document.getElementById('selectAll')?.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.price-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Individual checkbox changes
            document.querySelectorAll('.price-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                });
            });

            function updateSelectAllCheckbox() {
                const checkboxes = document.querySelectorAll('.price-checkbox');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = allChecked;
                }
            }

            // Delete confirmation
            document.querySelectorAll('.confirm-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const priceId = this.getAttribute('data-id');
                    const priceName = this.getAttribute('data-name');

                    document.getElementById('deletePriceName').textContent = priceName;
                    document.getElementById('deleteForm').action =
                        `/admin/products/{{ $product->id }}/prices/${priceId}`;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });
        });

        // Bulk actions
        function bulkAction(action) {
            const selectedIds = [];
            document.querySelectorAll('.price-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select at least one price tier'
                });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete " + selectedIds.length +
                        " price tier(s). This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkAction(action, selectedIds);
                    }
                });
            } else {
                performBulkAction(action, selectedIds);
            }
        }

        function performBulkAction(action, ids) {
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkIds').value = JSON.stringify(ids);
            document.getElementById('bulkActionForm').submit();
        }
    </script>
@endpush
