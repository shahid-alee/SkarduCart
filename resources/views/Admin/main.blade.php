@include('Admin.header')

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar p-3">

        <div class="text-center mb-4">
            <h4 class="text-white fw-bold">Admin Panel</h4>
            <hr class="bg-light">
        </div>

        <ul class="nav flex-column sidebar-nav">

            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box me-2"></i> Products
                </a>
            </li>

            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Orders
                </a>
            </li>

            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> Customers
                </a>
            </li>

            <!-- Dropdown Menu -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#settingsMenu">
                    <i class="fa-solid fa-gear me-2"></i> Settings
                </a>
                <div class="collapse" id="settingsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">General</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Profile</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mt-3">
                <form action="#" method="POST">
                    @csrf
                    <button class="btn btn-danger w-100 btn-sm">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </li>

        </ul>

    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content flex-grow-1 p-4" style="background-color: whitesmoke;">
        @yield('content')
    </div>

</div>

</body>




@include('Admin.footer')