@extends('admin.layouts.master')

@section('title', 'Ads')
@section('page-title', 'Ads Management')
@section('page-subtitle', 'Manage homepage ads')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Ads</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Ads List</h4>
            <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">Add New Ad</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Text</th>
                        <th>Badge</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ads as $ad)
                        <tr>
                            <td>{{ $ad->id }}</td>
                            <td>{{ $ad->text }}</td>
                            <td>{{ $ad->badge }}</td>
                            <td>
                                <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this ad?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No ads found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
