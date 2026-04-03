@extends('layouts.main')

@section('content')

<style>
    body {
        overflow-x: hidden;
    }

    .error-wrapper {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        position: relative;
    }

    /* Glass effect box */
    .error-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        max-width: 550px;
        width: 100%;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        color: #fff;
        animation: fadeIn 0.8s ease-in-out;
    }

    /* Floating icon animation */
    .error-icon {
        font-size: 70px;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }

    .error-code {
        font-size: 110px;
        font-weight: 900;
        margin-bottom: 10px;
        letter-spacing: 3px;
    }

    .error-title {
        font-size: 28px;
        font-weight: 600;
    }

    .error-desc {
        margin: 15px 0 30px;
        opacity: 0.9;
    }

    .btn-custom {
        border-radius: 50px;
        padding: 10px 28px;
        margin: 5px;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-home {
        background: #fff;
        color: #333;
    }

    .btn-home:hover {
        background: #000;
        color: #fff;
    }

    .btn-orders {
        border: 1px solid #fff;
        color: #fff;
    }

    .btn-orders:hover {
        background: #fff;
        color: #000;
    }

    /* Background circles */
    .circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        animation: move 10s linear infinite;
    }

    .circle:nth-child(1) {
        width: 200px;
        height: 200px;
        top: 10%;
        left: 5%;
    }

    .circle:nth-child(2) {
        width: 150px;
        height: 150px;
        bottom: 15%;
        right: 10%;
    }

    @keyframes float {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes move {
        0% { transform: translateY(0); }
        50% { transform: translateY(30px); }
        100% { transform: translateY(0); }
    }
</style>

<div class="error-wrapper">

    <!-- Decorative circles -->
    <div class="circle"></div>
    <div class="circle"></div>

    <div class="error-box">

        <div class="error-icon">
            <i class="fa-solid fa-face-frown-open"></i>
        </div>

        <div class="error-code">404</div>

        <div class="error-title">Oops! Page Not Found</div>

        <p class="error-desc">
            The page you're looking for might have been removed, renamed,
            or is temporarily unavailable.
        </p>

        <!-- Buttons -->
        <div>
            <a href="{{ url('/') }}" class="btn btn-custom btn-home">
                <i class="fa fa-home me-1"></i> Home
            </a>

            <a href="{{ route('user.orders') }}" class="btn btn-custom btn-orders">
                <i class="fa fa-shopping-bag me-1"></i> My Orders
            </a>
        </div>

    </div>
</div>

@endsection