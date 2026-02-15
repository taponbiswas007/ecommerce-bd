{{-- @extends('layouts.app')

@section('content')
    <div class="container py-6">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-7">
                <h3>Shipping Details</h3>
                <form method="POST" action="{{ route('checkout.process') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="shipping_name"
                            value="{{ old('shipping_name', auth()->user()->name ?? '') }}"
                            class="form-control @error('shipping_name') is-invalid @enderror">
                        @error('shipping_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="shipping_phone"
                            value="{{ old('shipping_phone', auth()->user()->phone ?? '') }}"
                            class="form-control @error('shipping_phone') is-invalid @enderror">
                        @error('shipping_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (optional)</label>
                        <input type="email" name="shipping_email"
                            value="{{ old('shipping_email', auth()->user()->email ?? '') }}"
                            class="form-control @error('shipping_email') is-invalid @enderror">
                        @error('shipping_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">District</label>
                        <input type="hidden" name="shipping_district" id="shipping_district_input"
                            value="{{ old('shipping_district', auth()->user()->district ?? '') }}">

                        <div class="dropdown">
                            <input id="shipping_district_display" type="text"
                                class="form-control @error('shipping_district') is-invalid @enderror"
                                data-bs-toggle="dropdown" aria-expanded="false" readonly
                                value="{{ old('shipping_district', auth()->user()->district ?? '') }}">
                            <div class="dropdown-menu p-2" id="shipping_district_menu"
                                style="max-height: 300px; overflow-y: auto; min-width: 300px;">
                                <input type="text" id="shipping_district_search" class="form-control mb-2"
                                    placeholder="Search district...">
                                <div id="shipping_district_list">
                                    @foreach ($districts as $district)
                                        <a href="#" class="dropdown-item shipping-district-item"
                                            data-value="{{ $district }}">{{ $district }}</a>
                                    @endforeach
                                    <a href="#" class="dropdown-item shipping-district-item"
                                        data-value="__other__">Other (not listed)</a>
                                </div>
                            </div>
                        </div>
                        @error('shipping_district')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="mt-2" id="shipping_district_other_wrap" style="display:none;">
                            <input type="text" id="shipping_district_other" class="form-control"
                                placeholder="Type district name" value="{{ old('shipping_district') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upazila</label>
                        <input type="hidden" name="shipping_upazila" id="shipping_upazila_input"
                            value="{{ old('shipping_upazila', auth()->user()->upazila ?? '') }}">

                        <div class="dropdown">
                            <input id="shipping_upazila_display" type="text"
                                class="form-control @error('shipping_upazila') is-invalid @enderror"
                                data-bs-toggle="dropdown" aria-expanded="false" readonly
                                value="{{ old('shipping_upazila', auth()->user()->upazila ?? '') }}">
                            <div class="dropdown-menu p-2" id="shipping_upazila_menu"
                                style="max-height: 300px; overflow-y: auto; min-width: 300px;">
                                <input type="text" id="shipping_upazila_search" class="form-control mb-2"
                                    placeholder="Search upazila...">
                                <div id="shipping_upazila_list">
                                    <div id="shipping_upazila_loading" class="px-2 text-muted" style="display:none;">
                                        Loading…</div>
                                    <div id="shipping_upazila_error" class="px-2 text-danger" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        @error('shipping_upazila')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="mt-2" id="shipping_upazila_other_wrap" style="display:none;">
                            <input type="text" id="shipping_upazila_other" class="form-control"
                                placeholder="Type upazila name" value="{{ old('shipping_upazila') }}">
                        </div>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="shipping_address" required class="form-control @error('shipping_address') is-invalid @enderror">{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shipping Method</label>
                        <select name="shipping_method" id="shipping_method"
                            class="form-select @error('shipping_method') is-invalid @enderror">
                            @foreach ($shippingMethods as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('shipping_method') == $key ? 'selected' : ($key == 'transport' ? 'selected' : '') }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        @error('shipping_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="transport_company_wrap" style="display: none;">
                        <label class="form-label">Transport Company</label>
                        <select name="transport_company_id" id="transport_company_id"
                            class="form-select @error('transport_company_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach ($transports as $id => $name)
                                <option value="{{ $id }}"
                                    {{ old('transport_company_id') == $id ? 'selected' : '' }}>{{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('transport_company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Transport name (optional)</label>
                        <input type="text" name="transport_name" value="{{ old('transport_name') }}"
                            class="form-control @error('transport_name') is-invalid @enderror">
                        @error('transport_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"></label>Payment Method</label>
                        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery
                            </option>
                            <option value="bank_transfer"
                                {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input @error('terms_accepted') is-invalid @enderror"
                            id="terms" name="terms_accepted" {{ old('terms_accepted') ? 'checked' : '' }}>
                        <label class="form-check-label" for="terms">I accept the terms and conditions</label>
                        @error('terms_accepted')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="customer_notes" class="form-control">{{ old('customer_notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
            </div>

            <div class="col-md-5">
                <h4>Order Summary</h4>
                <ul class="list-group mb-3">
                    @foreach ($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $img =
                                        $item->product && $item->product->images->count()
                                            ? $item->product->images->first()->image_url ??
                                                asset('storage/' . $item->product->images->first()->image_path)
                                            : 'https://via.placeholder.com/40x40';
                                @endphp
                                <img src="{{ $img }}" alt="{{ $item->product->name ?? 'Product' }}"
                                    style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                <div>
                                    <strong>{{ $item->product->name ?? 'Product' }}</strong>
                                    @if (!empty($item->attributes))
                                        <div class="text-muted small mb-1">
                                            @foreach ($item->attributes as $key => $value)
                                                <span class="me-2"><strong>{{ ucfirst($key) }}:</strong>
                                                    {{ $value }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="small">{{ $item->quantity }} × {{ number_format($item->price, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div>{{ number_format($item->price * $item->quantity, 2) }}</div>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Subtotal</span>
                        <strong>{{ number_format($subtotal, 2) }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Discount</span>
                        <strong>-{{ number_format($discount, 2) }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span>VAT</span>
                            <div class="small text-muted">
                                {{ ($taxSummary['vat_amount'] ?? 0) > 0 ? 'Will be added' : 'Included/Not applicable' }}
                            </div>
                        </div>
                        <strong>{{ number_format($taxSummary['vat_amount'] ?? 0, 2) }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span>AIT</span>
                            <div class="small text-muted">
                                {{ ($taxSummary['ait_amount'] ?? 0) > 0 ? 'Will be added' : 'Included/Not applicable' }}
                            </div>
                        </div>
                        <strong>{{ number_format($taxSummary['ait_amount'] ?? 0, 2) }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Tax</span>
                        <strong>{{ number_format($tax, 2) }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Shipping</span>
                        <strong id="shipping_amount_display">{{ number_format($shipping, 2) }}</strong>
                    </li>
                    @if (isset($detailed) && is_array($detailed))
                        <li class="list-group-item">
                            <div><strong>Shipping breakdown:</strong></div>
                            @if (!empty($detailed['breakdown']))
                                <ul class="small">
                                    @foreach ($detailed['breakdown'] as $row)
                                        <li>{{ $row['quantity'] }} × {{ $row['package_type'] }} @
                                            {{ number_format($row['rate'], 2) }} = {{ number_format($row['cost'], 2) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @if (isset($detailed['shop_to_transport']))
                                <div class="small text-muted">Shop → Transport:
                                    {{ number_format($detailed['shop_to_transport'], 2) }}</div>
                            @endif
                            @if (!empty($detailed['note']))
                                <div class="small text-warning">{{ $detailed['note'] }}</div>
                            @endif
                        </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total</span>
                        <strong>{{ number_format($total, 2) }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {

            function initShippingDropdowns() {

                function setDistrict(value, display) {
                    $('#shipping_district_input').val(value);
                    $('#shipping_district_display').val(display || value);
                }

                function setUpazila(value, display) {
                    $('#shipping_upazila_input').val(value);
                    $('#shipping_upazila_display').val(display || value);
                }

                // District click
                $(document).on('click', '.shipping-district-item', function(e) {
                    e.preventDefault();
                    var val = $(this).data('value');
                    console.debug('district clicked', val);
                });

                // Shipping method toggle
                function updateShippingMethodUI() {
                    var method = $('#shipping_method').val();
                    if (method === 'transport') {
                        $('#transport_company_wrap').show();
                    } else {
                        $('#transport_company_wrap').hide();
                    }
                }
                $('#shipping_method').on('change', function() {
                    updateShippingMethodUI();
                    fetchEstimate();
                });
                $('#transport_company_id').on('change', function() {
                    fetchEstimate();
                });
                // initialize
                updateShippingMethodUI();

                function fetchEstimate() {
                    var district = $('#shipping_district_input').val();
                    var upazila = $('#shipping_upazila_input').val();
                    var method = $('#shipping_method').val();
                    var transportId = $('#transport_company_id').val();

                    if (!district || !upazila) return;

                    $('#shipping_amount_display').text('...');
                    $.getJSON('{{ route('shipping.estimate') }}', {
                            district: district,
                            upazila: upazila,
                            method: method,
                            transport_company_id: transportId
                        })
                        .done(function(data) {
                            if (data && data.total !== undefined) {
                                $('#shipping_amount_display').text((parseFloat(data.total) || 0).toFixed(2));

                                // update breakdown
                                var html = '';
                                if (data.breakdown && data.breakdown.length) {
                                    html += '<ul class="small">';
                                    data.breakdown.forEach(function(r) {
                                        html += '<li>' + r.quantity + ' × ' + r.package_type + ' @ ' +
                                            parseFloat(r.rate).toFixed(2) + ' = ' + parseFloat(r.cost)
                                            .toFixed(2) + '</li>';
                                    });
                                    html += '</ul>';
                                }
                                if (data.shop_to_transport) {
                                    html += '<div class="small text-muted">Shop → Transport: ' + parseFloat(data
                                        .shop_to_transport).toFixed(2) + '</div>';
                                }
                                if (data.note) {
                                    html += '<div class="small text-warning">' + data.note + '</div>';
                                }
                                if (html) {
                                    if ($('#shipping_breakdown').length) $('#shipping_breakdown').html(html);
                                    else $('#shipping_amount_display').closest('li').after(
                                        '<li id="shipping_breakdown" class="list-group-item">' + html + '</li>');
                                }
                            }
                        })
                        .fail(function() {
                            $('#shipping_amount_display').text('—');
                        });
                }

                // trigger estimate when district/upazila selection changes
                $(document).on('change', '#shipping_district_input, #shipping_upazila_input', function() {
                    fetchEstimate();
                });

                // initial fetch if values exist
                setTimeout(function() {
                    fetchEstimate();
                }, 200);

                $(document).on('click', '.shipping-district-item', function(e) {
                    e.preventDefault();
                    var val = $(this).data('value');

                    if (val === '__other__') {
                        $('#shipping_district_other_wrap').show();
                        $('#shipping_district_other').focus();
                        setDistrict($('#shipping_district_other').val() || '');

                        $('#shipping_upazila_list').empty();
                        setUpazila('');
                        $('#shipping_upazila_other_wrap').show();
                    } else {
                        $('#shipping_district_other_wrap').hide();
                        setDistrict(val, val);
                        loadUpazilas(val, $('#shipping_upazila_input').val());
                    }

                    bootstrap.Dropdown.getOrCreateInstance(
                        document.getElementById('shipping_district_display')
                    ).hide();
                });

                // District search
                $('#shipping_district_search').on('keyup', function() {
                    var q = $(this).val().toLowerCase();
                    $('#shipping_district_list a').each(function() {
                        $(this).toggle($(this).text().toLowerCase().includes(q));
                    });
                });

                // Upazila click
                $(document).on('click', '.shipping-upazila-item', function(e) {
                    e.preventDefault();
                    var val = $(this).data('value');

                    if (val === '__other__') {
                        $('#shipping_upazila_other_wrap').show();
                        $('#shipping_upazila_other').focus();
                        setUpazila($('#shipping_upazila_other').val() || '');
                    } else {
                        $('#shipping_upazila_other_wrap').hide();
                        setUpazila(val, val);
                    }

                    bootstrap.Dropdown.getOrCreateInstance(
                        document.getElementById('shipping_upazila_display')
                    ).hide();
                });

                // Other inputs
                $('#shipping_district_other').on('input', function() {
                    $('#shipping_district_input').val(this.value);
                });

                $('#shipping_upazila_other').on('input', function() {
                    $('#shipping_upazila_input').val(this.value);
                });

                function loadUpazilas(district, selected) {
                    if (!district) return;

                    // Load from config (locations) directly
                    var locations = @json(config('locations', []));
                    var upazilas = locations[district] || [];

                    $('#shipping_upazila_list').empty();

                    upazilas.forEach(function(up) {
                        $('#shipping_upazila_list').append(
                            `<a href="#" class="dropdown-item shipping-upazila-item" data-value="${up}">${up}</a>`
                        );
                    });

                    $('#shipping_upazila_list').append(
                        `<a href="#" class="dropdown-item shipping-upazila-item" data-value="__other__">Other (not listed)</a>`
                    );

                    if (selected) setUpazila(selected, selected);
                }

                // Initial load
                var initialDistrict = $('#shipping_district_input').val();
                var initialUpazila = $('#shipping_upazila_input').val();

                if (initialDistrict) {
                    $('#shipping_district_display').val(initialDistrict);
                    loadUpazilas(initialDistrict, initialUpazila);
                }
            }

            // Wait for jQuery
            if (window.jQuery) {
                initShippingDropdowns();
            } else {
                var wait = setInterval(function() {
                    if (window.jQuery) {
                        clearInterval(wait);
                        initShippingDropdowns();
                    }
                }, 100);
            }

        })();
    </script>
@endsection --}}

@extends('layouts.app')

@section('content')
    <div class="checkout-container">
        <!-- Header Section -->
        <div class="checkout-header">
            <h1 class="checkout-title">
                <i class="fas fa-shopping-bag me-2" style="color: var(--primary-color);"></i>
                Complete Your Order
            </h1>
            <p class="checkout-subtitle">Fill in your shipping details to place your order</p>
        </div>

        @if (session('success'))
            <div class="alert-modern alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert-modern alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="checkout-grid">
            <!-- Shipping Form Section -->
            <div class="checkout-form-section">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <h3>Shipping Details</h3>
                            <p>Enter your delivery information</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('checkout.process') }}" class="shipping-form">
                        @csrf

                        <!-- Personal Information -->
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <input type="text" name="shipping_name"
                                    value="{{ old('shipping_name', auth()->user()->name ?? '') }}"
                                    class="form-control-modern @error('shipping_name') is-invalid @enderror"
                                    placeholder="Enter your full name">
                                @error('shipping_name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone me-2"></i>Phone Number
                                </label>
                                <input type="text" name="shipping_phone"
                                    value="{{ old('shipping_phone', auth()->user()->phone ?? '') }}"
                                    class="form-control-modern @error('shipping_phone') is-invalid @enderror"
                                    placeholder="01XXXXXXXXX">
                                @error('shipping_phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email (Optional)
                                </label>
                                <input type="email" name="shipping_email"
                                    value="{{ old('shipping_email', auth()->user()->email ?? '') }}"
                                    class="form-control-modern @error('shipping_email') is-invalid @enderror"
                                    placeholder="your@email.com">
                                @error('shipping_email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>District
                                </label>
                                <input type="hidden" name="shipping_district" id="shipping_district_input"
                                    value="{{ old('shipping_district', auth()->user()->district ?? '') }}">

                                <div class="dropdown-custom">
                                    <input id="shipping_district_display" type="text"
                                        class="form-control-modern dropdown-toggle @error('shipping_district') is-invalid @enderror"
                                        data-bs-toggle="dropdown" aria-expanded="false" readonly
                                        value="{{ old('shipping_district', auth()->user()->district ?? '') }}"
                                        placeholder="Select district">
                                    <i class="fas fa-chevron-down dropdown-icon"></i>

                                    <div class="dropdown-menu-custom" id="shipping_district_menu">
                                        <div class="dropdown-search">
                                            <i class="fas fa-search"></i>
                                            <input type="text" id="shipping_district_search"
                                                placeholder="Search district...">
                                        </div>
                                        <div id="shipping_district_list" class="dropdown-items">
                                            @foreach ($districts as $district)
                                                <a href="#" class="dropdown-item-custom shipping-district-item"
                                                    data-value="{{ $district }}">
                                                    <i class="fas fa-map-pin"></i>
                                                    {{ $district }}
                                                </a>
                                            @endforeach
                                            <a href="#" class="dropdown-item-custom shipping-district-item"
                                                data-value="__other__">
                                                <i class="fas fa-plus-circle"></i>
                                                Other (not listed)
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @error('shipping_district')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror

                                <div class="other-input" id="shipping_district_other_wrap" style="display:none;">
                                    <input type="text" id="shipping_district_other" class="form-control-modern"
                                        placeholder="Type district name" value="{{ old('shipping_district') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-location-dot me-2"></i>Upazila
                                </label>
                                <input type="hidden" name="shipping_upazila" id="shipping_upazila_input"
                                    value="{{ old('shipping_upazila', auth()->user()->upazila ?? '') }}">

                                <div class="dropdown-custom">
                                    <input id="shipping_upazila_display" type="text"
                                        class="form-control-modern dropdown-toggle @error('shipping_upazila') is-invalid @enderror"
                                        data-bs-toggle="dropdown" aria-expanded="false" readonly
                                        value="{{ old('shipping_upazila', auth()->user()->upazila ?? '') }}"
                                        placeholder="Select upazila">
                                    <i class="fas fa-chevron-down dropdown-icon"></i>

                                    <div class="dropdown-menu-custom" id="shipping_upazila_menu">
                                        <div class="dropdown-search">
                                            <i class="fas fa-search"></i>
                                            <input type="text" id="shipping_upazila_search"
                                                placeholder="Search upazila...">
                                        </div>
                                        <div id="shipping_upazila_list" class="dropdown-items">
                                            <div id="shipping_upazila_loading" class="loading-indicator">
                                                <div class="spinner-small"></div>
                                                Loading...
                                            </div>
                                            <div id="shipping_upazila_error" class="error-message" style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('shipping_upazila')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror

                                <div class="other-input" id="shipping_upazila_other_wrap" style="display:none;">
                                    <input type="text" id="shipping_upazila_other" class="form-control-modern"
                                        placeholder="Type upazila name" value="{{ old('shipping_upazila') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-home me-2"></i>Full Address
                            </label>
                            <textarea name="shipping_address" required class="form-control-modern @error('shipping_address') is-invalid @enderror"
                                rows="3" placeholder="House number, road, village/area">{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                            @error('shipping_address')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Shipping Method -->
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-shipping-fast me-2"></i>Shipping Method
                                </label>
                                <div class="select-wrapper">
                                    <select name="shipping_method" id="shipping_method"
                                        class="form-control-modern @error('shipping_method') is-invalid @enderror">
                                        @foreach ($shippingMethods as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('shipping_method') == $key ? 'selected' : ($key == 'transport' ? 'selected' : '') }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down select-icon"></i>
                                </div>
                                @error('shipping_method')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group" id="transport_company_wrap" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-building me-2"></i>Transport Company
                                </label>
                                <div class="select-wrapper">
                                    <select name="transport_company_id" id="transport_company_id"
                                        class="form-control-modern @error('transport_company_id') is-invalid @enderror">
                                        <option value="">-- Select Company --</option>
                                        @foreach ($transports as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('transport_company_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down select-icon"></i>
                                </div>
                                @error('transport_company_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Transport Name -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-truck-moving me-2"></i>Transport Name (Optional)
                            </label>
                            <input type="text" name="transport_name" value="{{ old('transport_name') }}"
                                class="form-control-modern @error('transport_name') is-invalid @enderror"
                                placeholder="e.g., S. Alam, Hanif, etc.">
                            @error('transport_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-credit-card me-2"></i>Payment Method
                            </label>
                            <div class="payment-methods">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cod"
                                        {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                                    <div class="payment-card">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <div>
                                            <h4>Cash on Delivery</h4>
                                            <p>Pay when you receive</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                        {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                    <div class="payment-card">
                                        <i class="fas fa-university"></i>
                                        <div>
                                            <h4>Bank Transfer</h4>
                                            <p>Pay via bank account</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Terms & Notes -->
                        <div class="form-group full-width">
                            <label class="checkbox-container">
                                <input type="checkbox" name="terms_accepted"
                                    class="form-checkbox @error('terms_accepted') is-invalid @enderror"
                                    {{ old('terms_accepted') ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                <span class="checkbox-label">
                                    I accept the <a href="#" class="terms-link">terms and conditions</a>
                                </span>
                            </label>
                            @error('terms_accepted')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-pen me-2"></i>Additional Notes (Optional)
                            </label>
                            <textarea name="customer_notes" class="form-control-modern" rows="2"
                                placeholder="Any special instructions...">{{ old('customer_notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-check-circle me-2"></i>
                            Place Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="order-summary-section">
                <div class="section-card summary-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h3>Order Summary</h3>
                            <p>{{ count($cartItems) }} items in cart</p>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items">
                        @foreach ($cartItems as $item)
                            <div class="cart-item">
                                <div class="item-image">
                                    @php
                                        $img =
                                            $item->product && $item->product->images->count()
                                                ? $item->product->images->first()->image_url ??
                                                    asset('storage/' . $item->product->images->first()->image_path)
                                                : 'https://via.placeholder.com/60x60';
                                    @endphp
                                    <img src="{{ $img }}" alt="{{ $item->product->name ?? 'Product' }}">
                                    <span class="item-quantity">{{ $item->quantity }}</span>
                                </div>
                                <div class="item-details">
                                    <h4>{{ $item->product->name ?? 'Product' }}</h4>
                                    @if (!empty($item->attributes))
                                        <div class="item-attributes">
                                            @foreach ($item->attributes as $key => $value)
                                                <span class="attribute-badge">
                                                    <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="item-price">
                                        {{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="item-total">
                                    {{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Subtotal</span>
                            <strong>{{ number_format($subtotal, 2) }}</strong>
                        </div>

                        <div class="price-row discount">
                            <span>Discount</span>
                            <strong>-{{ number_format($discount, 2) }}</strong>
                        </div>

                        <!-- Tax Breakdown -->
                        <div class="price-row tax-row">
                            <span>
                                VAT
                                <small
                                    class="text-muted">{{ ($taxSummary['vat_amount'] ?? 0) > 0 ? '(Will be added)' : '(Included)' }}</small>
                            </span>
                            <strong>{{ number_format($taxSummary['vat_amount'] ?? 0, 2) }}</strong>
                        </div>

                        <div class="price-row tax-row">
                            <span>
                                AIT
                                <small
                                    class="text-muted">{{ ($taxSummary['ait_amount'] ?? 0) > 0 ? '(Will be added)' : '(Included)' }}</small>
                            </span>
                            <strong>{{ number_format($taxSummary['ait_amount'] ?? 0, 2) }}</strong>
                        </div>

                        <div class="price-row">
                            <span>Tax Total</span>
                            <strong>{{ number_format($tax, 2) }}</strong>
                        </div>

                        <div class="price-row shipping-row" id="shipping_row">
                            <span>
                                Shipping
                                <small class="text-muted" id="shipping_note"></small>
                            </span>
                            <strong id="shipping_amount_display">{{ number_format($shipping, 2) }}</strong>
                        </div>

                        <div class="shipping-details" id="shipping_breakdown">
                            @if (isset($detailed) && is_array($detailed))
                                @if (!empty($detailed['breakdown']))
                                    <div class="shipping-breakdown">
                                        @foreach ($detailed['breakdown'] as $row)
                                            <div class="breakdown-item">
                                                <span>{{ $row['quantity'] }} × {{ $row['package_type'] }}</span>
                                                <span>{{ number_format($row['cost'], 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if (isset($detailed['shop_to_transport']))
                                    <div class="shop-transport-fee">
                                        <span>Shop → Transport</span>
                                        <span>{{ number_format($detailed['shop_to_transport'], 2) }}</span>
                                    </div>
                                @endif
                                @if (!empty($detailed['note']))
                                    <div class="shipping-note">
                                        <i class="fas fa-info-circle"></i>
                                        {{ $detailed['note'] }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="price-row total-row">
                            <span>Total</span>
                            <strong class="total-amount">{{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    <!-- Secure Checkout Badge -->
                    <div class="secure-checkout-badge">
                        <i class="fas fa-lock"></i>
                        <span>Secure Checkout</span>
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Modern Checkout CSS */
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --border-radius: 16px;
            --border-radius-sm: 12px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        }

        /* * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }

                    body {
                        background: linear-gradient(135deg, #f1f5f9 0%, #e6eef9 100%);
                        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                        min-height: 100vh;
                    } */

        .checkout-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .checkout-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkout-subtitle {
            color: var(--gray-500);
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* Modern Alerts */
        .alert-modern {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-sm);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            animation: slideDown 0.3s ease;
            border: 1px solid transparent;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .alert-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Grid Layout */
        .checkout-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 2rem;
        }

        /* Section Cards */
        .section-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--gray-200);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray-100);
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-light), #fff);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        .section-header h3 {
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .section-header p {
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-label i {
            color: var(--primary-color);
            width: 20px;
        }

        .form-control-modern {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            background: white;
            transition: all 0.3s;
            color: var(--gray-800);
        }

        .form-control-modern:hover {
            border-color: var(--gray-300);
        }

        .form-control-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .form-control-modern.is-invalid {
            border-color: var(--danger-color);
            background: #fef2f2;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Dropdown Custom */
        .dropdown-custom {
            position: relative;
        }

        .dropdown-toggle {
            cursor: pointer;
            /* background: white; */
            padding-right: 2.5rem;
        }

        .dropdown-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
            transition: transform 0.3s;
        }

        .dropdown-custom.show .dropdown-icon {
            transform: translateY(-50%) rotate(180deg);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            box-shadow: var(--shadow-xl);
            z-index: 1000;
            margin-top: 0.5rem;
            max-height: 350px;
            overflow: hidden;
            display: none;
        }

        .dropdown-custom.show .dropdown-menu-custom {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-search {
            position: relative;
            padding: 0.75rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .dropdown-search i {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        .dropdown-search input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            font-size: 0.95rem;
        }

        .dropdown-search input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .dropdown-items {
            max-height: 250px;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .dropdown-item-custom i {
            color: var(--gray-400);
            font-size: 0.9rem;
            width: 20px;
        }

        .dropdown-item-custom:hover {
            background: var(--gray-50);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .dropdown-item-custom:hover i {
            color: var(--primary-color);
        }

        /* Other Input */
        .other-input {
            margin-top: 1rem;
            animation: slideDown 0.3s;
        }

        /* Payment Methods */
        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .payment-option {
            cursor: pointer;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-card {
            padding: 1.25rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s;
            background: white;
        }

        .payment-card i {
            font-size: 1.5rem;
            color: var(--gray-400);
            transition: color 0.3s;
        }

        .payment-card h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.25rem;
        }

        .payment-card p {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin: 0;
        }

        .payment-option input[type="radio"]:checked+.payment-card {
            border-color: var(--primary-color);
            background: var(--primary-light);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }

        .payment-option input[type="radio"]:checked+.payment-card i {
            color: var(--primary-color);
        }

        /* Select Wrapper */
        .select-wrapper {
            position: relative;
        }

        .select-wrapper select {
            appearance: none;
            padding-right: 2.5rem;
        }

        .select-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
        }

        /* Checkbox Custom */
        .checkbox-container {
            display: flex;
            align-items: center;
            position: relative;
            padding-left: 2rem;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            left: 0;
            height: 20px;
            width: 20px;
            background: white;
            border: 2px solid var(--gray-300);
            border-radius: 6px;
            transition: all 0.2s;
        }

        .checkbox-container:hover input~.checkmark {
            border-color: var(--primary-color);
        }

        .checkbox-container input:checked~.checkmark {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .checkmark:after {
            content: '';
            position: absolute;
            display: none;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .checkbox-container input:checked~.checkmark:after {
            display: block;
        }

        .checkbox-label {
            font-size: 0.95rem;
            color: var(--gray-600);
        }

        .terms-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--border-radius-sm);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 2rem;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Order Summary Styles */
        .summary-card {
            position: sticky;
            top: 2rem;
        }

        .cart-items {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 1.5rem;
            padding-right: 0.5rem;
        }

        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 10px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 10px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
            transition: background 0.3s;
        }

        .cart-item:hover {
            background: var(--gray-50);
        }

        .item-image {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 10px;
            /* overflow: hidden; */
            box-shadow: var(--shadow-sm);
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .item-quantity {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-color);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .item-details h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .item-attributes {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .attribute-badge {
            background: var(--gray-100);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            color: var(--gray-600);
        }

        .item-price {
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .item-total {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1rem;
        }

        /* Price Breakdown */
        .price-breakdown {
            border-top: 2px dashed var(--gray-200);
            padding-top: 1.5rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            font-size: 1rem;
        }

        .price-row.discount strong {
            color: var(--success-color);
        }

        .price-row.tax-row {
            color: var(--gray-600);
            font-size: 0.95rem;
        }

        .price-row.shipping-row {
            border-top: 1px solid var(--gray-100);
            padding-top: 1rem;
        }

        .price-row.total-row {
            border-top: 2px solid var(--gray-200);
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .total-amount {
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .shipping-details {
            background: var(--gray-50);
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            margin: 1rem 0;
        }

        .shipping-breakdown {
            margin-bottom: 0.5rem;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--gray-600);
            padding: 0.25rem 0;
        }

        .shop-transport-fee {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--gray-600);
            padding: 0.25rem 0;
            border-top: 1px solid var(--gray-200);
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }

        .shipping-note {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff3cd;
            color: #856404;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Secure Checkout Badge */
        .secure-checkout-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            padding: 0.75rem;
            background: var(--gray-50);
            border-radius: var(--border-radius-sm);
            color: var(--gray-600);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .secure-checkout-badge i {
            color: var(--primary-color);
        }

        /* Loading Spinner */
        .loading-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem;
            color: var(--gray-500);
        }

        .spinner-small {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-200);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .checkout-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .summary-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .checkout-container {
                padding: 0 1rem;
                margin: 1rem auto;
            }

            .checkout-title {
                font-size: 2rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .payment-methods {
                grid-template-columns: 1fr;
            }

            .section-card {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .checkout-title {
                font-size: 1.5rem;
            }

            .cart-item {
                grid-template-columns: auto 1fr;
                gap: 0.75rem;
            }

            .item-total {
                grid-column: 1 / -1;
                text-align: right;
                padding-left: 70px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        (function() {
            function initShippingDropdowns() {
                function setDistrict(value, display) {
                    $('#shipping_district_input').val(value);
                    $('#shipping_district_display').val(display || value);
                }

                function setUpazila(value, display) {
                    $('#shipping_upazila_input').val(value);
                    $('#shipping_upazila_display').val(display || value);
                }

                // Toggle dropdowns
                $('.dropdown-toggle').on('click', function(e) {
                    e.preventDefault();
                    const parent = $(this).closest('.dropdown-custom');
                    $('.dropdown-custom').not(parent).removeClass('show');
                    parent.toggleClass('show');
                });

                // Close dropdowns when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.dropdown-custom').length) {
                        $('.dropdown-custom').removeClass('show');
                    }
                });

                // Shipping method toggle
                function updateShippingMethodUI() {
                    var method = $('#shipping_method').val();
                    if (method === 'transport') {
                        $('#transport_company_wrap').slideDown(300);
                    } else {
                        $('#transport_company_wrap').slideUp(300);
                    }
                }

                $('#shipping_method').on('change', function() {
                    updateShippingMethodUI();
                    fetchEstimate();
                });

                $('#transport_company_id').on('change', function() {
                    fetchEstimate();
                });

                // Initialize
                updateShippingMethodUI();

                // Fetch shipping estimate
                function fetchEstimate() {
                    var district = $('#shipping_district_input').val();
                    var upazila = $('#shipping_upazila_input').val();
                    var method = $('#shipping_method').val();
                    var transportId = $('#transport_company_id').val();

                    if (!district || !upazila) return;

                    $('#shipping_amount_display').text('...');
                    $('#shipping_breakdown').html(`
                    <div class="loading-indicator">
                        <div class="spinner-small"></div>
                        Calculating shipping...
                    </div>
                `);

                    $.getJSON('{{ route('shipping.estimate') }}', {
                            district: district,
                            upazila: upazila,
                            method: method,
                            transport_company_id: transportId
                        })
                        .done(function(data) {
                            if (data && data.total !== undefined) {
                                $('#shipping_amount_display').text(parseFloat(data.total || 0).toFixed(2));

                                // Update breakdown
                                var html = '';
                                if (data.breakdown && data.breakdown.length) {
                                    html += '<div class="shipping-breakdown">';
                                    data.breakdown.forEach(function(r) {
                                        html += '<div class="breakdown-item">' +
                                            '<span>' + r.quantity + ' × ' + r.package_type + '</span>' +
                                            '<span>' + parseFloat(r.cost).toFixed(2) + '</span>' +
                                            '</div>';
                                    });
                                    html += '</div>';
                                }
                                if (data.shop_to_transport) {
                                    html += '<div class="shop-transport-fee">' +
                                        '<span>Shop → Transport</span>' +
                                        '<span>' + parseFloat(data.shop_to_transport).toFixed(2) + '</span>' +
                                        '</div>';
                                }
                                if (data.note) {
                                    html += '<div class="shipping-note">' +
                                        '<i class="fas fa-info-circle"></i>' +
                                        data.note +
                                        '</div>';
                                }

                                if (html) {
                                    $('#shipping_breakdown').html(html);
                                } else {
                                    $('#shipping_breakdown').empty();
                                }
                            }
                        })
                        .fail(function() {
                            $('#shipping_amount_display').text('—');
                            $('#shipping_breakdown').html(`
                        <div class="shipping-note" style="background:#fef2f2; color:#991b1b;">
                            <i class="fas fa-exclamation-circle"></i>
                            Unable to calculate shipping
                        </div>
                    `);
                        });
                }

                // Trigger estimate when district/upazila changes
                $(document).on('change', '#shipping_district_input, #shipping_upazila_input', function() {
                    fetchEstimate();
                });

                // Initial fetch if values exist
                setTimeout(function() {
                    fetchEstimate();
                }, 200);

                // District selection
                $(document).on('click', '.shipping-district-item', function(e) {
                    e.preventDefault();
                    var val = $(this).data('value');

                    if (val === '__other__') {
                        $('#shipping_district_other_wrap').slideDown(300);
                        $('#shipping_district_other').focus();
                        setDistrict($('#shipping_district_other').val() || '');

                        $('#shipping_upazila_list').empty();
                        setUpazila('');
                        $('#shipping_upazila_other_wrap').slideDown(300);
                    } else {
                        $('#shipping_district_other_wrap').slideUp(300);
                        setDistrict(val, val);
                        loadUpazilas(val, $('#shipping_upazila_input').val());
                    }

                    $('.dropdown-custom').removeClass('show');
                });

                // District search
                $('#shipping_district_search').on('keyup', function() {
                    var q = $(this).val().toLowerCase();
                    $('#shipping_district_list a').each(function() {
                        $(this).toggle($(this).text().toLowerCase().includes(q));
                    });
                });

                // Upazila click
                $(document).on('click', '.shipping-upazila-item', function(e) {
                    e.preventDefault();
                    var val = $(this).data('value');

                    if (val === '__other__') {
                        $('#shipping_upazila_other_wrap').slideDown(300);
                        $('#shipping_upazila_other').focus();
                        setUpazila($('#shipping_upazila_other').val() || '');
                    } else {
                        $('#shipping_upazila_other_wrap').slideUp(300);
                        setUpazila(val, val);
                    }

                    $('.dropdown-custom').removeClass('show');
                });

                // Other inputs
                $('#shipping_district_other').on('input', function() {
                    $('#shipping_district_input').val(this.value);
                });

                $('#shipping_upazila_other').on('input', function() {
                    $('#shipping_upazila_input').val(this.value);
                });

                function loadUpazilas(district, selected) {
                    if (!district) return;

                    $('#shipping_upazila_loading').show();
                    $('#shipping_upazila_list').empty();

                    // Simulate loading (replace with actual AJAX call if needed)
                    setTimeout(function() {
                        var locations = @json(config('locations', []));
                        var upazilas = locations[district] || [];

                        $('#shipping_upazila_loading').hide();
                        $('#shipping_upazila_list').empty();

                        if (upazilas.length) {
                            upazilas.forEach(function(up) {
                                $('#shipping_upazila_list').append(
                                    `<a href="#" class="dropdown-item-custom shipping-upazila-item" data-value="${up}">
                                    <i class="fas fa-location-dot"></i>
                                    ${up}
                                </a>`
                                );
                            });
                        }

                        $('#shipping_upazila_list').append(
                            `<a href="#" class="dropdown-item-custom shipping-upazila-item" data-value="__other__">
                            <i class="fas fa-plus-circle"></i>
                            Other (not listed)
                        </a>`
                        );

                        if (selected) setUpazila(selected, selected);
                    }, 300);
                }

                // Initial load
                var initialDistrict = $('#shipping_district_input').val();
                var initialUpazila = $('#shipping_upazila_input').val();

                if (initialDistrict) {
                    $('#shipping_district_display').val(initialDistrict);
                    loadUpazilas(initialDistrict, initialUpazila);
                }
            }

            // Wait for jQuery
            if (window.jQuery) {
                $(document).ready(function() {
                    initShippingDropdowns();
                });
            } else {
                var wait = setInterval(function() {
                    if (window.jQuery) {
                        clearInterval(wait);
                        $(document).ready(function() {
                            initShippingDropdowns();
                        });
                    }
                }, 100);
            }

            // Auto-hide alerts
            setTimeout(function() {
                $('.alert-modern').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
        })();
    </script>
@endsection
