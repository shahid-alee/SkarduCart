@extends('layouts.main')
@push('title')
<title>Product Detail</title>
@endpush
@section('content')



<div class="container-fluid bg-light p-5">
    <h1 class="text-center"><i class="fa-brands fa-product-hunt"></i></i>Product Detail</h1>
</div>

<section class="my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card" style="width: 20rem;">
                    <img src="{{ asset('assets/images/products/41.jpg') }}" class="rounded img-fluid" alt="...">

                </div>
            </div>
            <div class="col-lg-8">
                <div>
                    <h3>Samsung LED TV</h3>

                    <h4>RS 69,999</h4>
                    <div>

                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>

                        <h6>5 Customer Ratings</h6>
                        <!-- </div>
                    <a href="#">Add to Cart</a>
                    <a href="#">Buy Now</a>
                </div> -->
                        <div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime impedit illo accusamus fugit alias sint odio consectetur quos consequuntur. Eum eius odio eos magnam ducimus ullam? Quod ipsam ipsum odit!
                            </p>
                        </div>
                        <div>
                            <a href="#" class="btn theme-green-btn text-light rounded-pill me-3">Add to Cart</a>
                            <a href="#" class="btn theme-orange-btn text-light rounded-pill">Buy Now</a>
                        </div>

                    </div>


                </div>


            </div>
            <div class="my-5">
                <h4>Product Description</h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime impedit illo accusamus fugit alias sint odio consectetur quos consequuntur. Eum eius odio eos magnam ducimus ullam? Quod ipsam ipsum odit!
                </p>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime impedit illo accusamus fugit alias sint odio consectetur quos consequuntur. Eum eius odio eos magnam ducimus ullam? Quod ipsam ipsum odit!
                </p>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime impedit illo accusamus fugit alias sint odio consectetur quos consequuntur. Eum eius odio eos magnam ducimus ullam? Quod ipsam ipsum odit!
                </p>
            </div>

            <!--  Related products -->
            <div>
                <section>
                    <div class="container">


                        <div class="d-flex bd-highlight">
                            <div class="flex-grow-1 bd-highlight">
                                <h3>Related products</h3>
                            </div>
                            <div><a href="#" class="btn btn-sm theme-orange-btn  text-light">View All</a></div>

                        </div>

                        <div class="row theme-product">
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
                                    <a href="#"><img src="{{ asset('assets/images/products/6.jpg') }}" class="card-img-top" alt="product"></a>
                                    <div class="card-body">
                                        <a href="#" class="text-dark text-decoration-none">
                                            <h6 class="card-title text-center">ladies shoes</h6>
                                        </a>
                                        <h5 class="card-text text-center">Rs 5,999</h5>
                                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="card">
                                    <a href="#"><img src="{{ asset('assets/images/products/8.jpg') }}" class="card-img-top" alt="product"></a>
                                    <div class="card-body">
                                        <a href="#" class="text-dark text-decoration-none">
                                            <h6 class="card-title text-center">Apple Airbads</h6>
                                        </a>
                                        <h5 class="card-text text-center">Rs 4,999</h5>
                                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="card">
                                    <a href="#"><img src="{{ asset('assets/images/products/18.jpg') }}" class="card-img-top" alt="product"></a>
                                    <div class="card-body">
                                        <a href="#" class="text-dark text-decoration-none">
                                            <h6 class="card-title text-center">Fancy girl sweater</h6>
                                        </a>
                                        <h5 class="card-text text-center">Rs 1,499</h5>
                                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </section>
            </div>
            <hr>
            <section>
                <h3>2 Reviews</h3>
                <div class="row">
                    <div class="col-lg-2">
                        <img src="{{ asset('assets/images/reviews/user.jpg') }}" class=" rounded-circle img-fluid" alt="review user">

                    </div>
                    <div class="col-lg-10">

                    </div>
                </div>
            </section>
</section>



@endsection