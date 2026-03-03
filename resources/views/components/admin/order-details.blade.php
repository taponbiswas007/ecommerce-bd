@props(['order'])
<div class="row">
    <div class="col-md-6">
        <h5 class="fw-bold">Order #{{ $order->order_number }}</h5>
        <p><strong>Customer:</strong> {{ $order->user ? $order->user->name : 'Guest' }}</p>
        <p><strong>Status:</strong> <x-admin.order-status-badge :status="$order->order_status" /></p>
        <p><strong>Negotiation:</strong> {{ ucwords(str_replace('_', ' ', $order->negotiation_status ?? 'open')) }}</p>
        <p><strong>Total:</strong> ৳{{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Final Payable:</strong> ৳{{ number_format($order->payable_amount, 2) }}</p>
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
        <p><strong>Payment Instructions:</strong> {{ $order->payment_instructions ?? 'N/A' }}</p>
        <p><strong>Additional Transport Cost:</strong> ৳{{ number_format($order->additional_transport_cost ?? 0, 2) }}
        </p>
        <p><strong>Additional Carrying Cost:</strong> ৳{{ number_format($order->additional_carrying_cost ?? 0, 2) }}
        </p>
        <p><strong>Bank/Transfer Cost:</strong> ৳{{ number_format($order->bank_transfer_cost ?? 0, 2) }}</p>
        <p><strong>Other Cost:</strong> ৳{{ number_format($order->additional_other_cost ?? 0, 2) }}</p>
        <p><strong>VAT:</strong> ৳{{ number_format($order->vat_amount ?? 0, 2) }}</p>
        <p><strong>AIT:</strong> ৳{{ number_format($order->ait_amount ?? 0, 2) }}</p>
        <p><strong>Admin Discount:</strong> ৳{{ number_format($order->admin_discount_amount ?? 0, 2) }}</p>
        <p><strong>Total Adjustments:</strong> ৳{{ number_format($order->total_adjustments ?? 0, 2) }}</p>

        <h6 class="fw-bold mt-3">Payment Details</h6>
        <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method ?? 'N/A') }}</p>
        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'unpaid') }}</p>
        <p><strong>Transaction ID:</strong> {{ $order->payment_reference ?? 'N/A' }}</p>

        @if ($order->paymentAccount)
            <p><strong>Assigned Account:</strong> {{ $order->paymentAccount->account_name }}</p>
            <p><strong>Account Number:</strong> {{ $order->paymentAccount->account_number }}</p>
            <p><strong>Account Holder:</strong> {{ $order->paymentAccount->account_holder ?? 'N/A' }}</p>
            <p><strong>Branch:</strong> {{ $order->paymentAccount->branch ?? 'N/A' }}</p>
        @else
            <p><strong>Assigned Account:</strong> N/A</p>
        @endif

        @if ($order->payment_proof_path)
            @php
                $proofUrl = asset('storage/' . $order->payment_proof_path);
                $proofExt = strtolower(pathinfo($order->payment_proof_path, PATHINFO_EXTENSION));
                $isImageProof = in_array($proofExt, ['jpg', 'jpeg', 'png']);
                $isPdfProof = $proofExt === 'pdf';
            @endphp
            <div class="mb-2"><strong>Payment Proof:</strong></div>

            @if ($isImageProof)
                <div class="mb-2">
                    <img src="{{ $proofUrl }}" alt="Payment Proof" class="img-fluid rounded border"
                        style="max-height: 420px; object-fit: contain;">
                </div>
            @elseif ($isPdfProof)
                <div class="mb-2 border rounded overflow-hidden" style="height: 500px;">
                    <iframe src="{{ $proofUrl }}" width="100%" height="100%" style="border: 0;"></iframe>
                </div>
            @endif

            <a href="{{ $proofUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-external-link-alt me-1"></i> Open Full Document
            </a>
        @else
            <p><strong>Payment Proof:</strong> Not submitted yet</p>
        @endif
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
