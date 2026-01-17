@props(['order'])
<form method="POST" action="{{ route('admin.orders.update', $order->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Order Status</label>
        <select name="order_status" class="form-select">
            <option value="pending" @selected($order->order_status == 'pending')>Pending</option>
            <option value="confirmed" @selected($order->order_status == 'confirmed')>Confirmed</option>
            <option value="processing" @selected($order->order_status == 'processing')>Processing</option>
            <option value="ready_to_ship" @selected($order->order_status == 'ready_to_ship')>Ready to Ship</option>
            <option value="shipped" @selected($order->order_status == 'shipped')>Shipped</option>
            <option value="delivered" @selected($order->order_status == 'delivered')>Delivered</option>
            <option value="completed" @selected($order->order_status == 'completed')>Completed</option>
            <option value="cancelled" @selected($order->order_status == 'cancelled')>Cancelled</option>
            <option value="refunded" @selected($order->order_status == 'refunded')>Refunded</option>
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
    <div class="mb-3" id="delivery-document-group" style="display: none;">
        <label class="form-label">Delivery Document (Required for Shipped)</label>
        <input type="file" name="delivery_document" class="form-control" accept=".png,.jpg,.jpeg,.pdf">
        @if ($order->delivery_document)
            <div class="mt-2">
                <a href="{{ asset('storage/' . $order->delivery_document) }}" target="_blank">View Current Document</a>
            </div>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Update Order</button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.querySelector('select[name="order_status"]');
        const docGroup = document.getElementById('delivery-document-group');

        function toggleDocField() {
            if (statusSelect.value === 'shipped') {
                docGroup.style.display = '';
            } else {
                docGroup.style.display = 'none';
            }
        }
        statusSelect.addEventListener('change', toggleDocField);
        toggleDocField();
    });
</script>
</form>
