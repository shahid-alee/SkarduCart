@extends('admin.layout')

@section('admin-dashboard-orders.view')

<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">

            <!-- Order Information -->
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Order Details</h4>

                        <table class="table table-bordered">
                            <tr>
                                <th>Order ID</th>
                                <td>{{ $order->id }}</td>
                            </tr>

                            <tr>
                                <th>Customer Name</th>
                                <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>{{ $order->email }}</td>
                            </tr>

                            <tr>
                                <th>Phone</th>
                                <td>{{ $order->phone }}</td>
                            </tr>

                            <tr>
                                <th>Address</th>
                                <td>{{ $order->address }}</td>
                            </tr>

                            <tr>
                                <th>Total</th>
                                <td>Rs {{ $order->total }}</td>
                            </tr>

                            <tr>
                                <th>Payment Method</th>
                                <td>{{ strtoupper($order->payment_method) }}</td>
                            </tr>

                            <tr>
                                <th>Payment Status</th>
                                <td>{{ ucfirst($order->payment_status) }}</td>
                            </tr>

                            <tr>
                                <th>Date</th>
                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            </tr>

                        </table>

                    </div>
                </div>
            </div>


            <!-- Order Products -->
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Order Items</h4>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($order->items as $item)

                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>Rs {{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs {{ $item->price * $item->quantity }}</td>
                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection