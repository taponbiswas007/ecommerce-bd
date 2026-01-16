@extends('layouts.app')

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
@endsection
