@extends('admin.layouts.master')

@section('title', 'Coupons')
@section('page-title', 'Coupons')
@section('page-subtitle', 'Manage discount coupons')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Coupons</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-hover-shadow">
        <i class="fas fa-plus me-1"></i> New Coupon
    </a>
@endsection

@push('styles')
    <style>
        .coupon-code {
            font-weight: 700;
            letter-spacing: 1px;
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
        }

        .status-badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-active {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }

        .status-inactive {
            background: rgba(235, 87, 87, 0.1);
            color: #eb5757;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px 25px;
            overflow: visible;
        }

        .card-header-gradient .card-title {
            color: white;
            font-weight: 600;
            margin-bottom: 0;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 8px 15px;
            width: 220px;
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-box:focus {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: none;
        }

        .table-actions {
            opacity: 0;
            transition: opacity 0.2s;
        }

        tr:hover .table-actions {
            opacity: 1;
        }

        .btn-hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
            transform: translateY(-2px);
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            margin: 20px 0;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
        }
    </style>
@endpush

@section('content')
    <div class="card border shadow-sm rounded">
        <div class="card-header-gradient">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="card-title mb-0">All Coupons</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" id="searchBox" class="form-control search-box" placeholder="Search coupons...">
                    <button class="btn btn-light" type="button" id="generateCouponCodeIndex">
                        <i class="fas fa-random me-1"></i> Generate & Create
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="couponsTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Discount</th>
                            <th>Validity</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td>
                                    <span class="coupon-code">{{ $coupon->code }}</span>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-1">{{ $coupon->name }}</h6>
                                        @if ($coupon->description)
                                            <small class="text-muted">{{ Str::limit($coupon->description, 60) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($coupon->discount_type === 'percentage')
                                        <span
                                            class="badge bg-info">{{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%</span>
                                    @else
                                        <span class="badge bg-primary">BDT
                                            {{ number_format($coupon->discount_value, 2) }}</span>
                                    @endif
                                    @if ($coupon->max_discount_amount)
                                        <div class="small text-muted mt-1">Max: BDT
                                            {{ number_format($coupon->max_discount_amount, 2) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        {{ $coupon->valid_from->format('M d, Y') }} -
                                        {{ $coupon->valid_to->format('M d, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        {{ $coupon->used_count }}
                                        @if ($coupon->usage_limit)
                                            / {{ $coupon->usage_limit }}
                                        @else
                                            / Unlimited
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($coupon->is_active)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex gap-1">
                                        <a href="{{ route('admin.coupons.show', $coupon) }}"
                                            class="btn btn-sm btn-light text-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                            class="btn btn-sm btn-light text-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <h5 class="mb-2">No Coupons Found</h5>
                                        <p class="text-muted mb-3">Create your first coupon to start discounts</p>
                                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Coupon
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($coupons->hasPages())
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $coupons->firstItem() }} to {{ $coupons->lastItem() }} of {{ $coupons->total() }}
                        coupons
                    </div>
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#searchBox').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#couponsTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('#generateCouponCodeIndex').on('click', async function() {
                const button = $(this);
                button.prop('disabled', true);

                try {
                    const response = await fetch("{{ route('admin.coupons.generate-code') }}");
                    const data = await response.json();

                    if (!data.success || !data.code) {
                        throw new Error('Failed to generate code');
                    }

                    await navigator.clipboard.writeText(data.code);

                    Swal.fire({
                        icon: 'success',
                        title: 'Copied',
                        text: `Coupon code ${data.code} copied to clipboard`,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    window.location.href = "{{ route('admin.coupons.create') }}" +
                        `?code=${encodeURIComponent(data.code)}`;
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to generate or copy coupon code. Please try again.'
                    });
                } finally {
                    button.prop('disabled', false);
                }
            });

            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Delete Coupon?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
