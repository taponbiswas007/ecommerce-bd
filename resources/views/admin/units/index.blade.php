@extends('admin.layouts.master')

@section('title', 'Units')
@section('page-title', 'Measurement Units')
@section('page-subtitle', 'Manage product measurement units')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Units</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Unit
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Units</h5>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="table_search" class="form-control float-right"
                                placeholder="Search units...">
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
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">Select All</label>
                            </div>
                        </div>
                    </div>

                    <!-- Units Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </th>
                                    <th>Unit Name</th>
                                    <th>Symbol</th>
                                    <th>Description</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($units as $unit)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input unit-checkbox"
                                                value="{{ $unit->id }}">
                                        </td>
                                        <td>
                                            <strong>{{ $unit->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $unit->symbol }}</span>
                                        </td>
                                        <td>
                                            @if ($unit->description)
                                                <p class="text-muted mb-0 small">{{ Str::limit($unit->description, 50) }}
                                                </p>
                                            @else
                                                <span class="text-muted">No description</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info rounded-pill">
                                                {{ $unit->products_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox"
                                                    data-id="{{ $unit->id }}" {{ $unit->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.units.show', $unit->id) }}"
                                                    class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.units.edit', $unit->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger confirm-delete"
                                                    data-id="{{ $unit->id }}" data-name="{{ $unit->name }}"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-balance-scale fa-3x text-muted mb-3"></i>
                                            <h4>No Units Found</h4>
                                            <p class="text-muted">Start by creating your first measurement unit</p>
                                            <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i> Create Unit
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($units->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $units->firstItem() }} to {{ $units->lastItem() }} of {{ $units->total() }}
                                entries
                            </div>
                            <div>
                                {{ $units->links() }}
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
                    <p>Are you sure you want to delete <strong id="deleteUnitName"></strong>?</p>
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.units.bulk-action') }}">
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
                const checkboxes = document.querySelectorAll('.unit-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Individual checkbox changes
            document.querySelectorAll('.unit-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                });
            });

            function updateSelectAllCheckbox() {
                const checkboxes = document.querySelectorAll('.unit-checkbox');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = allChecked;
                }
            }

            // Status toggle
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const unitId = this.getAttribute('data-id');
                    const status = this.checked ? 1 : 0;

                    fetch(`/admin/units/${unitId}/status`, {
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
                    const unitId = this.getAttribute('data-id');
                    const unitName = this.getAttribute('data-name');

                    document.getElementById('deleteUnitName').textContent = unitName;
                    document.getElementById('deleteForm').action = `/admin/units/${unitId}`;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });
        });

        // Bulk actions
        function bulkAction(action) {
            const selectedIds = [];
            document.querySelectorAll('.unit-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select at least one unit'
                });
                return;
            }

            if (action === 'delete') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete " + selectedIds.length + " unit(s). This action cannot be undone!",
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
