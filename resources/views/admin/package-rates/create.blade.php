@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Add Package Rate</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.package-rates.store') }}">
                            @csrf
                            @include('admin.package-rates._form')
                            <a href="{{ route('admin.package-rates.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
