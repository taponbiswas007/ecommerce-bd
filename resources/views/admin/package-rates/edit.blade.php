@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Edit Package Rate</h3>
        <form method="POST" action="{{ route('admin.package-rates.update', $rate) }}">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label class="form-label">Transport Company</label>
                <select name="transport_company_id" class="form-select" required>
                    @foreach ($companies as $id => $name)
                        <option value="{{ $id }}" {{ $rate->transport_company_id == $id ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Package Type</label>
                <input type="text" name="package_type" class="form-control" value="{{ $rate->package_type }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">District (optional)</label>
                <input type="text" name="district" class="form-control" value="{{ $rate->district }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Upazila (optional)</label>
                <input type="text" name="upazila" class="form-control" value="{{ $rate->upazila }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Rate</label>
                <input type="number" step="0.01" name="rate" class="form-control" value="{{ $rate->rate }}"
                    required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" {{ $rate->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
