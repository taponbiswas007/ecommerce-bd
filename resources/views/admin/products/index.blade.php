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
    <a href="{{ route('admin.bulk-price.index') }}" class="btn btn-info">
        <i class="fas fa-dollar-sign me-2"></i> Bulk Price Update
    </a>
    <a href="{{ route('admin.products.trash') }}" class="btn btn-warning ms-2">
        <i class="fas fa-trash-alt me-2"></i> Trashed
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

        .search-highlight {
            background-color: #fff3cd;
        }

        .bulk-actions-section {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-0">
                                All Products
                                <span class="badge bg-info ms-2" id="totalProducts">{{ $products->total() }}</span>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" id="liveSearch" class="form-control" placeholder="Search by name..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions Section -->
                <div class="bulk-actions-section" id="bulkActionsSection" style="display: none;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span id="selectedCount" class="badge bg-primary">0 selected</span>
                            <div class="btn-group ms-3" role="group">
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="performBulkAction('activate')" title="Activate selected products">
                                    <i class="fas fa-eye me-1"></i> Activate
                                </button>
                                <button type="button" class="btn btn-sm btn-warning"
                                    onclick="performBulkAction('deactivate')" title="Deactivate selected products">
                                    <i class="fas fa-eye-slash me-1"></i> Deactivate
                                </button>
                                <button type="button" class="btn btn-sm btn-info" onclick="performBulkAction('featured')"
                                    title="Mark as featured">
                                    <i class="fas fa-star me-1"></i> Featured
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="performBulkAction('delete')"
                                    title="Delete selected">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                                <i class="fas fa-times me-1"></i> Clear Selection
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAll"
                                            title="Select all products on this page">
                                    </th>
                                    <th style="min-width: 400px">Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                @forelse($products as $product)
                                    <tr data-id="{{ $product->id }}" class="product-row">
                                        <td>
                                            <input type="checkbox" class="form-check-input product-checkbox"
                                                value="{{ $product->id }}" data-name="{{ $product->name }}">
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
                                                    <h6 class="mb-0 product-name">{{ $product->name }}</h6>
                                                    @if ($product->is_featured)
                                                        <span class="badge bg-warning badge-sm">Featured</span>
                                                    @endif
                                                    <small class="text-muted d-block product-sku">
                                                        ID: {{ $product->id }} | Slug:
                                                        {{ Str::limit($product->slug, 20) }}
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
                                                    <span class="fw-bold product-price"
                                                        data-price="{{ $product->base_price }}">
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
                                                    data-product-name="{{ $product->name }}"
                                                    {{ $product->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label status-label">
                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.products.images.index', $product->id) }}"
                                                    class="btn btn-sm btn-warning" title="Manage Images">
                                                    <i class="fas fa-images"></i>
                                                </a>
                                                <a href="{{ route('admin.products.prices.index', $product->id) }}"
                                                    class="btn btn-sm btn-success" title="Manage Prices">
                                                    <i class="fas fa-tags"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger confirm-delete"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    title="Delete Product">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyState">
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
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                                {{ $products->total() }} entries
                            </div>
                            <nav>
                                {{ $products->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Form -->
    <form id="bulkActionForm" method="POST" action="{{ route('admin.products.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="actionType">
    </form>
@endsection

@section('modalpopup')
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const searchInput = document.getElementById('liveSearch');
        const searchBtn = document.getElementById('searchBtn');
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkActionsSection = document.getElementById('bulkActionsSection');
        const selectedCountBadge = document.getElementById('selectedCount');

        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
        });

        function initializeEventListeners() {
            // Select all checkbox
            selectAllCheckbox?.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.product-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });

            // Individual product checkboxes
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                    updateSelectedCount();
                });
            });

            // Status toggles
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    toggleProductStatus(this);
                });
            });

            // Delete buttons
            document.querySelectorAll('.confirm-delete').forEach(button => {
                button.addEventListener('click', function() {
                    showDeleteModal(this.getAttribute('data-id'), this.getAttribute('data-name'));
                });
            });

            // Search functionality
            searchBtn?.addEventListener('click', performSearch);
            searchInput?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }

        function updateSelectAllCheckbox() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount > 0 && checkedCount === checkboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            }
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

            if (checkedCount > 0) {
                bulkActionsSection.style.display = 'block';
                selectedCountBadge.textContent = checkedCount + ' selected';
            } else {
                bulkActionsSection.style.display = 'none';
            }
        }

        function clearSelection() {
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            bulkActionsSection.style.display = 'none';
        }

        function toggleProductStatus(toggle) {
            const productId = toggle.getAttribute('data-id');
            const productName = toggle.getAttribute('data-product-name');
            const newStatus = toggle.checked ? 1 : 0;
            const oldStatus = !toggle.checked ? 1 : 0;
            const label = toggle.nextElementSibling;

            // Disable toggle during request
            toggle.disabled = true;

            fetch(`/admin/products/${productId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update label
                        if (label) {
                            label.textContent = newStatus ? 'Active' : 'Inactive';
                        }

                        // Show success message
                        if (typeof Toast !== 'undefined') {
                            Toast.fire({
                                icon: 'success',
                                title: 'Status updated successfully!'
                            });
                        }
                    } else {
                        // Revert checkbox on failure
                        toggle.checked = oldStatus;
                        if (typeof Toast !== 'undefined') {
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'Error updating status'
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert checkbox on error
                    toggle.checked = oldStatus;

                    if (typeof Toast !== 'undefined') {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error updating status. Please try again.'
                        });
                    } else {
                        alert('Error updating status. Please try again.');
                    }
                })
                .finally(() => {
                    // Re-enable toggle
                    toggle.disabled = false;
                });
        }

        function performBulkAction(action) {
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

            let title = '';
            let message = '';

            switch (action) {
                case 'activate':
                    title = 'Activate Products';
                    message = `Activate ${selectedIds.length} product(s)?`;
                    break;
                case 'deactivate':
                    title = 'Deactivate Products';
                    message = `Deactivate ${selectedIds.length} product(s)?`;
                    break;
                case 'featured':
                    title = 'Mark as Featured';
                    message = `Mark ${selectedIds.length} product(s) as featured?`;
                    break;
                case 'delete':
                    Swal.fire({
                        title: 'Delete Products',
                        text: `Delete ${selectedIds.length} product(s)? This cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete!'
                    }).then(result => {
                        if (result.isConfirmed) {
                            submitBulkAction(action);
                        }
                    });
                    return;
            }

            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Continue'
            }).then(result => {
                if (result.isConfirmed) {
                    submitBulkAction(action);
                }
            });
        }

        function submitBulkAction(action) {
            const form = document.getElementById('bulkActionForm');
            const oldIdInputs = form.querySelectorAll('input[name="ids[]"]');
            oldIdInputs.forEach(input => input.remove());

            document.getElementById('actionType').value = action;

            const selectedIds = [];
            document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            form.submit();
        }

        function performSearch() {
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                window.location.href = '{{ route('admin.products.index') }}?search=' + encodeURIComponent(searchValue);
            }
        }

        function showDeleteModal(id, name) {
            document.getElementById('deleteProductName').textContent = name;
            document.getElementById('deleteForm').action = `/admin/products/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush
