@extends('admin.layouts.master')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category' . (isset($category) ? ': ' . $category->name : ''))
@section('page-subtitle', 'Update category information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Category</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Category Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id"
                                    name="parent_id">
                                    <option value="">Select Parent Category (Optional)</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}
                                            {{ $cat->id == $category->id ? 'disabled' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Category Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($category->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                            class="img-thumbnail" style="max-height: 100px;">
                                        <small class="text-muted d-block">Current image</small>
                                    </div>
                                @endif
                                <small class="text-muted">Recommended size: 300x300 pixels. Max file size: 2MB</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="order" class="form-label">Display Order</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    id="order" name="order" value="{{ old('order', $category->order) }}"
                                    min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">SEO Settings</h6>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title"
                                    value="{{ old('meta_title', $category->meta_title) }}" maxlength="255">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Title for search engines (optional)</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" rows="2" maxlength="500">{{ old('meta_description', $category->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Description for search engines (optional)</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                    id="meta_keywords" name="meta_keywords"
                                    value="{{ old('meta_keywords', $category->meta_keywords) }}">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Comma-separated keywords (optional)</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i> Back to List
                            </a>
                            <div>
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-2"></i> View
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Current Category Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Details</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if ($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="img-fluid rounded" style="max-height: 150px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 150px;">
                                <i class="fas fa-tag fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <table class="table table-sm">
                        <tr>
                            <th>Slug:</th>
                            <td>{{ $category->slug }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $category->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Updated:</th>
                            <td>{{ $category->updated_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Products:</th>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $category->products()->count() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Subcategories:</th>
                            <td>
                                <span class="badge bg-info">
                                    {{ $category->children()->count() }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger mt-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Once you delete a category, there is no going back. Please be certain.
                    </p>

                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                        id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 confirm-delete"
                            data-name="{{ $category->name }}">
                            <i class="fas fa-trash me-2"></i> Delete This Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview
            const imageInput = document.getElementById('image');
            const currentImage = document.querySelector('img[src*="storage"]');

            if (imageInput && currentImage) {
                imageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            currentImage.src = e.target.result;
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

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
        });
    </script>
@endpush
