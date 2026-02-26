@extends('layouts.main')
@push('title')
<title>Categories</title>
@endpush
@section('content')

<div class="container-fluid bg-light p-5">
    <h1 class="text-center"><i class="fa-solid fa-layer-group"></i>Category</h1>
</div>

<section class="my-5">
    <div class="container">


        <div class="row theme-product">
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/23.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Apple Iphone 12pro</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 169,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/2.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Head phone</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 1,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="3"><img src="{{ asset('assets/images/products/7.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Nikkon D750 Camera</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 244,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/17.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Portable Speaker</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 1,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#" class="text-dark text-decoration-none"><img src="{{ asset('assets/images/products/8.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#"></a>
                        <h6 class="card-title text-center">Apple airbads</h6></a>
                        <h5 class="card-text text-center">Rs 4,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

           

            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#" class="text-dark text-decoration-none"><img src="{{ asset('assets/images/products/22.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#"></a>
                        <h6 class="card-title text-center">Meizu 16th smartphone</h6></a>
                        <h5 class="card-text text-center">Rs 44,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>


            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/24.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Apple Iphone 11pro</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 114,499</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

        </div>


    </div>
</section>

@endsection