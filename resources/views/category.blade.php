@extends('layouts.main')
@push('title')
<title>Categories</title>
@endpush
@section('content')

<nav class="navbar navbar-expand-lg bg-light theme-navbar-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="nav">
                @foreach($subcategories as $subcategory)
                <li class="nav-item">
                    <a class="nav-link text-dark"
                        href="{{ route('subcategory.products',$subcategory->id) }}">
                        {{ $subcategory->sub_category_name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

<!-- <div class="container-fluid bg-light p-5">
    <h1 class="text-center"><i class="fa-solid fa-layer-group"></i>Category</h1>
</div> -->

<section class="my-5">
    <div class="container">
        <div class="row theme-product mt-5">
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
</section>

@endsection