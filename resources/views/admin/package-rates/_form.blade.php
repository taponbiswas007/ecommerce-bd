@php
    $districts = array_keys(config('locations', []));
@endphp

<div class="mb-3">
    <label class="form-label">Transport Company</label>
    <select name="transport_company_id" class="form-select @error('transport_company_id') is-invalid @enderror" required>
        <option value="">-- Select Company --</option>
        @foreach ($companies as $id => $name)
            <option value="{{ $id }}"
                {{ old('transport_company_id', $packageRate->transport_company_id ?? '') == $id ? 'selected' : '' }}>
                {{ $name }}</option>
        @endforeach
    </select>
    @error('transport_company_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Package Type</label>
    <select id="package_type" name="package_type" class="form-select @error('package_type') is-invalid @enderror"
        required>
        <option value="">-- Select Package Type --</option>
        @foreach ($packageTypes ?? [] as $type)
            <option value="{{ $type }}"
                {{ old('package_type', $packageRate->package_type ?? '') == $type ? 'selected' : '' }}>
                {{ $type }}</option>
        @endforeach
    </select>
    @error('package_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">District (optional)</label>
    <input type="hidden" name="district" id="district_input"
        value="{{ old('district', $packageRate->district ?? '') }}">

    <div class="dropdown">
        <input id="district_display" type="text" class="form-control @error('district') is-invalid @enderror"
            data-bs-toggle="dropdown" aria-expanded="false" readonly
            value="{{ old('district', $packageRate->district ?? '') }}" placeholder="Leave empty for global rate">
        <div class="dropdown-menu p-2" id="district_menu"
            style="max-height: 300px; overflow-y: auto; min-width: 300px;">
            <input type="text" id="district_search" class="form-control mb-2" placeholder="Search district...">
            <div id="district_list">
                <a href="#" class="dropdown-item district-item" data-value="">-- Global (no district) --</a>
                @foreach ($districts as $district)
                    <a href="#" class="dropdown-item district-item"
                        data-value="{{ $district }}">{{ $district }}</a>
                @endforeach
            </div>
        </div>
    </div>
    @error('district')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Upazila (optional)</label>
    <input type="hidden" name="upazila" id="upazila_input" value="{{ old('upazila', $packageRate->upazila ?? '') }}">

    <div class="dropdown">
        <input id="upazila_display" type="text" class="form-control @error('upazila') is-invalid @enderror"
            data-bs-toggle="dropdown" aria-expanded="false" readonly
            value="{{ old('upazila', $packageRate->upazila ?? '') }}" placeholder="Leave empty for district rate">
        <div class="dropdown-menu p-2" id="upazila_menu" style="max-height: 300px; overflow-y: auto; min-width: 300px;">
            <input type="text" id="upazila_search" class="form-control mb-2" placeholder="Search upazila...">
            <div id="upazila_list">
                <a href="#" class="dropdown-item upazila-item" data-value="">-- Use district rate --</a>
                @if (isset($packageRate) && $packageRate->district)
                    @php $upazilas = config('locations')[$packageRate->district] ?? [] @endphp
                    @foreach ($upazilas as $up)
                        <a href="#" class="dropdown-item upazila-item"
                            data-value="{{ $up }}">{{ $up }}</a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @error('upazila')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Rate (TK) per Package</label>
    <input type="number" step="0.01" name="rate" value="{{ old('rate', $packageRate->rate ?? '') }}"
        class="form-control @error('rate') is-invalid @enderror" required>
    @error('rate')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
        {{ old('is_active', $packageRate->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">Save</button>

<script>
    // District dropdown search
    document.getElementById('district_search').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        document.querySelectorAll('#district_list .district-item').forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(searchValue) ? 'block' :
                'none';
        });
    });

    // District item click
    document.querySelectorAll('.district-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const value = this.getAttribute('data-value');
            document.getElementById('district_input').value = value;
            document.getElementById('district_display').value = value || '-- Global (no district) --';
            document.getElementById('district_search').value = '';
            document.querySelectorAll('#district_list .district-item').forEach(i => i.style.display =
                'block');

            // Reset upazila
            document.getElementById('upazila_input').value = '';
            document.getElementById('upazila_display').value = '';
            document.getElementById('upazila_search').value = '';

            // Load upazilas for this district
            const upazilaList = document.getElementById('upazila_list');
            const locations = @json(config('locations', []));
            const upazilas = locations[value] || [];

            upazilaList.innerHTML =
                '<a href="#" class="dropdown-item upazila-item" data-value="">-- Use district rate --</a>';
            upazilas.forEach(upazila => {
                const link = document.createElement('a');
                link.href = '#';
                link.className = 'dropdown-item upazila-item';
                link.setAttribute('data-value', upazila);
                link.textContent = upazila;
                link.addEventListener('click', selectUpazila);
                upazilaList.appendChild(link);
            });

            const dropdown = bootstrap.Dropdown.getInstance(document.getElementById(
                'district_display'));
            if (dropdown) dropdown.hide();
        });
    });

    // Upazila dropdown search
    document.getElementById('upazila_search').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        document.querySelectorAll('#upazila_list .upazila-item').forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(searchValue) ? 'block' :
                'none';
        });
    });

    // Upazila item click
    function selectUpazila(e) {
        e.preventDefault();
        const value = this.getAttribute('data-value');
        document.getElementById('upazila_input').value = value;
        document.getElementById('upazila_display').value = value || '-- Use district rate --';
        document.getElementById('upazila_search').value = '';
        document.querySelectorAll('#upazila_list .upazila-item').forEach(i => i.style.display = 'block');

        const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('upazila_display'));
        if (dropdown) dropdown.hide();
    }

    document.querySelectorAll('.upazila-item').forEach(item => {
        item.addEventListener('click', selectUpazila);
    });
</script>

<!-- Add select2 for searchable dropdown -->
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#package_type').select2({
                placeholder: '-- Select Package Type --',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
