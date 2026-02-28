@extends('admin.layouts.master')

@section('title', 'Category Description Generator')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai.index') }}">AI Assistant</a></li>
                        <li class="breadcrumb-item active">Category Description</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">🏷️ Category Description Generator</h1>
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
                            <div class="mb-3">
                                <label class="form-label">Select Category</label>
                                <select class="form-select" id="categorySelect">
                                    <option value="">-- Select a category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" data-name="{{ $category->name }}"
                                            data-subcategories="{{ $category->children->pluck('name')->implode(', ') }}">
                                            {{ $category->name }}
                                            @if ($category->children->count())
                                                ({{ $category->children->count() }} subcategories)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <hr class="my-4">
                            <p class="text-muted small text-center mb-3">Or Enter Manually</p>

                            <div class="mb-3">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryName"
                                    placeholder="Enter category name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subcategories (comma separated)</label>
                                <textarea class="form-control" id="subcategories" rows="2" placeholder="e.g., T-Shirts, Pants, Jackets"></textarea>
                            </div>

                            <hr class="my-4">

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

                            <button type="submit" class="btn btn-danger w-100" id="generateBtn">
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
                        <button class="btn btn-outline-secondary btn-sm" id="copyBtn" disabled>
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="resultArea" class="border rounded p-4 bg-light" style="min-height: 300px;">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-tags fa-3x mb-3 opacity-50"></i>
                                <p>Generated description will appear here</p>
                            </div>
                        </div>

                        <div id="applySection" class="mt-4" style="display: none;">
                            <h6 class="mb-3">Apply to Category</h6>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <select class="form-select" id="applyCategorySelect">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @foreach ($category->children as $child)
                                                <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button class="btn btn-success w-100" id="applyBtn">
                                        <i class="fas fa-check me-1"></i>Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categorySelect = document.getElementById('categorySelect');
                const categoryName = document.getElementById('categoryName');
                const subcategories = document.getElementById('subcategories');
                const generateForm = document.getElementById('generateForm');
                const generateBtn = document.getElementById('generateBtn');
                const resultArea = document.getElementById('resultArea');
                const copyBtn = document.getElementById('copyBtn');
                const applySection = document.getElementById('applySection');
                const applyCategorySelect = document.getElementById('applyCategorySelect');
                const applyBtn = document.getElementById('applyBtn');

                let generatedDescription = '';

                categorySelect.addEventListener('change', function() {
                    const option = this.selectedOptions[0];
                    if (option.value) {
                        categoryName.value = option.dataset.name;
                        subcategories.value = option.dataset.subcategories;
                        applyCategorySelect.value = option.value;
                    }
                });

                generateForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const catId = categorySelect.value;
                    const name = categoryName.value.trim();

                    if (!catId && !name) {
                        Swal.fire('Error', 'Please select a category or enter a name', 'error');
                        return;
                    }

                    generateBtn.disabled = true;
                    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
                    resultArea.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-danger mb-3"></div>
                <p class="text-muted">AI is generating description...</p>
            </div>
        `;

                    try {
                        const response = await fetch(
                            '{{ route('admin.ai.category-description.generate') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    category_id: catId || null,
                                    name: name,
                                    subcategories: subcategories.value,
                                    language: document.getElementById('language').value,
                                    provider: document.getElementById('provider').value
                                })
                            });

                        const data = await response.json();

                        if (data.success && data.description) {
                            generatedDescription = data.description;
                            resultArea.innerHTML = `
                    <div class="mb-2"><span class="badge bg-danger">${data.provider}</span></div>
                    <p style="white-space: pre-wrap;">${escapeHtml(data.description)}</p>
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
                        resultArea.innerHTML =
                            `<div class="text-center text-danger py-5"><i class="fas fa-exclamation-circle fa-3x mb-3"></i><p>Connection error</p></div>`;
                    }

                    generateBtn.disabled = false;
                    generateBtn.innerHTML = '<i class="fas fa-magic me-2"></i>Generate Description';
                });

                copyBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(generatedDescription).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    });
                });

                applyBtn.addEventListener('click', async function() {
                    const categoryId = applyCategorySelect.value;
                    if (!categoryId) {
                        Swal.fire('Error', 'Please select a category', 'error');
                        return;
                    }

                    applyBtn.disabled = true;
                    applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    try {
                        const response = await fetch(
                        '{{ route('admin.ai.category-description.apply') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                category_id: categoryId,
                                description: generatedDescription
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Applied!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', 'Failed to apply', 'error');
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
