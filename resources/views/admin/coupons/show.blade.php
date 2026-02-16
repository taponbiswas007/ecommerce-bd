@extends('admin.layouts.master')

@section('title', 'Coupon Details')
@section('page-title', 'Coupon Details')
@section('page-subtitle', $coupon->code)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning btn-sm me-2">
        <i class="fas fa-edit me-1"></i> Edit Coupon
    </a>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border shadow-sm rounded mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Coupon Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Code</small>
                            <div class="fw-bold">{{ $coupon->code }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Name</small>
                            <div class="fw-bold">{{ $coupon->name }}</div>
                        </div>
                        <div class="col-12 mb-3">
                            <small class="text-muted">Description</small>
                            <div class="fw-bold">{{ $coupon->description ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Discount Type</small>
                            <div class="fw-bold text-capitalize">{{ $coupon->discount_type }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Discount Value</small>
                            <div class="fw-bold">
                                @if ($coupon->discount_type === 'percentage')
                                    {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                                @else
                                    BDT {{ number_format($coupon->discount_value, 2) }}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Minimum Order Amount</small>
                            <div class="fw-bold">
                                {{ $coupon->min_order_amount ? 'BDT ' . number_format($coupon->min_order_amount, 2) : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Maximum Discount Amount</small>
                            <div class="fw-bold">
                                {{ $coupon->max_discount_amount ? 'BDT ' . number_format($coupon->max_discount_amount, 2) : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Valid From</small>
                            <div class="fw-bold">{{ $coupon->valid_from->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Valid To</small>
                            <div class="fw-bold">{{ $coupon->valid_to->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Usage Limit</small>
                            <div class="fw-bold">{{ $coupon->usage_limit ?: 'Unlimited' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Used Count</small>
                            <div class="fw-bold">{{ $coupon->used_count }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Status</small>
                            <div class="fw-bold">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">New Users Only</small>
                            <div class="fw-bold">{{ $coupon->for_new_users_only ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Created At</small>
                        <div class="fw-bold">{{ $coupon->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Updated At</small>
                        <div class="fw-bold">{{ $coupon->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
