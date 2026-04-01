@extends('layouts.main')
@push('title')
<title>Home Page</title>
@endpush
@section('content')

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('assets/images/banner1.png') }}" class="d-block w-100" alt="slider 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('assets/images/banner2.png') }}" class="d-block w-100" alt="slider 2">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Top Deals Section -->
<section class="my-5">
    <div class="container">
        <div class="d-flex bd-highlight">
            <div class="flex-grow-1 bd-highlight">
                <h3>Top Deals</h3>
            </div>
            <!-- Remove View All button or keep it without link -->
            <!-- <div>
                <a href="#" class="btn btn-sm theme-green-btn text-light rounded-pill">View All</a>
            </div> -->
        </div>

        <div class="row theme-product">
            @forelse($topDeals as $product)
            <div class="col-lg-3 mt-4">
                <div class="card">
                    <a href="{{ route('product.show', $product->id) }}">
                        <img src="{{ asset('storage/'.(is_array($product->image) ? $product->image[0] : $product->image)) }}" class="card-img-top" alt="product">
                    </a>
                    <div class="card-body">
                        <a href="{{ route('product.show', $product->id) }}" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">{{ $product->product_name }}</h6>
                        </a>
                        <h5 class="card-text text-center">Rs {{ number_format($product->price, 2) }}</h5>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>No products found.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>


<!-- Popular Categories Section -->
<section class="my-5">
    <div class="container">
        <div class="d-flex bd-highlight">
            <div class="flex-grow-1 bd-highlight">
                <h3>Popular Categories</h3>
            </div>
        </div>

        <div class="row theme-product">
            @forelse($popularCategories as $product)
            <div class="col-lg-3 mt-4">
                <div class="card">
                    <a href="{{ route('product.show', $product->id) }}">
                        <img src="{{ asset('storage/'.(is_array($product->image) ? $product->image[0] : $product->image)) }}" class="card-img-top" alt="product">
                    </a>
                    <div class="card-body">
                        <a href="{{ route('product.show', $product->id) }}" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">{{ $product->product_name }}</h6>
                        </a>
                        <h5 class="card-text text-center">Rs {{ number_format($product->price, 2) }}</h5>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>No products found in Popular Categories.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Recently Viewed Section -->
<section class="my-5">
    <div class="container">
        <div class="d-flex bd-highlight">
            <div class="flex-grow-1 bd-highlight">
                <h3>Recently Viewed</h3>
            </div>
        </div>

        <div class="row theme-product">
            @forelse($recentlyViewed as $product)
            <div class="col-lg-3 mt-4">
                <div class="card">
                    <a href="{{ route('product.show', $product->id) }}">
                        <img src="{{ asset('storage/'.(is_array($product->image) ? $product->image[0] : $product->image)) }}" class="card-img-top" alt="product">
                    </a>
                    <div class="card-body">
                        <a href="{{ route('product.show', $product->id) }}" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">{{ $product->product_name }}</h6>
                        </a>
                        <h5 class="card-text text-center">Rs {{ number_format($product->price, 2) }}</h5>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>No recently viewed products.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection