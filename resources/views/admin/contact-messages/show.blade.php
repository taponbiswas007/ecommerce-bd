@extends('admin.layouts.master')

@section('title', $message->subject)
@section('page-title', 'Message Details')
@section('page-subtitle', $message->subject)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.contact-messages.index') }}">Messages</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $message->subject }}</h5>
                        @if (!$message->is_read)
                            <small class="badge bg-success mt-2">Marked as Read</small>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Sender Info -->
                    <div class="border p-4 rounded mb-4 bg-light">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-muted mb-3">Sender Information</h6>
                                <div class="mb-3">
                                    <strong>Name:</strong>
                                    <p class="mb-0">{{ $message->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Email:</strong>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                    </p>
                                </div>
                                @if ($message->phone)
                                    <div class="mb-3">
                                        <strong>Phone:</strong>
                                        <p class="mb-0">
                                            <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                <div
                                    style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;
                                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                            border-radius: 50%; margin-left: auto;">
                                    <span class="text-white" style="font-size: 24px; font-weight: bold;">
                                        {{ $message->getInitials() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Message</h6>
                        <div class="border p-4 rounded bg-white" style="line-height: 1.8; color: #4a5568;">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>

                    <!-- Message Info -->
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Received:</strong> {{ $message->created_at->format('M d, Y \a\t H:i A') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                @if ($message->is_read)
                                    <strong>Read:</strong> {{ $message->read_at->format('M d, Y \a\t H:i A') }}
                                @else
                                    <strong>Status:</strong> <span class="badge bg-success">Just Read</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <a href="mailto:{{ $message->email }}" class="btn btn-primary">
                        <i class="fas fa-reply me-2"></i> Reply via Email
                    </a>
                    <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST"
                        class="confirm-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.confirm-delete', function(e) {
                e.preventDefault();
                const form = $(this);

                Swal.fire({
                    title: 'Delete Message?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
