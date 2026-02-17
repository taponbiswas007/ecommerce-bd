@extends('admin.layouts.master')

@section('title', 'Edit Dropshipping Product')
@section('page-title', 'Edit Product')
@section('page-subtitle', $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.dropshipping.products.index') }}">Dropshipping Products</a></li>
    <li class="breadcrumb-item active">Edit Product</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.dropshipping.products.update', $product->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">CJ Product ID</label>
                                    <input type="text" class="form-control" value="{{ $product->cj_product_id }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Cost Price (CJ Price)</label>
                                    <input type="text" class="form-control"
                                        value="{{ number_format($product->unit_price, 2) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Your Selling Price <span class="text-danger">*</span></label>
                                    <input type="number" name="selling_price" class="form-control"
                                        value="{{ $product->selling_price }}" step="0.01" required id="sellingPrice">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Profit Margin</label>
                                    <input type="text" class="form-control" id="profitMargin" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-select" required>
                                        <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Availability</label>
                                    <select name="is_available" class="form-select" required>
                                        <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available
                                        </option>
                                        <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Not
                                            Available
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="4" readonly>{{ $product->description }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.dropshipping.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>\n\n
    <script>
        const costPrice = {{ $product->unit_price }};
        const sellingPriceInput = document.getElementById('sellingPrice');
        const profitMarginInput = document.getElementById('profitMargin');

        function updateProfit() {
            const selling = parseFloat(sellingPriceInput.value) || 0;
            const profit = selling - costPrice;
            profitMarginInput.value = profit.toFixed(2) + ' ৳';
        }

        sellingPriceInput.addEventListener('input', updateProfit);
        updateProfit();
    </script>
@endsection
