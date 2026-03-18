@extends('layouts.main')

@section('content')

<style>

    
    .timeline {
    position: relative;
    margin-left: 20px;
    padding-left: 30px;
    border-left: 3px solid #ddd;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-icon {
    position: absolute;
    left: -42px;
    top: 0;
    background: #ccc;
    color: #fff;
    width: 25px;
    height: 25px;
    text-align: center;
    border-radius: 50%;
    line-height: 25px;
    font-size: 12px;
}

.timeline-item.active .timeline-icon {
    background: #28a745;
}

.timeline-content h5 {
    margin: 0;
    font-weight: 600;
}

.timeline-content p {
    margin: 5px 0;
}
</style>

<div class="container my-5">

    <h3 class="mb-4 text-center">Order Tracking - #{{ $order->id }}</h3>

    <div class="card p-4 mb-4">

        <h5 class="mb-3">Order Details</h5>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Address:</strong> {{ $order->address }}</p>
                <p><strong>City:</strong> {{ $order->city }}</p>
                <p><strong>Total:</strong> Rs {{ $order->total }}</p>
            </div>
        </div>

    </div>

    <div class="card p-4 mb-4">

        <h5 class="mb-3">Products</h5>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>Rs {{ $item->price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs {{ $item->subtotal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div class="card p-4">

        <h5 class="mb-4">Tracking Timeline</h5>

        <div class="timeline">

            @php
                $statuses = ['pending', 'processing', 'shipped', 'delivered'];
            @endphp

            @foreach($statuses as $status)

                @php
                    $track = $order->tracking->where('status', $status)->first();
                @endphp

                <div class="timeline-item {{ $track ? 'active' : '' }}">

                    <div class="timeline-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div class="timeline-content">
                        <h5>{{ ucfirst($status) }}</h5>

                        @if($track)
                            <p>{{ $track->message }}</p>
                            <small>{{ $track->created_at->format('d M Y, h:i A') }}</small>
                        @else
                            <small class="text-muted">Waiting...</small>
                        @endif
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection