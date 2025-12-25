@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Shop to Transport Rates</h2>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('admin.shop-to-transport-rates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Rate
                </a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Package Type</th>
                                    <th>District</th>
                                    <th>Upazila</th>
                                    <th>Rate (TK)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rates as $rate)
                                    <tr>
                                        <td>{{ $rate->package_type }}</td>
                                        <td>{{ $rate->district }}</td>
                                        <td>{{ $rate->upazila }}</td>
                                        <td>{{ number_format($rate->rate, 2) }}</td>
                                        <td>
                                            @if ($rate->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.shop-to-transport-rates.edit', $rate->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST"
                                                action="{{ route('admin.shop-to-transport-rates.destroy', $rate->id) }}"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No rates found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
