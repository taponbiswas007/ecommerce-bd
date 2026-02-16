@extends('admin.layouts.master')

@section('title', 'Edit Coupon')
@section('page-title', 'Edit Coupon')
@section('page-subtitle', 'Update coupon details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('code');
            const generateBtn = document.getElementById('generateCouponCode');

            if (generateBtn && codeInput) {
                generateBtn.addEventListener('click', async function() {
                    generateBtn.disabled = true;
                    try {
                        const response = await fetch("{{ route('admin.coupons.generate-code') }}");
                        const data = await response.json();

                        if (data.success && data.code) {
                            codeInput.value = data.code;
                        } else {
                            throw new Error('Failed to generate code');
                        }
                    } catch (error) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Unable to generate coupon code. Please try again.'
                            });
                        } else {
                            alert('Unable to generate coupon code. Please try again.');
                        }
                    } finally {
                        generateBtn.disabled = false;
                    }
                });
            }

            if (codeInput) {
                codeInput.addEventListener('input', function() {
                    codeInput.value = codeInput.value.toUpperCase().replace(/\s+/g, '');
                });
            }
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Coupon Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <strong>Please fix the errors below.</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Coupon Code *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
                                    <button class="btn btn-outline-secondary" type="button" id="generateCouponCode">
                                        Generate
                                    </button>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Coupon Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $coupon->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $coupon->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="discount_type" class="form-label">Discount Type *</label>
                                <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type"
                                    name="discount_type" required>
                                    <option value="percentage"
                                        {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>
                                        Percentage
                                    </option>
                                    <option value="fixed"
                                        {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount
                                    </option>
                                </select>
                                @error('discount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="discount_value" class="form-label">Discount Value *</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('discount_value') is-invalid @enderror" id="discount_value"
                                    name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}"
                                    required>
                                @error('discount_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('min_order_amount') is-invalid @enderror"
                                    id="min_order_amount" name="min_order_amount"
                                    value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                                @error('min_order_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_discount_amount" class="form-label">Maximum Discount Amount</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('max_discount_amount') is-invalid @enderror"
                                    id="max_discount_amount" name="max_discount_amount"
                                    value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                                @error('max_discount_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="valid_from" class="form-label">Valid From *</label>
                                <input type="date" class="form-control @error('valid_from') is-invalid @enderror"
                                    id="valid_from" name="valid_from"
                                    value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}" required>
                                @error('valid_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="valid_to" class="form-label">Valid To *</label>
                                <input type="date" class="form-control @error('valid_to') is-invalid @enderror"
                                    id="valid_to" name="valid_to"
                                    value="{{ old('valid_to', $coupon->valid_to->format('Y-m-d')) }}" required>
                                @error('valid_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="usage_limit" class="form-label">Usage Limit</label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror"
                                    id="usage_limit" name="usage_limit"
                                    value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                <div class="form-check form-switch ms-4">
                                    <input class="form-check-input" type="checkbox" id="for_new_users_only"
                                        name="for_new_users_only" value="1"
                                        {{ old('for_new_users_only', $coupon->for_new_users_only) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="for_new_users_only">New Users Only</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Coupon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Coupon Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Used Count</small>
                        <div class="fw-bold">{{ $coupon->used_count }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Created At</small>
                        <div class="fw-bold">{{ $coupon->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Updated At</small>
                        <div class="fw-bold">{{ $coupon->updated_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
