@extends('admin.layouts.master')

@section('title', 'AI Product Recommendations')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai.index') }}">AI Assistant</a></li>
                        <li class="breadcrumb-item active">Recommendations</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">💡 AI Product Recommendations</h1>
            </div>
        </div>

        <div class="row">
            <!-- Current Stats -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient text-white"
                        style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                        <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Sales Overview</h6>
                    </div>
                    <div class="card-body">
                        <h5 class="text-muted mb-3">Top Categories</h5>
                        @forelse($salesData['category_performance'] ?? [] as $index => $category)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $index + 1 }}. {{ $category['name'] ?? 'N/A' }}</span>
                                <span class="badge bg-primary">৳{{ number_format($category['revenue'] ?? 0) }}</span>
                            </div>
                        @empty
                            <p class="text-muted small">No category data</p>
                        @endforelse
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-fire text-danger me-2"></i>Best Sellers</h6>
                    </div>
                    <div class="card-body">
                        @forelse(array_slice($salesData['top_products'] ?? [], 0, 5) as $index => $product)
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <span class="badge bg-warning text-dark me-2">{{ $index + 1 }}</span>
                                    <span class="text-truncate"
                                        style="max-width: 150px;">{{ $product['name'] ?? 'N/A' }}</span>
                                </div>
                                <span class="badge bg-success">{{ $product['sold_count'] ?? 0 }} sold</span>
                            </div>
                        @empty
                            <p class="text-muted small">No sales data</p>
                        @endforelse
                    </div>
                </div>

                <!-- Options -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
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
                        <button class="btn btn-warning w-100" id="getRecommendationsBtn">
                            <i class="fas fa-brain me-2"></i>Get Recommendations
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-lightbulb text-warning me-2"></i>AI Recommendations</h6>
                        <button class="btn btn-outline-secondary btn-sm" id="copyBtn" disabled>
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="recommendationsResult" style="min-height: 500px;">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-lightbulb fa-4x mb-4 text-warning opacity-50"></i>
                                <h5>Get Smart Recommendations</h5>
                                <p>AI will analyze your sales data and provide actionable recommendations:</p>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-bullhorn text-primary me-2"></i>Which products to
                                        promote</li>
                                    <li class="mb-2"><i class="fas fa-boxes text-success me-2"></i>Stock management advice
                                    </li>
                                    <li class="mb-2"><i class="fas fa-tags text-info me-2"></i>Category focus suggestions
                                    </li>
                                    <li class="mb-2"><i class="fas fa-chart-line text-danger me-2"></i>Marketing strategy
                                        tips</li>
                                </ul>
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
                const getBtn = document.getElementById('getRecommendationsBtn');
                const resultDiv = document.getElementById('recommendationsResult');
                const copyBtn = document.getElementById('copyBtn');
                let recommendationsText = '';

                getBtn.addEventListener('click', async function() {
                    getBtn.disabled = true;
                    getBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Analyzing...';

                    resultDiv.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;"></div>
                <h5 class="text-muted">AI is generating recommendations...</h5>
                <p class="text-muted small">Analyzing sales patterns and trends</p>
            </div>
        `;

                    try {
                        const response = await fetch('{{ route('admin.ai.recommendations.get') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                language: document.getElementById('language').value,
                                provider: document.getElementById('provider').value
                            })
                        });

                        const data = await response.json();

                        if (data.success && data.recommendations) {
                            recommendationsText = data.recommendations;
                            resultDiv.innerHTML = `
                    <div class="recommendations-content">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-warning text-dark">${data.provider}</span>
                        </div>
                        <div class="border rounded p-4 bg-light">
                            <p style="white-space: pre-wrap; line-height: 1.8;">${escapeHtml(data.recommendations)}</p>
                        </div>
                    </div>
                `;
                            copyBtn.disabled = false;
                        } else {
                            resultDiv.innerHTML = `
                    <div class="text-center text-danger py-5">
                        <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                        <p>Failed to get recommendations</p>
                        <small>${data.error || 'Unknown error'}</small>
                    </div>
                `;
                        }
                    } catch (error) {
                        resultDiv.innerHTML = `
                <div class="text-center text-danger py-5">
                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                    <p>Connection error</p>
                </div>
            `;
                    }

                    getBtn.disabled = false;
                    getBtn.innerHTML = '<i class="fas fa-brain me-2"></i>Get Recommendations';
                });

                copyBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(recommendationsText).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    });
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
