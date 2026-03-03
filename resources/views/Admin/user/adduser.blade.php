@extends('admin.layout')
@section('admin-user-add')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card" style="width:80%;">
                <div class="card">
                    <div class="card-body" style="width:100%;">

                        <h4 class="card-title">
                            {{ isset($user) ? 'Edit User' : 'Add New User' }}
                        </h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                         <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}"
                              method="POST">
                            @csrf
                            @if(isset($user))
                                @method('PUT')
                            @endif

                            {{-- First Name --}}
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control"
                                       value="{{ $user->name ?? old('name') }}"
                                       required>
                            </div>

                           

                            {{-- Email --}}
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control"
                                       value="{{ $user->email ?? old('email') }}"
                                       required>
                            </div>

                            {{-- Password --}}
                            <div class="form-group">
                                <label for="password">
                                    {{ isset($user) ? 'New Password (Leave blank to keep old)' : 'Password' }}
                                </label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control">
                            </div>

                            {{-- Role --}}
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="user"
                                        {{ (isset($user) && $user->role == 'user') ? 'selected' : '' }}>
                                        User
                                    </option>
                                    <option value="admin"
                                        {{ (isset($user) && $user->role == 'admin') ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ isset($user) ? 'Update User' : 'Add User' }}
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection