@extends('layouts.main')

@push('title')
<title>Change Password</title>
@endpush

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Change Password</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.password.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Old Password</label>
            <input type="password" name="old_password" class="form-control">
        </div>

        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="new_password_confirmation" class="form-control">
        </div>

        <button class="btn theme-orange-btn text-light">Update Password</button>
    </form>
</div>
@endsection