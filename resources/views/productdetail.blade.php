@extends('layouts.main')

@push('title')
<title>{{ $product->product_name }}</title>
@endpush

@section('content')

<div class="container-fluid bg-light p-5">
    <h1 class="text-center">
        <i class="fa-brands fa-product-hunt"></i> Product Detail
    </h1>
</div>

<section class="my-5">
    <div class="container">

        <div class="row">

            <!-- Product Image -->
            <div class="col-lg-4">
                <div class="card">

                    <img src="{{ asset('storage/'.$product->image) }}"
                        class="rounded img-fluid"
                        alt="{{ $product->product_name }}">

                </div>
            </div>


            <!-- Product Info -->
            <div class="col-lg-8">

                <h3>{{ $product->product_name }}</h3>

                <h4 class="text-success">Rs {{ $product->price }}</h4>

                <div>

                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>

                    <h6>5 Customer Ratings</h6>

                </div>

                <div class="mt-3">
                    <p>
                        {{ $product->description }}
                    </p>
                </div>

                <div>
                    <div class="d-flex flex-row">
                        <div class="p-2">Quantity : </div>
                        <div class="p-2">
                            <span class="btn btn-secondary btn-sm rounded-start-pill"><i class="fa-solid fa-minus"></i></span>
                            <span class="mx-2">01</span>
                            <span class="btn btn-secondary btn-sm rounded-end-pill"><i class="fa-solid fa-plus"></i></span>
                        </div>
                        
                    </div>
                </div>

                <div class="mt-3">

                    <a href="#" class="btn theme-green-btn text-light rounded-pill me-3">
                        Add to Cart
                    </a>

                    <a href="#" class="btn theme-orange-btn text-light rounded-pill">
                        Buy Now
                    </a>

                </div>

            </div>

        </div>


        <!-- Product Description -->
        <div class="my-5">

            <h4>Product Description</h4>

            <p>{{ $product->description }}</p>

        </div>


        <!-- Related Products -->
        <section>

            <div class="container">

                <div class="d-flex bd-highlight mb-3">

                    <div class="flex-grow-1 bd-highlight">
                        <h3>Related Products</h3>
                    </div>

                </div>

                <div class="row theme-product">

                    @foreach($products as $product)

                    <div class="col-lg-3 mt-4">
                        <div class="card">
                            <a href="{{ route('product.show', $product->id) }}">
                                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="product">
                            </a>

                            <div class="card-body">
                                <a href="{{ route('product.show', $product->id) }}" class="text-dark text-decoration-none">
                                    <h6 class="card-title text-center">{{ $product->product_name }}</h6>
                                </a>

                                <h5 class="card-text text-center">Rs {{ $product->price }}</h5>
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>


            </div>


    </div>

</section>


<hr>


<!-- Reviews -->
<section>

    <h3>2 Reviews</h3>

    <div class="row mt-4">

        <div class="col-lg-2">

            <img src="{{ asset('assets/images/reviews/user.jpg') }}"
                class="rounded-circle img-fluid">

        </div>

        <div class="col-lg-10">

            <h5>John Doe</h5>

            <p>
                Great product quality. Highly recommended.
            </p>

        </div>

    </div>

</section>


</div>
</section>

@endsection