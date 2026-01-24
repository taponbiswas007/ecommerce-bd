@props(['orders'])
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0"
        style="background: var(--bs-body-bg); color: var(--bs-body-color);">
        <thead class="table-light">
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Transport</th>
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
                    <td>
                        <select class="form-select form-select-sm order-status-dropdown"
                            data-order-id="{{ $order->id }}">
                            @php
                                $statuses = [
                                    'pending' => 'Pending',
                                    'confirmed' => 'Confirmed',
                                    'processing' => 'Processing',
                                    'ready_to_ship' => 'Ready to Ship',
                                    'shipped' => 'Shipped',
                                    'delivered' => 'Delivered',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                    'refunded' => 'Refunded',
                                ];
                            @endphp
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" @if ($order->order_status === $key) selected @endif>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        <form class="order-document-form mt-2" data-order-id="{{ $order->id }}"
                            enctype="multipart/form-data" style="display: none;">
                            <input type="file" name="delivery_document" accept=".png,.jpg,.jpeg,.pdf"
                                class="form-control form-control-sm mb-1" required>
                            <button type="submit" class="btn btn-sm btn-success">Upload Document</button>
                        </form>
                        @if ($order->delivery_document)
                            <a href="{{ asset('storage/' . $order->delivery_document) }}" target="_blank"
                                class="d-block mt-1 text-primary">View Document</a>
                        @endif
                    </td>
                    <td>
                        {{ $order->transportCompany ? $order->transportCompany->name : $order->transport_name ?? '-' }}
                    </td>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.order-status-dropdown').forEach(function(dropdown) {
                const orderId = dropdown.getAttribute('data-order-id');
                const form = document.querySelector(`.order-document-form[data-order-id='${orderId}']`);
                // Show/hide document upload form based on status
                function toggleDocumentForm() {
                    if (dropdown.value === 'shipped') {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                }
                toggleDocumentForm();
                dropdown.addEventListener('change', function() {
                    const status = this.value;
                    fetch(`/admin/orders/${orderId}/update-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                order_status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (window.Swal && Swal.mixin) {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        background: getComputedStyle(document
                                            .documentElement).getPropertyValue(
                                            '--bs-body-bg'),
                                        color: getComputedStyle(document
                                            .documentElement).getPropertyValue(
                                            '--bs-body-color'),
                                    });
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Order status updated!'
                                    });
                                } else {
                                    alert('Order status updated!');
                                }
                            } else {
                                alert('Failed to update status.');
                            }
                            toggleDocumentForm();
                        })
                        .catch(() => alert('Error updating status.'));
                });
            });

            // Handle document upload
            document.querySelectorAll('.order-document-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const orderId = form.getAttribute('data-order-id');
                    const fileInput = form.querySelector('input[name="delivery_document"]');
                    const file = fileInput.files[0];
                    if (!file) return;
                    const formData = new FormData();
                    formData.append('delivery_document', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'));
                    fetch(`/admin/orders/${orderId}/upload-document`, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (window.Swal && Swal.mixin) {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        background: getComputedStyle(document
                                            .documentElement).getPropertyValue(
                                            '--bs-body-bg'),
                                        color: getComputedStyle(document
                                            .documentElement).getPropertyValue(
                                            '--bs-body-color'),
                                    });
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Document uploaded!'
                                    });
                                } else {
                                    alert('Document uploaded!');
                                }
                                fileInput.value = '';
                            } else {
                                alert('Failed to upload document.');
                            }
                        })
                        .catch(() => alert('Error uploading document.'));
                });
            });
        });
    </script>
@endpush
