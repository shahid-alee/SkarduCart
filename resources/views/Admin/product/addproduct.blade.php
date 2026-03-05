@extends('admin.layout')
@section('admin-product-add')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card" style="width:80%;">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Add New Product</h4>

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

                        <form action="{{ isset($product) ? route('product.update', $product->id) : route('product.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            @if(isset($product))
                            @method('PUT')
                            @endif

                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" class="form-control" value="{{ $product->product_name ?? old('product_name') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Product Image</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" name="price" class="form-control" value="{{ $product->price ?? old('price') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="category" class="form-control" value="{{ $product->category ?? old('category') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Stock Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="{{ $product->quantity ?? old('quantity') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control " style="height: 150px;" required>{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Add Product
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection