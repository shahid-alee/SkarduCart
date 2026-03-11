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
                            <a href="{{route('product.create')}}"
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
                                        <th>Sub Category</th>
                                        <th>price</th>
                                        <th>Quantity</th>


                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>@if(!empty($product->image))
                                            <img src="{{ asset('storage/' . $product->image[0]) }}" width="60">
                                            @endif
                                        </td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                        <td>{{ $product->subcategory->sub_category_name ?? 'N/A' }}</td>
                                        <td>{{ $product->price}}</td>
                                        <td>{{ $product->quantity}}</td>

                                        <td>
                                            <a href="{{ route('product.view', $product->id) }}">
                                                <button type="button" class="btn btn-secondary btn-rounded btn-fw">VIEW</button>
                                            </a>


                                            <a href="{{ route('product.edit', $product->id) }}">
                                                <button type="button" class="btn btn-info btn-rounded btn-fw">EDIT</button>
                                            </a>


                                            <form action="{{ route('product.destroy', $product->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this product?')">

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