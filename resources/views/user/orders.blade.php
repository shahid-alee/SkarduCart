@extends('layouts.main')

@push('title')
<title>My Orders</title>
@endpush

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Order History</h3>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>View</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>Rs {{ $order->total }}</td>
                <td>{{ $order->payment_status }}</td>
                <td>{{ $order->order_status }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('order.tracking', $order->id) }}"
                        class="btn btn-sm btn-primary">
                        View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No Orders Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection