@extends('admin.layouts.master')

@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your admin account information')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profile Settings</li>
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <!-- Left Sidebar - Profile Overview -->
            <div class="col-lg-4 col-md-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <!-- Profile Image -->
                        <div class="position-relative d-inline-block mb-3">
                            <div class="avatar-xxl">
                                @if ($user->user_image)
                                    <img src="{{ asset('storage/' . $user->user_image) }}" alt="Profile Image"
                                        class="img-fluid rounded-circle border border-4 border-white shadow-sm"
                                        style="width: 140px; height: 140px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center"
                                        style="width: 140px; height: 140px;">
                                        <span class="text-white fs-1 fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <label for="user_image"
                                    class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 cursor-pointer">
                                    <i class="fas fa-camera text-primary"></i>
                                </label>
                            </div>
                        </div>

                        <!-- User Info -->
                        <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <!-- Stats -->
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bg-light rounded p-3">
                                    <h6 class="text-muted small mb-1">Role</h6>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">Admin</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3">
                                    <h6 class="text-muted small mb-1">Member Since</h6>
                                    <p class="mb-0 fw-semibold">{{ $user->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Info -->
                        <div class="text-start">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-phone text-muted me-3" style="width: 20px;"></i>
                                <span>{{ $user->phone ?: 'Not set' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-muted me-3" style="width: 20px;"></i>
                                <span>{{ $user->address ?: 'Address not set' }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building text-muted me-3" style="width: 20px;"></i>
                                <span>{{ $user->company_logo ? 'Company logo set' : 'No company logo' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Forms -->
            <div class="col-lg-8 col-md-7">
                <!-- Profile Update Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom px-4 py-3">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-user-edit text-primary me-2"></i>
                            Profile Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            id="profileForm">
                            @csrf
                            @method('PATCH')

                            <div class="row g-4">
                                <!-- Personal Info Section -->
                                <div class="col-12">
                                    <h6 class="text-muted mb-3 pb-2 border-bottom">Personal Details</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input id="name" name="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required>
                                        <label for="name" class="form-label">Full Name</label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input id="email" name="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        <label for="email" class="form-label">Email Address</label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input id="phone" name="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $user->phone) }}">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Location Section -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-muted mb-3 pb-2 border-bottom">Location Information</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input id="district" name="district" type="text"
                                            class="form-control @error('district') is-invalid @enderror"
                                            value="{{ old('district', $user->district) }}">
                                        <label for="district" class="form-label">District</label>
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input id="upazila" name="upazila" type="text"
                                            class="form-control @error('upazila') is-invalid @enderror"
                                            value="{{ old('upazila', $user->upazila) }}">
                                        <label for="upazila" class="form-label">Upazila</label>
                                        @error('upazila')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror"
                                            style="height: 100px">{{ old('address', $user->address) }}</textarea>
                                        <label for="address" class="form-label">Full Address</label>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File Uploads Section -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-muted mb-3 pb-2 border-bottom">Media Uploads</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-dashed h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                <i class="fas fa-image fa-2x text-muted mb-3"></i>
                                                <h6 class="mb-2">Company Logo</h6>
                                                @if ($user->company_logo)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/' . $user->company_logo) }}"
                                                            alt="Company Logo" class="img-fluid rounded shadow-sm"
                                                            style="max-height: 60px;">
                                                    </div>
                                                @endif
                                            </div>
                                            <input id="company_logo" name="company_logo" type="file"
                                                class="form-control form-control-sm @error('company_logo') is-invalid @enderror"
                                                accept="image/*">
                                            @error('company_logo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-2">Recommended: 200×80px</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-dashed h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                <i class="fas fa-user-circle fa-2x text-muted mb-3"></i>
                                                <h6 class="mb-2">Profile Picture</h6>
                                                @if ($user->user_image)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/' . $user->user_image) }}"
                                                            alt="User Image" class="img-fluid rounded-circle shadow-sm"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                    </div>
                                                @endif
                                            </div>
                                            <input id="user_image" name="user_image" type="file"
                                                class="form-control form-control-sm @error('user_image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('user_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-2">Square image recommended</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-save me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Change Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom px-4 py-3">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-lock text-warning me-2"></i>
                            Change Password
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}" id="passwordForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input id="current_password" name="current_password" type="password"
                                            class="form-control @error('current_password') is-invalid @enderror" required>
                                        <label for="current_password">Current Password</label>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input id="password" name="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        <label for="password">New Password</label>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input id="password_confirmation" name="password_confirmation" type="password"
                                            class="form-control" required>
                                        <label for="password_confirmation">Confirm Password</label>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-key me-2"></i>Update Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone Card -->
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-white border-danger px-4 py-3">
                        <h5 class="mb-0 d-flex align-items-center text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Danger Zone
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-danger border-danger bg-danger bg-opacity-10 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading fw-bold">Warning: Account Deletion</h6>
                                    <p class="mb-2">Once you delete your account, there is no going back. This action is
                                        permanent and cannot be undone.</p>
                                    <ul class="small mb-0">
                                        <li>All your data will be permanently removed</li>
                                        <li>You will lose access to the system</li>
                                        <li>This action cannot be reversed</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                            @csrf
                            @method('DELETE')

                            <div class="row g-3 align-items-end">
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input id="password_delete" name="password" type="password"
                                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                            required placeholder="Enter your password to confirm">
                                        <label for="password_delete">Confirm Your Password</label>
                                        @error('password', 'userDeletion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <button type="button" onclick="confirmDelete()" class="btn btn-danger w-100">
                                        <i class="fas fa-trash-alt me-2"></i>Delete Account
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .border-dashed {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            transition: border-color 0.3s;
        }

        .border-dashed:hover {
            border-color: #0d6efd;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .avatar-xxl {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto;
        }

        .form-floating>.form-control {
            height: calc(3.5rem + 2px);
            min-height: calc(3.5rem + 2px);
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-control:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-floating>label {
            padding: 1rem 0.75rem;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: "This action cannot be undone. All your data will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete my account',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteAccountForm').submit();
                }
            });
        }

        // File input preview
        document.getElementById('user_image').addEventListener('change', function(e) {
            if (e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.avatar-xxl img').src = e.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
@endpush
