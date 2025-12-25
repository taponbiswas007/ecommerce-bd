@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Packaging Rules</h3>
        <a href="{{ route('admin.packaging-rules.create') }}" class="btn btn-primary mb-3">Create</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit</th>
                    <th>Units per</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rules as $r)
                    <tr>
                        <td>{{ $r->product->name ?? '' }}</td>
                        <td>{{ $r->unit_name }}</td>
                        <td>{{ $r->units_per }}</td>
                        <td>{{ $r->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.packaging-rules.edit', $r) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('admin.packaging-rules.destroy', $r) }}" method="POST"
                                style="display:inline">@csrf @method('DELETE')<button
                                    class="btn btn-sm btn-danger">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $rules->links() }}
    </div>
@endsection
