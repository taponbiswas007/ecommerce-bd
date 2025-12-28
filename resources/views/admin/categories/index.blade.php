@extends('admin.layouts.master')

@section('title', 'Categories')
@section('page-title', 'Product Categories')
@section('page-subtitle', 'Manage your product categories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.categories.trashed') }}" class="btn btn-light btn-hover-soft-danger btn-sm me-2">
        <i class="fas fa-trash-restore me-1"></i> Trashed Categories
    </a>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-hover-shadow">
        <i class="fas fa-plus me-1"></i> New Category
    </a>
@endsection

@push('styles')
    <style>
        .category-image {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f2f5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .category-image-placeholder {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .sortable-handle {
            cursor: move;
            color: #8a8d93;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.2s;
            background: #f8f9fa;
        }

        .sortable-handle:hover {
            background: #e9ecef;
            color: #4361ee;
        }

        .category-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 20px;
        }

        .category-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .category-item:hover {
            background: linear-gradient(90deg, rgba(67, 97, 238, 0.03) 0%, rgba(67, 97, 238, 0) 100%);
            border-left-color: #4361ee;
        }

        .subcategory-item {
            background: #fafbfe;
            border-left: 3px solid #e9ecef;
        }

        .subcategory-item:hover {
            background: #f0f2f8;
            border-left-color: #8a8d93;
        }

        .status-badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-active {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }

        .status-inactive {
            background: rgba(235, 87, 87, 0.1);
            color: #eb5757;
        }

        .table-actions {
            opacity: 0;
            transition: opacity 0.2s;
        }

        tr:hover .table-actions {
            opacity: 1;
        }

        .order-input {
            width: 70px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-align: center;
            font-weight: 500;
        }

        .order-input:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .select-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            cursor: pointer;
        }

        .products-count {
            min-width: 36px;
            height: 36px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #2d3748;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            margin: 20px 0;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px 25px;
            overflow: visible;
        }

        .card-body {
            overflow: visible;
            padding: 0;
        }

        .card-header-gradient .card-title {
            color: white;
            font-weight: 600;
            margin-bottom: 0;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 8px 15px;
            width: 200px;
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-box:focus {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: none;
        }

        .bulk-actions-btn {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .bulk-actions-btn:hover {
            background: #f8f9fa;
            border-color: #4361ee;
            transform: translateY(-1px);
        }

        .btn-hover-soft-danger:hover {
            background: rgba(235, 87, 87, 0.1);
            border-color: rgba(235, 87, 87, 0.2);
            color: #eb5757;
        }

        .btn-hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-gradient">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Category Management</h5>
                        <small class="opacity-75">Organize and manage your product categories</small>
                    </div>
                    <div>
                        <input type="text" class="search-box" placeholder="Search categories...">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Bulk Actions Bar -->
                <div class="bg-light p-3 border-bottom">
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <button type="button" class="btn btn-light bulk-actions-btn dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-layer-group me-2"></i> Bulk Actions
                                </button>
                                <div class="dropdown-menu shadow">
                                    <button class="dropdown-item py-2" onclick="bulkAction('activate')">
                                        <i class="fas fa-check-circle text-success me-2"></i> Activate Selected
                                    </button>
                                    <button class="dropdown-item py-2" onclick="bulkAction('deactivate')">
                                        <i class="fas fa-times-circle text-danger me-2"></i> Deactivate Selected
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item py-2 text-danger" onclick="bulkAction('delete')">
                                        <i class="fas fa-trash-alt me-2"></i> Move to Trash
                                    </button>
                                </div>
                                <button type="button" class="btn btn-light bulk-actions-btn" onclick="saveOrder()">
                                    <i class="fas fa-save me-2"></i> Save Order
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-3 text-end">
                            <span id="selectedCount" class="badge bg-primary rounded-pill px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>
                                <span class="count">0</span> selected
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Categories Table -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="min-width: 950px">
                        <thead class="bg-light">
                            <tr>
                                <th width="50" class="ps-4">
                                    <div class="form-check">
                                        <input class="form-check-input select-checkbox" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th width="100" class="text-muted small fw-normal">ORDER</th>
                                <th class="text-muted small fw-normal">CATEGORY</th>
                                <th width="120" class="text-muted small fw-normal text-center">PRODUCTS</th>
                                <th width="120" class="text-muted small fw-normal text-center">STATUS</th>
                                <th width="150" class="text-muted small fw-normal text-end pe-4">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-categories">
                            @forelse($categories as $category)
                                <!-- Main Category -->
                                <tr data-id="{{ $category->id }}" class="category-item align-middle">
                                    <td class="ps-4">
                                        <div class="form-check">
                                            <input class="form-check-input select-checkbox category-checkbox"
                                                type="checkbox" value="{{ $category->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="sortable-handle">
                                                <i class="fas fa-bars"></i>
                                            </span>
                                            <input type="number" class="form-control order-input"
                                                value="{{ $category->order }}" min="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                @if ($category->image)
                                                    <img src="{{ asset('storage/' . $category->image) }}"
                                                        alt="{{ $category->name }}" class="category-image">
                                                @else
                                                    <div class="category-image-placeholder">
                                                        <i class="fas fa-tag"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $category->name }}</h6>
                                                <div class="d-flex align-items-center flex-column gap-2">
                                                    <div class="d-flex align-items-center gap-1">
                                                        @if ($category->parent)
                                                            <span class="badge category-badge bg-light text-muted">
                                                                <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>
                                                                {{ $category->parent->name }}
                                                            </span>
                                                        @else
                                                            <span
                                                                class="badge category-badge bg-primary bg-opacity-10 text-primary">
                                                                <i class="fas fa-star me-1"></i>
                                                                Main Category
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if ($category->description)
                                                        <span class="text-muted small">
                                                            {{ Str::limit($category->description, 40) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="products-count mx-auto">
                                            {{ $category->products_count }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox"
                                                    data-id="{{ $category->id }}"
                                                    {{ $category->is_active ? 'checked' : '' }}>
                                            </div>
                                            <span
                                                class="status-badge ms-2 {{ $category->is_active ? 'status-active' : 'status-inactive' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        <div class="table-actions d-flex justify-content-end gap-1">
                                            <a href="{{ route('admin.categories.show', $category->id) }}"
                                                class="btn btn-sm btn-light btn-icon rounded-circle" title="View"
                                                data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-light btn-icon rounded-circle" title="Edit"
                                                data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-sm btn-light btn-icon rounded-circle text-danger confirm-delete"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                title="Delete" data-bs-toggle="tooltip">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Subcategories -->
                                @foreach ($category->children as $child)
                                    <tr data-id="{{ $child->id }}" class="subcategory-item align-middle">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input class="form-check-input select-checkbox category-checkbox"
                                                    type="checkbox" value="{{ $child->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 ps-3">
                                                <span class="sortable-handle">
                                                    <i class="fas fa-bars"></i>
                                                </span>
                                                <input type="number" class="form-control order-input"
                                                    value="{{ $child->order }}" min="0">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="ps-3">
                                                    <i class="fas fa-arrow-right text-muted me-2"></i>
                                                    @if ($child->image)
                                                        <img src="{{ asset('storage/' . $child->image) }}"
                                                            alt="{{ $child->name }}" class="category-image">
                                                    @else
                                                        <div class="category-image-placeholder">
                                                            <i class="fas fa-tag"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $child->name }}</h6>
                                                    <span class="badge category-badge bg-light text-muted">
                                                        <i class="fas fa-folder me-1"></i>
                                                        {{ $category->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="products-count mx-auto">
                                                {{ $child->products_count }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-id="{{ $child->id }}"
                                                        {{ $child->is_active ? 'checked' : '' }}>
                                                </div>
                                                <span
                                                    class="status-badge ms-2 {{ $child->is_active ? 'status-active' : 'status-inactive' }}">
                                                    {{ $child->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="pe-4">
                                            <div class="table-actions d-flex justify-content-end gap-1">
                                                <a href="{{ route('admin.categories.show', $child->id) }}"
                                                    class="btn btn-sm btn-light btn-icon rounded-circle" title="View"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $child->id) }}"
                                                    class="btn btn-sm btn-light btn-icon rounded-circle" title="Edit"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-light btn-icon rounded-circle text-danger confirm-delete"
                                                    data-id="{{ $child->id }}" data-name="{{ $child->name }}"
                                                    title="Delete" data-bs-toggle="tooltip">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-tags"></i>
                                            </div>
                                            <h4 class="mb-3">No Categories Found</h4>
                                            <p class="text-muted mb-4">Start organizing your products by creating
                                                categories</p>
                                            <a href="{{ route('admin.categories.create') }}"
                                                class="btn btn-primary btn-hover-shadow">
                                                <i class="fas fa-plus me-2"></i> Create Your First Category
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($categories->hasPages())
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                        <div class="text-muted small">
                            <i class="fas fa-list me-1"></i>
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of
                            {{ $categories->total() }} entries
                        </div>
                        <div>
                            {{ $categories->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Form (used by sweet alert) -->
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
        </form>

        <!-- Bulk Action Form -->
        <form id="bulkActionForm" method="POST" action="{{ route('admin.categories.bulk-action') }}">
            @csrf
            <input type="hidden" name="action" id="bulkAction">
        </form>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Initialize sortable
            const sortableTable = document.getElementById('sortable-categories');
            if (sortableTable) {
                new Sortable(sortableTable, {
                    animation: 150,
                    handle: '.sortable-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: function(evt) {
                        updateOrderNumbers();
                    }
                });
            }

            // Update order numbers after sorting
            function updateOrderNumbers() {
                document.querySelectorAll('.sortable-item').forEach((row, index) => {
                    const orderInput = row.querySelector('.order-input');
                    if (orderInput) {
                        orderInput.value = index + 1;
                    }
                });
            }

            // Select all checkboxes
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.category-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            // Individual checkbox changes
            document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                    updateSelectedCount();
                });
            });

            function updateSelectAllCheckbox() {
                const checkboxes = document.querySelectorAll('.category-checkbox');
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = checkedCount > 0 && checkedCount === checkboxes.length;
                    selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
                }
            }

            function updateSelectedCount() {
                const checkboxes = document.querySelectorAll('.category-checkbox:checked');
                const countElement = document.querySelector('#selectedCount .count');
                const badge = document.getElementById('selectedCount');
                if (countElement) {
                    countElement.textContent = checkboxes.length;

                    if (checkboxes.length > 0) {
                        badge.classList.add('bg-primary');
                        badge.classList.remove('bg-secondary');
                    } else {
                        badge.classList.remove('bg-primary');
                        badge.classList.add('bg-secondary');
                    }
                }
            }

            // Status toggle
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function(e) {
                    const toggleSwitch = this;
                    const categoryId = this.getAttribute('data-id');
                    const newStatus = this.checked ? 1 : 0;
                    const oldStatus = !this.checked ? 1 : 0;

                    // Find the status badge in the parent flex container
                    const container = this.closest('.d-flex');
                    const statusBadge = container ? container.querySelector('.status-badge') : null;

                    // Disable toggle during request
                    toggleSwitch.disabled = true;

                    fetch(`/admin/categories/${categoryId}/status`, {
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
                                // Update the status badge
                                if (statusBadge) {
                                    statusBadge.textContent = newStatus ? 'Active' : 'Inactive';
                                    if (newStatus) {
                                        statusBadge.classList.remove('status-inactive');
                                        statusBadge.classList.add('status-active');
                                    } else {
                                        statusBadge.classList.remove('status-active');
                                        statusBadge.classList.add('status-inactive');
                                    }
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
                                toggleSwitch.checked = oldStatus;
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
                            toggleSwitch.checked = oldStatus;

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
                            toggleSwitch.disabled = false;
                        });
                });
            });

            // Delete confirmation
            document.querySelectorAll('.confirm-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const categoryId = this.getAttribute('data-id');
                    const categoryName = this.getAttribute('data-name');

                    Swal.fire({
                        title: 'Move to Trash?',
                        text: 'Move "' + categoryName + '" to trash?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, move to trash',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn-hover-shadow'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('deleteForm').action =
                                `/admin/categories/${categoryId}`;
                            document.getElementById('deleteForm').submit();
                        }
                    });
                });
            });
        });

        // Save order
        function saveOrder() {
            const items = [];
            document.querySelectorAll('[data-id]').forEach((row) => {
                const categoryId = row.getAttribute('data-id');
                const orderInput = row.querySelector('.order-input');
                const order = orderInput ? orderInput.value : 0;

                items.push({
                    id: categoryId,
                    order: order
                });
            });

            fetch('{{ route('admin.categories.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        categories: items
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Order saved successfully!',
                            background: '#4361ee',
                            color: 'white'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Error saving order'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error saving order'
                    });
                });
        }

        // Bulk actions
        function bulkAction(action) {
            const selectedIds = [];
            document.querySelectorAll('.category-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select at least one category'
                });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Move to Trash?',
                    text: "Move " + selectedIds.length + " category(s) to trash?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4361ee',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, move to trash',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
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
            const form = document.getElementById('bulkActionForm');
            const oldIdInputs = form.querySelectorAll('input[name="ids[]"]');
            oldIdInputs.forEach(input => input.remove());

            document.getElementById('bulkAction').value = action;

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            form.submit();
        }
    </script>
@endpush
