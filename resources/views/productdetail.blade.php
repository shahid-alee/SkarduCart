@extends('layouts.main')

@push('title')
<title>{{ $product->product_name }}</title>
@endpush

@section('content')


<style>
.thumb-img{
transition:0.3s;
border:2px solid transparent;
}

.thumb-img:hover{
border:2px solid #ff6600;
transform:scale(1.05);
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
                <h4 class="text-success">Rs {{ $product->price }}</h4>

                <div class="d-flex">
                    <div class="p-1">

                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>
                    </div>
                    <div class="me-3">
                        <h6 class="p-1">5 Customer Ratings</h6>
                    </div>
                </div>

                @foreach($product->properties as $property)

                <div class="mb-2">

                    <strong>{{ ucfirst($property->property_name) }} :</strong>

                    @foreach($property->values as $value)

                    <span class="badge bg-secondary">
                        {{ $value->value }}
                    </span>

                    @endforeach

                </div>

                @endforeach

                <div class="mt-3">
                    <p>
                        {{ $product->description }}
                    </p>
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
function changeImage(element)
{
    document.getElementById("mainImage").src = element.src;
}
</script>

@endsection