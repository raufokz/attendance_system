@extends('layouts.master-blank')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
    .auth-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .auth-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 500px;
    }

    .auth-header {
        background: rgba(0, 0, 0, 0.2);
        padding: 30px 20px;
        text-align: center;
        position: relative;
    }

    .auth-logo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    .auth-title {
        color: white;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .auth-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }

    .auth-body {
        background: white;
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
        width: 100%;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }

    .btn-auth {
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        border: none;
        width: 100%;
    }

    .btn-register {
        background: linear-gradient(to right, #667eea, #764ba2);
        color: white;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .auth-footer {
        text-align: center;
        margin-top: 20px;
        color: #6c757d;
    }

    .auth-footer a {
        color: #667eea;
        font-weight: 500;
    }

    .profile-picture-wrapper {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-picture-container {
        width: 120px;
        height: 120px;
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

    .cropper-modal {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 100%;
    }

    .cropper-container {
        max-height: 300px;
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" class="auth-logo">
            <h4 class="auth-title">Attendance Management System</h4>
            <p class="auth-subtitle">Create your account</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <div class="profile-picture-wrapper">
                    <div class="profile-picture-container" onclick="document.getElementById('profile_picture').click()">
                        <div class="profile-picture-placeholder" id="profile_placeholder">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <img id="profile_preview" class="profile-picture" style="display: none;">
                        <div class="profile-picture-edit">Click to upload</div>
                    </div>
                    <input type="file" id="profile_picture" name="profile_picture" class="profile-picture-upload" accept="image/*">
                    @error('profile_picture')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-auth btn-register">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </button>
                </div>

                <div class="auth-footer">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </div>
            </form>
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
    // Profile picture preview
    document.getElementById('profile_picture').addEventListener('change', function(e) {
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
</script>
@endsection
