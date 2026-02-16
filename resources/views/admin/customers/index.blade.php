@extends('admin.layouts.master')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Manage customer accounts')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-hover-shadow">
        <i class="fas fa-plus me-1"></i> New Customer
    </a>
@endsection

@push('styles')
    <style>
        .customer-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f0f2f5;
        }

        .customer-avatar-placeholder {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
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

        .card-header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px 25px;
            overflow: visible;
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
            width: 220px;
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-box:focus {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: none;
        }

        .table-actions {
            opacity: 0;
            transition: opacity 0.2s;
        }

        tr:hover .table-actions {
            opacity: 1;
        }

        .btn-hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
            transform: translateY(-2px);
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
    </style>
@endpush

@section('content')
    <div class="card border shadow-sm rounded">
        <div class="card-header-gradient">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="card-title mb-0">All Customers</h5>
                <input type="text" id="searchBox" class="form-control search-box" placeholder="Search customers...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="customersTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Orders</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($customer->user_image)
                                            <img src="{{ asset('storage/' . $customer->user_image) }}"
                                                alt="{{ $customer->name }}" class="customer-avatar">
                                        @else
                                            <div class="customer-avatar-placeholder">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                            <small class="text-muted">{{ $customer->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><i class="fas fa-phone me-1"></i>{{ $customer->phone ?? 'N/A' }}</div>
                                        <div><i class="fas fa-map-marker-alt me-1"></i>{{ $customer->district ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $customer->orders_count }}</span>
                                </td>
                                <td>
                                    @if ($customer->is_active)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">{{ $customer->created_at->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div class="table-actions d-flex gap-1">
                                        <a href="{{ route('admin.customers.show', $customer) }}"
                                            class="btn btn-sm btn-light text-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.customers.edit', $customer) }}"
                                            class="btn btn-sm btn-light text-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h5 class="mb-2">No Customers Found</h5>
                                        <p class="text-muted mb-3">Add your first customer to get started</p>
                                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Customer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages())
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of
                        {{ $customers->total() }} customers
                    </div>
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#searchBox').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#customersTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete Customer?',
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
        });
    </script>
@endpush
