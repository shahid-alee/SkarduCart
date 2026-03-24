@extends('layouts.main')

@push('title')
<title>My Profile</title>
@endpush

@section('content')

<style>
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        padding: 30px;
        background: #fff;
    }

    .profile-img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #f1f1f1;
        transition: 0.3s;
    }

    .profile-img:hover {
        transform: scale(1.05);
    }

    .upload-btn {
        font-size: 14px;
        margin-top: 10px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px;
    }

    .btn-update {
        border-radius: 30px;
        padding: 10px 25px;
        font-weight: 500;
    }

    .profile-title {
        font-weight: 600;
        color: #333;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="profile-card">

                <!-- Title -->
                <div class="text-center mb-4">
                    <h3 class="profile-title">My Profile</h3>
                    <p class="text-muted">Manage your personal information</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile-update', Auth::id()) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-4 text-center">
                            <img src="{{ Auth::user()->profile_image 
                                ? asset('storage/'.Auth::user()->profile_image) 
                                : asset('assets/images/users/default-user.png') }}"     
                                class="profile-img mb-3">

                            <input type="file" name="profile_image" class="form-control upload-btn">
                        </div>

                        <div class="col-md-8">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ Auth::user()->name }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control"
                                    value="{{ Auth::user()->email }}" disabled>
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-success btn-update">
                                    <i class="fa fa-save me-2"></i> Update Profile
                                </button>
                            </div>

                        </div>

                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

@endsection