@extends('admin.layout')
@section('admin-product-view')

<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Product Details</h4>

                        <div class="row">

                            <!-- Product Images -->
                            <div class="col-lg-4">

                                @if(!empty($product->image))

                                <div class="mb-3">
                                    <img id="mainImage"
                                        src="{{ asset('storage/'.$product->image[0]) }}"
                                        class="img-fluid rounded border"
                                        style="width:100%;">
                                </div>

                                <div class="d-flex flex-wrap gap-2">

                                    @foreach($product->image as $img)

                                    <img src="{{ asset('storage/'.$img) }}"
                                        class="img-thumbnail"
                                        style="width:70px; cursor:pointer"
                                        onclick="changeImage(this)">

                                    @endforeach

                                </div>

                                @endif

                            </div>

                            <!-- Product Info -->
                            <div class="col-lg-8">

                                <table class="table table-bordered">

                                    <tr>
                                        <th style="width:200px;">Product Name</th>
                                        <td>{{ $product->product_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Price</th>
                                        <td>Rs {{ $product->price }}</td>
                                    </tr>

                                    <tr>
                                        <th>Category</th>
                                        <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Sub Category</th>
                                        <td>{{ $product->subcategory->sub_category_name ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Stock Quantity</th>
                                        <td>{{ $product->quantity }}</td>
                                    </tr>

                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $product->description }}</td>
                                    </tr>

                                </table>

                            </div>

                        </div>

                        <hr>

                        <!-- Product Properties -->
                        <h4 class="mt-4">Product Properties</h4>

                        @if($product->properties->count())

                        @foreach($product->properties as $property)

                        <div class="mb-3">

                            <strong>{{ ucfirst($property->property_name) }} :</strong>

                            @foreach($property->values as $value)

                            <span class="badge bg-primary me-1">
                                {{ $value->value }}
                            </span>

                            @endforeach

                        </div>

                        @endforeach

                        @else

                        <p>No properties available</p>

                        @endif


                        <div class="mt-4">

                            <a href="{{ route('product.edit',$product->id) }}"
                                class="btn btn-info btn-rounded btn-fw">
                                Edit Product
                            </a>

                            <a href="{{ route('admin.product.products') }}"
                                class="btn btn-secondary btn-rounded btn-fw">
                                Back
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function changeImage(element) {
        document.getElementById('mainImage').src = element.src;
    }
</script>

@endsection