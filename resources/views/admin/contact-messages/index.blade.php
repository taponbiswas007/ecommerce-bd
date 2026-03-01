@extends('admin.layouts.master')

@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')
@section('page-subtitle', 'View and manage customer inquiries')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Contact Messages</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <span class="badge bg-info me-2">{{ $messages->total() }}</span> Messages
                    </h5>
                    <div>
                        <span
                            class="badge bg-success me-2">{{ \App\Models\ContactMessage::where('is_read', false)->count() }}
                            Unread</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;"></th>
                                        <th>From</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message Preview</th>
                                        <th>Date</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages as $message)
                                        <tr class="{{ !$message->is_read ? 'table-active' : '' }}">
                                            <td>
                                                @if (!$message->is_read)
                                                    <span class="badge bg-danger">New</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary p-2 me-2"
                                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <span
                                                            class="text-white small fw-bold">{{ $message->getInitials() }}</span>
                                                    </div>
                                                    <strong>{{ $message->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($message->subject, 50) }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($message->message, 60) }}</small>
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ $message->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.contact-messages.show', $message->id) }}"
                                                    class="btn btn-primary btn-sm me-1" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}"
                                                    method="POST" class="d-inline confirm-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No messages yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Delete confirmation
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
