@extends('admin.layouts.master')

@section('title', 'Categories')
@section('page-title', 'Product Categories')
@section('page-subtitle', 'Manage your product categories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Category
    </a>
@endsection

@push('styles')
    <style>
        .category-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .sortable-handle {
            cursor: move;
            color: #6c757d;
        }

        .sortable-handle:hover {
            color: #4361ee;
        }

        .category-tree {
            list-style: none;
            padding-left: 0;
        }

        .category-tree li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .category-tree li:last-child {
            border-bottom: none;
        }

        .subcategory {
            padding-left: 30px !important;
        }

        .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .category-item:hover {
            background-color: #f8f9fa;
        }

        .category-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Categories</h5>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
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
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item text-danger" onclick="bulkAction('delete')">
                                        <i class="fas fa-trash me-2"></i> Delete
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light ms-2" onclick="saveOrder()">
                                <i class="fas fa-save me-2"></i> Save Order
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">Select All</label>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </th>
                                    <th width="60">Order</th>
                                    <th>Category</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-categories">
                                @forelse($categories as $category)
                                    <tr data-id="{{ $category->id }}" class="sortable-item">
                                        <td>
                                            <input type="checkbox" class="form-check-input category-checkbox"
                                                value="{{ $category->id }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="sortable-handle me-2">
                                                    <i class="fas fa-bars"></i>
                                                </span>
                                                <input type="number" class="form-control form-control-sm order-input"
                                                    value="{{ $category->order }}" style="width: 60px;" min="0">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    @if ($category->image)
                                                        <img src="{{ asset('storage/' . $category->image) }}"
                                                            alt="{{ $category->name }}" class="category-image">
                                                    @else
                                                        <div
                                                            class="category-image bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-tag text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                                    @if ($category->parent)
                                                        <small class="text-muted">
                                                            <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>
                                                            Parent: {{ $category->parent->name }}
                                                        </small>
                                                    @else
                                                        <small class="text-success">
                                                            <i class="fas fa-folder me-1"></i>
                                                            Main Category
                                                        </small>
                                                    @endif
                                                    @if ($category->description)
                                                        <p class="text-muted mb-0 small">
                                                            {{ Str::limit($category->description, 50) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $category->products_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox"
                                                    data-id="{{ $category->id }}"
                                                    {{ $category->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.categories.show', $category->id) }}"
                                                    class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger confirm-delete"
                                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Subcategories -->
                                    @foreach ($category->children as $child)
                                        <tr data-id="{{ $child->id }}" class="sortable-item subcategory-row">
                                            <td>
                                                <input type="checkbox" class="form-check-input category-checkbox"
                                                    value="{{ $child->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="sortable-handle me-2">
                                                        <i class="fas fa-bars"></i>
                                                    </span>
                                                    <input type="number" class="form-control form-control-sm order-input"
                                                        value="{{ $child->order }}" style="width: 60px;" min="0">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-arrow-right text-muted me-3"></i>
                                                        @if ($child->image)
                                                            <img src="{{ asset('storage/' . $child->image) }}"
                                                                alt="{{ $child->name }}" class="category-image">
                                                        @else
                                                            <div
                                                                class="category-image bg-light d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-tag text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">{{ $child->name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>
                                                            Parent: {{ $category->name }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $child->products_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-id="{{ $child->id }}"
                                                        {{ $child->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        {{ $child->is_active ? 'Active' : 'Inactive' }}
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.categories.show', $child->id) }}"
                                                        class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.categories.edit', $child->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger confirm-delete"
                                                        data-id="{{ $child->id }}" data-name="{{ $child->name }}"
                                                        title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                            <h4>No Categories Found</h4>
                                            <p class="text-muted">Start by creating your first category</p>
                                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i> Create Category
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($categories->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of
                                {{ $categories->total() }} entries
                            </div>
                            <div>
                                {{ $categories->links() }}
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
                    <p>Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.categories.bulk-action') }}">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="ids" id="bulkIds">
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Initialize sortable
        document.addEventListener('DOMContentLoaded', function() {
            // Make table rows sortable
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
            document.getElementById('selectAll')?.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.category-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Individual checkbox changes
            document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                });
            });

            function updateSelectAllCheckbox() {
                const checkboxes = document.querySelectorAll('.category-checkbox');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = allChecked;
                }
            }

            // Status toggle
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const categoryId = this.getAttribute('data-id');
                    const status = this.checked ? 1 : 0;

                    fetch(`/admin/categories/${categoryId}/status`, {
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
                    const categoryId = this.getAttribute('data-id');
                    const categoryName = this.getAttribute('data-name');

                    document.getElementById('deleteCategoryName').textContent = categoryName;
                    document.getElementById('deleteForm').action =
                    `/admin/categories/${categoryId}`;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });
        });

        // Save order
        function saveOrder() {
            const items = [];
            document.querySelectorAll('.sortable-item').forEach((row, index) => {
                const categoryId = row.getAttribute('data-id');
                const orderInput = row.querySelector('.order-input');
                const order = orderInput ? orderInput.value : index + 1;

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
                            title: data.message || 'Order saved successfully!'
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
                    title: 'Are you sure?',
                    text: "This will delete " + selectedIds.length + " category(s). This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
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
