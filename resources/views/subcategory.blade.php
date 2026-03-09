@extends('layouts.main')
@push('title')
<title>Subcategories</title>
@endpush
@section('content')

<div class="container-fluid bg-light p-5">
    <h1 class="text-center"><i class="fa-solid fa-list"></i>Sub Category</h1>
</div>

<section class="my-5">
    <div class="container">


        <div class="row theme-product">
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="{{url('category/electronics/tv/samsung')}}"><img src="{{ asset('assets/images/products/41.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="{{url('category/electronics/tv/samsung')}}" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Samsung LED TV</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 69,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/42.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Haier LED TV</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 49,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="3"><img src="{{ asset('assets/images/products/43.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Dawlance Smart TV</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 74,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/44.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Sony LED TV</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 66,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            

           

            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#" class="text-dark text-decoration-none">
                        <img src="{{ asset('assets/images/products/45.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#"></a>
                        <h6 class="card-title text-center">Apple Smart TV</h6></a>
                        <h5 class="card-text text-center">Rs 94,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>


            <div class="col-lg-3 mb-4">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/46.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">LG Smart TV</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 34,560</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

        </div>


    </div>
</section>

@endsection