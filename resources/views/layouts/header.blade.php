<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <title>Home page</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg theme-navbar">
        <div class="container">
            <a class="navbar-brand text-light" href="{{url('/')}}">
                <img src="{{ asset('assets/images/logos/logo3-Photoroom.png') }}" style="width: 250px;" class="card-img-top" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div>
                <form class="d-flex">
                    <div class="input-group">
                        <input class="form-control form-control-sm " style="width: 350px;" type="search" placeholder="Search Products" aria-label="Search">
                        <button class="btn btn-light btn-sm text-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <div>
                <a href="#" class="text-decoration-none text-light">Become a seller</a>
                <a href="#" class="btn theme-green-btn btn-sm text-light"><i class="fa-solid fa-cart-arrow-down"></i>Cart</a>
                <a href="#" class="btn theme-orange-btn btn-sm text-light"><i class="fa-solid fa-user"></i>Login</a>
            </div>
        </div>
    </nav>

    <!-- category Nav Bar -->

    <nav class="navbar navbar-expand-lg theme-navbar-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link active text-dark" href="#">Mobile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-dark" href="#">Fashion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-dark" href="#">Electronics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-dark" href="#">Furniture</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-dark" href="#">Grocery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-dark" href="#">Appliances</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>