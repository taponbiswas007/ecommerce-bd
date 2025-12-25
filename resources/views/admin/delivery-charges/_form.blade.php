<div class="mb-3">
    <label class="form-label">District</label>
    <input type="hidden" name="district" id="district_input"
        value="{{ old('district', $deliveryCharge->district ?? '') }}">

    <div class="dropdown">
        <input id="district_display" type="text" class="form-control @error('district') is-invalid @enderror"
            data-bs-toggle="dropdown" aria-expanded="false" readonly
            value="{{ old('district', $deliveryCharge->district ?? '') }}">
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
        value="{{ old('upazila', $deliveryCharge->upazila ?? '') }}">

    <div class="dropdown">
        <input id="upazila_display" type="text" class="form-control @error('upazila') is-invalid @enderror"
            data-bs-toggle="dropdown" aria-expanded="false" readonly
            value="{{ old('upazila', $deliveryCharge->upazila ?? '') }}">
        <div class="dropdown-menu p-2" id="upazila_menu" style="max-height: 300px; overflow-y: auto; min-width: 300px;">
            <input type="text" id="upazila_search" class="form-control mb-2" placeholder="Search upazila...">
            <div id="upazila_list">
                @if (isset($deliveryCharge) && $deliveryCharge->district)
                    @php $upazilas = config('locations')[$deliveryCharge->district] ?? [] @endphp
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
    <label class="form-label">Charge</label>
    <input type="number" step="0.01" name="charge" value="{{ old('charge', $deliveryCharge->charge ?? '') }}"
        class="form-control @error('charge') is-invalid @enderror">
    @error('charge')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Estimated days</label>
    <input type="number" name="estimated_days"
        value="{{ old('estimated_days', $deliveryCharge->estimated_days ?? '') }}"
        class="form-control @error('estimated_days') is-invalid @enderror">
    @error('estimated_days')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
        {{ old('is_active', $deliveryCharge->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">Save</button>
