@extends('admin.layout')
@section('admin-category-add')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card" style="width:80%;">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Add New Category</h4>

                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ isset($category) ? route('category.update',$category->id) : route('category.store') }}" method="POST"> 
                            @csrf
                           
                            @if(isset($category))
                            @method('PUT')
                            @endif

                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="category_name" class="form-control" value="{{ $category->category_name ?? old('category_name') }}"required>
                            </div>


                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control " style="height: 150px;" required>{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Add Category
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection