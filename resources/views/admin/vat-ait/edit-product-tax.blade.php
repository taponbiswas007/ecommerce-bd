@extends('admin.layouts.master')

@section('title', 'Edit Product Tax - ' . $product->name)

@section('content')
    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>There were some issues:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-2">
                    <i class="fas fa-box text-primary me-2"></i>{{ $product->name }}
                </h1>
                <p class="text-muted mb-0">
                    Configure VAT and AIT settings for this product
                </p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.vat-ait.products') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <form action="{{ route('admin.vat-ait.update-product', $product) }}" method="POST" id="taxForm">
            @csrf
            <div class="row">
                <!-- VAT Section -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-percent text-info me-2"></i>VAT Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Global VAT Display -->
                            <div class="alert alert-info mb-3">
                                <strong>Global VAT Rate:</strong> {{ $globalSettings->default_vat_percentage }}%
                                ({{ $globalSettings->vat_included_in_price ? 'Included' : 'Added' }})
                            </div>

                            <!-- Override VAT Toggle -->
                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="override_vat" name="override_vat"
                                        value="1"
                                        {{ old('override_vat', $override?->override_vat ?? false) ? 'checked' : '' }}
                                        onchange="toggleVatFields()">
                                    <label class="form-check-label fw-bold" for="override_vat">
                                        Override VAT for this product
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Enable to set a custom VAT percentage for this product
                                </small>
                            </div>

                            <!-- Custom VAT Percentage -->
                            <div class="form-group mb-3" id="vat_percentage_group" style="display: none;">
                                <label for="vat_percentage" class="form-label fw-bold">Custom VAT Percentage (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="vat_percentage" name="vat_percentage"
                                        step="0.01" min="0" max="100"
                                        value="{{ old('vat_percentage', $override?->vat_percentage ?? '') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Leave empty to use global rate. Set to 0 to exempt from VAT.
                                </small>
                            </div>

                            <!-- VAT Handling for this Product -->
                            <div class="form-group mb-3">
                                <label for="vat_included_in_price" class="form-label fw-bold">VAT Handling for this
                                    Product</label>
                                <select class="form-select" id="vat_included_in_price" name="vat_included_in_price">
                                    <option value="">Use Global Setting</option>
                                    <option value="1"
                                        {{ old('vat_included_in_price', $override?->vat_included_in_price) === '1' ? 'selected' : '' }}>
                                        Included in Price
                                    </option>
                                    <option value="0"
                                        {{ old('vat_included_in_price', $override?->vat_included_in_price) === '0' ? 'selected' : '' }}>
                                        Added at Checkout
                                    </option>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    Override how VAT is handled for this specific product
                                </small>
                            </div>

                            <!-- VAT Exemption -->
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="vat_exempt" name="vat_exempt"
                                        value="1"
                                        {{ old('vat_exempt', $override?->vat_exempt ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vat_exempt">
                                        <strong>Exempt this product from VAT</strong>
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    (Typically for essential commodities or government-mandated exemptions)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AIT Section -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-percent text-info me-2"></i>AIT Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Global AIT Display -->
                            <div class="alert alert-info mb-3">
                                <strong>Global AIT Rate:</strong> {{ $globalSettings->default_ait_percentage }}%
                                ({{ $globalSettings->ait_included_in_price ? 'Included' : 'Added' }})
                            </div>

                            <!-- Override AIT Toggle -->
                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="override_ait" name="override_ait"
                                        value="1"
                                        {{ old('override_ait', $override?->override_ait ?? false) ? 'checked' : '' }}
                                        onchange="toggleAitFields()">
                                    <label class="form-check-label fw-bold" for="override_ait">
                                        Override AIT for this product
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Enable to set a custom AIT percentage for this product
                                </small>
                            </div>

                            <!-- Custom AIT Percentage -->
                            <div class="form-group mb-3" id="ait_percentage_group" style="display: none;">
                                <label for="ait_percentage" class="form-label fw-bold">Custom AIT Percentage (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="ait_percentage" name="ait_percentage"
                                        step="0.01" min="0" max="100"
                                        value="{{ old('ait_percentage', $override?->ait_percentage ?? '') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Leave empty to use global rate. Set to 0 to exempt from AIT.
                                </small>
                            </div>

                            <!-- AIT Handling for this Product -->
                            <div class="form-group mb-3">
                                <label for="ait_included_in_price" class="form-label fw-bold">AIT Handling for this
                                    Product</label>
                                <select class="form-select" id="ait_included_in_price" name="ait_included_in_price">
                                    <option value="">Use Global Setting</option>
                                    <option value="1"
                                        {{ old('ait_included_in_price', $override?->ait_included_in_price) === '1' ? 'selected' : '' }}>
                                        Included in Price
                                    </option>
                                    <option value="0"
                                        {{ old('ait_included_in_price', $override?->ait_included_in_price) === '0' ? 'selected' : '' }}>
                                        Added at Checkout
                                    </option>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    Override how AIT is handled for this specific product
                                </small>
                            </div>

                            <!-- AIT Exemption -->
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="ait_exempt" name="ait_exempt"
                                        value="1"
                                        {{ old('ait_exempt', $override?->ait_exempt ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ait_exempt">
                                        <strong>Exempt this product from AIT</strong>
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    (Typically for essential commodities or export products)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason & Dates -->
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Additional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="reason" class="form-label fw-bold">Reason for Override</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3"
                                            placeholder="e.g., Essential commodity, Government exemption, Export product...">{{ old('reason', $override?->reason ?? '') }}</textarea>
                                        <small class="text-muted d-block mt-2">
                                            Document why this product has custom tax settings (for audit purposes)
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="effective_from" class="form-label fw-bold">Effective From</label>
                                        <input type="datetime-local" class="form-control" id="effective_from"
                                            name="effective_from"
                                            value="{{ old('effective_from', $override?->effective_from?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="effective_until" class="form-label fw-bold">Effective Until</label>
                                        <input type="datetime-local" class="form-control" id="effective_until"
                                            name="effective_until"
                                            value="{{ old('effective_until', $override?->effective_until?->format('Y-m-d\TH:i') ?? '') }}">
                                        <small class="text-muted d-block mt-2">
                                            Leave empty for permanent override
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info Summary -->
                <div class="col-lg-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">Product Information</h6>
                            <div class="row small">
                                <div class="col-6">
                                    <strong>SKU:</strong> {{ $product->id }}<br>
                                    <strong>Name:</strong> {{ Str::limit($product->name, 40) }}<br>
                                    <strong>Category:</strong> {{ $product->category?->name ?? 'N/A' }}
                                </div>
                                <div class="col-6">
                                    <strong>Price:</strong> ৳{{ number_format($product->final_price, 2) }}<br>
                                    <strong>Stock:</strong> {{ $product->stock_quantity }} units<br>
                                    <strong>Status:</strong>
                                    @if ($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Override Status -->
                @if ($override)
                    <div class="col-lg-6">
                        <div class="card bg-light border-warning">
                            <div class="card-body">
                                <h6 class="mb-3">Current Override Status</h6>
                                <div class="row small">
                                    <div class="col-6">
                                        <strong>Created:</strong> {{ $override->created_at->format('M d, Y H:i') }}<br>
                                        <strong>Updated:</strong> {{ $override->updated_at->format('M d, Y H:i') }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Effective From:</strong>
                                        {{ $override->effective_from ? $override->effective_from->format('M d, Y H:i') : 'Not set' }}<br>
                                        <strong>Status:</strong>
                                        @if ($override->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Save Tax Settings
                </button>
                <a href="{{ route('admin.vat-ait.products') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleVatFields() {
            const checked = document.getElementById('override_vat').checked;
            document.getElementById('vat_percentage_group').style.display = checked ? 'block' : 'none';

            // Auto-uncheck VAT exempt when override is disabled
            if (!checked) {
                document.getElementById('vat_exempt').checked = false;
            }
        }

        function toggleAitFields() {
            const checked = document.getElementById('override_ait').checked;
            document.getElementById('ait_percentage_group').style.display = checked ? 'block' : 'none';

            // Auto-uncheck AIT exempt when override is disabled
            if (!checked) {
                document.getElementById('ait_exempt').checked = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleVatFields();
            toggleAitFields();
        });
    </script>
@endsection
