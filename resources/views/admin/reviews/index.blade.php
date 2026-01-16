@extends('admin.layouts.master')

@section('title', 'Product Reviews')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Product Reviews</h1>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>
                                    <a href="{{ route('admin.products.show', $review->product_id) }}" target="_blank">
                                        {{ $review->product->name ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $review->user->name ?? ($review->name ?? 'Guest') }}</td>
                                <td>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                </td>
                                <td>{!! nl2br(e($review->comment)) !!}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $review->status == 'approved' ? 'success' : ($review->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td>{{ $review->created_at->diffForHumans() }}</td>
                                <td>
                                    @if ($review->status == 'pending')
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm" title="Approve"><i
                                                    class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-danger btn-sm" title="Reject"><i
                                                    class="fas fa-times"></i></button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Delete"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $reviews->links() }}</div>
            </div>
        </div>
    </div>
@endsection
