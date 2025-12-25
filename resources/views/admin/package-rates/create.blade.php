@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Create Package Rate</h3>
        <form method="POST" action="{{ route('admin.package-rates.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Transport Company</label>
                <select name="transport_company_id" class="form-select" required>
                    @foreach ($companies as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Package Type</label>
                <input type="text" name="package_type" class="form-control" placeholder="Cartoon, Roll, Loose" required>
            </div>
            <div class="mb-3">
                <label class="form-label">District (optional)</label>
                <input type="text" name="district" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Upazila (optional)</label>
                <input type="text" name="upazila" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Rate</label>
                <input type="number" step="0.01" name="rate" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" checked>
                <label class="form-check-label">Active</label>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
