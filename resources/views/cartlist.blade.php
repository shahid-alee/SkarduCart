@extends('layouts.main')

@push('title')
<title>Cart List</title>
@endpush

@section('content')

<div class="container-fluid bg-light p-5">
    <h1 class="text-center">
        <i class="fa-solid fa-cart-arrow-down"></i>Cart List
    </h1>
</div>

<section>
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Sub total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $details)
                        @php $subtotal = $details['price'] * $details['quantity']; @endphp
                        @php $total += $subtotal; @endphp
                        <tr>
                            <td>
                                <div class="d-flex">
                                    <div>
                                        <img src="{{ asset('storage/' . $details['image'][0]) }}" style="width:100px">
                                    </div>
                                    <div>
                                        <h5 class="p-3">{{ $details['product_name'] }}</h5>
                                    </div>
                                </div>
                            </td>
                            <td>Rs {{ $details['price'] }}</td>
                            <td>

                                <form action="{{ route('cart.update',$id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf

                                    <button type="button" class="btn btn-secondary rounded-start-pill btn-sm" onclick="decreaseCartQty({{ $id }})">-</button>

                                    <input type="number" name="quantity" id="qty{{ $id }}" value="{{ $details['quantity'] }}" min="1"
                                        class="form-control mx-2 text-center"
                                        style="width:70px">

                                    <button type="button" class="btn btn-secondary rounded-end-pill btn-sm" onclick="increaseCartQty({{ $id }})">+</button>

                                    <button type="submit" class="btn btn-success rounded-pill btn-sm ms-2">
                                        Update
                                    </button>

                                </form>

                            </td>
                            <td>Rs {{ $subtotal }}</td>
                            <td>
                                <a href="{{ route('cart.remove',$id) }}" class="btn btn-danger rounded-pill">
                                    remove
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-lg-5 ms-auto my-5">
                @php
                $discount = 0;
                $delivery = 0;
                $grandTotal = $total - $discount + $delivery;
                @endphp

                <div>
                    <h3>Price Details</h3>
                </div>

                <hr>

                <div class="d-flex">
                    <div>
                        <h5>Sub Total</h5>
                    </div>
                    <div class="ms-auto">
                        <h5>Rs {{ $total }}</h5>
                    </div>
                </div>

                <div class="d-flex">
                    <div>
                        <h5>Discount</h5>
                    </div>
                    <div class="ms-auto">
                        <h5>Rs {{ $discount}}</h5>
                    </div>
                </div>

                <div class="d-flex">
                    <div>
                        <h5>Delivery Charge</h5>
                    </div>
                    <div class="ms-auto">
                        <h5>Rs {{ $delivery }}</h5>
                    </div>
                </div>

                <hr>

                <div class="d-flex">
                    <div>
                        <h5>Total</h5>
                    </div>
                    <div class="ms-auto">
                        <h5>Rs {{ $grandTotal }}</h5>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{route('checkout')}}" class="btn theme-orange-btn text-light rounded-pill w-100">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
</section>

<script>
    function increaseCartQty(id) {

        let qty = document.getElementById('qty' + id);
        qty.value = parseInt(qty.value) + 1;

    }

    function decreaseCartQty(id) {

        let qty = document.getElementById('qty' + id);

        if (qty.value > 1) {
            qty.value = parseInt(qty.value) - 1;
        }

    }
</script>
@endsection