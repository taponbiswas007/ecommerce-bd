@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Edit Packaging Rule</h3>
        <form method="POST" action="{{ route('admin.packaging-rules.update', $rule) }}">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label class="form-label">Product</label>
                <select name="product_id" class="form-select" required>
                    @foreach ($products as $id => $name)
                        <option value="{{ $id }}" {{ $rule->product_id == $id ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Unit Name</label>
                <input type="text" name="unit_name" class="form-control" value="{{ $rule->unit_name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Units per (sales unit)</label>
                <input type="number" step="0.0001" name="units_per" class="form-control" value="{{ $rule->units_per }}"
                    required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" {{ $rule->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
