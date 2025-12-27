@extends('admin.layouts.master')

@section('title', 'Trashed Categories')
@section('page-title', 'Trashed Categories')
@section('page-subtitle', 'Manage deleted categories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Trashed</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back to Categories
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
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Deleted Categories</h5>
                </div>
                <div class="card-body">
                    @if ($categories->count() > 0)
                        <!-- Bulk Actions -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                        Bulk Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" onclick="bulkAction('restore')">
                                            <i class="fas fa-undo text-success me-2"></i> Restore
                                        </button>
                                        <div class="dropdown-divider"></div>
                                        <button class="dropdown-item text-danger" onclick="bulkAction('force-delete')">
                                            <i class="fas fa-trash me-2"></i> Permanently Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <span id="selectedCount" class="text-muted">0 selected</span>
                            </div>
                        </div>

                        <!-- Categories Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </th>
                                        <th>Category</th>
                                        <th>Products</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input category-checkbox"
                                                    value="{{ $category->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if ($category->image && file_exists(public_path('storage/' . $category->image)))
                                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                                alt="{{ $category->name }}" class="category-image"
                                                                onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'category-image bg-light d-flex align-items-center justify-content-center\'><i class=\'fas fa-tag text-muted\'></i></div>';">
                                                        @elseif($category->image)
                                                            <div class="category-image bg-warning d-flex align-items-center justify-content-center"
                                                                title="Image file not found: {{ $category->image }}">
                                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                                            </div>
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
                                                        @endif
                                                        @if ($category->description)
                                                            <p class="text-muted mb-0 small">
                                                                {{ Str::limit($category->description, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill">
                                                    {{ $category->products_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $category->deleted_at->diffForHumans() }}<br>
                                                    {{ $category->deleted_at->format('M d, Y h:i A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-success restore-btn"
                                                        data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                        title="Restore">
                                                        <i class="fas fa-undo"></i> Restore
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger force-delete-btn"
                                                        data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                        title="Permanently Delete">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-trash-alt fa-3x text-muted mb-3"></i>
                            <h4>No Trashed Categories</h4>
                            <p class="text-muted">All your categories are active</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Restore</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to restore <strong id="restoreCategoryName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="restoreForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Restore</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Force Delete Modal -->
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete <strong id="forceDeleteCategoryName"></strong>?</p>
                    <p class="text-danger"><small><strong>Warning:</strong> This action cannot be undone!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="forceDeleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Permanently Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Restore Form -->
    <form id="bulkRestoreForm" method="POST" action="{{ route('admin.categories.bulk-restore') }}">
        @csrf
    </form>

    <!-- Bulk Force Delete Form -->
    <form id="bulkForceDeleteForm" method="POST" action="{{ route('admin.categories.bulk-force-delete') }}">
        @csrf
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                const countElement = document.getElementById('selectedCount');
                if (countElement) {
                    countElement.textContent = checkboxes.length + ' selected';
                }
            }

            // Restore button
            document.querySelectorAll('.restore-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-id');
                    const categoryName = this.getAttribute('data-name');

                    document.getElementById('restoreCategoryName').textContent = categoryName;
                    document.getElementById('restoreForm').action =
                        `/admin/categories/${categoryId}/restore`;

                    const restoreModal = new bootstrap.Modal(document.getElementById(
                        'restoreModal'));
                    restoreModal.show();
                });
            });

            // Force delete button
            document.querySelectorAll('.force-delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-id');
                    const categoryName = this.getAttribute('data-name');

                    document.getElementById('forceDeleteCategoryName').textContent = categoryName;
                    document.getElementById('forceDeleteForm').action =
                        `/admin/categories/${categoryId}/force-delete`;

                    const forceDeleteModal = new bootstrap.Modal(document.getElementById(
                        'forceDeleteModal'));
                    forceDeleteModal.show();
                });
            });
        });

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

            if (action === 'force-delete') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete " + selectedIds.length +
                        " category(s). This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete permanently!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkAction(action, selectedIds);
                    }
                });
            } else if (action === 'restore') {
                Swal.fire({
                    title: 'Restore Categories?',
                    text: "This will restore " + selectedIds.length + " category(s).",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, restore them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkAction(action, selectedIds);
                    }
                });
            }
        }

        function performBulkAction(action, ids) {
            const formId = action === 'restore' ? 'bulkRestoreForm' : 'bulkForceDeleteForm';
            const form = document.getElementById(formId);

            // Clear previous hidden inputs for ids
            const oldIdInputs = form.querySelectorAll('input[name="ids[]"]');
            oldIdInputs.forEach(input => input.remove());

            // Add each id as a separate input with name="ids[]"
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            // Submit the form
            form.submit();
        }
    </script>
@endpush
