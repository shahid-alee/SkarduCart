@extends('layouts.main')

@push('title')
<title>{{ $product->product_name }}</title>
@endpush

@section('content')
<style>
    .thumb-img {
        transition: 0.3s;
        border: 2px solid transparent;
    }

    .thumb-img:hover {
        border: 2px solid #ff6600;
        transform: scale(1.05);
    }
</style>

<div class="container-fluid bg-light p-5">
    <h1 class="text-center">
        <i class="fa-brands fa-product-hunt"></i> Product Detail
    </h1>
</div>

<section class="my-5">
    <div class="container">
        <div class="row">

            <div class="col-lg-4">

                <div class="card mb-3">
                    <img id="mainImage"
                        src="{{ asset('storage/'.$product->image[0]) }}"
                        class="img-fluid rounded"
                        alt="{{ $product->product_name }}">
                </div>

                <div class="d-flex flex-wrap gap-2">

                    @foreach($product->image as $img)

                    <img src="{{ asset('storage/'.$img) }}"
                        class="img-thumbnail thumb-img"
                        style="width:70px; cursor:pointer;"
                        onclick="changeImage(this)">

                    @endforeach

                </div>

            </div>

            <div class="col-lg-8">
                <h3>{{ $product->product_name }}</h3>
                <h4 class="text-success">RS {{ number_format($product->price, 2) }}</h4>

                @php
                $avgRating = round($product->reviews->avg('rating'));
                @endphp

                <div class="p-1">

                    @for($i=1; $i<=5; $i++)

                        @if($i <=$avgRating)
                        <span class="fa fa-star text-warning"></span>
                        @else
                        <span class="fa fa-star text-secondary"></span>
                        @endif

                        @endfor

                </div>

                @foreach($product->properties as $property)

                <div class="mb-3">
                    <strong>{{ ucfirst($property->property_name) }} :</strong>

                    <div class="mt-2">

                        @foreach($property->values as $value)

                        <label class="me-3">
                            <input type="radio"
                                name="property[{{ $property->id }}]"
                                value="{{ $value->id }}"
                                required>

                            {{ $value->value }}
                        </label>

                        @endforeach

                    </div>
                </div>

                @endforeach

                <div class="mt-3">
                    <p>{{ $product->description }}</p>
                </div>

                <form action="{{ route('cart.add',$product->id) }}" method="POST">
                    @csrf

                    <div class="d-flex flex-row align-items-center mb-3">
                        <div class="me-2">Quantity :</div>

                        <button type="button" class="btn btn-secondary btn-sm" onclick="decreaseQty()">-</button>

                        <input type="number"
                            name="quantity"
                            id="quantity"
                            value="1"
                            min="1"
                            class="form-control mx-2 text-center"
                            style="width:70px;">

                        <button type="button" class="btn btn-secondary btn-sm" onclick="increaseQty()">+</button>
                    </div>

                    <button class="btn theme-green-btn text-light rounded-pill me-3">
                        Add to Cart
                    </button>
                </form>

                @auth
                <a href="{{ route('checkout',$product->id) }}" class="btn theme-orange-btn text-light mt-3 rounded-pill">
                    Buy Now
                </a>
                @endauth

                @guest
                <a href="{{ route('login.form') }}" class="btn theme-orange-btn text-light mt-3 rounded-pill">
                    Buy Now
                </a>
                @endguest

            </div>
        </div>
    </div>


    <!-- Product Description -->
    <div class="m-5">
        <h4>Product Description</h4>
        <p>{{ $product->description }}</p>
    </div>

    <hr>

    <!-- Reviews -->
    <section class="mt-5">
        <div class="col-lg-8 ms-5">

            <h3>{{ $product->reviews->count() }} Reviews</h3>

            @foreach($product->reviews as $review)

            <div class="row mt-4">

                <div class="col-lg-1">
                    <img src="{{ asset('assets/images/reviews/user.jpg') }}"
                        class="rounded-circle img-fluid">
                </div>

                <div class="col-lg-10">

                    <h5>{{ $review->user->name }}</h5>

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h6>Date: {{ $review->created_at->format('d-m-Y') }}</h6>
                        </div>

                        <div class="p-1">

                            @for($i = 1; $i <= 5; $i++)

                                @if($i <=$review->rating)
                                <span class="fa fa-star checked text-warning"></span>
                                @else
                                <span class="fa fa-star text-secondary"></span>
                                @endif

                                @endfor

                        </div>

                    </div>

                    <p>{{ $review->review }}</p>

                </div>

            </div>

            @endforeach
        </div>
    </section>



    </div>
</section>


<script>
    function increaseQty() {
        let qty = document.getElementById('quantity');
        qty.value = parseInt(qty.value) + 1;

    }

    function decreaseQty() {
        let qty = document.getElementById('quantity');
        if (qty.value > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }
</script>

<script>
    function changeImage(element) {
        document.getElementById("mainImage").src = element.src;
    }
</script>

@endsection