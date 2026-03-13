@extends('layouts.main')

@push('title')
<title>Checkout</title>
@endpush

@section('content')

<div class="container-fluid bg-light p-5">
    <h1 class="text-center">
        <i class="fa-solid fa-credit-card"></i> Checkout
    </h1>
</div>

<section>
    <div class="container my-5">

        <form action="{{ route('stripe.checkout') }}" method="POST">
            @csrf

            <div class="row">

                <!-- Billing Details -->
                <div class="col-lg-7">

                    <h4 class="mb-4">Billing Details</h4>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Postal Code</label>
                            <input type="text" name="postal_code" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Order Notes</label>
                            <textarea name="notes" class="form-control" rows="4"></textarea>
                        </div>

                    </div>

                </div>

                <!-- Order Summary -->
                <div class="col-lg-5">

                    <div class="card p-4">

                        <h4 class="mb-4">Your Order</h4>

                        @php
                        $cart = session('cart');
                        $total = 0;
                        @endphp

                        <table class="table">

                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($cart as $id => $details)

                                @php
                                $subtotal = $details['price'] * $details['quantity'];
                                $total += $subtotal;
                                @endphp

                                <tr>
                                    <td>
                                        {{ $details['product_name'] }}
                                        <strong> × {{ $details['quantity'] }}</strong>
                                    </td>

                                    <td>
                                        Rs {{ $subtotal }}
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                        <hr>

                        <div class="d-flex">
                            <strong>Subtotal</strong>
                            <strong class="ms-auto">Rs {{ $total }}</strong>
                        </div>

                        <div class="d-flex mt-2">
                            <strong>Delivery</strong>
                            <strong class="ms-auto">Rs 0</strong>
                        </div>

                        <hr>

                        <div class="d-flex">
                            <h5>Total</h5>
                            <h5 class="ms-auto">Rs {{ $total }}</h5>
                        </div>


                        <!-- Payment Method -->

                        <div class="mt-4">

                            <h5>Payment Method</h5>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="payment" value="cod" checked>
                                <label class="form-check-label">
                                    Cash On Delivery
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" value="card">
                                <label class="form-check-label">
                                    Credit / Debit Card
                                </label>
                            </div>

                        </div>

                        <button class="btn theme-orange-btn text-light w-100 mt-4 rounded-pill">
                            proceed to pay
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>
</section>

@endsection