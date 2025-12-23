@extends('admin.layouts.master')

@section('title', $unit->name)
@section('page-title', $unit->name . ' (' . $unit->symbol . ')')
@section('page-subtitle', 'Unit Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.units.index') }}">Units</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i> Edit
        </a>
        <a href="{{ route('admin.units.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Add New
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Unit Info Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Unit Information</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-balance-scale fa-3x"></i>
                        </div>
                    </div>

                    <h4>{{ $unit->name }}</h4>
                    <h5 class="text-primary">{{ $unit->symbol }}</h5>

                    <div class="mt-3">
                        <span class="badge bg-{{ $unit->is_active ? 'success' : 'danger' }} me-2">
                            {{ $unit->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="badge bg-primary">
                            {{ $unit->products_count }} Products
                        </span>
                    </div>
                </div>

                <div class="card-footer">
                    <small class="text-muted">
                        Created: {{ $unit->created_at->format('M d, Y h:i A') }}
                    </small>
                    <br>
                    <small class="text-muted">
                        Updated: {{ $unit->updated_at->format('M d, Y h:i A') }}
                    </small>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.create', ['unit_id' => $unit->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add Product
                        </a>
                        <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i> Edit Unit
                        </a>
                        <button type="button" class="btn btn-outline-danger confirm-delete" data-id="{{ $unit->id }}"
                            data-name="{{ $unit->name }}" {{ $unit->products_count > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash me-2"></i>
                            {{ $unit->products_count > 0 ? 'Cannot Delete' : 'Delete Unit' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#details">
                                <i class="fas fa-info-circle me-2"></i> Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#products">
                                <i class="fas fa-box me-2"></i> Products
                                <span class="badge bg-primary ms-2">{{ $unit->products_count }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">Name:</th>
                                            <td>{{ $unit->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Symbol:</th>
                                            <td>
                                                <span class="badge bg-primary">{{ $unit->symbol }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge bg-{{ $unit->is_active ? 'success' : 'danger' }}">
                                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">Created:</th>
                                            <td>{{ $unit->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated:</th>
                                            <td>{{ $unit->updated_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Products:</th>
                                            <td>
                                                <span class="badge bg-primary">{{ $unit->products_count }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if ($unit->description)
                                <div class="mt-4">
                                    <h6>Description</h6>
                                    <p class="text-muted">{{ $unit->description }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Products Tab -->
                        <div class="tab-pane fade" id="products">
                            @if ($unit->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($unit->products as $product)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if ($product->primary_image)
                                                                <img src="{{ asset('storage/' . $product->primary_image->image_path) }}"
                                                                    alt="{{ $product->name }}"
                                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                                    class="rounded me-2">
                                                            @endif
                                                            <div>
                                                                <strong>{{ $product->name }}</strong>
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ Str::limit($product->short_description, 30) }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($product->category)
                                                            <span
                                                                class="badge bg-info">{{ $product->category->name }}</span>
                                                        @else
                                                            <span class="text-muted">Uncategorized</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>৳{{ number_format($product->base_price, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'warning' : 'danger') }}">
                                                            {{ $product->stock_quantity }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.products.show', $product->id) }}"
                                                            class="btn btn-sm btn-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                                            class="btn btn-sm btn-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                    <h5>No Products Found</h5>
                                    <p class="text-muted">This unit doesn't have any products yet.</p>
                                    <a href="{{ route('admin.products.create', ['unit_id' => $unit->id]) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> Add Product
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" action="{{ route('admin.units.destroy', $unit->id) }}">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            const deleteButton = document.querySelector('.confirm-delete');
            if (deleteButton && !deleteButton.disabled) {
                deleteButton.addEventListener('click', function() {
                    const unitName = this.getAttribute('data-name');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete "${unitName}". This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('deleteForm').submit();
                        }
                    });
                });
            }

            // Tab persistence
            const hash = window.location.hash;
            if (hash) {
                const tab = document.querySelector(`a[href="${hash}"]`);
                if (tab) {
                    const tabInstance = new bootstrap.Tab(tab);
                    tabInstance.show();
                }
            }
        });
    </script>
@endpush
