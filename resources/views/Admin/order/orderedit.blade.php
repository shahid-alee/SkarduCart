@extends('admin.layout')

@section('admin-dashboard-orders')

<div class="main-panel">
    <div class="content-wrapper">
        
        <div class="card">
            <div class="card-body">

                <h4>Edit Order Status - #{{ $order->id }}</h4>

                <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control"
                               value="{{ $order->first_name }} {{ $order->last_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <input type="text" class="form-control"
                               value="{{ ucfirst($order->order_status) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Change Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Update Status</button>
                    <a href="{{ route('admin.order.orders') }}" class="btn btn-secondary">Back</a>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection