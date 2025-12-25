@php
    $districts = array_keys(config('locations', []));
@endphp

<div class="mb-3">
    <label class="form-label">Package Type</label>
    <select name="package_type" class="form-select @error('package_type') is-invalid @enderror">
        <option value="">-- Select Package Type --</option>
        <option value="Cartoon"
            {{ old('package_type', $shopToTransportRate->package_type ?? '') == 'Cartoon' ? 'selected' : '' }}>Cartoon
        </option>
        <option value="Roll"
            {{ old('package_type', $shopToTransportRate->package_type ?? '') == 'Roll' ? 'selected' : '' }}>Roll
        </option>
        <option value="Loose"
            {{ old('package_type', $shopToTransportRate->package_type ?? '') == 'Loose' ? 'selected' : '' }}>Loose
        </option>
    </select>
    @error('package_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">District</label>
    <input type="hidden" name="district" id="district_input"
        value="{{ old('district', $shopToTransportRate->district ?? '') }}">

    <div class="dropdown">
        <input id="district_display" type="text" class="form-control @error('district') is-invalid @enderror"
            data-bs-toggle="dropdown" aria-expanded="false" readonly
            value="{{ old('district', $shopToTransportRate->district ?? '') }}">
        <div class="dropdown-menu p-2" id="district_menu"
            style="max-height: 300px; overflow-y: auto; min-width: 300px;">
            <input type="text" id="district_search" class="form-control mb-2" placeholder="Search district...">
            <div id="district_list">
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
    <label class="form-label">Upazila</label>
    <input type="hidden" name="upazila" id="upazila_input"
        value="{{ old('upazila', $shopToTransportRate->upazila ?? '') }}">

    <div class="dropdown">
        <input id="upazila_display" type="text" class="form-control @error('upazila') is-invalid @enderror"
            data-bs-toggle="dropdown" aria-expanded="false" readonly
            value="{{ old('upazila', $shopToTransportRate->upazila ?? '') }}">
        <div class="dropdown-menu p-2" id="upazila_menu" style="max-height: 300px; overflow-y: auto; min-width: 300px;">
            <input type="text" id="upazila_search" class="form-control mb-2" placeholder="Search upazila...">
            <div id="upazila_list">
                @if (isset($shopToTransportRate) && $shopToTransportRate->district)
                    @php $upazilas = config('locations')[$shopToTransportRate->district] ?? [] @endphp
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
    <label class="form-label">Rate (TK)</label>
    <input type="number" step="0.01" name="rate" value="{{ old('rate', $shopToTransportRate->rate ?? '') }}"
        class="form-control @error('rate') is-invalid @enderror">
    @error('rate')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
        {{ old('is_active', $shopToTransportRate->is_active ?? true) ? 'checked' : '' }}>
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
            document.getElementById('district_display').value = value;
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

            upazilaList.innerHTML = '';
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
        document.getElementById('upazila_display').value = value;
        document.getElementById('upazila_search').value = '';
        document.querySelectorAll('#upazila_list .upazila-item').forEach(i => i.style.display = 'block');

        const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('upazila_display'));
        if (dropdown) dropdown.hide();
    }

    document.querySelectorAll('.upazila-item').forEach(item => {
        item.addEventListener('click', selectUpazila);
    });
</script>
