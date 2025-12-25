@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Package Rates</h3>
        <a href="{{ route('admin.package-rates.create') }}" class="btn btn-primary mb-3">Create</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Package</th>
                    <th>District</th>
                    <th>Upazila</th>
                    <th>Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rates as $r)
                    <tr>
                        <td>{{ $r->transportCompany->name ?? '' }}</td>
                        <td>{{ $r->package_type }}</td>
                        <td>{{ $r->district ?? 'Any' }}</td>
                        <td>{{ $r->upazila ?? 'Any' }}</td>
                        <td>{{ number_format($r->rate, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.package-rates.edit', $r) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('admin.package-rates.destroy', $r) }}" method="POST"
                                style="display:inline">@csrf @method('DELETE')<button
                                    class="btn btn-sm btn-danger">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $rates->links() }}
    </div>
@endsection
