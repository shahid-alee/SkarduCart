@extends('admin.layout')
@section('admin-subcategory-add')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card" style="width:80%;">
                <div class="card">
                    <div class="card-body">
                         <h4 class="card-title">Add New Sub Category</h4>

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

                        <form action="{{ isset($subcategory) ? route('subcategory.update',$subcategory->id) : route('subcategory.store') }}" method="POST">

                            @csrf

                            @if(isset($subcategory))
                            @method('PUT')
                            @endif

                            <div class="form-group">
                                <label>Sub Category Name</label>
                                <input type="text"
                                    name="sub_category_name"
                                    class="form-control"
                                    value="{{ $subcategory->sub_category_name ?? old('sub_category_name') }}"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (isset($subcategory) && $subcategory->category_id == $category->id) ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" style="height:150px;" required>{{ $subcategory->description ?? old('description') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($subcategory) ? 'Update Sub Category' : 'Add Sub Category' }}
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection