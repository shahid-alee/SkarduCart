@extends('layouts.main')

@section('content')

<div class="container mt-5">
    <h4 class="mb-4">
        Search Results for: <span class="text-primary">"{{ $query }}"</span>
    </h4>

    <div class="row">

        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">

                    <img src="{{ asset('images/products/'.$product->image) }}"
                         class="card-img-top"
                         style="height:200px; object-fit:cover;">

                    <div class="card-body text-center">
                        <h6>{{ $product->product_name }}</h6>
                        <p class="text-success">Rs {{ $product->price }}</p>

                        <a href="{{ url('product/'.$product->id) }}"
                           class="btn btn-sm btn-primary">
                           View
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center">
                <h5>No products found 😔</h5>
            </div>
        @endforelse

    </div>
</div>

@endsection