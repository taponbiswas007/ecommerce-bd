@extends('admin.layouts.master')

@section('title', 'AI SEO Generator')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai.index') }}">AI Assistant</a></li>
                        <li class="breadcrumb-item active">SEO Generator</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">🔍 AI SEO Generator</h1>
            </div>
        </div>

        <div class="row">
            <!-- Product Selection -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-box me-2"></i>Select Product</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Products Without SEO ({{ $products->count() }})</label>
                            <select class="form-select" id="productSelect" size="10" style="height: auto;">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-description="{{ $product->short_description ?? ($product->full_description ?? '') }}">
                                        {{ Str::limit($product->name, 40) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Language</label>
                            <select class="form-select" id="language">
                                <option value="bn">বাংলা</option>
                                <option value="en">English</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">AI Provider</label>
                            <select class="form-select" id="provider">
                                <option value="gemini">Gemini</option>
                                <option value="groq">Groq</option>
                            </select>
                        </div>

                        <button class="btn btn-secondary w-100" id="generateBtn" disabled>
                            <i class="fas fa-search me-2"></i>Generate SEO
                        </button>
                    </div>
                </div>

                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6><i class="fas fa-info-circle text-info me-2"></i>SEO Tips</h6>
                        <ul class="small text-muted mb-0">
                            <li>Meta title should be under 60 characters</li>
                            <li>Meta description should be 150-160 characters</li>
                            <li>Include main keywords naturally</li>
                            <li>Make it compelling for users to click</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- SEO Preview & Edit -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-search-dollar me-2"></i>Generated SEO Data</h6>
                    </div>
                    <div class="card-body">
                        <div id="seoResult">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-search fa-4x mb-4 opacity-50"></i>
                                <h5>Select a Product</h5>
                                <p>Choose a product from the list and generate SEO meta tags</p>
                            </div>
                        </div>

                        <!-- Editable SEO Fields -->
                        <div id="seoFields" style="display: none;">
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between">
                                    Meta Title
                                    <small class="text-muted"><span id="titleCount">0</span>/60</small>
                                </label>
                                <input type="text" class="form-control" id="metaTitle" maxlength="60">
                                <small class="text-muted">Appears as the clickable headline in search results</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between">
                                    Meta Description
                                    <small class="text-muted"><span id="descCount">0</span>/160</small>
                                </label>
                                <textarea class="form-control" id="metaDescription" rows="3" maxlength="160"></textarea>
                                <small class="text-muted">Brief summary shown below the title in search results</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="metaKeywords"
                                    placeholder="keyword1, keyword2, keyword3">
                                <small class="text-muted">Comma-separated keywords (5-10 recommended)</small>
                            </div>

                            <!-- Google Preview -->
                            <div class="mb-4">
                                <label class="form-label"><i class="fab fa-google me-2"></i>Search Preview</label>
                                <div class="border rounded p-3 bg-white">
                                    <div id="googlePreview">
                                        <div class="text-primary" style="font-size: 18px; line-height: 1.2;"
                                            id="previewTitle">Page Title</div>
                                        <div class="text-success small" id="previewUrl">www.yoursite.com/product/...</div>
                                        <div class="text-secondary small" style="line-height: 1.4;" id="previewDesc">Meta
                                            description will appear here...</div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-success w-100" id="applyBtn">
                                <i class="fas fa-check me-2"></i>Apply SEO to Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productSelect = document.getElementById('productSelect');
                const generateBtn = document.getElementById('generateBtn');
                const seoResult = document.getElementById('seoResult');
                const seoFields = document.getElementById('seoFields');
                const metaTitle = document.getElementById('metaTitle');
                const metaDescription = document.getElementById('metaDescription');
                const metaKeywords = document.getElementById('metaKeywords');
                const applyBtn = document.getElementById('applyBtn');

                let selectedProductId = null;

                // Enable generate button when product selected
                productSelect.addEventListener('change', function() {
                    generateBtn.disabled = !this.value;
                    selectedProductId = this.value;
                });

                // Character counters
                metaTitle.addEventListener('input', function() {
                    document.getElementById('titleCount').textContent = this.value.length;
                    document.getElementById('previewTitle').textContent = this.value || 'Page Title';
                });

                metaDescription.addEventListener('input', function() {
                    document.getElementById('descCount').textContent = this.value.length;
                    document.getElementById('previewDesc').textContent = this.value ||
                        'Meta description will appear here...';
                });

                // Generate SEO
                generateBtn.addEventListener('click', async function() {
                    if (!selectedProductId) return;

                    generateBtn.disabled = true;
                    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';

                    seoResult.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-secondary mb-3"></div>
                <p class="text-muted">AI is generating SEO data...</p>
            </div>
        `;
                    seoFields.style.display = 'none';

                    try {
                        const response = await fetch('{{ route('admin.ai.seo-generator.generate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: selectedProductId,
                                language: document.getElementById('language').value,
                                provider: document.getElementById('provider').value
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            seoResult.innerHTML =
                                `<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>SEO data generated successfully!</div>`;
                            seoFields.style.display = 'block';

                            // Try to parse and fill fields
                            if (data.parsed_meta) {
                                metaTitle.value = data.parsed_meta.meta_title || '';
                                metaDescription.value = data.parsed_meta.meta_description || '';
                                metaKeywords.value = data.parsed_meta.meta_keywords || '';
                            } else {
                                // Show raw response for manual extraction
                                seoResult.innerHTML += `
                        <div class="alert alert-info">
                            <strong>Raw AI Response:</strong><br>
                            <small style="white-space: pre-wrap;">${escapeHtml(data.seo_data || '')}</small>
                        </div>
                    `;
                            }

                            // Trigger updates
                            metaTitle.dispatchEvent(new Event('input'));
                            metaDescription.dispatchEvent(new Event('input'));
                        } else {
                            seoResult.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to generate SEO: ${data.error || 'Unknown error'}
                    </div>
                `;
                        }
                    } catch (error) {
                        seoResult.innerHTML =
                            `<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Connection error</div>`;
                    }

                    generateBtn.disabled = false;
                    generateBtn.innerHTML = '<i class="fas fa-search me-2"></i>Generate SEO';
                });

                // Apply SEO
                applyBtn.addEventListener('click', async function() {
                    if (!selectedProductId) return;

                    applyBtn.disabled = true;
                    applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Applying...';

                    try {
                        const response = await fetch('{{ route('admin.ai.seo-generator.apply') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: selectedProductId,
                                meta_title: metaTitle.value,
                                meta_description: metaDescription.value,
                                meta_keywords: metaKeywords.value
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Applied!',
                                text: 'SEO data has been saved to the product',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Remove from list
                            const option = productSelect.querySelector(
                                `option[value="${selectedProductId}"]`);
                            if (option) option.remove();

                            // Reset
                            seoFields.style.display = 'none';
                            seoResult.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-check-circle fa-4x mb-4 text-success"></i>
                        <h5>SEO Applied Successfully!</h5>
                        <p>Select another product to continue</p>
                    </div>
                `;
                        } else {
                            Swal.fire('Error', 'Failed to apply SEO', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Connection error', 'error');
                    }

                    applyBtn.disabled = false;
                    applyBtn.innerHTML = '<i class="fas fa-check me-2"></i>Apply SEO to Product';
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
