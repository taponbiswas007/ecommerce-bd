@extends('admin.layouts.master')

@section('title', 'Trashed Brands')
@section('page-title', 'Trashed Brands')
@section('page-subtitle', 'Manage deleted brands')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
    <li class="breadcrumb-item active">Trashed</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.brands.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left me-1"></i> Back to Brands
    </a>
@endsection

@push('styles')
    <style>
        .brand-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 8px;
            border: 2px solid #f0f2f5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            background: white;
            padding: 4px;
        }

        .brand-logo-placeholder {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .deleted-date {
            font-size: 12px;
            color: #999;
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
        <div class="card-header bg-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-trash-restore me-2"></i>Trashed Brands</h5>
                <span class="badge bg-white text-danger">{{ $brands->total() }} deleted brands</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="60">Logo</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Products</th>
                            <th>Deleted At</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                            <tr>
                                <td>
                                    @if ($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                            class="brand-logo">
                                    @else
                                        <div class="brand-logo-placeholder">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h6 class="mb-0">{{ $brand->name }}</h6>
                                    @if ($brand->website)
                                        <div class="small text-muted">
                                            <i class="fas fa-globe me-1"></i>{{ $brand->website }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($brand->country)
                                        <span class="badge bg-light text-dark">{{ $brand->country }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $brand->products_count }}</span>
                                </td>
                                <td>
                                    <div class="deleted-date">
                                        <i class="fas fa-clock me-1"></i>{{ $brand->deleted_at->diffForHumans() }}
                                    </div>
                                    <small class="text-muted">{{ $brand->deleted_at->format('M d, Y h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('admin.brands.restore', $brand->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Restore"
                                                onclick="return confirm('Are you sure you want to restore this brand?')">
                                                <i class="fas fa-undo me-1"></i>Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.brands.force-delete', $brand->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Permanent Delete"
                                                onclick="return confirm('This action is PERMANENT and cannot be undone. Are you sure?')">
                                                <i class="fas fa-times me-1"></i>Delete Forever
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h5 class="mb-2">No Trashed Brands</h5>
                                        <p class="text-muted mb-3">All brands are active</p>
                                        <a href="{{ route('admin.brands.index') }}" class="btn btn-primary">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Brands
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($brands->hasPages())
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} of {{ $brands->total() }}
                        brands
                    </div>
                    {{ $brands->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
