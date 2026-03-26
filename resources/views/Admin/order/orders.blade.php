@extends('admin.layout')
@section('admin-dashboard-orders')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Orders</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <!-- <th>Customer</th> -->
                                        <th>Email</th>
                                        <th>Total</th>
                                        <th>Payment Method</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <!-- <td>{{ $order->first_name }} {{ $order->last_name }}</td> -->
                                        <td>{{ $order->email }}</td>
                                        <td>Rs {{ $order->total }}</td>
                                        <td>{{ strtoupper($order->payment_method) }}</td>
                                        <td>

                                            @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>

                                            @elseif($order->payment_status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>

                                            @else
                                            <span class="badge bg-danger">Failed</span>

                                            @endif

                                        </td>
                                        <td>
                                            @if($order->order_status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>

                                            @elseif($order->order_status == 'processing')
                                            <span class="badge bg-info">Processing</span>

                                            @elseif($order->order_status == 'shipped')
                                            <span class="badge bg-primary">Shipped</span>

                                            @elseif($order->order_status == 'delivered')
                                            <span class="badge bg-success">Delivered</span>

                                            @else
                                            <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.order.view', $order->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('admin.order.edit', $order->id) }}">
                                                <button type="button" class="btn btn-success btn-rounded btn-sm">EDIT</button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>



stripe.confirmCardPayment(clientSecret, {
    payment_method: { card: cardElement }
}).then(function(result) {
    if (result.error) {
    } else if (result.paymentIntent.status === 'succeeded') {
        window.location.href = "/stripe/success/" + orderId;
    }
});

</script>

@endsection