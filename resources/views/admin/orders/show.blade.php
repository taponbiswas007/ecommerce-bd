@extends('admin.layouts.master')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', 'View order information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
@endsection

@section('content')
    <div class="card border shadow-sm mb-4 rounded-1">
        <div class="card-body p-3">
            <x-admin.order-details :order="$order" />
        </div>
    </div>

    <div class="card border shadow-sm mb-4 rounded-1">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Negotiation & Final Quote</h5>
        </div>
        <div class="card-body p-3">
            @php
                $baseOrderSubtotal = (float) $order->subtotal;
                $storedTransportCost =
                    (float) ($order->additional_transport_cost ?? 0) > 0
                        ? (float) $order->additional_transport_cost
                        : (float) ($order->shipping_charge ?? 0);
                $storedVatAmount = (float) ($order->vat_amount ?? 0);
                $storedAitAmount =
                    (float) (($order->ait_amount ?? null) !== null ? $order->ait_amount : $order->tax_amount ?? 0);
            @endphp

            <form id="negotiationForm" method="POST" action="{{ route('admin.orders.update-negotiation', $order->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Transport Cost</label>
                        <input type="number" min="0" step="0.01" name="additional_transport_cost"
                            id="additional_transport_cost" class="form-control"
                            value="{{ old('additional_transport_cost', $storedTransportCost) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Carrying Cost</label>
                        <input type="number" min="0" step="0.01" name="additional_carrying_cost"
                            id="additional_carrying_cost" class="form-control"
                            value="{{ old('additional_carrying_cost', $order->additional_carrying_cost) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Bank/Transfer Cost</label>
                        <input type="number" min="0" step="0.01" name="bank_transfer_cost"
                            id="bank_transfer_cost" class="form-control"
                            value="{{ old('bank_transfer_cost', $order->bank_transfer_cost) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Other Cost</label>
                        <input type="number" min="0" step="0.01" name="additional_other_cost"
                            id="additional_other_cost" class="form-control"
                            value="{{ old('additional_other_cost', $order->additional_other_cost) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Admin Discount</label>
                        <input type="number" min="0" step="0.01" name="admin_discount_amount"
                            id="admin_discount_amount" class="form-control"
                            value="{{ old('admin_discount_amount', $order->admin_discount_amount) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">VAT Amount</label>
                        <input type="number" min="0" step="0.01" name="vat_amount" id="vat_amount"
                            class="form-control" value="{{ old('vat_amount', $storedVatAmount) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">AIT Amount</label>
                        <input type="number" min="0" step="0.01" name="ait_amount" id="ait_amount"
                            class="form-control" value="{{ old('ait_amount', $storedAitAmount) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Final Payable Total</label>
                        <input type="number" min="0" step="0.01" name="negotiated_total_amount"
                            id="negotiated_total_amount" class="form-control"
                            value="{{ old('negotiated_total_amount', $order->negotiated_total_amount ?? $order->total_amount) }}">
                        <small class="text-muted d-block mt-1">
                            Base product subtotal: ৳<span
                                id="base_subtotal_preview">{{ number_format($baseOrderSubtotal, 2) }}</span>
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Negotiation Status</label>
                        <select class="form-select" name="negotiation_status" required>
                            @php
                                $negotiationStatuses = [
                                    'open' => 'Open',
                                    'quoted' => 'Quoted',
                                    'awaiting_customer_payment' => 'Awaiting Customer Payment',
                                    'proof_submitted' => 'Proof Submitted',
                                    'finalized' => 'Finalized',
                                    'cancelled' => 'Cancelled',
                                ];
                            @endphp
                            @foreach ($negotiationStatuses as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('negotiation_status', $order->negotiation_status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Account ({{ strtoupper($order->payment_method) }})</label>
                        <select name="payment_account_id"
                            class="form-select @error('payment_account_id') is-invalid @enderror">
                            <option value="">-- Select account --</option>
                            @foreach ($paymentAccounts ?? collect() as $account)
                                <option value="{{ $account->id }}"
                                    {{ (string) old('payment_account_id', $order->payment_account_id) === (string) $account->id ? 'selected' : '' }}>
                                    {{ $account->account_name }} - {{ $account->account_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if (($paymentAccounts ?? collect())->isEmpty())
                            <small class="text-danger d-block mt-1">
                                No active account found for {{ strtoupper($order->payment_method) }}. Add one from Account
                                Information.
                            </small>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="1" name="send_chat_update"
                                id="send_chat_update" checked>
                            <label class="form-check-label" for="send_chat_update">Send update in chat</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item-wise Billing (Unit Price Editable)</label>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th style="width: 90px;">Qty</th>
                                    <th style="width: 220px;">Unit Price</th>
                                    <th style="width: 140px;">Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr data-item-row>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="item-qty" data-qty="{{ (int) $item->quantity }}">
                                            {{ (int) $item->quantity }}</td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" min="0" step="0.01"
                                                    name="item_unit_prices[{{ $item->id }}]"
                                                    class="form-control item-unit-price"
                                                    value="{{ old('item_unit_prices.' . $item->id, $item->unit_price) }}"
                                                    data-original-price="{{ number_format((float) $item->unit_price, 2, '.', '') }}"
                                                    readonly>
                                                <button type="button" class="btn btn-outline-secondary toggle-price-edit"
                                                    title="Edit unit price">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mt-1 item-price-badge">Original:
                                                ৳{{ number_format((float) $item->unit_price, 2) }}</small>
                                        </td>
                                        <td>
                                            ৳<span
                                                class="item-line-total">{{ number_format((float) $item->total_price, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="alert alert-light border" id="negotiation_breakdown_preview">
                        <strong>Live Preview:</strong>
                        <span class="ms-2">Subtotal ৳<span data-preview="subtotal">0.00</span></span> +
                        <span>Transport ৳<span data-preview="transport">0.00</span></span> +
                        <span>VAT ৳<span data-preview="vat">0.00</span></span> +
                        <span>AIT ৳<span data-preview="ait">0.00</span></span> +
                        <span>Carrying ৳<span data-preview="carrying">0.00</span></span> +
                        <span>Transfer ৳<span data-preview="transfer">0.00</span></span> +
                        <span>Other ৳<span data-preview="other">0.00</span></span> -
                        <span>Discount ৳<span data-preview="discount">0.00</span></span> =
                        <strong>৳<span data-preview="final">0.00</span></strong>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Instructions (for customer)</label>
                    <textarea name="payment_instructions" class="form-control" rows="3">{{ old('payment_instructions', $order->payment_instructions) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Internal Admin Notes</label>
                    <textarea name="admin_notes" class="form-control" rows="2">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="mark_payment_paid"
                        id="mark_payment_paid">
                    <label class="form-check-label" for="mark_payment_paid">Mark payment as paid and finalize</label>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Save Negotiation Update
                </button>
            </form>
        </div>
    </div>

    <!-- Order Status Update Section -->
    <div class="card border shadow-sm mb-4 rounded-1">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Update Order Status</h5>
        </div>
        <div class="card-body p-3">
            <!-- Alert Messages -->
            <div id="alertMessage" class="alert d-none" role="alert"></div>

            <form id="statusUpdateForm" action="javascript:void(0);" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="order_status" class="form-label">Order Status <span
                                class="text-danger">*</span></label>
                        <select name="order_status" id="order_status" class="form-select" required>
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="ready_to_ship" {{ $order->order_status == 'ready_to_ship' ? 'selected' : '' }}>
                                Ready to Ship</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped
                            </option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                            <option value="refunded" {{ $order->order_status == 'refunded' ? 'selected' : '' }}>Refunded
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="location" class="form-label">Current Location (Optional)</label>
                        <input type="text" name="location" id="location" class="form-control"
                            placeholder="e.g., Dhaka Distribution Center">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="document" class="form-label">Upload Document (Optional)</label>
                        <input type="file" name="document" id="document" class="form-control"
                            accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Max 5MB (PDF, JPG, PNG)</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Status Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"
                        placeholder="Add any additional information about this status update..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Status
                </button>
            </form>
        </div>
    </div>

    <!-- Order Tracking History -->
    <div class="card border shadow-sm mb-4 rounded-1">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Order Tracking History</h5>
        </div>
        <div class="card-body p-3">
            @if ($order->statusHistories->count() > 0)
                <div class="timeline">
                    @foreach ($order->statusHistories as $history)
                        <div class="timeline-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="timeline-marker">
                                    <div class="badge bg-{{ $history->status_color }} p-2">
                                        <i class="{{ $history->status_icon }}"></i>
                                    </div>
                                </div>
                                <div class="timeline-content grow ms-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $history->status_display }}</h6>
                                            <small class="text-muted">
                                                <i
                                                    class="far fa-clock me-1"></i>{{ $history->status_date->format('d M Y, h:i A') }}
                                                @if ($history->updatedBy)
                                                    | By: {{ $history->updatedBy->name }}
                                                @endif
                                            </small>
                                        </div>
                                        @if ($history->previous_status)
                                            <span class="badge bg-secondary">
                                                Changed from: {{ ucfirst($history->previous_status) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if ($history->notes)
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-sticky-note me-2"></i>{{ $history->notes }}
                                        </p>
                                    @endif
                                    @if ($history->location)
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                            <strong>Location:</strong> {{ $history->location }}
                                        </p>
                                    @endif
                                    @if ($history->document_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $history->document_path) }}"
                                                class="btn btn-sm btn-outline-success" target="_blank"
                                                download="{{ $history->document_name }}">
                                                <i class="fas fa-file-download me-1"></i>
                                                Download {{ $history->document_name ?? 'Document' }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No tracking history available yet.
                </div>
            @endif
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 17px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #dee2e6;
        }

        /* Alert Animation */
        #alertMessage {
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #alertMessage.alert-success {
            border-left: 4px solid #28a745;
            font-size: 15px;
        }

        #countdown {
            font-weight: bold;
            color: #155724;
        }
    </style>
@endsection

@push('scripts')
    <script>
        (function() {
            const transportInput = document.getElementById('additional_transport_cost');
            const carryingInput = document.getElementById('additional_carrying_cost');
            const bankInput = document.getElementById('bank_transfer_cost');
            const otherInput = document.getElementById('additional_other_cost');
            const discountInput = document.getElementById('admin_discount_amount');
            const vatInput = document.getElementById('vat_amount');
            const aitInput = document.getElementById('ait_amount');
            const finalInput = document.getElementById('negotiated_total_amount');
            const subtotalPreview = document.getElementById('base_subtotal_preview');
            const itemRows = Array.from(document.querySelectorAll('[data-item-row]'));

            if (!finalInput) {
                return;
            }

            const readAmount = (input) => {
                if (!input) {
                    return 0;
                }
                const value = parseFloat(input.value);
                return Number.isFinite(value) ? value : 0;
            };

            const recalculateSubtotalFromItems = () => {
                let subtotal = 0;

                itemRows.forEach((row) => {
                    const qtyElement = row.querySelector('.item-qty');
                    const unitInput = row.querySelector('.item-unit-price');
                    const lineTotalElement = row.querySelector('.item-line-total');

                    const qty = Number(qtyElement?.dataset.qty || 0);
                    const unitPrice = readAmount(unitInput);
                    const lineTotal = unitPrice * qty;
                    subtotal += lineTotal;

                    if (lineTotalElement) {
                        lineTotalElement.textContent = lineTotal.toFixed(2);
                    }
                });

                if (subtotalPreview) {
                    subtotalPreview.textContent = subtotal.toFixed(2);
                }

                return subtotal;
            };

            const recalculateFinalPayable = () => {
                const subtotal = recalculateSubtotalFromItems();

                const finalAmount = subtotal +
                    readAmount(transportInput) +
                    readAmount(vatInput) +
                    readAmount(aitInput) +
                    readAmount(carryingInput) +
                    readAmount(bankInput) +
                    readAmount(otherInput) -
                    readAmount(discountInput);

                finalInput.value = Math.max(0, finalAmount).toFixed(2);

                const setPreview = (key, value) => {
                    const element = document.querySelector(`[data-preview="${key}"]`);
                    if (element) element.textContent = Number(value).toFixed(2);
                };

                setPreview('subtotal', subtotal);
                setPreview('transport', readAmount(transportInput));
                setPreview('vat', readAmount(vatInput));
                setPreview('ait', readAmount(aitInput));
                setPreview('carrying', readAmount(carryingInput));
                setPreview('transfer', readAmount(bankInput));
                setPreview('other', readAmount(otherInput));
                setPreview('discount', readAmount(discountInput));
                setPreview('final', Math.max(0, finalAmount));
            };

            [transportInput, vatInput, aitInput, carryingInput, bankInput, otherInput, discountInput].forEach((
                input) => {
                if (input) {
                    input.addEventListener('input', recalculateFinalPayable);
                    input.addEventListener('change', recalculateFinalPayable);
                }
            });

            itemRows.forEach((row) => {
                const unitInput = row.querySelector('.item-unit-price');
                const editBtn = row.querySelector('.toggle-price-edit');

                if (unitInput) {
                    unitInput.addEventListener('input', recalculateFinalPayable);
                    unitInput.addEventListener('change', recalculateFinalPayable);

                    const syncOldNewBadge = () => {
                        const badge = row.querySelector('.item-price-badge');
                        if (!badge) {
                            return;
                        }
                        const originalPrice = parseFloat(unitInput.dataset.originalPrice || '0');
                        const currentPrice = readAmount(unitInput);
                        if (Math.abs(currentPrice - originalPrice) < 0.001) {
                            badge.className = 'text-muted d-block mt-1 item-price-badge';
                            badge.textContent = 'Original: ৳' + originalPrice.toFixed(2);
                        } else {
                            badge.className = 'd-block mt-1 item-price-badge text-primary fw-semibold';
                            badge.textContent = 'Old ৳' + originalPrice.toFixed(2) + ' → New ৳' +
                                currentPrice.toFixed(2);
                        }
                    };

                    unitInput.addEventListener('input', syncOldNewBadge);
                    unitInput.addEventListener('change', syncOldNewBadge);
                    syncOldNewBadge();
                }

                if (editBtn && unitInput) {
                    editBtn.addEventListener('click', () => {
                        const isReadonly = unitInput.hasAttribute('readonly');
                        if (isReadonly) {
                            unitInput.removeAttribute('readonly');
                            unitInput.focus();
                            editBtn.classList.remove('btn-outline-secondary');
                            editBtn.classList.add('btn-outline-success');
                            editBtn.innerHTML = '<i class="fas fa-check"></i>';
                        } else {
                            unitInput.setAttribute('readonly', 'readonly');
                            editBtn.classList.remove('btn-outline-success');
                            editBtn.classList.add('btn-outline-secondary');
                            editBtn.innerHTML = '<i class="fas fa-pen"></i>';
                            recalculateFinalPayable();
                        }
                    });
                }
            });

            recalculateFinalPayable();
        })();

        (function() {
            'use strict';

            const form = document.getElementById('statusUpdateForm');
            const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
            const alertDiv = document.getElementById('alertMessage');

            console.log('Form found:', !!form); // DEBUG
            console.log('Submit button found:', !!submitBtn); // DEBUG
            console.log('Alert div found:', !!alertDiv); // DEBUG

            if (!form || !submitBtn || !alertDiv) {
                console.error('Required elements not found');
                return;
            }

            form.onsubmit = function(e) {
                e.preventDefault();

                console.log('Form submitted'); // DEBUG

                const formData = new FormData(form);
                const originalText = submitBtn.innerHTML;

                // Hide previous alerts
                alertDiv.classList.add('d-none');

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';

                fetch('{{ route('admin.orders.update-status', $order->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw err;
                            }).catch(() => {
                                throw new Error('Server error: ' + response.status);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update button to show success
                            submitBtn.innerHTML =
                                '<i class="fas fa-check-circle me-2"></i>Updated Successfully!';
                            submitBtn.classList.remove('btn-primary');
                            submitBtn.classList.add('btn-success');

                            // Show success alert with countdown
                            alertDiv.className = 'alert alert-success alert-dismissible fade show';
                            alertDiv.innerHTML = `
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Success!</strong> ${data.message}
                            ${data.document_url ? '<br><small class="mt-2 d-block"><i class="fas fa-file me-1"></i>Document uploaded successfully!</small>' : ''}
                            <br><small class="mt-2 d-block"><i class="fas fa-sync-alt me-1"></i>Page will reload in <span id="countdown">3</span> seconds...</small>
                        `;
                            alertDiv.classList.remove('d-none');

                            // Scroll to alert
                            alertDiv.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            // Countdown timer
                            let countdown = 3;
                            const countdownElement = document.getElementById('countdown');
                            const countdownInterval = setInterval(() => {
                                countdown--;
                                if (countdownElement) {
                                    countdownElement.textContent = countdown;
                                }
                                if (countdown <= 0) {
                                    clearInterval(countdownInterval);
                                }
                            }, 1000);

                            // Reload page after 3 seconds
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            // Show error alert
                            alertDiv.className = 'alert alert-danger';
                            alertDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + (data
                                .message || 'Failed to update status');
                            alertDiv.classList.remove('d-none');

                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        let errorMessage = 'An error occurred while updating the status';

                        // Handle validation errors
                        if (error.errors) {
                            errorMessage = '<strong>Validation Errors:</strong><ul class="mb-0 mt-2">';
                            Object.values(error.errors).flat().forEach(err => {
                                errorMessage += '<li>' + err + '</li>';
                            });
                            errorMessage += '</ul>';
                        } else if (error.message) {
                            errorMessage = error.message;
                        }

                        // Show error alert
                        alertDiv.className = 'alert alert-danger';
                        alertDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>' + errorMessage;
                        alertDiv.classList.remove('d-none');

                        // Scroll to alert
                        alertDiv.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });

                return false;
            };
        })();
    </script>
@endpush
