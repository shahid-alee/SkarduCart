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
                                        <img src="{{ asset('storage/'.$details['image']) }}" style="width:100px">
                                    </div>
                                    <div>
                                        <h5 class="p-3">{{ $details['product_name'] }}</h5>
                                    </div>
                                </div>
                            </td>
                            <td>Rs {{ $details['price'] }}</td>
                            <td>{{ $details['quantity'] }}</td>
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
                        <h5>Rs 0</h5>
                    </div>
                </div>

                <div class="d-flex">
                    <div>
                        <h5>Delivery charge</h5>
                    </div>
                    <div class="ms-auto">
                        <h5>Rs 0</h5>
                    </div>
                </div>
                <hr>
                <div class="d-flex">
                    <div>
                        <h5>Total</h5>
                    </div>
                    <div class="ms-auto">
                        <h5>Rs {{ $total }}</h5>
                    </div>
                </div>

                <div class="mt-4"><a href="#" class="btn theme-orange-btn text-light rounded-pill w-100">
                        Buy Now
                    </a></div>
            </div>
        </div>
    </div>
</section>
@endsection