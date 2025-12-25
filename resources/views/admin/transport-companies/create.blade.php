@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Create Transport Company</h3>
        <form method="POST" action="{{ route('admin.transport-companies.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" checked>
                <label class="form-check-label">Active</label>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
