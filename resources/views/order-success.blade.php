@extends('layouts.main')

@push('title')
<title>Order Success</title>
@endpush

@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <!-- Thank You Card -->
            <div class="card text-center p-5 shadow-sm">
                <div class="card-body">

                    <i class="fa-solid fa-check-circle text-success" style="font-size:60px;"></i>

                    <h2 class="card-title mt-4">Order Created Successfully!</h2>
                    <p class="card-text mt-3">
                        Thank you, <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>, 
                        for your order. Your order ID is <strong>#{{ $order->id }}</strong>.
                    </p>

                    <p class="card-text">
                        Payment Status: 
                        @if($order->payment_status == 'paid')
                            <span class="text-success">{{ ucfirst($order->payment_status) }}</span>
                        @else
                            <span class="text-warning">{{ ucfirst($order->payment_status) }}</span>
                        @endif
                    </p>

                    <a href="{{ url('/') }}" class="btn btn-primary mt-4 rounded-pill">
                        Back to Home
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection