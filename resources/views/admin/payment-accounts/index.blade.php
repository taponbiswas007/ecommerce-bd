@extends('admin.layouts.master')

@section('title', 'Payment Accounts')
@section('page-title', 'Payment Accounts')
@section('page-subtitle', 'Manage transfer accounts for customer payments')

@section('content')
    <div class="card border shadow-sm rounded-1">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Accounts</h5>
            <a href="{{ route('admin.payment-accounts.create') }}" class="btn btn-primary btn-sm">Add Account</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Method</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td>{{ strtoupper(str_replace('_', ' ', $account->method)) }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td>{{ $account->account_number }}</td>
                                <td>
                                    <span class="badge {{ $account->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $account->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.payment-accounts.show', $account) }}"
                                        class="btn btn-sm btn-outline-info">View</a>
                                    <a href="{{ route('admin.payment-accounts.edit', $account) }}"
                                        class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="{{ route('admin.payment-accounts.destroy', $account) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this account?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No payment accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">{{ $accounts->links() }}</div>
    </div>
@endsection
