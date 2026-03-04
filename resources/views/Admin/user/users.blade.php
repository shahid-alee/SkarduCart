@extends('admin.layout')
@section('admin-dashboard-users')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body" style="width: max-content;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Users Table</h4>
                        <a href="#"
                                class="btn btn-primary btn-rounded btn-fw">
                                Add New User
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name}}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role}}</td>
                                        <td>
                                            <a href="{{ route('user.edit', $user->id) }}">
                                                <button type="button" class="btn btn-info btn-rounded btn-fw">EDIT</button>
                                            </a>

                                            <form action="{{ route('user.destroy', $user->id) }}"
                                                  method="POST"
                                                  style="display:inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-rounded btn-fw">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                      
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection