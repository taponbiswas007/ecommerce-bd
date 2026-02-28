@extends('admin.layouts.master')

@section('title', 'AI Sales Analysis')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai.index') }}">AI Assistant</a></li>
                        <li class="breadcrumb-item active">Sales Analysis</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">📊 AI Sales Analysis</h1>
            </div>
        </div>

        <div class="row">
            <!-- Options Panel -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Analysis Options</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Time Period</label>
                            <select class="form-select" id="period">
                                <option value="week">Last Week</option>
                                <option value="month" selected>Last Month</option>
                                <option value="quarter">Last Quarter</option>
                                <option value="year">Last Year</option>
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

                        <button class="btn btn-info w-100" id="analyzeBtn">
                            <i class="fas fa-chart-bar me-2"></i>Analyze Sales
                        </button>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-database me-2"></i>Current Data Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Top Products</small>
                            <ul class="list-unstyled mb-0 mt-1">
                                @forelse($salesData['top_products'] ?? [] as $product)
                                    <li class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-truncate"
                                            style="max-width: 150px;">{{ $product['name'] ?? 'N/A' }}</span>
                                        <span class="badge bg-success">{{ $product['sold_count'] ?? 0 }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted small">No data available</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Low Stock ({{ count($salesData['low_stock'] ?? []) }})</small>
                            <ul class="list-unstyled mb-0 mt-1">
                                @forelse(array_slice($salesData['low_stock'] ?? [], 0, 5) as $product)
                                    <li class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-truncate"
                                            style="max-width: 150px;">{{ $product['name'] ?? 'N/A' }}</span>
                                        <span class="badge bg-danger">{{ $product['stock'] ?? 0 }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted small">All products stocked</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analysis Result -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-brain me-2"></i>AI Analysis</h6>
                        <button class="btn btn-outline-secondary btn-sm" id="copyBtn" disabled>
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="analysisResult" style="min-height: 400px;">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-chart-line fa-4x mb-4 opacity-50"></i>
                                <h5>Ready to Analyze</h5>
                                <p>Select your preferences and click "Analyze Sales" to get AI-powered insights</p>
                                <ul class="list-unstyled small">
                                    <li><i class="fas fa-check text-success me-2"></i>Sales trend analysis</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Top performing products</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Category performance</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Stock recommendations</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Marketing suggestions</li>
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
                const analyzeBtn = document.getElementById('analyzeBtn');
                const analysisResult = document.getElementById('analysisResult');
                const copyBtn = document.getElementById('copyBtn');
                let analysisText = '';

                analyzeBtn.addEventListener('click', async function() {
                    analyzeBtn.disabled = true;
                    analyzeBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Analyzing...';

                    analysisResult.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-info mb-3" style="width: 3rem; height: 3rem;"></div>
                <h5 class="text-muted">AI is analyzing your sales data...</h5>
                <p class="text-muted small">This may take a moment</p>
            </div>
        `;

                    try {
                        const response = await fetch('{{ route('admin.ai.sales-analysis.analyze') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                period: document.getElementById('period').value,
                                language: document.getElementById('language').value,
                                provider: document.getElementById('provider').value
                            })
                        });

                        const data = await response.json();

                        if (data.success && data.analysis) {
                            analysisText = data.analysis;
                            analysisResult.innerHTML = `
                    <div class="analysis-content">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-info">${data.provider}</span>
                            <small class="text-muted">Period: ${data.sales_data.period}</small>
                        </div>
                        <div class="border rounded p-4 bg-light">
                            <p style="white-space: pre-wrap; line-height: 1.8;">${escapeHtml(data.analysis)}</p>
                        </div>
                    </div>
                `;
                            copyBtn.disabled = false;
                        } else {
                            analysisResult.innerHTML = `
                    <div class="text-center text-danger py-5">
                        <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                        <p>Failed to generate analysis</p>
                        <small>${data.error || 'Unknown error'}</small>
                    </div>
                `;
                        }
                    } catch (error) {
                        analysisResult.innerHTML = `
                <div class="text-center text-danger py-5">
                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                    <p>Connection error</p>
                </div>
            `;
                    }

                    analyzeBtn.disabled = false;
                    analyzeBtn.innerHTML = '<i class="fas fa-chart-bar me-2"></i>Analyze Sales';
                });

                copyBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(analysisText).then(() => {
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
