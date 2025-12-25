@extends('admin.layouts.master')

@section('title', 'Delivery Charges')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.delivery-charges.create') }}" class="btn btn-primary">Create Delivery Charge</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>District</th>
                <th>Upazila</th>
                <th>Charge</th>
                <th>Estimated days</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deliveryCharges as $d)
                <tr>
                    <td>{{ $d->district }}</td>
                    <td>{{ $d->upazila }}</td>
                    <td>{{ number_format($d->charge, 2) }}</td>
                    <td>{{ $d->estimated_days }}</td>
                    <td>{{ $d->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.delivery-charges.edit', $d->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('admin.delivery-charges.destroy', $d->id) }}" method="POST"
                            style="display:inline">@csrf @method('DELETE') <button
                                class="btn btn-sm btn-danger confirm-delete">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $deliveryCharges->links() }}
@endsection
