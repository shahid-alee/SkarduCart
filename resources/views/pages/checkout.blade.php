@extends('layouts.main')

@section('content')
<div class="container-fluid bg-light p-5">
    <h1 class="text-center">
        <i class="fas fa-credit-card"></i> Checkout
    </h1>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Shipping Information</h5>
                    
                    <form action="{{ route('stripe.checkout') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ Auth::user()->email }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Special instructions for delivery"></textarea>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Payment Method</h5>
                        
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment" id="cash_on_delivery" value="cod" checked>
                                <label class="form-check-label" for="cash_on_delivery">
                                    <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id="card_payment" value="card">
                                <label class="form-check-label" for="card_payment">
                                    <i class="fab fa-cc-stripe"></i> Credit / Debit Card 
                                </label>
                            </div>
                        </div>
                        
                        <div id="card-details" style="display: none;" class="mt-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> You will be redirected to secure payment page after placing order.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn theme-green-btn text-light btn-lg" id="place-order-btn">
                                <i class="fas fa-check-circle"></i> Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <hr>
                    
                    @foreach($cart as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item['product_name'] }} x {{ $item['quantity'] }}</span>
                        <span>Rs {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                    @endforeach
                    
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide card details based on payment method selection
    document.querySelectorAll('input[name="payment"]').forEach((elem) => {
        elem.addEventListener('change', function() {
            const cardDetails = document.getElementById('card-details');
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });
    
    // Form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment"]:checked').value;
        const placeOrderBtn = document.getElementById('place-order-btn');
        
        // Disable button to prevent double submission
        placeOrderBtn.disabled = true;
        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // If COD, submit normally
        if (paymentMethod === 'cod') {
            return true;
        }
        
        // For card payment, the form will be submitted to Stripe
        return true;
    });
</script>

<style>
    .form-check-input:checked {
        background-color: #ff6600;
        border-color: #ff6600;
    }
    
    .theme-green-btn {
        background-color: #28a745;
        transition: all 0.3s ease;
    }
    
    .theme-green-btn:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }
    
    .theme-green-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endsection