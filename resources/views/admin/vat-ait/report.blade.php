@extends('admin.layouts.master')

@section('title', 'VAT/AIT Report')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-0">
                    <i class="fas fa-chart-bar text-primary me-2"></i>VAT/AIT Report
                </h1>
                <small class="text-muted">Summary of VAT and AIT configuration across your store</small>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.vat-ait.index') }}" class="btn btn-secondary">
                    <i class="fas fa-cog me-1"></i>Settings
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Total Products</h6>
                                <h2 class="mb-0">{{ number_format($stats['total_products']) }}</h2>
                            </div>
                            <div style="font-size: 40px; color: #0d6efd; opacity: 0.1;">
                                <i class="fas fa-boxes"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Tax Overrides</h6>
                                <h2 class="mb-0">{{ number_format($stats['products_with_override']) }}</h2>
                                <small class="text-muted">
                                    {{ number_format(($stats['products_with_override'] / max($stats['total_products'], 1)) * 100, 1) }}%
                                </small>
                            </div>
                            <div style="font-size: 40px; color: #0d6efd; opacity: 0.1;">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">VAT Exempt</h6>
                                <h2 class="mb-0">{{ number_format($stats['vat_exempt_products']) }}</h2>
                                <small class="text-muted">
                                    {{ number_format(($stats['vat_exempt_products'] / max($stats['total_products'], 1)) * 100, 1) }}%
                                </small>
                            </div>
                            <div style="font-size: 40px; color: #198754; opacity: 0.1;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">AIT Exempt</h6>
                                <h2 class="mb-0">{{ number_format($stats['ait_exempt_products']) }}</h2>
                                <small class="text-muted">
                                    {{ number_format(($stats['ait_exempt_products'] / max($stats['total_products'], 1)) * 100, 1) }}%
                                </small>
                            </div>
                            <div style="font-size: 40px; color: #198754; opacity: 0.1;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Settings Summary -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Current VAT Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p class="text-muted mb-1">Status</p>
                                <h5 class="mb-3">
                                    @if ($currentSettings->vat_enabled)
                                        <span class="badge bg-success px-3 py-2">Enabled</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Disabled</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Rate</p>
                                <h5 class="mb-3">{{ $currentSettings->default_vat_percentage }}%</h5>
                            </div>
                        </div>
                        <p class="text-muted small mb-2"><strong>Handling:</strong>
                            {{ $currentSettings->vat_included_in_price ? 'Included in Price' : 'Added at Checkout' }}</p>
                        <p class="text-muted small mb-0"><strong>Effective From:</strong>
                            {{ $currentSettings->effective_from->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Current AIT Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p class="text-muted mb-1">Status</p>
                                <h5 class="mb-3">
                                    @if ($currentSettings->ait_enabled)
                                        <span class="badge bg-success px-3 py-2">Enabled</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Disabled</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Rate</p>
                                <h5 class="mb-3">{{ $currentSettings->default_ait_percentage }}%</h5>
                            </div>
                        </div>
                        <p class="text-muted small mb-2"><strong>Handling:</strong>
                            {{ $currentSettings->ait_included_in_price ? 'Included in Price' : 'Added at Checkout' }}</p>
                        <p class="text-muted small mb-0"><strong>Effective From:</strong>
                            {{ $currentSettings->effective_from->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products with Custom Tax Rates -->
        @if ($customTaxProducts->count() > 0)
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Products with Custom Tax Rates ({{ $customTaxProducts->count() }})</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>VAT</th>
                                <th>AIT</th>
                                <th>Status</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customTaxProducts as $product)
                                @php
                                    $override = $product->taxOverride;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($product->name, 50) }}</strong><br>
                                        <small class="text-muted">ID: {{ $product->id }}</small>
                                    </td>
                                    <td>{{ $product->category?->name ?? '-' }}</td>
                                    <td>
                                        @if ($override->vat_exempt)
                                            <span class="badge bg-warning">Exempt</span>
                                        @elseif($override->override_vat)
                                            <span class="badge bg-info">{{ $override->vat_percentage }}%</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($override->ait_exempt)
                                            <span class="badge bg-warning">Exempt</span>
                                        @elseif($override->override_ait)
                                            <span class="badge bg-info">{{ $override->ait_percentage }}%</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($override->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($override->reason ?? '-', 30) }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <p class="text-muted">No products have custom tax rates. All products use global settings.</p>
                </div>
            </div>
        @endif

        <!-- Info Section -->
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">VAT Information</h6>
                    </div>
                    <div class="card-body small">
                        <p><strong>What is VAT?</strong></p>
                        <p class="text-muted mb-3">Value Added Tax (VAT) is a consumption tax that is collected at each
                            stage of the supply chain. It's typically included in the final price paid by consumers.</p>
                        <p><strong>In Bangladesh:</strong></p>
                        <ul class="text-muted mb-0">
                            <li>Standard rate: 15%</li>
                            <li>Applied to most goods and services</li>
                            <li>Some essentials may be exempt</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">AIT Information</h6>
                    </div>
                    <div class="card-body small">
                        <p><strong>What is AIT?</strong></p>
                        <p class="text-muted mb-3">Advance Income Tax (AIT) is a withholding tax on domestic purchases that
                            must be deducted and remitted to tax authorities. It's typically withheld at the point of sale.
                        </p>
                        <p><strong>In Bangladesh:</strong></p>
                        <ul class="text-muted mb-0">
                            <li>Standard rate: 0-5% (varies by product)</li>
                            <li>Usually NOT included in displayed prices</li>
                            <li>Essential commodities are typically exempt</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Option -->
        <div class="mt-4 text-end">
            <a href="{{ route('admin.vat-ait.export') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Export All Product Taxes as CSV
            </a>
        </div>
    </div>
@endsection
