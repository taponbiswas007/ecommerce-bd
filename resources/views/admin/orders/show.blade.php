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
                        <label for="order_status" class="form-label">Order Status <span class="text-danger">*</span></label>
                        <select name="order_status" id="order_status" class="form-select" required>
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="ready_to_ship" {{ $order->order_status == 'ready_to_ship' ? 'selected' : '' }}>
                                Ready to Ship</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped
                            </option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled
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
                                <div class="timeline-content flex-grow-1 ms-3">
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
