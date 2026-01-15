@props(['orders'])
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0"
        style="background: var(--bs-body-bg); color: var(--bs-body-color);">
        <thead class="table-light">
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user ? $order->user->name : 'Guest' }}</td>
                    <td><x-admin.order-status-badge :status="$order->order_status" /></td>
                    <td>৳{{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $orders->links() }}
</div>
