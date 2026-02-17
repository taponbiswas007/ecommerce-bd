@extends('admin.layouts.master')

@section('title', 'Submit Order to CJ')
@section('page-title', 'Submit Orders to CJ')
@section('page-subtitle', 'Select and confirm orders to submit to CJ Dropshipping')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.dropshipping.orders.index') }}">Dropshipping Orders</a></li>
    <li class="breadcrumb-item active">Submit Orders</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </td>
                                    <td><strong>{{ number_format($order->total_amount, 2) }} ৳</strong></td>
                                    <td>{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($order->order_status) }}</span></td>
                                    <td><small>{{ $order->created_at->format('M d, Y') }}</small></td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.dropshipping.orders.submit') }}"
                                            style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary"
                                                onclick="return confirm('Submit this order to CJ?')">
                                                <i class="fas fa-arrow-right"></i> Submit
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="7">
                                        <small><strong>Items:</strong></small><br>
                                        <div style="padding-left: 20px;">
                                            @foreach ($order->items as $item)
                                                <small>
                                                    • {{ $item->product->name ?? 'Unknown Product' }}
                                                    x{{ $item->quantity }} = {{ number_format($item->total_price, 2) }} ৳
                                                </small><br>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        No confirmed orders available to submit
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
        </div>
    </div>
@endsection
