@extends('admin.layouts.master')

@section('title', 'Product Tax Configuration')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-0">
                    <i class="fas fa-boxes text-primary me-2"></i>Product Tax Configuration
                </h1>
                <small class="text-muted">Manage VAT and AIT overrides for individual products</small>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.vat-ait.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download me-1"></i>Export CSV
                </a>
                <a href="{{ route('admin.vat-ait.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-cog me-1"></i>Settings
                </a>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.vat-ait.search') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Search Product</label>
                        <input type="text" name="search" class="form-control" placeholder="Product name or ID..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Filter by Tax Status</label>
                        <select name="tax_filter" class="form-select">
                            <option value="">All Products</option>
                            <option value="has_override" {{ request('tax_filter') === 'has_override' ? 'selected' : '' }}>
                                Has Tax Override
                            </option>
                            <option value="vat_override" {{ request('tax_filter') === 'vat_override' ? 'selected' : '' }}>
                                VAT Override Only
                            </option>
                            <option value="ait_override" {{ request('tax_filter') === 'ait_override' ? 'selected' : '' }}>
                                AIT Override Only
                            </option>
                            <option value="vat_exempt" {{ request('tax_filter') === 'vat_exempt' ? 'selected' : '' }}>
                                VAT Exempt
                            </option>
                            <option value="ait_exempt" {{ request('tax_filter') === 'ait_exempt' ? 'selected' : '' }}>
                                AIT Exempt
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Products</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>VAT %</th>
                            <th>AIT %</th>
                            <th>Status</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $override = $product->taxOverride;
                                $globalSettings = \App\Models\VatAitSetting::current();
                                $vatPercent =
                                    $override && $override->override_vat
                                        ? $override->vat_percentage
                                        : $globalSettings->default_vat_percentage;
                                $aitPercent =
                                    $override && $override->override_ait
                                        ? $override->ait_percentage
                                        : $globalSettings->default_ait_percentage;
                            @endphp
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong><br>
                                    <small class="text-muted">SKU: {{ $product->id }}</small>
                                </td>
                                <td>
                                    @if ($product->category)
                                        <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($override && $override->override_vat)
                                        <span class="badge bg-info">{{ $vatPercent }}%</span>
                                        @if ($override->vat_exempt)
                                            <span class="badge bg-warning">Exempt</span>
                                        @endif
                                    @else
                                        <span class="text-muted">({{ $vatPercent }}%)</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($override && $override->override_ait)
                                        <span class="badge bg-info">{{ $aitPercent }}%</span>
                                        @if ($override->ait_exempt)
                                            <span class="badge bg-warning">Exempt</span>
                                        @endif
                                    @else
                                        <span class="text-muted">({{ $aitPercent }}%)</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($override)
                                        <span class="badge bg-success">Custom</span>
                                    @else
                                        <span class="badge bg-secondary">Default</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.vat-ait.edit-product', $product) }}"
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($override)
                                        <form action="{{ route('admin.vat-ait.remove-product', $product) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Remove tax override for this product?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Remove Override">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No products found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="card-footer bg-light">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
