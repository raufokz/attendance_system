@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
    .profile-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .profile-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .profile-card-body {
        padding: 1.75rem 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }

    .btn-profile {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .btn-update {
        background-color: #4361ee;
        border-color: #4361ee;
    }

    .btn-update:hover {
        background-color: #3a56d4;
        border-color: #3a56d4;
        transform: translateY(-1px);
    }

    .btn-change {
        background-color: #2b2d42;
        border-color: #2b2d42;
    }

    .btn-change:hover {
        background-color: #1f2133;
        border-color: #1f2133;
        transform: translateY(-1px);
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 75%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }

    .password-toggle:hover {
        color: #4361ee;
    }

    .password-input-group {
        position: relative;
    }

    .alert-profile {
        border-radius: 8px;
        padding: 1rem 1.25rem;
    }

    /* Profile Picture Styles */
    .profile-picture-wrapper {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-picture-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: #f8f9fa;
        margin: 0 auto 15px;
        overflow: hidden;
        position: relative;
        border: 3px solid #e0e0e0;
        cursor: pointer;
    }

    .profile-picture {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-picture-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
        color: #6c757d;
    }

    .profile-picture-upload {
        display: none;
    }

    .profile-picture-edit {
        position: absolute;
        bottom: 0;
        right: 0;
        background: rgba(0,0,0,0.5);
        color: white;
        width: 100%;
        text-align: center;
        padding: 5px;
        font-size: 12px;
    }

    /* Cropper Modal Styles */
    .cropper-modal {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 100%;
    }

    .cropper-container {
        max-height: 300px;
    }

    @media (max-width: 767.98px) {
        .profile-card-body {
            padding: 1.25rem;
        }

        .profile-picture-container {
            width: 120px;
            height: 120px;
        }
    }
</style>
@endsection

@section('breadcrumb')
<div class="col-sm-6 text-left">
    <h4 class="page-title mb-0">Edit Profile</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ auth()->user()->hasRole('admin') ? route('admin') : route('user.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile Settings</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-profile alert-dismissible fade show" role="alert">
                <i class="ti-check mr-2"></i> Profile updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="ti-close"></i>

                </button>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-profile alert-dismissible fade show" role="alert">
                <i class="ti-check mr-2"></i> Password updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
        @endif

        @if (session('status') === 'profile-picture-updated')
            <div class="alert alert-success alert-profile alert-dismissible fade show" role="alert">
                <i class="ti-check mr-2"></i> Profile picture updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="ti-close"></i>

                </button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-12 mb-4">
        <div class="profile-card h-100">
            <div class="profile-card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ti-camera mr-2"></i> Profile Picture</h5>
            </div>
            <div class="profile-card-body">
                <form method="POST" action="{{ route('profile.picture') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="profile-picture-wrapper">
                        <div class="profile-picture-container" onclick="document.getElementById('profile_picture').click()">
                            @if($user->profile_picture)
                                <img src="{{ Storage::url($user->profile_picture) }}" class="profile-picture" id="profile_preview">
                            @else
                                <div class="profile-picture-placeholder" id="profile_placeholder">
                                    <i class="ti-user fa-3x"></i>
                                </div>
                                <img class="profile-picture" id="profile_preview" style="display: none;">
                            @endif
                            <div class="profile-picture-edit">Click to change</div>
                        </div>
                        <input type="file" id="profile_picture" name="profile_picture" class="profile-picture-upload" accept="image/*">
                        @error('profile_picture')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-update btn-block">
                            <i class="ti-save mr-1"></i> Update Picture
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-4 col-md-12 mb-4">
        <div class="profile-card h-100">
            <div class="profile-card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ti-user mr-2"></i> Personal Information</h5>
            </div>
            <div class="profile-card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" name="name" type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" name="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-update btn-block ">
                            <i class="ti-save mr-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-4 col-md-12 mb-4">
        <div class="profile-card">
            <div class="profile-card-header bg-warning text-white">
                <h5 class="mb-0"><i class="ti-lock mr-2"></i> Change Password</h5>
            </div>
            <div class="profile-card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4 password-input-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input id="current_password" name="current_password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        <i class="ti-eye password-toggle" onclick="togglePassword('current_password')"></i>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 password-input-group">
                        <label for="password" class="form-label">New Password</label>
                        <input id="password" name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        <i class="ti-eye password-toggle" onclick="togglePassword('password')"></i>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 password-input-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control" required>
                        <i class="ti-eye password-toggle" onclick="togglePassword('password_confirmation')"></i>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-dark btn-change">
                            <i class="ti-key mr-1"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content cropper-modal">
            <div class="modal-header">
                <h5 class="modal-title">Crop Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="image" src="" alt="" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    // Password toggle functionality
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('ti-eye');
            icon.classList.add('ti-close');
        } else {
            input.type = 'password';
            icon.classList.remove('ti-close');
            icon.classList.add('ti-eye');
        }
    }

    // Profile picture preview and cropping
    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureInput = document.getElementById('profile_picture');

        if (profilePictureInput) {
            profilePictureInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        // Show the cropper modal
                        $('#cropperModal').modal('show');
                        $('#image').attr('src', event.target.result);

                        // Initialize cropper
                        const image = document.getElementById('image');
                        const cropper = new Cropper(image, {
                            aspectRatio: 1,
                            viewMode: 1,
                            autoCropArea: 1,
                        });

                        // On crop button click
                        document.getElementById('crop').addEventListener('click', function() {
                            // Get cropped canvas
                            const canvas = cropper.getCroppedCanvas({
                                width: 400,
                                height: 400,
                                minWidth: 256,
                                minHeight: 256,
                                maxWidth: 1024,
                                maxHeight: 1024,
                                fillColor: '#fff',
                                imageSmoothingEnabled: true,
                                imageSmoothingQuality: 'high',
                            });

                            // Convert canvas to blob
                            canvas.toBlob(function(blob) {
                                // Create a new file from the blob
                                const file = new File([blob], 'profile_picture.jpg', {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                });

                                // Create a new DataTransfer and add the file
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);

                                // Update the file input
                                document.getElementById('profile_picture').files = dataTransfer.files;

                                // Update the preview
                                const url = URL.createObjectURL(blob);
                                document.getElementById('profile_preview').src = url;
                                document.getElementById('profile_preview').style.display = 'block';
                                document.getElementById('profile_placeholder').style.display = 'none';

                                // Hide the modal
                                $('#cropperModal').modal('hide');
                                cropper.destroy();
                            }, 'image/jpeg', 0.9);
                        });
                    };

                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection
