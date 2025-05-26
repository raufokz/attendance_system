<!-- resources/views/auth/not-approved.blade.php -->
@extends('layouts.master-blank')

@section('content')
<style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                    url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: white;
    }

    .not-approved-card {
        width: 100%;
        max-width: 500px;
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .not-approved-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .not-approved-icon {
        margin-bottom: 25px;
    }

    .not-approved-icon svg {
        width: 80px;
        height: 80px;
        color: #FFC107;
        filter: drop-shadow(0 4px 8px rgba(255, 193, 7, 0.3));
    }

    h3 {
        color: #343a40;
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 1.8rem;
    }

    p {
        color: #6c757d;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .btn-primary {
        background-color: #FFC107;
        border-color: #FFC107;
        color: #212529;
        padding: 12px 32px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);
        border: none;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .btn-primary:hover {
        background-color: #E0A800;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.35);
    }

    .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.5);
    }

    @media (max-width: 576px) {
        .not-approved-card {
            padding: 30px 20px;
            max-width: 90%;
        }

        h3 {
            font-size: 1.5rem;
        }

        p {
            font-size: 1rem;
        }

        .btn-primary {
            padding: 10px 24px;
            font-size: 1rem;
        }

        .not-approved-icon svg {
            width: 60px;
            height: 60px;
        }
    }
</style>

<div class="not-approved-card">
    <div class="not-approved-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.71c.89 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
        </svg>
    </div>

    <h3>Account Not Approved</h3>
    <p>
        Your account is currently pending approval by an administrator.
        You will be notified via email once your account has been approved.
    </p>
    <a href="{{ route('login') }}" class="btn btn-primary">
        Back to Login
    </a>
</div>
@endsection
