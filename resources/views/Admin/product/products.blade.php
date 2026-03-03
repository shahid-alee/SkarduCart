@extends('admin.layout')
@section('admin-dashboard-product')

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
                                Add New product
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Image</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>price</th>
                                        <th>Quantity</th>
                                        <th>Description</th>

                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td><img src="{{ asset('storage/' . $product->image) }}" width="60"></td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->category }}</td>
                                        <td>{{ $product->price}}</td>
                                        <td>{{ $product->quantity}}</td>
                                        <td>{{ $product->description}}</td>
                                        <td>
                                            <a href="{{ route('product.edit', $product->id) }}">
                                                <button type="button" class="btn btn-info btn-rounded btn-fw">EDIT</button>
                                            </a>

                                            <form action="#"
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
                           {{ $products->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection