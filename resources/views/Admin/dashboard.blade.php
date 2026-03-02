@extends('Admin.main')

@push('title')
<title>Admin Dashboard</title>
@endpush

@section('content')

<div class="container-fluid py-4">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Dashboard Overview</h3>
        <span class="text-muted">Welcome back, Admin</span>
    </div>

    <!-- KPI CARDS -->
    <div class="row g-4">

        <!-- Total Revenue -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 dashboard-card">
                <div class="card-body bg-success text-light">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text ">Total Revenue</h6>
                            <h4 class="fw-bold">$12,540</h4>
                        </div>
                        <div class="icon-box bg-success">
                            <i class="fa-solid fa-dollar-sign text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 dashboard-card">
                <div class="card-body bg-primary text-light">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text">Total Orders</h6>
                            <h4 class="fw-bold">320</h4>
                        </div>
                        <div class="icon-box bg-primary">
                            <i class="fa-solid fa-cart-shopping text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 dashboard-card">
                <div class="card-body bg-warning text-light">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text">Customers</h6>
                            <h4 class="fw-bold">1,245</h4>
                        </div>
                        <div class="icon-box bg-warning">
                            <i class="fa-solid fa-users text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 dashboard-card">
                <div class="card-body bg-danger text-light">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text">Products</h6>
                            <h4 class="fw-bold">85</h4>
                        </div>
                        <div class="icon-box bg-danger">
                            <i class="fa-solid fa-box text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- SALES + RECENT ORDERS -->
    <div class="row mt-5">

        <!-- Sales Overview -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    Monthly Sales Progress
                </div>
                <div class="card-body">

                    <p class="mb-1">Electronics</p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" style="width: 75%">75%</div>
                    </div>

                    <p class="mb-1">Fashion</p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" style="width: 60%">60%</div>
                    </div>

                    <p class="mb-1">Home & Living</p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-warning" style="width: 45%">45%</div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    Recent Orders
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#Order</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#1021</td>
                                <td>Ali Khan</td>
                                <td>02 Mar 2026</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>$250</td>
                            </tr>
                            <tr>
                                <td>#1022</td>
                                <td>Usman Tariq</td>
                                <td>01 Mar 2026</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>$120</td>
                            </tr>
                            <tr>
                                <td>#1023</td>
                                <td>Ahmed Raza</td>
                                <td>28 Feb 2026</td>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td>$90</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection