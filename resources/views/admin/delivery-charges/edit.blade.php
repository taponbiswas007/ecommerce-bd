@extends('admin.layouts.master')

@section('title', 'Edit Delivery Charge')

@section('content')
    <form action="{{ route('admin.delivery-charges.update', $deliveryCharge->id) }}" method="POST">@csrf
        @method('PUT')
        @include('admin.delivery-charges._form')
    </form>
@endsection

@push('scripts')
    <script>
        $(function() {
            function closeDropdown(el) {
                var inst = bootstrap.Dropdown.getInstance(el);
                if (inst) inst.hide();
            }

            function openDropdown(el) {
                var inst = bootstrap.Dropdown.getOrCreateInstance(el);
                inst.show();
            }

            // Open district dropdown and focus search
            $('#district_display').on('click', function() {
                openDropdown(this);
                $('#district_search').focus();
            });

            // Open upazila dropdown; if no district selected show helper
            $('#upazila_display').on('click', function() {
                var district = $('#district_input').val();
                if (!district) {
                    $('#upazila_list').empty().append(
                        '<div class="px-2 text-muted">Please select a district first</div>');
                    openDropdown(this);
                    return;
                }
                openDropdown(this);
                $('#upazila_search').focus();
            });

            // District search filter
            $('#district_search').on('input', function() {
                var q = $(this).val().toLowerCase();
                $('#district_list .district-item').each(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(q) !== -1);
                });
            });

            // District selection
            $(document).on('click', '.district-item', function(e) {
                e.preventDefault();
                var val = $(this).data('value');
                $('#district_input').val(val);
                $('#district_display').val(val);
                closeDropdown(document.getElementById('district_display'));
                loadUpazilas(val);
            });

            // Upazila search filter (client-side)
            $('#upazila_search').on('input', function() {
                var q = $(this).val().toLowerCase();
                $('#upazila_list .upazila-item').each(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(q) !== -1);
                });
            });

            // Upazila selection
            $(document).on('click', '.upazila-item', function(e) {
                e.preventDefault();
                var val = $(this).data('value');
                $('#upazila_input').val(val);
                $('#upazila_display').val(val);
                closeDropdown(document.getElementById('upazila_display'));
            });

            function loadUpazilas(district, selected) {
                $('#upazila_input').val('');
                $('#upazila_display').val('');
                $('#upazila_list').empty().append(
                    '<div class="px-2 text-muted"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...</div>'
                );

                if (!district) {
                    $('#upazila_list').empty();
                    return;
                }

                $.getJSON('{{ url('/delivery-charges/upazilas') }}', {
                        district: district
                    })
                    .done(function(data) {
                        $('#upazila_list').empty();
                        if (!data || data.length === 0) {
                            $('#upazila_list').append('<div class="px-2 text-muted">No upazilas found</div>');
                            openDropdown(document.getElementById('upazila_display'));
                            return;
                        }
                        $.each(data, function(i, up) {
                            var a = $('<a href="#" class="dropdown-item upazila-item"></a>').attr(
                                'data-value', up).text(up);
                            $('#upazila_list').append(a);
                        });

                        // Open upazila dropdown and focus search
                        openDropdown(document.getElementById('upazila_display'));
                        $('#upazila_search').focus();

                        if (selected) {
                            $('#upazila_list .upazila-item').filter(function() {
                                return $(this).data('value') === selected;
                            }).first().trigger('click');
                        }
                    })
                    .fail(function(jqxhr, status, error) {
                        console.error('Upazilas load failed:', status, error, jqxhr.responseText);
                        var msg = 'Error loading upazilas';
                        if (jqxhr && jqxhr.status) msg += ' (HTTP ' + jqxhr.status + ')';
                        $('#upazila_list').empty().append('<div class="px-2 text-danger">' + msg + '</div>');
                        openDropdown(document.getElementById('upazila_display'));
                    });
            }

            // On page load, if a district is selected, load upazilas
            var initialDistrict = $('#district_input').val();
            var initialUpazila = '{{ old('upazila', $deliveryCharge->upazila ?? '') }}';
            if (initialDistrict) {
                $('#district_display').val(initialDistrict);
                loadUpazilas(initialDistrict, initialUpazila);
            }
        });
    </script>
@endpush
