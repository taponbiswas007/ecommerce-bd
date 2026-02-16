@extends('admin.layouts.master')

@section('title', 'Edit Brand')
@section('page-title', 'Edit Brand')
@section('page-subtitle', 'Update brand information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Brand Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
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
                                <label for="name" class="form-label">Brand Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $brand->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror"
                                    id="country" name="country" value="{{ old('country', $brand->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $brand->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="logo" class="form-label">Brand Logo</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                    id="logo" name="logo" accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 300x300 pixels. Max file size: 2MB</small>
                                @if ($brand->logo)
                                    <div class="mt-2">
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Current logo
                                            uploaded</span>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Website URL</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('website', $brand->website) }}"
                                    placeholder="https://example.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                    id="contact_email" name="contact_email"
                                    value="{{ old('contact_email', $brand->contact_email) }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                    id="contact_phone" name="contact_phone"
                                    value="{{ old('contact_phone', $brand->contact_phone) }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="contact_address" class="form-label">Contact Address</label>
                                <textarea class="form-control @error('contact_address') is-invalid @enderror" id="contact_address"
                                    name="contact_address" rows="2">{{ old('contact_address', $brand->contact_address) }}</textarea>
                                @error('contact_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="founded_year" class="form-label">Founded Year</label>
                                <input type="number" class="form-control @error('founded_year') is-invalid @enderror"
                                    id="founded_year" name="founded_year"
                                    value="{{ old('founded_year', $brand->founded_year) }}" min="1800"
                                    max="{{ date('Y') }}">
                                @error('founded_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Display Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                    id="sort_order" name="sort_order"
                                    value="{{ old('sort_order', $brand->sort_order) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Featured</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Social Media Links (Optional)</h6>

                        <div class="row">
                            @php
                                $socialLinks = old('social_links', $brand->social_links ?? []);
                            @endphp

                            <div class="col-md-6 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="url" class="form-control" id="facebook" name="social_links[facebook]"
                                    value="{{ $socialLinks['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input type="url" class="form-control" id="twitter" name="social_links[twitter]"
                                    value="{{ $socialLinks['twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="url" class="form-control" id="instagram" name="social_links[instagram]"
                                    value="{{ $socialLinks['instagram'] ?? '' }}"
                                    placeholder="https://instagram.com/...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="url" class="form-control" id="linkedin" name="social_links[linkedin]"
                                    value="{{ $socialLinks['linkedin'] ?? '' }}" placeholder="https://linkedin.com/...">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">SEO Settings</h6>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title"
                                    value="{{ old('meta_title', $brand->meta_title) }}" maxlength="255">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Title for search engines (optional)</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" rows="2" maxlength="500">{{ old('meta_description', $brand->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Description for search engines (optional)</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                    id="meta_keywords" name="meta_keywords"
                                    value="{{ old('meta_keywords', $brand->meta_keywords) }}">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Comma-separated keywords (optional)</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Brand
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Current Logo Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Logo Preview</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if ($brand->logo)
                            <img id="logoPreview" src="{{ asset('storage/' . $brand->logo) }}"
                                alt="{{ $brand->name }}" class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <img id="logoPreview" src="https://via.placeholder.com/300x300?text=No+Logo" alt="Preview"
                                class="img-fluid rounded" style="max-height: 200px;">
                        @endif
                    </div>
                    <p class="text-muted small">Upload new logo to replace current</p>
                </div>
            </div>

            <!-- Brand Stats Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-line text-primary me-2"></i>Brand Stats</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="text-muted">Total Products</span>
                        <span class="badge bg-primary fs-6">{{ $brand->products_count ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="text-muted">Status</span>
                        <span
                            class="badge {{ $brand->is_active ? 'bg-success' : 'bg-danger' }}">{{ $brand->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="text-muted">Featured</span>
                        <span
                            class="badge {{ $brand->is_featured ? 'bg-warning' : 'bg-secondary' }}">{{ $brand->is_featured ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Created</span>
                        <span class="small">{{ $brand->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Image Preview
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
