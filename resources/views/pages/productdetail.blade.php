@extends('layouts.main')

@push('title')
<title>{{ $product->product_name }}</title>
@endpush

@section('content')
<style>
    .thumb-img {
        transition: 0.3s;
        border: 2px solid transparent;
    }

    .thumb-img:hover {
        border: 2px solid #ff6600;
        transform: scale(1.05);
    }

    .variant-option {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .variant-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .variant-option.selected {
        border-color: #ff6600;
        background-color: #fff8f0;
        box-shadow: 0 0 0 2px rgba(255,102,0,0.2);
    }

    .price-transition {
        transition: all 0.3s ease;
    }

    .stock-badge {
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .stock-low {
        background-color: #ffc107;
        color: #000;
    }

    .stock-out {
        background-color: #dc3545;
        color: #fff;
    }

    .stock-in {
        background-color: #28a745;
        color: #fff;
    }
</style>

<div class="container-fluid bg-light p-5">
    <h1 class="text-center">
        <i class="fa-brands fa-product-hunt"></i> Product Detail
    </h1>
</div>

<section class="my-5">
    <div class="container">
        <div class="row">

            <!-- Product Images Section -->
            <div class="col-lg-5">
                <div class="card mb-3 shadow-sm">
                    <img id="mainImage"
                        src="{{ asset('storage/'.$product->image[0]) }}"
                        class="img-fluid rounded"
                        alt="{{ $product->product_name }}"
                        style="height: 400px; object-fit: contain;">
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @foreach($product->image as $img)
                    <img src="{{ asset('storage/'.$img) }}"
                        class="img-thumbnail thumb-img"
                        style="width:80px; height:80px; object-fit: cover; cursor:pointer;"
                        onclick="changeImage(this)">
                    @endforeach
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="col-lg-7">
                <h2 class="mb-3">{{ $product->product_name }}</h2>
                
                <!-- Price Display -->
                <div class="mb-3">
                    <h3 class="text-success" id="display-price">
                        Rs {{ number_format($product->base_price, 2) }}
                    </h3>
                    <small class="text-muted">Inclusive of all taxes</small>
                </div>

                <!-- Rating Display -->
                @php
                $avgRating = round($product->reviews->avg('rating'), 1);
                $reviewCount = $product->reviews->count();
                @endphp

                <div class="d-flex align-items-center mb-3">
                    <div class="me-2">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $avgRating)
                                <span class="fa fa-star text-warning"></span>
                            @else
                                <span class="fa fa-star text-secondary"></span>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted">({{ $reviewCount }} reviews)</span>
                </div>

                <!-- Short Description -->
                <div class="mb-4">
                    <p class="text-muted">{{ Str::limit($product->description, 150) }}</p>
                </div>

                <!-- Product Variants Section -->
                @if($product->variants && $product->variants->count() > 0)
                    @php
                        $groupedVariants = $product->variants->groupBy('variant_type');
                        // Get category name (convert to lowercase for comparison)
                        $categoryName = strtolower($product->category->category_name ?? '');
                    @endphp
                    
                    @foreach($groupedVariants as $type => $variants)
                        <!-- Skip generation variants for mobile category -->
                        @if($type == 'generation' && $categoryName == 'mobiles')
                            @continue
                        @endif
                        
                        <!-- Skip storage variants for laptops? (optional - you can customize) -->
                        {{-- @if($type == 'storage' && $categoryName == 'laptops')
                            @continue
                        @endif --}}
                        
                        <div class="mb-4">
                            <h5 class="mb-3">{{ ucfirst($type) }} Options:</h5>
                            <div class="row g-3">
                                @foreach($variants as $variant)
                                <div class="col-md-4 col-sm-6">
                                    <div class="variant-option p-3" 
                                         data-variant-id="{{ $variant->id }}"
                                         data-variant-name="{{ $variant->variant_name }}"
                                         data-price="{{ $product->base_price + $variant->price_adjustment }}"
                                         data-stock="{{ $variant->stock_quantity }}">
                                        <div class="text-center">
                                            <strong class="d-block mb-2">{{ $variant->variant_name }}</strong>
                                            <div class="text-success fw-bold mb-1">
                                                Rs {{ number_format($product->base_price + $variant->price_adjustment, 2) }}
                                            </div>
                                            @if($variant->stock_quantity > 10)
                                                <span class="stock-badge stock-in">In Stock</span>
                                            @elseif($variant->stock_quantity > 0)
                                                <span class="stock-badge stock-low">Only {{ $variant->stock_quantity }} left</span>
                                            @else
                                                <span class="stock-badge stock-out">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Original Properties (if any) -->
                @if($product->properties && $product->properties->count() > 0)
                    @foreach($product->properties as $property)
                    <div class="mb-3">
                        <strong>{{ ucfirst($property->property_name) }} :</strong>
                        <div class="mt-2">
                            @foreach($property->values as $value)
                            <label class="me-3">
                                <input type="radio"
                                    name="property[{{ $property->id }}]"
                                    value="{{ $value->id }}"
                                    required>
                                {{ $value->value }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif

                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add', $product->id) }}" method="POST" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="variant_id" id="selected-variant-id" value="">
                    
                    <!-- Quantity Selector -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 fw-bold">Quantity :</div>
                        <div class="input-group" style="width: 130px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQty()">-</button>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="100" 
                                   class="form-control text-center" 
                                   style="width: 60px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQty()">+</button>
                        </div>
                        <span id="stock-message" class="ms-3 text-muted small"></span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn theme-green-btn text-light rounded-pill px-4" id="add-to-cart-btn">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                        
                        @auth
                        <a href="{{ route('checkout', $product->id) }}" class="btn theme-orange-btn text-light rounded-pill px-4">
                            <i class="fas fa-bolt me-2"></i> Buy Now
                        </a>
                        @endauth
                        
                        @guest
                        <a href="{{ route('login.form') }}" class="btn theme-orange-btn text-light rounded-pill px-4">
                            <i class="fas fa-bolt me-2"></i> Buy Now
                        </a>
                        @endguest
                    </div>
                </form>

                <!-- Product Details Accordion -->
                <div class="mt-5">
                    <div class="accordion" id="productAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#description">
                                    Product Description
                                </button>
                            </h2>
                            <div id="description" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#specifications">
                                    Specifications
                                </button>
                            </h2>
                            <div id="specifications" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <table class="table table-bordered">
                                         <tr>
                                            <th>Product Name</th>
                                            <td>{{ $product->product_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sub Category</th>
                                            <td>{{ $product->subcategory->sub_category_name ?? 'N/A' }}</td>
                                        </tr>
                                        @if($product->variants && $product->variants->count() > 0)
                                        <tr>
                                            <th>Available Options</th>
                                            <td>
                                                @foreach($product->variants->groupBy('variant_type') as $type => $variants)
                                                    @php
                                                        $categoryName = strtolower($product->category->category_name ?? '');
                                                        // Skip generation for mobiles in specifications as well
                                                        if($type == 'generation' && $categoryName == 'mobiles') {
                                                            continue;
                                                        }
                                                    @endphp
                                                    <strong>{{ ucfirst($type) }}:</strong> 
                                                    {{ $variants->pluck('variant_name')->implode(', ') }}<br>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section -->
@if(isset($products) && $products->count() > 0)
<section class="my-5">
    <div class="container">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            @foreach($products as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('storage/'.$relatedProduct->image[0]) }}" 
                         class="card-img-top" 
                         alt="{{ $relatedProduct->product_name }}"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h6 class="card-title">{{ $relatedProduct->product_name }}</h6>
                        <p class="text-success fw-bold">Rs {{ number_format($relatedProduct->base_price, 2) }}</p>
                        <a href="{{ route('product.show', $relatedProduct->id) }}" class="btn btn-sm theme-green-btn text-light">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Reviews Section -->
<section class="my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="mb-4">Customer Reviews ({{ $product->reviews->count() }})</h3>
                
                @forelse($product->reviews as $review)
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-3"
                                    src="{{ $review->user && $review->user->profile_image 
                                        ? asset('images/users/'.$review->user->profile_image) 
                                        : asset('assets/images/default-user.png') }}"
                                    alt="Profile image"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1">{{ $review->user->name ?? 'Anonymous' }}</h5>
                                    <small class="text-muted">{{ $review->created_at->format('d-m-Y') }}</small>
                                </div>
                            </div>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <span class="fa fa-star text-warning"></span>
                                    @else
                                        <span class="fa fa-star text-secondary"></span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="card-text mt-3">{{ $review->review }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    // Image changer function
    function changeImage(element) {
        document.getElementById("mainImage").src = element.src;
    }
    
    // Quantity functions
    function increaseQty() {
        let qty = document.getElementById('quantity');
        let maxQty = parseInt(qty.max);
        let currentQty = parseInt(qty.value);
        if (currentQty < maxQty) {
            qty.value = currentQty + 1;
        }
    }

    function decreaseQty() {
        let qty = document.getElementById('quantity');
        if (qty.value > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }
    
    // Variant selection handling
    let selectedVariant = null;
    
    document.querySelectorAll('.variant-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            document.querySelectorAll('.variant-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Get variant data
            const variantId = this.dataset.variantId;
            const variantPrice = parseFloat(this.dataset.price);
            const variantStock = parseInt(this.dataset.stock);
            const variantName = this.dataset.variantName;
            
            // Store selected variant
            selectedVariant = {
                id: variantId,
                price: variantPrice,
                stock: variantStock,
                name: variantName
            };
            
            // Update hidden input
            document.getElementById('selected-variant-id').value = variantId;
            
            // Update price display with animation
            const priceElement = document.getElementById('display-price');
            priceElement.style.opacity = '0';
            setTimeout(() => {
                priceElement.innerHTML = `Rs ${variantPrice.toFixed(2)}`;
                priceElement.style.opacity = '1';
            }, 150);
            
            // Update quantity max based on stock
            const quantityInput = document.getElementById('quantity');
            quantityInput.max = variantStock;
            quantityInput.value = 1;
            
            // Update stock message
            const stockMessage = document.getElementById('stock-message');
            if (variantStock > 10) {
                stockMessage.innerHTML = '<i class="fas fa-check-circle text-success"></i> In Stock';
                stockMessage.className = 'ms-3 text-success small';
            } else if (variantStock > 0) {
                stockMessage.innerHTML = `<i class="fas fa-exclamation-circle text-warning"></i> Only ${variantStock} left in stock`;
                stockMessage.className = 'ms-3 text-warning small';
            } else {
                stockMessage.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Out of Stock';
                stockMessage.className = 'ms-3 text-danger small';
                document.getElementById('add-to-cart-btn').disabled = true;
                return;
            }
            
            // Enable add to cart button
            document.getElementById('add-to-cart-btn').disabled = false;
        });
    });
    
    // If no variant is selected, disable add to cart button (if variants exist)
    @if($product->variants && $product->variants->count() > 0)
        // Initially disable add to cart until variant is selected
        document.getElementById('add-to-cart-btn').disabled = true;
        document.getElementById('add-to-cart-btn').style.opacity = '0.6';
        document.getElementById('add-to-cart-btn').style.cursor = 'not-allowed';
        
        // Enable when variant is selected
        document.querySelectorAll('.variant-option').forEach(option => {
            option.addEventListener('click', function() {
                document.getElementById('add-to-cart-btn').disabled = false;
                document.getElementById('add-to-cart-btn').style.opacity = '1';
                document.getElementById('add-to-cart-btn').style.cursor = 'pointer';
            });
        });
    @endif
    
    // Prevent form submission if variant is required but not selected
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        @if($product->variants && $product->variants->count() > 0)
            const variantId = document.getElementById('selected-variant-id').value;
            if (!variantId) {
                e.preventDefault();
                alert('Please select a product variant before adding to cart.');
                return false;
            }
        @endif
        
        // Check stock before submission
        if (selectedVariant) {
            const quantity = parseInt(document.getElementById('quantity').value);
            if (quantity > selectedVariant.stock) {
                e.preventDefault();
                alert(`Only ${selectedVariant.stock} items available in stock.`);
                return false;
            }
        }
    });
</script>

<!-- Add this to handle price transition animation -->
<style>
    #display-price {
        transition: opacity 0.15s ease-in-out;
    }
    
    .variant-option {
        transition: all 0.2s ease;
    }
    
    .variant-option.selected {
        border: 2px solid #ff6600;
        background: linear-gradient(135deg, #fff8f0 0%, #fff 100%);
    }
</style>

@endsection