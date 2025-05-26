@include('layouts.welcome')

<!-- Add this in your <head> section if not already included -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .welcome-container {
        min-height: 100vh;
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        padding: 1rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') center/cover;
        opacity: 0.15;
        z-index: 0;
    }

    .auth-links {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: flex-end;
        z-index: 10;
    }

    .auth-btn {
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
    }

    .login-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        color: white;
    }

    .register-btn {
        background: white;
        color: #667eea;
        border: 2px solid white;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        color: white;
    }

    .auth-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .content {
        position: relative;
        z-index: 5;
        width: 100%;
        max-width: 1200px;
        padding: 1rem;
    }

    .title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .clock-container {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1.5rem auto;
        max-width: 600px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .clock_Style {
        font-size: clamp(2.5rem, 8vw, 5rem);
        font-weight: 700;
        font-family: 'Courier New', monospace;
        letter-spacing: 3px;
        color: white;
        text-shadow: 0 0 8px rgba(255, 255, 255, 0.7);
        margin: 0;
    }

    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 12px;
        padding: 1.5rem;
        width: 100%;
        max-width: 350px;
        min-width: 250px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-sizing: border-box;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 0.8rem;
        color: white;
    }

    .feature-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
    }

    .feature-desc {
        font-size: 0.95rem;
        opacity: 0.9;
        line-height: 1.5;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .auth-links {
            position: static;
            margin-bottom: 1.5rem;
            justify-content: center;
        }

        .clock-container {
            padding: 1rem;
            margin: 1rem auto;
        }

        .feature-card {
            max-width: 100%;
            min-width: auto;
        }
    }

    @media (max-width: 480px) {
        .auth-btn {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .title {
            margin-bottom: 1rem;
        }

        .feature-icon {
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.1rem;
        }

        .feature-desc {
            font-size: 0.85rem;
        }
    }
    </style>

<div class="welcome-container">
    @if (Route::has('login'))
    <div class="auth-links">
        @auth
        <button onclick="history.back()" class="auth-btn back-btn">
            <i class="fas fa-arrow-left mr-2"></i> Go Back
        </button>
        @else
        <a href="{{ route('login') }}" class="auth-btn login-btn">
            <i class="fas fa-sign-in-alt mr-2"></i> Login
        </a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="auth-btn register-btn">
            <i class="fas fa-user-plus mr-2"></i> Register
        </a>
        @endif
        @endauth
    </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Welcome to Attendance System
        </div>

        <div class="clock-container">
            <div class="clock_Style" id="clock"></div>
        </div>

        <div class="row features">
            <div class="col=md-4 feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <h3 class="feature-title">Time Tracking</h3>
                <p class="feature-desc">Accurate employee time tracking with real-time monitoring</p>
            </div>

            <div class=" col-md-4 feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="feature-title">Attendance</h3>
                <p class="feature-desc">Comprehensive attendance records and reporting</p>
            </div>

            <div class="col-md-4 feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Analytics</h3>
                <p class="feature-desc">Detailed analytics and insights for better management</p>
            </div>
        </div>
    </div>
</div>


