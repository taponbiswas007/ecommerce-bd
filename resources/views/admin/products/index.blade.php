@extends('admin.layouts.master')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage your products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Product
    </a>
@endsection

@push('styles')
    <style>
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .stock-badge {
            font-size: 0.75rem;
        }

        .price-old {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Products</h5>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search"
                                id="searchInput">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
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
                                    <button class="dropdown-item" onclick="bulkAction('activate')">
                                        <i class="fas fa-check-circle text-success me-2"></i> Activate
                                    </button>
                                    <button class="dropdown-item" onclick="bulkAction('deactivate')">
                                        <i class="fas fa-times-circle text-danger me-2"></i> Deactivate
                                    </button>
                                    <button class="dropdown-item" onclick="bulkAction('featured')">
                                        <i class="fas fa-star text-warning me-2"></i> Mark as Featured
                                    </button>
                                    <button class="dropdown-item" onclick="bulkAction('unfeatured')">
                                        <i class="fas fa-star text-muted me-2"></i> Remove Featured
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item text-danger" onclick="bulkAction('delete')">
                                        <i class="fas fa-trash me-2"></i> Delete
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

                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr data-id="{{ $product->id }}">
                                        <td>
                                            <input type="checkbox" class="form-check-input product-checkbox"
                                                value="{{ $product->id }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    @if ($product->featured_image)
                                                        <img src="{{ asset('storage/' . $product->featured_image->image_path) }}"
                                                            alt="{{ $product->name }}" class="product-image">
                                                    @else
                                                        <div
                                                            class="product-image bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-box text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                                    @if ($product->is_featured)
                                                        <span class="badge bg-warning badge-sm">Featured</span>
                                                    @endif
                                                    <small class="text-muted d-block">
                                                        SKU: {{ $product->sku ?? 'N/A' }}
                                                    </small>
                                                    @if ($product->short_description)
                                                        <p class="text-muted mb-0 small">
                                                            {{ Str::limit($product->short_description, 50) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $product->category->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                @if ($product->discount_price)
                                                    <span class="price-old">
                                                        {{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}
                                                    </span>
                                                    <br>
                                                    <span class="text-danger fw-bold">
                                                        {{ config('app.currency_symbol') }}{{ number_format($product->discount_price, 2) }}
                                                    </span>
                                                    <small class="text-danger">
                                                        ({{ $product->discount_percentage }}% off)
                                                    </small>
                                                @else
                                                    <span class="fw-bold">
                                                        {{ config('app.currency_symbol') }}{{ number_format($product->base_price, 2) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if ($product->stock_quantity > 10)
                                                <span class="badge bg-success stock-badge">
                                                    In Stock ({{ $product->stock_quantity }})
                                                </span>
                                            @elseif($product->stock_quantity > 0)
                                                <span class="badge bg-warning stock-badge">
                                                    Low Stock ({{ $product->stock_quantity }})
                                                </span>
                                            @else
                                                <span class="badge bg-danger stock-badge">
                                                    Out of Stock
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox"
                                                    data-id="{{ $product->id }}"
                                                    {{ $product->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger confirm-delete"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td> --}}
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- New Image Management Button -->
                                                <a href="{{ route('admin.products.images.index', $product->id) }}"
                                                    class="btn btn-sm btn-warning" title="Manage Images">
                                                    <i class="fas fa-images"></i>
                                                </a>

                                                <!-- New Price Management Button -->
                                                <a href="{{ route('admin.products.prices.index', $product->id) }}"
                                                    class="btn btn-sm btn-success" title="Manage Prices">
                                                    <i class="fas fa-tags"></i>
                                                </a>

                                                <button type="button" class="btn btn-sm btn-danger confirm-delete"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                                            <h4>No Products Found</h4>
                                            <p class="text-muted">Start by creating your first product</p>
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i> Create Product
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                                {{ $products->total() }} entries
                            </div>
                            <div>
                                {{ $products->links() }}
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
                    <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.products.bulk-action') }}">
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
                const checkboxes = document.querySelectorAll('.product-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Individual checkbox changes
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                });
            });

            function updateSelectAllCheckbox() {
                const checkboxes = document.querySelectorAll('.product-checkbox');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = allChecked;
                }
            }

            // Status toggle
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const productId = this.getAttribute('data-id');
                    const status = this.checked ? 1 : 0;

                    fetch(`/admin/products/${productId}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const label = this.nextElementSibling;
                                if (label) {
                                    label.textContent = status ? 'Active' : 'Inactive';
                                }
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Status updated successfully!'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.checked = !this.checked;
                            Toast.fire({
                                icon: 'error',
                                title: 'Error updating status'
                            });
                        });
                });
            });

            // Delete confirmation
            document.querySelectorAll('.confirm-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    const productName = this.getAttribute('data-name');

                    document.getElementById('deleteProductName').textContent = productName;
                    document.getElementById('deleteForm').action =
                        `/admin/products/${productId}`;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });

            // Search functionality
            document.getElementById('searchBtn')?.addEventListener('click', function() {
                const searchValue = document.getElementById('searchInput').value;
                if (searchValue.trim()) {
                    window.location.href = '{{ route('admin.products.index') }}?search=' +
                        encodeURIComponent(searchValue);
                }
            });

            document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchValue = this.value;
                    if (searchValue.trim()) {
                        window.location.href = '{{ route('admin.products.index') }}?search=' +
                            encodeURIComponent(searchValue);
                    }
                }
            });
        });

        // Bulk actions
        function bulkAction(action) {
            const selectedIds = [];
            document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select at least one product'
                });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete " + selectedIds.length + " product(s). This action cannot be undone!",
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
