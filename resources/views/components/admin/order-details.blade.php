@props(['order'])
<div class="row">
    <div class="col-md-6">
        <h5 class="fw-bold">Order #{{ $order->order_number }}</h5>
        <p><strong>Customer:</strong> {{ $order->user ? $order->user->name : 'Guest' }}</p>
        <p><strong>Status:</strong> <x-admin.order-status-badge :status="$order->order_status" /></p>
        <p><strong>Total:</strong> ৳{{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold">Shipping Info</h6>
        <p><strong>Name:</strong> {{ $order->shipping_name }}</p>
        <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
        <p><strong>Email:</strong> {{ $order->shipping_email ?? 'N/A' }}</p>
        <p><strong>District:</strong> {{ $order->shipping_district }}</p>
        <p><strong>Upazila:</strong> {{ $order->shipping_upazila }}</p>
        <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
        <h6 class="fw-bold mt-3">Order Notes</h6>
        <p>{{ $order->customer_notes ?? 'N/A' }}</p>
        <p><strong>Admin Notes:</strong> {{ $order->admin_notes ?? 'N/A' }}</p>
    </div>
</div>
<hr>
<h6 class="fw-bold">Order Items</h6>
<div class="table-responsive">
    <table class="table table-bordered" style="background: var(--bs-body-bg); color: var(--bs-body-color);">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }} {{ $item->product->unit ? $item->product->unit->symbol : '' }}</td>
                    <td>৳{{ number_format($item->unit_price, 2) }}</td>
                    <td>৳{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
