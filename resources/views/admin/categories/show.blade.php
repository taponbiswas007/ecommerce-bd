@extends('admin.layouts.master')

@section('title', $category->name)
@section('page-title', $category->name)
@section('page-subtitle', 'Category Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i> Edit
        </a>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Add New
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Category Info Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Information</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if ($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto"
                                style="width: 200px; height: 200px;">
                                <i class="fas fa-tag fa-4x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <h4>{{ $category->name }}</h4>

                    @if ($category->parent)
                        <p class="text-muted">
                            <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>
                            Subcategory of:
                            <a href="{{ route('admin.categories.show', $category->parent_id) }}">
                                {{ $category->parent->name }}
                            </a>
                        </p>
                    @else
                        <span class="badge bg-success">
                            <i class="fas fa-folder me-1"></i> Main Category
                        </span>
                    @endif

                    <div class="mt-3">
                        <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }} me-2">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="badge bg-primary">
                            Order: {{ $category->order }}
                        </span>
                    </div>
                </div>

                <div class="card-footer">
                    <small class="text-muted">
                        Created: {{ $category->created_at->format('M d, Y h:i A') }}
                    </small>
                    <br>
                    <small class="text-muted">
                        Updated: {{ $category->updated_at->format('M d, Y h:i A') }}
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
                        <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add Product
                        </a>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i> Edit Category
                        </a>
                        <button type="button" class="btn btn-outline-danger confirm-delete" data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}">
                            <i class="fas fa-trash me-2"></i> Delete Category
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
                                <span class="badge bg-primary ms-2">{{ $category->products_count }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#subcategories">
                                <i class="fas fa-sitemap me-2"></i> Subcategories
                                <span class="badge bg-info ms-2">{{ $category->children->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#seo">
                                <i class="fas fa-search me-2"></i> SEO
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
                                            <td>{{ $category->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Slug:</th>
                                            <td>{{ $category->slug }}</td>
                                        </tr>
                                        <tr>
                                            <th>Parent:</th>
                                            <td>
                                                @if ($category->parent)
                                                    <a href="{{ route('admin.categories.show', $category->parent_id) }}">
                                                        {{ $category->parent->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">Display Order:</th>
                                            <td>{{ $category->order }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ $category->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated:</th>
                                            <td>{{ $category->updated_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Products:</th>
                                            <td>
                                                <span class="badge bg-primary">{{ $category->products_count }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if ($category->description)
                                <div class="mt-4">
                                    <h6>Description</h6>
                                    <p class="text-muted">{{ $category->description }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Products Tab -->
                        <div class="tab-pane fade" id="products">
                            @if ($category->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->products as $product)
                                                <tr>
                                                    <td>
                                                        @if ($product->primary_image)
                                                            <img src="{{ asset('storage/' . $product->primary_image->image_path) }}"
                                                                alt="{{ $product->name }}"
                                                                style="width: 40px; height: 40px; object-fit: cover;"
                                                                class="rounded">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                <i class="fas fa-box text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $product->name }}</strong>
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ Str::limit($product->short_description, 30) }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>৳{{ number_format($product->base_price, 2) }}</strong>
                                                        @if ($product->has_discount)
                                                            <br>
                                                            <small class="text-danger">
                                                                <s>৳{{ number_format($product->discount_price, 2) }}</s>
                                                            </small>
                                                        @endif
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
                                    <p class="text-muted">This category doesn't have any products yet.</p>
                                    <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> Add Product
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Subcategories Tab -->
                        <div class="tab-pane fade" id="subcategories">
                            @if ($category->children->count() > 0)
                                <div class="row">
                                    @foreach ($category->children as $subcategory)
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            @if ($subcategory->image)
                                                                <img src="{{ asset('storage/' . $subcategory->image) }}"
                                                                    alt="{{ $subcategory->name }}"
                                                                    style="width: 60px; height: 60px; object-fit: cover;"
                                                                    class="rounded">
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="width: 60px; height: 60px;">
                                                                    <i class="fas fa-tag text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0">{{ $subcategory->name }}</h6>
                                                            <small class="text-muted">
                                                                {{ $subcategory->products_count }} products
                                                            </small>
                                                            <div class="mt-1">
                                                                <span
                                                                    class="badge bg-{{ $subcategory->is_active ? 'success' : 'danger' }} me-1">
                                                                    {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                                <span class="badge bg-primary">
                                                                    Order: {{ $subcategory->order }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="btn-group">
                                                                <a href="{{ route('admin.categories.show', $subcategory->id) }}"
                                                                    class="btn btn-sm btn-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.categories.edit', $subcategory->id) }}"
                                                                    class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
                                    <h5>No Subcategories</h5>
                                    <p class="text-muted">This category doesn't have any subcategories.</p>
                                    <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> Add Subcategory
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- SEO Tab -->
                        <div class="tab-pane fade" id="seo">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <p class="form-control-static">
                                        {{ $category->meta_title ?: 'Not set' }}
                                    </p>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <p class="form-control-static">
                                        {{ $category->meta_description ?: 'Not set' }}
                                    </p>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <p class="form-control-static">
                                        {{ $category->meta_keywords ?: 'Not set' }}
                                    </p>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Category URL</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            value="{{ route('category.show', $category->slug) }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="copyToClipboard(this)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Copy this URL to share the category</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" action="{{ route('admin.categories.destroy', $category->id) }}">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            const deleteButton = document.querySelector('.confirm-delete');
            if (deleteButton) {
                deleteButton.addEventListener('click', function() {
                    const categoryName = this.getAttribute('data-name');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete "${categoryName}". This action cannot be undone!`,
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

            // Copy to clipboard
            function copyToClipboard(button) {
                const input = button.closest('.input-group').querySelector('input');
                input.select();
                document.execCommand('copy');

                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-success');

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-secondary');
                }, 2000);
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
