@extends('admin.layouts.master')

@section('title', 'Upload Images')
@section('page-title', 'Upload Images')
@section('page-subtitle', 'Add images to: ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('admin.products.show', $product->id) }}">{{ Str::limit($product->name, 20) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.images.index', $product->id) }}">Images</a></li>
    <li class="breadcrumb-item active">Upload</li>
@endsection

@push('styles')
    <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f8f9fa;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover,
        .upload-area.dragover {
            border-color: #4361ee;
            background-color: #eef2ff;
        }

        .upload-icon {
            font-size: 48px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .upload-area:hover .upload-icon {
            color: #4361ee;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .preview-item {
            width: 120px;
            position: relative;
        }

        .preview-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #dee2e6;
        }

        .remove-preview {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            border: 2px solid white;
        }

        .file-info {
            font-size: 12px;
            text-align: center;
            margin-top: 5px;
            word-break: break-all;
        }

        .upload-progress {
            display: none;
            margin-top: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upload Images for: {{ $product->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.images.store', $product->id) }}" method="POST"
                        enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <div class="row">
                            <div class="col-xl-8">
                                <!-- Upload Area -->
                                <div class="mb-4">
                                    <div class="upload-area" id="uploadArea">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <h4>Drag & Drop Images Here</h4>
                                        <p class="text-muted">or click to browse</p>
                                        <p class="text-muted small">
                                            Supported formats: JPG, PNG, GIF, WEBP<br>
                                            Maximum file size: 5MB per image
                                        </p>
                                        <input type="file" name="images[]" id="fileInput" multiple accept="image/*"
                                            style="display: none;">
                                        <button type="button" class="btn btn-primary mt-2"
                                            onclick="document.getElementById('fileInput').click()">
                                            <i class="fas fa-folder-open me-2"></i> Browse Files
                                        </button>
                                    </div>

                                    <!-- Preview Container -->
                                    <div id="previewContainer" class="preview-container"></div>

                                    <!-- Upload Progress -->
                                    <div class="upload-progress" id="uploadProgress">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <p class="text-center mt-2 mb-0">Uploading... <span id="progressText">0%</span></p>
                                    </div>
                                </div>

                                <!-- Image Details -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Image Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="alt_text" class="form-label">Alt Text (Default)</label>
                                            <input type="text"
                                                class="form-control @error('alt_text') is-invalid @enderror" id="alt_text"
                                                name="alt_text" placeholder="Enter default alt text for all images"
                                                value="{{ old('alt_text') }}">
                                            <small class="text-muted">This will be used as default alt text for all uploaded
                                                images. Individual images can be edited later.</small>
                                            @error('alt_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="display_order" class="form-label">Starting Display
                                                        Order</label>
                                                    <input type="number"
                                                        class="form-control @error('display_order') is-invalid @enderror"
                                                        id="display_order" name="display_order"
                                                        value="{{ old('display_order', $product->images()->max('display_order') + 1) }}"
                                                        min="0">
                                                    <small class="text-muted">Images will be ordered starting from this
                                                        number</small>
                                                    @error('display_order')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="set_first_as_primary"
                                                    name="set_first_as_primary" value="1"
                                                    {{ old('set_first_as_primary', $product->images()->where('is_primary', true)->doesntExist() ? 'checked' : '') }}>
                                                <label class="form-check-label" for="set_first_as_primary">
                                                    Set first image as primary (if no primary exists)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <!-- Upload Summary -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Upload Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <h6>Product Information</h6>
                                            <p class="mb-1">
                                                <strong>Name:</strong> {{ $product->name }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Current Images:</strong> {{ $product->images()->count() }}
                                            </p>
                                            @if ($product->images()->where('is_primary', true)->exists())
                                                <p class="mb-1">
                                                    <strong>Primary Image:</strong> ✓ Set
                                                </p>
                                            @endif
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                            <h6>Upload Stats</h6>
                                            <p class="mb-1">
                                                <strong>Selected Files:</strong> <span id="fileCount">0</span>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Total Size:</strong> <span id="totalSize">0 MB</span>
                                            </p>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            You can upload multiple images at once. Maximum 20 files per upload.
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                                <i class="fas fa-upload me-2"></i> Upload Images
                                            </button>
                                            <a href="{{ route('admin.products.images.index', $product->id) }}"
                                                class="btn btn-light">
                                                Cancel
                                            </a>
                                        </div>

                                        <div class="alert alert-warning mt-3">
                                            <small>
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Make sure images are clear, well-lit, and show the product from multiple
                                                angles.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('previewContainer');
            const fileCount = document.getElementById('fileCount');
            const totalSize = document.getElementById('totalSize');
            const submitBtn = document.getElementById('submitBtn');
            const uploadProgress = document.getElementById('uploadProgress');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            const progressText = document.getElementById('progressText');

            let files = [];
            let totalSizeBytes = 0;

            // Drag and drop events
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');

                const droppedFiles = Array.from(e.dataTransfer.files);
                handleFiles(droppedFiles);
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                handleFiles(Array.from(e.target.files));
            });

            // Click upload area to trigger file input
            uploadArea.addEventListener('click', function(e) {
                if (e.target !== fileInput) {
                    fileInput.click();
                }
            });

            // Handle selected files
            function handleFiles(selectedFiles) {
                // Filter only image files
                const imageFiles = selectedFiles.filter(file =>
                    file.type.startsWith('image/') && ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
                    .includes(file.type)
                );

                // Check file size (5MB limit)
                const validFiles = imageFiles.filter(file => file.size <= 5 * 1024 * 1024);

                // Show warnings for invalid files
                if (validFiles.length < imageFiles.length) {
                    const invalidCount = imageFiles.length - validFiles.length;
                    Toast.fire({
                        icon: 'warning',
                        title: `${invalidCount} file(s) skipped (size limit: 5MB)`
                    });
                }

                // Add to files array
                files = [...files, ...validFiles];

                // Update UI
                updateFileList();
                updatePreview();
                updateStats();
            }

            // Update file list
            function updateFileList() {
                // Update file input
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }

            // Update preview
            function updatePreview() {
                previewContainer.innerHTML = '';

                files.forEach((file, index) => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';

                        previewItem.innerHTML = `
                            <div class="remove-preview" onclick="removeFile(${index})">
                                <i class="fas fa-times"></i>
                            </div>
                            <img src="${e.target.result}" alt="${file.name}" class="preview-image">
                            <div class="file-info">
                                ${file.name}<br>
                                ${(file.size / 1024 / 1024).toFixed(2)} MB
                            </div>
                        `;

                        previewContainer.appendChild(previewItem);
                    };

                    reader.readAsDataURL(file);
                });
            }

            // Update statistics
            function updateStats() {
                fileCount.textContent = files.length;

                totalSizeBytes = files.reduce((sum, file) => sum + file.size, 0);
                const totalSizeMB = (totalSizeBytes / 1024 / 1024).toFixed(2);
                totalSize.textContent = `${totalSizeMB} MB`;

                // Enable/disable submit button
                submitBtn.disabled = files.length === 0;
            }

            // Remove file from list
            window.removeFile = function(index) {
                files.splice(index, 1);
                updateFileList();
                updatePreview();
                updateStats();
            };

            // Form submission
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                if (files.length === 0) {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please select at least one image'
                    });
                    return;
                }

                if (files.length > 20) {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'warning',
                        title: 'Maximum 20 images per upload'
                    });
                    return;
                }

                // Show progress bar
                uploadProgress.style.display = 'block';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Uploading...';

                // Simulate progress (you can replace with actual upload progress)
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    if (progress > 90) {
                        clearInterval(interval);
                    }
                    progressBar.style.width = progress + '%';
                    progressText.textContent = progress + '%';
                }, 200);
            });
        });
    </script>
@endpush
