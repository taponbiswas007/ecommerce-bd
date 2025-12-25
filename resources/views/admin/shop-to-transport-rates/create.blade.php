@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Add Shop to Transport Rate</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.shop-to-transport-rates.store') }}">
                            @csrf
                            @include('admin.shop-to-transport-rates._form')
                            <a href="{{ route('admin.shop-to-transport-rates.index') }}"
                                class="btn btn-secondary ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
