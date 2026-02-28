@extends('admin.layouts.master')

@section('title', 'Product Description Generator')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai.index') }}">AI Assistant</a></li>
                        <li class="breadcrumb-item active">Product Description</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">📝 Product Description Generator</h1>
            </div>
        </div>

        <div class="row">
            <!-- Generator Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-magic me-2"></i>Generate Description</h6>
                    </div>
                    <div class="card-body">
                        <form id="generateForm">
                            <!-- Select Existing Product -->
                            <div class="mb-3">
                                <label class="form-label">Select Existing Product</label>
                                <select class="form-select" id="productSelect">
                                    <option value="">-- Or enter manually below --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-category="{{ $product->category->name ?? 'General' }}"
                                            data-price="{{ $product->discount_price ?? $product->base_price }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <hr class="my-4">
                            <p class="text-muted small text-center mb-3">Or Enter Manually</p>

                            <!-- Manual Entry -->
                            <div class="mb-3">
                                <label class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="productName"
                                    placeholder="Enter product name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" id="categorySelect">
                                    <option value="General">General</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price (৳)</label>
                                <input type="number" class="form-control" id="productPrice" placeholder="0.00">
                            </div>

                            <hr class="my-4">

                            <!-- Options -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label">Language</label>
                                    <select class="form-select" id="language">
                                        <option value="bn">বাংলা</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">AI Provider</label>
                                    <select class="form-select" id="provider">
                                        <option value="gemini">Gemini</option>
                                        <option value="groq">Groq</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="generateBtn">
                                <i class="fas fa-magic me-2"></i>Generate Description
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Generated Result -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Generated Description</h6>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary" id="copyBtn" disabled>
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="resultArea" class="border rounded p-4 bg-light" style="min-height: 300px;">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-robot fa-3x mb-3 opacity-50"></i>
                                <p>Generated description will appear here</p>
                                <small>Select a product or enter details and click Generate</small>
                            </div>
                        </div>

                        <!-- Apply to Product Section -->
                        <div id="applySection" class="mt-4" style="display: none;">
                            <h6 class="mb-3">Apply to Product</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <select class="form-select" id="applyProductSelect">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <select class="form-select" id="descriptionType">
                                        <option value="short">Short Description</option>
                                        <option value="full">Full Description</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-success w-100" id="applyBtn">
                                        <i class="fas fa-check me-1"></i>Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6><i class="fas fa-lightbulb text-warning me-2"></i>Tips</h6>
                        <ul class="mb-0 text-muted small">
                            <li>Select an existing product to auto-fill details</li>
                            <li>Bangla descriptions work better for local customers</li>
                            <li>You can regenerate if you don't like the first result</li>
                            <li>Apply directly to update product descriptions in database</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productSelect = document.getElementById('productSelect');
                const productName = document.getElementById('productName');
                const categorySelect = document.getElementById('categorySelect');
                const productPrice = document.getElementById('productPrice');
                const generateForm = document.getElementById('generateForm');
                const generateBtn = document.getElementById('generateBtn');
                const resultArea = document.getElementById('resultArea');
                const copyBtn = document.getElementById('copyBtn');
                const applySection = document.getElementById('applySection');
                const applyProductSelect = document.getElementById('applyProductSelect');
                const applyBtn = document.getElementById('applyBtn');

                let generatedDescription = '';

                // Auto-fill when product selected
                productSelect.addEventListener('change', function() {
                    const option = this.selectedOptions[0];
                    if (option.value) {
                        productName.value = option.dataset.name;
                        categorySelect.value = option.dataset.category || 'General';
                        productPrice.value = option.dataset.price;
                        applyProductSelect.value = option.value;
                    }
                });

                // Generate description
                generateForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const productId = productSelect.value;
                    const name = productName.value.trim();

                    if (!productId && !name) {
                        Swal.fire('Error', 'Please select a product or enter a name', 'error');
                        return;
                    }

                    generateBtn.disabled = true;
                    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';

                    resultArea.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <p class="text-muted">AI is generating description...</p>
            </div>
        `;

                    try {
                        const response = await fetch(
                            '{{ route('admin.ai.product-description.generate') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    product_id: productId || null,
                                    name: name,
                                    category: categorySelect.value,
                                    price: productPrice.value,
                                    language: document.getElementById('language').value,
                                    provider: document.getElementById('provider').value
                                })
                            });

                        const data = await response.json();

                        if (data.success && data.description) {
                            generatedDescription = data.description;
                            resultArea.innerHTML = `
                    <div class="generated-content">
                        <div class="mb-2">
                            <span class="badge bg-primary">${data.provider}</span>
                        </div>
                        <p style="white-space: pre-wrap;">${escapeHtml(data.description)}</p>
                    </div>
                `;
                            copyBtn.disabled = false;
                            applySection.style.display = 'block';
                        } else {
                            resultArea.innerHTML = `
                    <div class="text-center text-danger py-5">
                        <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                        <p>Failed to generate description</p>
                        <small>${data.error || 'Unknown error'}</small>
                    </div>
                `;
                        }
                    } catch (error) {
                        resultArea.innerHTML = `
                <div class="text-center text-danger py-5">
                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                    <p>Connection error</p>
                </div>
            `;
                    }

                    generateBtn.disabled = false;
                    generateBtn.innerHTML = '<i class="fas fa-magic me-2"></i>Generate Description';
                });

                // Copy to clipboard
                copyBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(generatedDescription).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied!',
                            text: 'Description copied to clipboard',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    });
                });

                // Apply to product
                applyBtn.addEventListener('click', async function() {
                    const productId = applyProductSelect.value;
                    if (!productId) {
                        Swal.fire('Error', 'Please select a product to apply', 'error');
                        return;
                    }

                    applyBtn.disabled = true;
                    applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    try {
                        const response = await fetch('{{ route('admin.ai.product-description.apply') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                description: generatedDescription,
                                type: document.getElementById('descriptionType').value
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Applied!',
                                text: 'Description has been applied to the product',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', 'Failed to apply description', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Connection error', 'error');
                    }

                    applyBtn.disabled = false;
                    applyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Apply';
                });

                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
            });
        </script>
    @endpush
@endsection
