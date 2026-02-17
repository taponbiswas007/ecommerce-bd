@extends('admin.layouts.master')

@section('title', 'Dropshipping Products')
@section('page-title', 'Dropshipping Products')
@section('page-subtitle', 'Manage imported dropshipping products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Dropshipping Products</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.dropshipping.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Import Product
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name, ID, SKU..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="availability" class="form-select">
                                <option value="">All Products</option>
                                <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>In
                                    Stock</option>
                                <option value="out_of_stock"
                                    {{ request('availability') === 'out_of_stock' ? 'selected' : '' }}>
                                    Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="selectAll"></th>
                                <th>Product Name</th>
                                <th>CJ ID</th>
                                <th>Cost Price</th>
                                <th>Selling Price</th>
                                <th>Profit Margin</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td><input type="checkbox" class="form-check-input product-checkbox"
                                            value="{{ $product->id }}"></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $imageUrl = $product->image_url;
                                                if (is_string($imageUrl) && str_starts_with(trim($imageUrl), '[')) {
                                                    $decodedImages = json_decode($imageUrl, true);
                                                    if (is_array($decodedImages) && !empty($decodedImages)) {
                                                        $imageUrl = $decodedImages[0];
                                                    }
                                                }

                                                if ($imageUrl && str_starts_with($imageUrl, 'http://')) {
                                                    $imageUrl = 'https://' . substr($imageUrl, 7);
                                                }
                                            @endphp
                                            @if ($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="" class="rounded me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                    onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2YxZjNmNSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmaWxsPSIjYWRiNWJkIiBmb250LXNpemU9IjEwIiBmb250LWZhbWlseT0iQXJpYWwiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.dropshipping.products.show', $product->id) }}"
                                                    class="text-decoration-none">{{ $product->name }}</a>
                                                @if ($product->sku)
                                                    <br><small class="text-muted">SKU: {{ $product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><small>{{ $product->cj_product_id }}</small></td>
                                    <td>{{ number_format($product->unit_price, 2) }} $</td>
                                    <td class="fw-bold">{{ number_format($product->selling_price, 2) }} $</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format($product->profit_margin, 2) }} $
                                        </span>
                                    </td>
                                    <td>
                                        @if ($product->stock > 10)
                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                        @elseif($product->stock > 0)
                                            <span class="badge bg-warning">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.dropshipping.products.edit', $product->id) }}"
                                            class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST"
                                            action="{{ route('admin.dropshipping.products.destroy', $product->id) }}"
                                            style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        No dropshipping products found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->render() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Action Modal -->
    <div class="modal fade" id="bulkActionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bulkActionForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select" id="bulkAction" required>
                                <option value="">Select action</option>
                                <option value="price">Update Price (Margin %)</option>
                                <option value="status">Update Status</option>
                            </select>
                        </div>
                        <div class="mb-3" id="marginDiv" style="display: none;">
                            <label class="form-label">Profit Margin Percent</label>
                            <input type="number" name="margin_percent" class="form-control" min="0"
                                max="100" step="0.01">
                        </div>
                        <div class="mb-3" id="statusDiv" style="display: none;">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('bulkAction').addEventListener('change', function() {
            document.getElementById('marginDiv').style.display = this.value === 'price' ? 'block' : 'none';
            document.getElementById('statusDiv').style.display = this.value === 'status' ? 'block' : 'none';
        });

        document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb
                .value);

            if (selectedIds.length === 0) {
                alert('Please select at least one product');
                return;
            }

            const formData = new FormData(this);
            formData.append('product_ids', JSON.stringify(selectedIds));

            fetch('{{ route('admin.dropshipping.products.bulk-update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
