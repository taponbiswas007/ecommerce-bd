@extends('admin.layouts.master')

@section('title', 'Advanced Bulk Price Management')
@section('page-title', 'Advanced Bulk Price Management')
@section('page-subtitle', 'Manage all price types with AI assistance')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Bulk Price Management</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back to Products
    </a>
    <button type="button" id="exportBtn" class="btn btn-info">
        <i class="fas fa-download me-2"></i> Export Prices
    </button>
@endsection

@push('styles')
    <style>
        .price-field-selector {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .ai-suggestion-card {
            border: 2px dashed #0d6efd;
            background: #e7f3ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .tier-price-preview {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .price-comparison {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .price-old {
            text-decoration: line-through;
            color: #dc3545;
        }

        .price-new {
            color: #28a745;
            font-weight: bold;
        }

        .ai-loading {
            display: none;
        }

        .card-header-tabs {
            margin-bottom: -1rem;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <!-- Filters Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Enter Product Name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select id="categoryFilter" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach (\App\Models\Category::where('is_active', true)->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Min Price</label>
                        <input type="number" id="minPriceFilter" class="form-control form-control-sm" placeholder="0"
                            step="0.01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Max Price</label>
                        <input type="number" id="maxPriceFilter" class="form-control form-control-sm" placeholder="0"
                            step="0.01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="statusFilter" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Discount Status</label>
                        <select id="discountFilter" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="with">With Discount</option>
                            <option value="without">Without Discount</option>
                        </select>
                    </div>

                    <button type="button" id="applyFilters" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </div>

            <!-- AI Assistant Card -->
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-robot me-2"></i>AI Price Assistant</h6>
                </div>
                <div class="card-body">
                    <button type="button" id="aiSuggest" class="btn btn-outline-info btn-sm w-100 mb-2">
                        <i class="fas fa-magic me-1"></i> Get AI Suggestions
                    </button>
                    <button type="button" id="aiOptimize" class="btn btn-outline-success btn-sm w-100 mb-2">
                        <i class="fas fa-chart-line me-1"></i> Optimize Prices
                    </button>
                    <button type="button" id="aiCompetitor" class="btn btn-outline-warning btn-sm w-100">
                        <i class="fas fa-balance-scale me-1"></i> Market Analysis
                    </button>
                    <div id="aiSuggestionBox" class="ai-suggestion-card mt-3" style="display:none;">
                        <h6 class="text-primary"><i class="fas fa-lightbulb me-2"></i>AI Suggestion</h6>
                        <p id="aiSuggestionText" class="mb-0 small"></p>
                    </div>
                    <div class="ai-loading text-center mt-3">
                        <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                        <p class="small mb-0 mt-2">AI is analyzing...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <!-- Price Update Controls -->
            <div class="card mb-3">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#basicPricing" role="tab">
                                <i class="fas fa-tag me-1"></i>Basic Pricing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#discountPricing" role="tab">
                                <i class="fas fa-percent me-1"></i>Discount Pricing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tierPricing" role="tab">
                                <i class="fas fa-layer-group me-1"></i>Tier Pricing
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Basic Pricing Tab -->
                        <div class="tab-pane fade show active" id="basicPricing" role="tabpanel">
                            <div class="price-field-selector">
                                <label class="form-label fw-bold">Target Price Field:</label>
                                <select id="priceFieldType" class="form-select form-select-sm mb-3">
                                    <option value="base_price">Base Price</option>
                                    <option value="discount_price">Discount Price</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="updateType" id="fixedPrice"
                                            value="fixed" checked>
                                        <label class="form-check-label" for="fixedPrice">
                                            <strong>Set Fixed Price</strong>
                                        </label>
                                    </div>
                                    <div id="fixedPriceInput" class="mt-2">
                                        <label class="form-label small">New Price
                                            ({{ config('app.currency_symbol') }})</label>
                                        <input type="number" id="newPrice" class="form-control form-control-sm"
                                            placeholder="0.00" step="0.01">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="updateType"
                                            id="percentageType" value="percentage">
                                        <label class="form-check-label" for="percentageType">
                                            <strong>Percentage Change</strong>
                                        </label>
                                    </div>
                                    <div id="percentageInput" class="mt-2" style="display: none;">
                                        <label class="form-label small">Percentage (%)</label>
                                        <div class="input-group input-group-sm">
                                            <select id="percentageDirection" class="form-select"
                                                style="max-width: 100px;">
                                                <option value="increase">Increase</option>
                                                <option value="decrease">Decrease</option>
                                            </select>
                                            <input type="number" id="percentageValue" class="form-control"
                                                placeholder="0" step="0.01">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="updateType"
                                            id="formulaType" value="formula">
                                        <label class="form-check-label" for="formulaType">
                                            <strong>Add/Subtract Amount</strong>
                                        </label>
                                    </div>
                                    <div id="formulaInput" class="mt-2" style="display: none;">
                                        <label class="form-label small">Amount
                                            ({{ config('app.currency_symbol') }})</label>
                                        <div class="input-group input-group-sm">
                                            <select id="formulaTypeSelect" class="form-select" style="max-width: 100px;">
                                                <option value="increase">Add (+)</option>
                                                <option value="decrease">Subtract (-)</option>
                                            </select>
                                            <input type="number" id="formulaValue" class="form-control"
                                                placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" id="updatePricesBtn" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i> Update Selected Products
                                </button>
                                <button type="button" id="previewBtn" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i> Preview Changes
                                </button>
                            </div>
                        </div>

                        <!-- Discount Pricing Tab -->
                        <div class="tab-pane fade" id="discountPricing" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Set discount prices for selected products. Discount price should be lower than base price.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Discount Type:</label>
                                    <select id="discountType" class="form-select mb-3">
                                        <option value="percentage">Percentage Off Base Price</option>
                                        <option value="fixed">Fixed Discount Amount</option>
                                        <option value="absolute">Set Absolute Discount Price</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Discount Value:</label>
                                    <input type="number" id="discountValue" class="form-control" placeholder="0"
                                        step="0.01">
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" id="applyDiscountBtn" class="btn btn-warning">
                                    <i class="fas fa-percent me-2"></i> Apply Discount to Selected
                                </button>
                                <button type="button" id="removeDiscountBtn" class="btn btn-outline-danger">
                                    <i class="fas fa-times me-2"></i> Remove Discounts
                                </button>
                            </div>
                        </div>

                        <!-- Tier Pricing Tab -->
                        <div class="tab-pane fade" id="tierPricing" role="tabpanel">
                            <div class="alert alert-warning">
                                <i class="fas fa-layer-group me-2"></i>
                                Add quantity-based pricing tiers for selected products. Lower prices for bulk orders.
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Min Quantity:</label>
                                    <input type="number" id="tierMinQty" class="form-control" placeholder="10"
                                        min="1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Max Quantity:</label>
                                    <input type="number" id="tierMaxQty" class="form-control"
                                        placeholder="50 or blank">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tier Price:</label>
                                    <input type="number" id="tierPrice" class="form-control" placeholder="0.00"
                                        step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" id="addTierBtn" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-1"></i> Add Tier
                                    </button>
                                </div>
                            </div>

                            <div id="tierPreview" class="tier-price-preview mt-3" style="display:none;">
                                <strong>Tier Preview:</strong>
                                <ul id="tierList" class="mb-0 mt-2"></ul>
                            </div>

                            <div class="mt-3">
                                <button type="button" id="applyTiersBtn" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i> Apply Tiers to Selected
                                </button>
                                <button type="button" id="clearTiersBtn" class="btn btn-outline-secondary">
                                    <i class="fas fa-eraser me-2"></i> Clear All Tiers
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-boxes me-2"></i>Products to Update
                        <span class="badge bg-info" id="productCount">0</span>
                        <span class="badge bg-success" id="selectedCount">0 selected</span>
                    </h6>
                    <div>
                        <button type="button" id="selectAllBtn" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-check-double me-1"></i> Select All
                        </button>
                        <button type="button" id="deselectAllBtn" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i> Deselect All
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div id="loadingSpinner" class="text-center py-4">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="productsContainer" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="bulkCheckAll" class="form-check-input">
                                        </th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Base Price</th>
                                        <th>Discount Price</th>
                                        <th>Tier Prices</th>
                                        <th>Preview</th>
                                    </tr>
                                </thead>
                                <tbody id="productsList">
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="paginationContainer"></div>
                    </div>

                    <div id="emptyState" style="display: none;" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No products found matching your filters</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let allProducts = [];
        let selectedProducts = [];
        let currentUpdateType = 'fixed';
        let currentPriceField = 'base_price';
        let tierPrices = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            initializeEventListeners();
        });

        function initializeEventListeners() {
            // Update type radio buttons
            document.querySelectorAll('input[name="updateType"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    currentUpdateType = this.value;
                    updateInputVisibility();
                });
            });

            // Price field selector
            document.getElementById('priceFieldType')?.addEventListener('change', function() {
                currentPriceField = this.value;
                updateExpectedPrices();
            });

            // Filters
            document.getElementById('applyFilters').addEventListener('click', loadProducts);
            document.getElementById('searchInput').addEventListener('keyup', debounce(loadProducts, 500));
            document.getElementById('categoryFilter').addEventListener('change', loadProducts);
            document.getElementById('minPriceFilter').addEventListener('change', loadProducts);
            document.getElementById('maxPriceFilter').addEventListener('change', loadProducts);
            document.getElementById('statusFilter').addEventListener('change', loadProducts);
            document.getElementById('discountFilter')?.addEventListener('change', loadProducts);

            // Select all/deselect all
            document.getElementById('bulkCheckAll').addEventListener('change', function() {
                document.querySelectorAll('.product-check').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectedProducts();
            });

            document.getElementById('selectAllBtn')?.addEventListener('click', function() {
                document.querySelectorAll('.product-check').forEach(cb => cb.checked = true);
                document.getElementById('bulkCheckAll').checked = true;
                updateSelectedProducts();
            });

            document.getElementById('deselectAllBtn')?.addEventListener('click', function() {
                document.querySelectorAll('.product-check').forEach(cb => cb.checked = false);
                document.getElementById('bulkCheckAll').checked = false;
                updateSelectedProducts();
            });

            // Update prices button
            document.getElementById('updatePricesBtn').addEventListener('click', submitPriceUpdate);
            document.getElementById('previewBtn')?.addEventListener('click', previewChanges);

            // Discount buttons
            document.getElementById('applyDiscountBtn')?.addEventListener('click', applyDiscounts);
            document.getElementById('removeDiscountBtn')?.addEventListener('click', removeDiscounts);

            // Tier pricing
            document.getElementById('addTierBtn')?.addEventListener('click', addTier);
            document.getElementById('applyTiersBtn')?.addEventListener('click', applyTiers);
            document.getElementById('clearTiersBtn')?.addEventListener('click', clearTiers);

            // AI Features
            document.getElementById('aiSuggest')?.addEventListener('click', getAISuggestion);
            document.getElementById('aiOptimize')?.addEventListener('click', optimizePrices);
            document.getElementById('aiCompetitor')?.addEventListener('click', marketAnalysis);

            // Export
            document.getElementById('exportBtn')?.addEventListener('click', exportPrices);

            // Input change listeners for live preview
            ['newPrice', 'percentageValue', 'formulaValue', 'percentageDirection', 'formulaTypeSelect'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateExpectedPrices);
                    el.addEventListener('change', updateExpectedPrices);
                }
            });
        }

        function updateInputVisibility() {
            document.getElementById('fixedPriceInput').style.display =
                currentUpdateType === 'fixed' ? 'block' : 'none';
            document.getElementById('percentageInput').style.display =
                currentUpdateType === 'percentage' ? 'block' : 'none';
            document.getElementById('formulaInput').style.display =
                currentUpdateType === 'formula' ? 'block' : 'none';
        }

        function loadProducts() {
            const spinner = document.getElementById('loadingSpinner');
            const container = document.getElementById('productsContainer');
            const empty = document.getElementById('emptyState');

            spinner.style.display = 'block';
            container.style.display = 'none';
            empty.style.display = 'none';

            const params = new URLSearchParams({
                search: document.getElementById('searchInput').value.trim(),
                category_id: document.getElementById('categoryFilter').value,
                min_price: document.getElementById('minPriceFilter').value,
                max_price: document.getElementById('maxPriceFilter').value,
                status: document.getElementById('statusFilter').value,
                discount_filter: document.getElementById('discountFilter')?.value || '',
            });

            fetch(`{{ route('admin.bulk-price.get-products') }}?${params}`)
                .then(r => r.json())
                .then(data => {
                    allProducts = data.products.data;
                    document.getElementById('productCount').textContent = data.total;
                    renderProducts(data.products);

                    spinner.style.display = 'none';
                    if (data.total > 0) {
                        container.style.display = 'block';
                    } else {
                        empty.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    spinner.style.display = 'none';
                    empty.style.display = 'block';
                });
        }

        function renderProducts(paginated) {
            const currencySymbol = '{{ config('app.currency_symbol') }}';
            const html = paginated.data.map(product => {
                const tierPricesHtml = product.prices && product.prices.length > 0 ?
                    product.prices.map(p =>
                        `<small class="badge bg-info">${p.min_quantity}-${p.max_quantity || '∞'}: ${currencySymbol}${parseFloat(p.price).toFixed(2)}</small>`
                    ).join(' ') :
                    '<small class="text-muted">No tiers</small>';

                return `
                <tr data-product-id="${product.id}">
                    <td>
                        <input type="checkbox" class="form-check-input product-check"
                            value="${product.id}"
                            data-base-price="${product.base_price}"
                            data-discount-price="${product.discount_price || ''}">
                    </td>
                    <td>
                        <strong>${product.name}</strong><br>
                        <small class="text-muted">${product.category?.name || 'N/A'}</small>
                    </td>
                    <td><small>${product.sku || 'N/A'}</small></td>
                    <td class="fw-bold">${currencySymbol}<span class="base-price">${parseFloat(product.base_price).toFixed(2)}</span></td>
                    <td class="text-success">
                        ${product.discount_price ? currencySymbol + parseFloat(product.discount_price).toFixed(2) : '<small class="text-muted">None</small>'}
                    </td>
                    <td>${tierPricesHtml}</td>
                    <td>
                        <span class="expected-price-display"></span>
                    </td>
                </tr>
            `
            }).join('');

            document.getElementById('productsList').innerHTML = html;

            // Add change listeners
            document.querySelectorAll('.product-check').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedProducts);
            });

            renderPagination(paginated);
        }

        function renderPagination(paginated) {
            if (paginated.last_page === 1) {
                document.getElementById('paginationContainer').innerHTML = '';
                return;
            }

            const html = `
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        ${paginated.prev_page_url ? `
                                        <li class="page-item">
                                            <a class="page-link" href="#" data-page="${paginated.current_page - 1}">Previous</a>
                                        </li>
                                    ` : `<li class="page-item disabled"><span class="page-link">Previous</span></li>`}

                        <li class="page-item"><span class="page-link">Page ${paginated.current_page} of ${paginated.last_page}</span></li>

                        ${paginated.next_page_url ? `
                                        <li class="page-item">
                                            <a class="page-link" href="#" data-page="${paginated.current_page + 1}">Next</a>
                                        </li>
                                    ` : `<li class="page-item disabled"><span class="page-link">Next</span></li>`}
                    </ul>
                </nav>
            `;
            document.getElementById('paginationContainer').innerHTML = html;

            // Add pagination click handlers
            document.querySelectorAll('#paginationContainer .page-link[data-page]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Implement pagination if needed
                });
            });
        }

        function updateSelectedProducts() {
            selectedProducts = Array.from(document.querySelectorAll('.product-check:checked'))
                .map(cb => ({
                    id: parseInt(cb.value),
                    base_price: parseFloat(cb.getAttribute('data-base-price')),
                    discount_price: cb.getAttribute('data-discount-price') ? parseFloat(cb.getAttribute(
                        'data-discount-price')) : null
                }));

            document.getElementById('selectedCount').textContent = `${selectedProducts.length} selected`;
            updateExpectedPrices();
        }

        function updateExpectedPrices() {
            const currencySymbol = '{{ config('app.currency_symbol') }}';

            document.querySelectorAll('.product-check').forEach(checkbox => {
                const row = checkbox.closest('tr');
                const expectedEl = row.querySelector('.expected-price-display');
                const currentPrice = currentPriceField === 'base_price' ?
                    parseFloat(checkbox.getAttribute('data-base-price')) :
                    parseFloat(checkbox.getAttribute('data-discount-price') || checkbox.getAttribute(
                        'data-base-price'));

                if (!checkbox.checked) {
                    expectedEl.innerHTML = '';
                    return;
                }

                let newPrice = 0;

                if (currentUpdateType === 'fixed') {
                    newPrice = parseFloat(document.getElementById('newPrice')?.value) || 0;
                } else if (currentUpdateType === 'percentage') {
                    const percent = parseFloat(document.getElementById('percentageValue')?.value) || 0;
                    const direction = document.getElementById('percentageDirection')?.value || 'increase';

                    if (direction === 'increase') {
                        newPrice = currentPrice * (1 + percent / 100);
                    } else {
                        newPrice = currentPrice * (1 - percent / 100);
                    }
                } else if (currentUpdateType === 'formula') {
                    const value = parseFloat(document.getElementById('formulaValue')?.value) || 0;
                    const type = document.getElementById('formulaTypeSelect')?.value || 'increase';

                    if (type === 'increase') {
                        newPrice = currentPrice + value;
                    } else {
                        newPrice = Math.max(0, currentPrice - value);
                    }
                }

                const priceChange = newPrice - currentPrice;
                const changeClass = priceChange > 0 ? 'text-success' : (priceChange < 0 ? 'text-danger' :
                    'text-muted');
                const changeIcon = priceChange > 0 ? '↑' : (priceChange < 0 ? '↓' : '');

                expectedEl.innerHTML = `
                    <div class="price-comparison">
                        <span class="price-old">${currencySymbol}${currentPrice.toFixed(2)}</span>
                        <span class="${changeClass}">${changeIcon}</span>
                        <span class="price-new">${currencySymbol}${newPrice.toFixed(2)}</span>
                    </div>
                `;
            });
        }

        function submitPriceUpdate() {
            if (selectedProducts.length === 0) {
                Swal.fire('Warning', 'Please select at least one product', 'warning');
                return;
            }

            const data = {
                update_type: currentUpdateType,
                price_field: currentPriceField,
                ids: selectedProducts.map(p => p.id),
            };

            if (currentUpdateType === 'fixed') {
                data.fixed_price = parseFloat(document.getElementById('newPrice')?.value);
                if (!data.fixed_price && data.fixed_price !== 0) {
                    Swal.fire('Error', 'Please enter a price', 'error');
                    return;
                }
            } else if (currentUpdateType === 'percentage') {
                data.percentage = parseFloat(document.getElementById('percentageValue')?.value);
                data.percentage_direction = document.getElementById('percentageDirection')?.value;
                if (!data.percentage && data.percentage !== 0) {
                    Swal.fire('Error', 'Please enter a percentage', 'error');
                    return;
                }
            } else if (currentUpdateType === 'formula') {
                data.formula_type = document.getElementById('formulaTypeSelect')?.value;
                data.formula_value = parseFloat(document.getElementById('formulaValue')?.value);
                if (!data.formula_value && data.formula_value !== 0) {
                    Swal.fire('Error', 'Please enter an amount', 'error');
                    return;
                }
            }

            Swal.fire({
                title: 'Confirm Update',
                html: `Update <strong>${currentPriceField.replace('_', ' ')}</strong> for <strong>${selectedProducts.length}</strong> product(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update!',
                confirmButtonColor: '#28a745',
            }).then((result) => {
                if (result.isConfirmed) {
                    performUpdate(data);
                }
            });
        }

        function performUpdate(data) {
            const loadingAlert = Swal.fire({
                title: 'Updating Prices',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('admin.bulk-price.update-prices') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(data),
                })
                .then(r => r.json())
                .then(data => {
                    loadingAlert.close();
                    if (data.success) {
                        Swal.fire('Success!', data.message, 'success').then(() => {
                            loadProducts();
                            selectedProducts = [];
                            document.getElementById('selectedCount').textContent = '0 selected';
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    loadingAlert.close();
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to update prices', 'error');
                });
        }

        function previewChanges() {
            if (selectedProducts.length === 0) {
                Swal.fire('Warning', 'Please select products to preview', 'warning');
                return;
            }

            const previewHtml = Array.from(document.querySelectorAll('.product-check:checked'))
                .map(cb => {
                    const row = cb.closest('tr');
                    const productName = row.querySelector('strong').textContent;
                    const previewDiv = row.querySelector('.expected-price-display');
                    return `<div class="mb-2"><strong>${productName}:</strong> ${previewDiv.innerHTML}</div>`;
                }).join('');

            Swal.fire({
                title: 'Price Changes Preview',
                html: previewHtml,
                width: 600,
                confirmButtonText: 'Close'
            });
        }

        // Discount Functions
        function applyDiscounts() {
            if (selectedProducts.length === 0) {
                Swal.fire('Warning', 'Please select at least one product', 'warning');
                return;
            }

            const discountType = document.getElementById('discountType').value;
            const discountValue = parseFloat(document.getElementById('discountValue').value);

            if (!discountValue && discountValue !== 0) {
                Swal.fire('Error', 'Please enter a discount value', 'error');
                return;
            }

            const data = {
                ids: selectedProducts.map(p => p.id),
                discount_type: discountType,
                discount_value: discountValue,
            };

            Swal.fire({
                title: 'Apply Discounts',
                text: `Apply discount to ${selectedProducts.length} product(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, apply!',
            }).then((result) => {
                if (result.isConfirmed) {
                    performDiscountUpdate(data);
                }
            });
        }

        function performDiscountUpdate(data) {
            fetch('{{ route('admin.bulk-price.apply-discount') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(data),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success!', data.message, 'success').then(() => loadProducts());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to apply discounts', 'error');
                });
        }

        function removeDiscounts() {
            if (selectedProducts.length === 0) {
                Swal.fire('Warning', 'Please select at least one product', 'warning');
                return;
            }

            Swal.fire({
                title: 'Remove Discounts',
                text: `Remove discounts from ${selectedProducts.length} product(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove!',
                confirmButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = {
                        ids: selectedProducts.map(p => p.id)
                    };

                    fetch('{{ route('admin.bulk-price.remove-discount') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success!', data.message, 'success').then(() => loadProducts());
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to remove discounts', 'error');
                        });
                }
            });
        }

        // Tier Pricing Functions
        function addTier() {
            const minQty = parseInt(document.getElementById('tierMinQty').value);
            const maxQty = document.getElementById('tierMaxQty').value ? parseInt(document.getElementById('tierMaxQty')
                .value) : null;
            const price = parseFloat(document.getElementById('tierPrice').value);

            if (!minQty || !price) {
                Swal.fire('Error', 'Please enter min quantity and price', 'error');
                return;
            }

            tierPrices.push({
                min_quantity: minQty,
                max_quantity: maxQty,
                price: price
            });
            updateTierPreview();

            // Clear inputs
            document.getElementById('tierMinQty').value = '';
            document.getElementById('tierMaxQty').value = '';
            document.getElementById('tierPrice').value = '';
        }

        function updateTierPreview() {
            const preview = document.getElementById('tierPreview');
            const list = document.getElementById('tierList');

            if (tierPrices.length === 0) {
                preview.style.display = 'none';
                return;
            }

            const currencySymbol = '{{ config('app.currency_symbol') }}';
            const html = tierPrices.map((tier, index) => `
                <li>
                    ${tier.min_quantity} - ${tier.max_quantity || '∞'}: ${currencySymbol}${tier.price.toFixed(2)}
                    <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeTier(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </li>
            `).join('');

            list.innerHTML = html;
            preview.style.display = 'block';
        }

        function removeTier(index) {
            tierPrices.splice(index, 1);
            updateTierPreview();
        }

        function applyTiers() {
            if (selectedProducts.length === 0) {
                Swal.fire('Warning', 'Please select at least one product', 'warning');
                return;
            }

            if (tierPrices.length === 0) {
                Swal.fire('Warning', 'Please add at least one tier', 'warning');
                return;
            }

            const data = {
                ids: selectedProducts.map(p => p.id),
                tiers: tierPrices,
            };

            Swal.fire({
                title: 'Apply Tier Prices',
                text: `Apply ${tierPrices.length} tier(s) to ${selectedProducts.length} product(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, apply!',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('admin.bulk-price.apply-tiers') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success!', data.message, 'success').then(() => {
                                    loadProducts();
                                    clearTiers();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to apply tiers', 'error');
                        });
                }
            });
        }

        function clearTiers() {
            tierPrices = [];
            updateTierPreview();
            document.getElementById('tierMinQty').value = '';
            document.getElementById('tierMaxQty').value = '';
            document.getElementById('tierPrice').value = '';
        }

        // AI Functions
        function getAISuggestion() {
            if (selectedProducts.length === 0) {
                Swal.fire('Info', 'Please select products for AI analysis', 'info');
                return;
            }

            showAILoading();

            fetch('{{ route('admin.bulk-price.ai-suggest') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ids: selectedProducts.map(p => p.id),
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    hideAILoading();
                    if (data.success) {
                        showAISuggestion(data.suggestion);
                    } else {
                        Swal.fire('Error', data.message || 'AI suggestion failed', 'error');
                    }
                })
                .catch(error => {
                    hideAILoading();
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to get AI suggestion', 'error');
                });
        }

        function optimizePrices() {
            if (selectedProducts.length === 0) {
                Swal.fire('Info', 'Please select products for optimization', 'info');
                return;
            }

            showAILoading();

            fetch('{{ route('admin.bulk-price.ai-optimize') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ids: selectedProducts.map(p => p.id),
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    hideAILoading();
                    if (data.success) {
                        showAISuggestion(data.optimization);
                    } else {
                        Swal.fire('Error', data.message || 'Optimization failed', 'error');
                    }
                })
                .catch(error => {
                    hideAILoading();
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to optimize prices', 'error');
                });
        }

        function marketAnalysis() {
            if (selectedProducts.length === 0) {
                Swal.fire('Info', 'Please select products for market analysis', 'info');
                return;
            }

            showAILoading();

            fetch('{{ route('admin.bulk-price.ai-market') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ids: selectedProducts.map(p => p.id),
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    hideAILoading();
                    if (data.success) {
                        showAISuggestion(data.analysis);
                    } else {
                        Swal.fire('Error', data.message || 'Market analysis failed', 'error');
                    }
                })
                .catch(error => {
                    hideAILoading();
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to analyze market', 'error');
                });
        }

        function showAILoading() {
            document.querySelector('.ai-loading').style.display = 'block';
        }

        function hideAILoading() {
            document.querySelector('.ai-loading').style.display = 'none';
        }

        function showAISuggestion(text) {
            const box = document.getElementById('aiSuggestionBox');
            const textEl = document.getElementById('aiSuggestionText');
            textEl.textContent = text;
            box.style.display = 'block';
        }

        // Export Function
        function exportPrices() {
            const params = new URLSearchParams({
                search: document.getElementById('searchInput').value,
                category_id: document.getElementById('categoryFilter').value,
                min_price: document.getElementById('minPriceFilter').value,
                max_price: document.getElementById('maxPriceFilter').value,
                status: document.getElementById('statusFilter').value,
            });

            window.location.href = `{{ route('admin.bulk-price.export') }}?${params}`;
        }

        // Utility
        function debounce(fn, ms) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn(...args), ms);
            };
        }
    </script>
@endpush
