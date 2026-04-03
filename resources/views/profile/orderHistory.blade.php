@extends('layouts.main')

@section('title', 'My Orders')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">My Order History</h2>

    <div class="card">
        <div class="card-body">

            @if($orders->count() > 0)
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>Order Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($orders as $key => $order)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>#{{ $order->id }}</td>

                                <td>Rs. {{ number_format($order->total ?? 0, 2) }}</td>

                                <td>
                                    @foreach($order->items as $item)
                                        <div>
                                            {{ $item->product_name }} 
                                            (x{{ $item->quantity }})
                                            <br>
                                            <small class="text-muted">
                                                {{ $item->variant_names }}
                                            </small>
                                        </div>
                                    @endforeach
                                </td>

                                <td>
                                    <span class="badge 
                                        @if($order->order_status == 'pending') bg-warning
                                        @elseif($order->order_status == 'processing') bg-info
                                        @elseif($order->order_status == 'delivered') bg-success
                                        @elseif($order->order_status == 'shipped') btn-primary
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($order->order_status ?? 'N/A') }}
                                    </span>
                                </td>

                                
                                <td>
                                    <span class="badge 
                                        @if($order->payment_status == 'paid') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($order->payment_status ?? 'N/A') }}
                                    </span>
                                </td>

                                <td>{{ $order->created_at->format('d M Y') }}</td>

                                <td>
                                    <!-- <a href="" 
                                       class="btn btn-sm btn-primary mb-1">
                                        View
                                    </a> -->

                                    <a href="{{ route('order.tracking', $order->id) }}" 
                                       class="btn btn-sm btn-info">
                                        Track
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">No orders found.</p>
            @endif

        </div>
    </div>
</div>
@endsection