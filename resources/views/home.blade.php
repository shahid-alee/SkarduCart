@extends('layouts.main')
@push('title')
<title>Home Page</title>
@endpush
@section('content')

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('assets/images/slider3.png') }}" class="d-block w-100" alt="slider 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('assets/images/slider2.png') }}" class="d-block w-100" alt="slider 2">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('assets/images/slider1.png') }}" class="d-block w-100" alt="slider 3">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- product section -->

<section class="my-5">
    <div class="container">


        <div class="d-flex bd-highlight">
            <div class="flex-grow-1 bd-highlight"><h3>Top Deals</h3></div>
            <div ><a href="#" class="btn btn-sm theme-green-btn  text-light">View All</a></div>
            
        </div>

        <div class="row theme-product">
            <div class="col-lg-3">
                <div class="card">
                    <a href="3"><img src="{{ asset('assets/images/products/1.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Zero lifestyle watch</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 4,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
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
                    <a href="#" class="text-dark text-decoration-none"><img src="{{ asset('assets/images/products/3.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Nike shoes</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 2,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
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

        </div>


    </div>
</section>

<!-- best of electronics -->

<section class="my-5">
    <div class="container">


        <div class="d-flex bd-highlight">
            <div class="flex-grow-1 bd-highlight"><h3>Best of Electronics</h3></div>
            <div ><a href="#" class="btn btn-sm theme-orange-btn  text-light">View All</a></div>
            
        </div>

        <div class="row theme-product">
            <div class="col-lg-3">
                <div class="card">
                    <a href="3"><img src="{{ asset('assets/images/products/32.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Mini MicroWave Oven</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 14,999</h5>
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

            <div class="col-lg-3">
                <div class="card">
                    <a href="#" ><img src="{{ asset('assets/images/products/31.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Digital stove</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 2,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="#" ><img src="{{ asset('assets/images/products/23.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                        <h6 class="card-title text-center">Apple Iphone 11pro</h6></a>
                        <h5 class="card-text text-center">Rs 114,499</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

        </div>


    </div>
</section>

<!-- popular categories -->
 <section class="my-5">
    <div class="container">


        <div class="d-flex bd-highlight">
            <div class="flex-grow-1 bd-highlight"><h3>popular categories</h3></div>
            <div ><a href="#" class="btn btn-sm theme-orange-btn  text-light">View All</a></div>
            
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
                    <a href="#" ><img src="{{ asset('assets/images/products/8.jpg') }}" class="card-img-top" alt="product"></a>
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
                    <a href="#" ><img src="{{ asset('assets/images/products/18.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                        <h6 class="card-title text-center">Fancy girl sweater</h6></a>
                        <h5 class="card-text text-center">Rs 1,499</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

        </div>


    </div>
</section>



<!-- recently veiwed -->

<section class="my-5">
    <div class="container">


        <div class="d-flex bd-highlight">
            <div class="flex-grow-1 bd-highlight"><h3>Recently viewed</h3></div>
            <div ><a href="#" class="btn btn-sm theme-orange-btn  text-light">View All</a></div>
            
        </div>

        <div class="row theme-product">
            <div class="col-lg-3">
                <div class="card">
                    <a href="3"><img src="{{ asset('assets/images/products/32.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Mini MicroWave Oven</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 14,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="#"><img src="{{ asset('assets/images/products/26.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">living room set</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 111,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="#" ><img src="{{ asset('assets/images/products/31.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                            <h6 class="card-title text-center">Digital stove</h6>
                        </a>
                        <h5 class="card-text text-center">Rs 2,999</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <a href="#" ><img src="{{ asset('assets/images/products/18.jpg') }}" class="card-img-top" alt="product"></a>
                    <div class="card-body">
                        <a href="#" class="text-dark text-decoration-none">
                        <h6 class="card-title text-center">Fancy girl sweater</h6></a>
                        <h5 class="card-text text-center">Rs 1,499</h5>
                        <!-- <a href="#" class="btn btn-primary">Buy Now</a> -->
                    </div>
                </div>
            </div>

        </div>


    </div>
</section>

@endsection