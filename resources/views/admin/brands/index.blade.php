@extends('admin.layouts.master')

@section('title', 'Brands')
@section('page-title', 'Product Brands')
@section('page-subtitle', 'Manage your product brands')

@push('head')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Brands</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.brands.trashed') }}" class="btn btn-light btn-hover-soft-danger btn-sm me-2">
        <i class="fas fa-trash-restore me-1"></i> Trashed Brands
    </a>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-hover-shadow">
        <i class="fas fa-plus me-1"></i> New Brand
    </a>
@endsection

@push('styles')
    <style>
        .brand-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 8px;
            border: 2px solid #f0f2f5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            background: white;
            padding: 4px;
        }

        .brand-logo-placeholder {
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

        .brand-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .brand-item:hover {
            background: linear-gradient(90deg, rgba(67, 97, 238, 0.03) 0%, rgba(67, 97, 238, 0) 100%);
            border-left-color: #4361ee;
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

        .featured-badge {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 20px;
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
            background: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            color: #2c3e50;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.08);
            cursor: pointer;
        }

        .bulk-actions-btn:hover {
            background: #f8fafc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.12);
            transform: translateY(-1px);
            color: #4361ee;
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

        /* Status Toggle Switch Enhancement */
        .form-check-input.status-toggle {
            cursor: pointer;
            width: 2.5em;
            height: 1.25em;
        }

        .form-check-input.status-toggle:checked {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .form-check-input.status-toggle:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.25rem rgba(39, 174, 96, 0.25);
        }
    </style>
@endpush

@section('content')
    <div class="card border shadow-sm rounded">
        <div class="card-header-gradient">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="card-title mb-0">All Brands</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchBox" class="form-control search-box" placeholder="Search brands...">
                    <button class="bulk-actions-btn dropdown-toggle" type="button" id="bulkActionsDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false" disabled>
                        <i class="fas fa-tasks me-2"></i>Bulk Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                        <li><a class="dropdown-item" href="#" data-action="activate"><i
                                    class="fas fa-check-circle me-2 text-success"></i>Activate</a></li>
                        <li><a class="dropdown-item" href="#" data-action="deactivate"><i
                                    class="fas fa-times-circle me-2 text-warning"></i>Deactivate</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#" data-action="delete"><i
                                    class="fas fa-trash me-2"></i>Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="brandsTable">
                    <thead class="bg-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="select-checkbox" id="selectAll">
                            </th>
                            <th width="50">Order</th>
                            <th width="60">Logo</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-brands">
                        @forelse ($brands as $brand)
                            <tr class="brand-item" data-id="{{ $brand->id }}">
                                <td>
                                    <input type="checkbox" class="select-checkbox item-checkbox"
                                        value="{{ $brand->id }}">
                                </td>
                                <td>
                                    <div class="sortable-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                </td>
                                <td>
                                    @if ($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                            class="brand-logo">
                                    @else
                                        <div class="brand-logo-placeholder">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $brand->name }}</h6>
                                            @if ($brand->is_featured)
                                                <span class="featured-badge">
                                                    <i class="fas fa-star me-1"></i>Featured
                                                </span>
                                            @endif
                                            @if ($brand->website)
                                                <div class="small text-muted">
                                                    <a href="{{ $brand->website }}" target="_blank"
                                                        class="text-decoration-none">
                                                        <i class="fas fa-globe me-1"></i>Website
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($brand->country)
                                        <span class="badge bg-light text-dark">{{ $brand->country }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="products-count">
                                        {{ $brand->products_count }}
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                            data-id="{{ $brand->id }}" {{ $brand->is_active ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions d-flex gap-1">
                                        <a href="{{ route('admin.brands.show', $brand) }}"
                                            class="btn btn-sm btn-light text-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.brands.edit', $brand) }}"
                                            class="btn btn-sm btn-light text-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <h5 class="mb-2">No Brands Found</h5>
                                        <p class="text-muted mb-3">Start by creating your first brand</p>
                                        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Brand
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($brands->hasPages())
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} of {{ $brands->total() }}
                        brands
                    </div>
                    {{ $brands->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            // Select All Checkbox
            $('#selectAll').on('change', function() {
                $('.item-checkbox').prop('checked', this.checked);
                updateBulkActionsButton();
            });

            $('.item-checkbox').on('change', function() {
                updateBulkActionsButton();
            });

            function updateBulkActionsButton() {
                const checkedCount = $('.item-checkbox:checked').length;
                $('#bulkActionsDropdown').prop('disabled', checkedCount === 0);
            }

            // Search Functionality
            $('#searchBox').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#brandsTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Status Toggle
            $('.status-toggle').on('change', function() {
                const $toggle = $(this);
                const brandId = $toggle.data('id');
                const isActive = $toggle.is(':checked');

                console.log('Toggling brand status:', {
                    brandId: brandId,
                    isActive: isActive
                });

                $.ajax({
                    url: `/admin/brands/${brandId}/status`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive ? 1 : 0
                    },
                    success: function(response) {
                        console.log('Status updated successfully:', response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Status update failed:', {
                            xhr: xhr,
                            status: status,
                            error: error
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Failed to update brand status'
                        });
                        // Revert the toggle on error
                        $toggle.prop('checked', !isActive);
                    }
                });
            });

            // Bulk Actions
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();
                const action = $(this).data('action');
                const selectedIds = $('.item-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one brand'
                    });
                    return;
                }

                let confirmText = '';
                switch (action) {
                    case 'delete':
                        confirmText = 'Are you sure you want to delete the selected brands?';
                        break;
                    case 'activate':
                        confirmText = 'Activate selected brands?';
                        break;
                    case 'deactivate':
                        confirmText = 'Deactivate selected brands?';
                        break;
                }

                Swal.fire({
                    title: 'Confirm Action',
                    text: confirmText,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.brands.bulk-action') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                action: action,
                                ids: selectedIds
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message ||
                                        'Failed to perform bulk action'
                                });
                            }
                        });
                    }
                });
            });

            // Delete Form
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete Brand?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Sortable
            const sortable = new Sortable(document.getElementById('sortable-brands'), {
                handle: '.sortable-handle',
                animation: 150,
                onEnd: function(evt) {
                    const orders = [];
                    $('#sortable-brands tr').each(function(index) {
                        const id = $(this).data('id');
                        if (id) {
                            orders.push({
                                id: id,
                                order: index
                            });
                        }
                    });

                    $.ajax({
                        url: '{{ route('admin.brands.reorder') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            orders: orders
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to reorder brands'
                            });
                            window.location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endpush
