@props(['order'])
<form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Order Status</label>
        <select name="order_status" class="form-select">
            <option value="pending" @selected($order->order_status == 'pending')>Pending</option>
            <option value="approved" @selected($order->order_status == 'approved')>Approved</option>
            <option value="cancelled" @selected($order->order_status == 'cancelled')>Cancelled</option>
            <option value="shipped" @selected($order->order_status == 'shipped')>Shipped</option>
            <option value="delivered" @selected($order->order_status == 'delivered')>Delivered</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Shipping Address</label>
        <input type="text" name="shipping_address" class="form-control" value="{{ $order->shipping_address }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Order Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Order</button>
</form>
