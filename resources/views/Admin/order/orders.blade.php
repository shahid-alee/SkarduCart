@extends('admin.layout')

@section('admin-dashboard-orders')

<div class="main-panel">
    <div class="content-wrapper">

        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Orders Dashboard</h3>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-4">All Orders</h4>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Order Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($orders as $order)
                            <tr>

                                <!-- ID -->
                                <td>#{{ $order->id }}</td>

                                <!-- Customer -->
                                <td>
                                    {{ $order->first_name }} {{ $order->last_name }}
                                </td>

                                <!-- Email -->
                                <td>{{ $order->email }}</td>

                                <!-- Total -->
                                <td>Rs {{ $order->total }}</td>

                                <!-- Payment Method -->
                                <td>
                                    <span class="badge bg-info">
                                        {{ strtoupper($order->payment_method) }}
                                    </span>
                                </td>

                                <!-- ORDER STATUS + UPDATE -->
                                <td>

                                    <!-- Current Status Badge -->
                                    <span class="badge
                                        @if($order->order_status == 'delivered') bg-success
                                        @elseif($order->order_status == 'pending') bg-warning text-dark
                                        @else bg-primary
                                        @endif
                                    ">
                                        {{ ucfirst($order->order_status ?? 'placed') }}
                                    </span>

                                    <!-- Update Dropdown -->
                                    <form action="{{ route('admin.order.orderstatus', $order->id) }}" method="POST" class="mt-2">
                                        @csrf

                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">

                                            <option value="placed" {{ $order->order_status == 'placed' ? 'selected' : '' }}>
                                                Placed
                                            </option>

                                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>

                                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                                                Delivered
                                            </option>

                                        </select>
                                    </form>

                                </td>

                                <!-- Date -->
                                <td>{{ $order->created_at->format('d M Y') }}</td>

                                <!-- Action -->
                                <td>
                                    <a href="{{ route('admin.order.view', $order->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </td>

                            </tr>

                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    No Orders Found
                                </td>
                            </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>

    </div>
</div>

@endsection