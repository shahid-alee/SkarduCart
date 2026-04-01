@extends('layouts.main')

@section('content')
<div class="container-fluid bg-light p-5">
    <h1 class="text-center">
        <i class="fas fa-shopping-cart"></i> Shopping Cart
    </h1>
</div>

<div class="container my-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(empty($cart))
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('index') }}" class="btn theme-green-btn text-light rounded-pill px-4">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($cart as $key => $item)
<tr>
    <td>
        <div>
            <h6 class="mb-0">{{ $item['product_name'] }}</h6>
            @if(isset($item['variant_details']))
                @foreach($item['variant_details'] as $type => $variant)
                    <small class="text-muted d-block">{{ ucfirst($type) }}: {{ $variant['name'] }}</small>
                @endforeach
            @endif
        </div>
    </td>
    <td>Rs {{ number_format($item['price'], 2) }}</td>
    <td>
        <form action="{{ route('cart.update', $key) }}" method="POST" class="d-flex">
            @csrf
            @method('PUT')
            <input type="number" 
                   name="quantity" 
                   value="{{ $item['quantity'] }}" 
                   min="1" 
                   max="100"
                   class="form-control" 
                   style="width: 80px;">
            <button type="submit" class="btn btn-sm btn-primary ms-2">Update</button>
        </form>
    </td>
    <td>Rs {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
    <td>
        <form action="{{ route('cart.remove', $key) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
@endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash-alt"></i> Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Cart Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rs {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery:</span>
                            <span>Rs {{ number_format($delivery, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-success">Rs {{ number_format($total, 2) }}</strong>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn theme-green-btn text-light w-100">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection