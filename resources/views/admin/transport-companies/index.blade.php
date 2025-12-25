@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <h3>Transport Companies</h3>
        <a href="{{ route('admin.transport-companies.create') }}" class="btn btn-primary mb-3">Create</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->contact }}</td>
                        <td>{{ $c->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.transport-companies.edit', $c) }}"
                                class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('admin.transport-companies.destroy', $c) }}" method="POST"
                                style="display:inline">@csrf @method('DELETE')<button
                                    class="btn btn-sm btn-danger">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $companies->links() }}
    </div>
@endsection
