@extends('admin.layouts.master')

@section('title', 'AI Assistant')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">🤖 AI Assistant</h1>
                <p class="text-muted mb-0">Manage your e-commerce with AI-powered tools</p>
            </div>
        </div>

        <!-- AI Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="fas fa-box text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Products Without Description</h6>
                                <h3 class="mb-0">{{ $stats['products_without_description'] }}</h3>
                                <small class="text-muted">of {{ $stats['total_products'] }} total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="fas fa-search text-warning fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Products Without SEO</h6>
                                <h3 class="mb-0">{{ $stats['products_without_seo'] }}</h3>
                                <small class="text-muted">need optimization</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="fas fa-tags text-info fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Categories Without Description</h6>
                                <h3 class="mb-0">{{ $stats['categories_without_description'] }}</h3>
                                <small class="text-muted">need content</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                    <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Low Stock Products</h6>
                                <h3 class="mb-0">{{ $stats['low_stock_products'] }}</h3>
                                <small class="text-muted">need restocking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Tools Grid -->
        <div class="row">
            <!-- AI Chat -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-comments text-white fa-2x"></i>
                        </div>
                        <h5 class="mb-3">AI Chat Assistant</h5>
                        <p class="text-muted mb-4">Chat with AI to get help with business decisions, product ideas, and
                            marketing strategies.</p>
                        <a href="{{ route('admin.ai.chat') }}" class="btn btn-primary">
                            <i class="fas fa-comment-dots me-2"></i>Start Chat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="fas fa-file-alt text-white fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Product Description</h5>
                        <p class="text-muted mb-4">Generate attractive product descriptions using AI. Supports both Bangla
                            and English.</p>
                        <a href="{{ route('admin.ai.product-description') }}" class="btn btn-success">
                            <i class="fas fa-magic me-2"></i>Generate
                        </a>
                    </div>
                </div>
            </div>

            <!-- Category Description -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-tags text-white fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Category Description</h5>
                        <p class="text-muted mb-4">Create SEO-friendly category descriptions for better visibility.</p>
                        <a href="{{ route('admin.ai.category-description') }}" class="btn btn-danger">
                            <i class="fas fa-magic me-2"></i>Generate
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sales Analysis -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #FA8BFF 0%, #2BD2FF 52%, #2BFF88 100%);">
                            <i class="fas fa-chart-line text-white fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Sales Analysis</h5>
                        <p class="text-muted mb-4">Get AI-powered insights on your sales performance and trends.</p>
                        <a href="{{ route('admin.ai.sales-analysis') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Analyze
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                            <i class="fas fa-lightbulb text-white fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Product Recommendations</h5>
                        <p class="text-muted mb-4">Get smart recommendations on which products to promote and stock.</p>
                        <a href="{{ route('admin.ai.recommendations') }}" class="btn btn-warning">
                            <i class="fas fa-brain me-2"></i>Get Recommendations
                        </a>
                    </div>
                </div>
            </div>

            <!-- SEO Generator -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #5B247A 0%, #1BCEDF 100%);">
                            <i class="fas fa-search-dollar text-white fa-2x"></i>
                        </div>
                        <h5 class="mb-3">SEO Generator</h5>
                        <p class="text-muted mb-4">Generate SEO meta tags for your products automatically.</p>
                        <a href="{{ route('admin.ai.seo-generator') }}" class="btn btn-secondary">
                            <i class="fas fa-search me-2"></i>Generate SEO
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0"><i class="fas fa-fire text-danger me-2"></i>Top Selling Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Sold Count</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['top_selling_products'] as $index => $product)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $product->sold_count }} sold</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.ai.product-description') }}?product={{ $product->id ?? '' }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-magic"></i> Generate Description
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No sales data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Link -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1"><i class="fas fa-cog me-2"></i>AI Settings</h6>
                            <p class="text-muted mb-0">Configure AI providers and test connections</p>
                        </div>
                        <a href="{{ route('admin.ai.settings') }}" class="btn btn-outline-dark">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
