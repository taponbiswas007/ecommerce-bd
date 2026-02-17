@extends('admin.layouts.master')

@section('title', 'Import Dropshipping Product')
@section('page-title', 'Import Product')
@section('page-subtitle', 'Search and import products from CJ Dropshipping')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.dropshipping.products.index') }}">Dropshipping Products</a></li>
    <li class="breadcrumb-item active">Import Product</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form id="searchForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Search Product</label>
                            <div class="input-group">
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    placeholder="Search by product name...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Search Results -->
                    <div id="results" style="display: none;">
                        <h5 class="mb-3" id="resultsTitle">Search Results</h5>
                        <div id="resultsNotice" class="alert alert-info d-none"></div>
                        <div id="resultsList"></div>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="loading" style="display: none;" class="text-center py-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Searching CJ Dropshipping...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Import Information</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading">How to Import:</h6>
                        <ol class="mb-0 small">
                            <li>Search for a product from CJ</li>
                            <li>Enter your selling price</li>
                            <li>Click Import to add to your store</li>
                        </ol>
                    </div>

                    <div class="alert alert-warning">
                        <small><strong>Note:</strong> Make sure API credentials are configured in settings.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('modalpopup')

    <!-- Product Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="importForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="cj_product_id" id="importProductId">
                        <input type="hidden" name="image_url" id="importImageUrl">

                        <div class="mb-3">
                            <label class="form-label">Cost Price</label>
                            <input type="number" class="form-control" id="costPrice" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Your Selling Price</label>
                            <input type="number" name="selling_price" class="form-control" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profit Margin</label>
                            <input type="number" class="form-control" id="profitDisplay" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const searchForm = document.getElementById('searchForm');
        const results = document.getElementById('results');
        const resultsList = document.getElementById('resultsList');
        const loading = document.getElementById('loading');
        const keywordInput = document.getElementById('keyword');
        let searchTimer = null;
        let activeController = null;
        const placeholderImage =
            "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='120' height='120'><rect width='100%25' height='100%25' fill='%23f1f3f5'/><text x='50%25' y='50%25' fill='%23adb5bd' font-size='12' font-family='Arial' dominant-baseline='middle' text-anchor='middle'>No Image</text></svg>";

        async function performSearch(keyword) {
            if (activeController) {
                activeController.abort();
            }

            activeController = new AbortController();
            const formData = new FormData(searchForm);
            formData.set('keyword', keyword || '');

            loading.style.display = 'block';
            results.style.display = 'none';

            try {
                const response = await fetch('{{ route('admin.dropshipping.products.search') }}', {
                    method: 'POST',
                    body: formData,
                    signal: activeController.signal,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();
                loading.style.display = 'none';

                if (data.success) {
                    displayResults(data.data, data.suggested, data.message);
                    results.style.display = 'block';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                if (error.name !== 'AbortError') {
                    loading.style.display = 'none';
                    console.error('Error:', error);
                    alert('An error occurred while searching');
                }
            }
        }

        searchForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const keyword = keywordInput.value.trim();
            await performSearch(keyword);
        });

        keywordInput.addEventListener('input', () => {
            const keyword = keywordInput.value.trim();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                performSearch(keyword);
            }, 400);
        });

        function displayResults(products, suggested = false, message = '') {
            const title = document.getElementById('resultsTitle');
            const notice = document.getElementById('resultsNotice');

            if (title) {
                title.textContent = suggested ? 'Suggested Products' : 'Search Results';
            }

            if (notice) {
                if (message) {
                    notice.textContent = message;
                    notice.classList.remove('d-none');
                } else {
                    notice.classList.add('d-none');
                }
            }

            if (!products || products.length === 0) {
                resultsList.innerHTML = '<p class="text-muted">No products found</p>';
                return;
            }

            resultsList.innerHTML = products.map(product => `
        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <img src="${product.imageUrl || placeholderImage}" class="img-fluid rounded" alt="${product.name}">
                    </div>
                    <div class="col-md-6">
                        <h6 class="card-title">${product.name}</h6>
                        <p class="card-text small">
                            <strong>Price:</strong> ${product.price} $<br>
                            <strong>Stock:</strong> ${product.stock ?? 'N/A'}
                        </p>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-sm btn-primary" onclick="openImportModal('${product.id}', ${product.price}, '${(product.imageUrl || '').replace(/'/g, "\\'")}')">
                            Import
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
        }

        function openImportModal(productId, costPrice, imageUrl) {
            document.getElementById('importProductId').value = productId;
            document.getElementById('costPrice').value = costPrice;
            document.getElementById('importImageUrl').value = imageUrl || '';
            document.querySelector('#importForm input[name="selling_price"]').value = costPrice * 1.2; // Default 20% markup
            updateProfit();
            new bootstrap.Modal(document.getElementById('importModal')).show();
        }

        document.querySelector('#importForm input[name="selling_price"]').addEventListener('input', updateProfit);

        function updateProfit() {
            const costPrice = parseFloat(document.getElementById('costPrice').value);
            const sellingPrice = parseFloat(document.querySelector('#importForm input[name="selling_price"]').value);
            if (sellingPrice) {
                document.getElementById('profitDisplay').value = (sellingPrice - costPrice).toFixed(2);
            }
        }

        document.getElementById('importForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            try {
                const response = await fetch('{{ route('admin.dropshipping.products.import') }}', {
                    method: 'POST',
                    body: new FormData(document.getElementById('importForm')),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Product imported successfully!');
                    window.location.href = '{{ route('admin.dropshipping.products.index') }}';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while importing');
            }
        });

        window.addEventListener('load', () => {
            performSearch('');
        });
    </script>
@endpush
