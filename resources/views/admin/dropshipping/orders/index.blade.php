@extends('admin.layouts.master')

@section('title', 'Dropshipping Orders')
@section('page-title', 'Dropshipping Orders')
@section('page-subtitle', 'Manage dropshipping orders and CJ integration')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Dropshipping Orders</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.dropshipping.orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Submit Order to CJ
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Orders</small>
                            <h3>{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Pending</small>
                            <h3>{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="fas fa-clock fa-3x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Shipped</small>
                            <h3>{{ $stats['shipped'] }}</h3>
                        </div>
                        <i class="fas fa-truck fa-3x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Profit</small>
                            <h3>{{ number_format($stats['total_profit'] ?? 0, 0) }} ৳</h3>
                        </div>
                        <i class="fas fa-chart-line fa-3x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by order #..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>
                                    Confirmed
                                </option>
                                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped
                                </option>
                                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>
                                    Delivered
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" placeholder="From"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" placeholder="To"
                                value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="selectAll"></th>
                                <th>CJ Order #</th>
                                <th>Original Order</th>
                                <th>Customer</th>
                                <th>Cost</th>
                                <th>Revenue</th>
                                <th>Profit</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><input type="checkbox" class="form-check-input order-checkbox"
                                            value="{{ $order->id }}"></td>
                                    <td>
                                        <a href="{{ route('admin.dropshipping.orders.show', $order->id) }}"
                                            class="text-decoration-none">
                                            {{ $order->cj_order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->order->id) }}"
                                            class="text-decoration-none">
                                            {{ $order->order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->order->user->name }}</td>
                                    <td>{{ number_format($order->cost_price, 2) }} ৳</td>
                                    <td class="fw-bold">{{ number_format($order->selling_price, 2) }} ৳</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format($order->profit, 2) }} ৳
                                        </span>
                                    </td>
                                    <td>
                                        @switch($order->cj_order_status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @break

                                            @case('confirmed')
                                                <span class="badge bg-info">Confirmed</span>
                                            @break

                                            @case('shipped')
                                                <span class="badge bg-primary">Shipped</span>
                                            @break

                                            @case('delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($order->cj_order_status) }}</span>
                                        @endswitch
                                    </td>
                                    <td><small>{{ $order->submitted_to_cj_at?->format('M d, Y') ?? 'N/A' }}</small></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.dropshipping.orders.show', $order->id) }}"
                                                class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.dropshipping.orders.sync-status', $order->id) }}"
                                                class="btn btn-outline-info" title="Sync Status">
                                                <i class="fas fa-sync"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            No dropshipping orders found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->render() }}
                    </div>
                @endif

                <!-- Bulk Sync Modal -->
                <div class="modal fade" id="bulkSyncModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bulk Sync Orders</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="bulkSyncForm" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p>Sync status for selected orders with CJ?</p>
                                    <p class="text-muted small">This will fetch the latest status from CJ for all selected
                                        orders.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Sync Orders</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('selectAll').addEventListener('change', function() {
                    document.querySelectorAll('.order-checkbox').forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                document.getElementById('bulkSyncForm').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb
                        .value);

                    if (selectedIds.length === 0) {
                        alert('Please select at least one order');
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('admin.dropshipping.orders.bulk-sync') }}', {
                            method: 'POST',
                            body: JSON.stringify({
                                order_ids: selectedIds,
                                _token: document.querySelector('input[name="_token"]').value
                            }),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred');
                    }
                });
            </script>
        @endsection
