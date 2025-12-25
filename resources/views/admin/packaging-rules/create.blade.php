@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Create Packaging Rule</h3>
        <form method="POST" action="{{ route('admin.packaging-rules.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Product</label>
                <select name="product_id" class="form-select" required>
                    @foreach ($products as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Unit Name (shipping unit)</label>
                <input type="text" name="unit_name" class="form-control" placeholder="Cartoon, Roll, Loose" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Units per (sales unit)</label>
                <input type="number" step="0.0001" name="units_per" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" checked>
                <label class="form-check-label">Active</label>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
