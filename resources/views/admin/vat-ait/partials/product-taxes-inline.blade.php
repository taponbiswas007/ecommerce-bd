@php
    $products = \App\Models\Product::with('taxOverride')->where('is_active', true)->orderBy('name')->paginate(15);
@endphp

<div class="card">
    <div class="card-header bg-light">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">Product Tax Configuration</h5>
                <small class="text-muted">Manage VAT and AIT overrides for individual products</small>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.vat-ait.export') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-download me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Search & Filter -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="#" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search product..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Global VAT</th>
                        <th>VAT Override</th>
                        <th>Global AIT</th>
                        <th>AIT Override</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong><br>
                                <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $globalVat = \App\Models\VatAitSetting::current();
                                    $vatPercentage = $globalVat->vat_enabled ? $globalVat->default_vat_percentage : 0;
                                @endphp
                                <strong>{{ $vatPercentage }}%</strong><br>
                                <small class="text-muted">
                                    {{ $globalVat->vat_included_in_price ? 'Included' : 'Added' }}
                                </small>
                            </td>
                            <td>
                                @if ($product->taxOverride && $product->taxOverride->isActive())
                                    @php
                                        $ovVat = $product->taxOverride->getEffectiveVatPercentage();
                                    @endphp
                                    <span class="badge bg-warning">
                                        {{ $ovVat }}%
                                    </span><br>
                                    <small class="text-muted">
                                        {{ $product->taxOverride->getVatIncludedInPrice() ? 'Included' : 'Added' }}
                                    </small>
                                @else
                                    <span class="badge bg-secondary">None</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $aitPercentage = $globalVat->ait_enabled ? $globalVat->default_ait_percentage : 0;
                                @endphp
                                <strong>{{ $aitPercentage }}%</strong><br>
                                <small class="text-muted">
                                    {{ $globalVat->ait_included_in_price ? 'Included' : 'Added' }}
                                </small>
                            </td>
                            <td>
                                @if ($product->taxOverride && $product->taxOverride->isActive())
                                    @php
                                        $ovAit = $product->taxOverride->getEffectiveAitPercentage();
                                    @endphp
                                    <span class="badge bg-warning">
                                        {{ $ovAit }}%
                                    </span><br>
                                    <small class="text-muted">
                                        {{ $product->taxOverride->getAitIncludedInPrice() ? 'Included' : 'Added' }}
                                    </small>
                                @else
                                    <span class="badge bg-secondary">None</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.vat-ait.edit-product', $product->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-box-open text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted">No products found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
                    products
                </div>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
