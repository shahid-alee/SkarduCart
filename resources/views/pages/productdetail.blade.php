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
    
    .variant-group {
        border-left: 3px solid #ff6600;
        padding-left: 15px;
    }
    
    .selected-variant-summary {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
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
                @php
                    $groupedVariants = $product->variants->groupBy('variant_type');
                    $categoryName = strtolower($product->category->category_name ?? '');
                    $selectedVariants = [];
                @endphp
                
                <form action="{{ route('cart.add.combined') }}" method="POST" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    @foreach($groupedVariants as $type => $variants)
                        @if($type == 'generation' && $categoryName == 'mobiles')
                            @continue
                        @endif
                        
                        <div class="mb-4 variant-group">
                            <h5 class="mb-3">{{ ucfirst($type) }} Options:</h5>
                            <div class="row g-3" data-variant-type="{{ $type }}">
                                @foreach($variants as $variant)
                                <div class="col-md-4 col-sm-6">
                                    <div class="variant-option p-3" 
                                         data-variant-type="{{ $type }}"
                                         data-variant-id="{{ $variant->id }}"
                                         data-variant-name="{{ $variant->variant_name }}"
                                         data-price-adjustment="{{ $variant->price_adjustment }}"
                                         data-stock="{{ $variant->stock_quantity }}">
                                        <div class="text-center">
                                            <strong class="d-block mb-2">{{ $variant->variant_name }}</strong>
                                            @if($type == 'storage')
                                                <div class="text-success fw-bold mb-1">
                                                    + Rs {{ number_format($variant->price_adjustment, 2) }}
                                                </div>
                                            @endif
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
                            <input type="hidden" name="variants[{{ $type }}]" id="selected-{{ $type }}" value="">
                        </div>
                    @endforeach
                    
                    <!-- Selected Variants Summary -->
                    <div class="selected-variant-summary" id="selected-summary" style="display: none;">
                        <h6>Selected Options:</h6>
                        <div id="selected-options-list"></div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Price:</strong>
                            <strong class="text-success" id="total-price">Rs {{ number_format($product->base_price, 2) }}</strong>
                        </div>
                    </div>
                    
                    <!-- Quantity Selector -->
                    <div class="d-flex align-items-center mb-4 mt-4">
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
                        <button type="button" onclick="buyNow()" class="btn theme-orange-btn text-light rounded-pill px-4">
                            <i class="fas fa-bolt me-2"></i> Buy Now
                        </button>
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
        <!-- Product Reviews Section -->
<div class="mt-5">
    <h4 class="mb-4">Customer Reviews</h4>

    @if($product->reviews && $product->reviews->count() > 0)

        @foreach($product->reviews as $review)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body">

                <!-- User + Rating -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                        <div class="small text-muted">
                            {{ $review->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div>
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $review->rating)
                                <span class="fa fa-star text-warning"></span>
                            @else
                                <span class="fa fa-star text-secondary"></span>
                            @endif
                        @endfor
                    </div>
                </div>

                <!-- Review Content -->
                <p class="mb-0 text-muted">
                    {{ $review->review }}
                </p>

            </div>
        </div>
        @endforeach

    @else
        <div class="alert alert-info">
            No reviews yet. Be the first to review this product!
        </div>
    @endif
</div>
    </div>
</section>

<script>
    let selectedVariants = {};
    let basePrice = {{ $product->base_price }};
    
    function changeImage(element) {
        document.getElementById("mainImage").src = element.src;
    }
    
    function increaseQty() {
        let qty = document.getElementById('quantity');
        let currentQty = parseInt(qty.value);
        let maxQty = parseInt(qty.max);
        if (currentQty < maxQty) {
            qty.value = currentQty + 1;
            updateStockMessage();
        }
    }

    function decreaseQty() {
        let qty = document.getElementById('quantity');
        if (qty.value > 1) {
            qty.value = parseInt(qty.value) - 1;
            updateStockMessage();
        }
    }
    
    function updateStockMessage() {
        // Find minimum stock among selected variants
        let minStock = Infinity;
        let allSelected = true;
        
        // Get all variant groups that have variants
        document.querySelectorAll('.variant-group').forEach(group => {
            const variantOptions = group.querySelectorAll('.variant-option');
            
            // Only require selection if there are variants in this group
            if (variantOptions.length > 0) {
                const type = group.querySelector('.row').getAttribute('data-variant-type');
                if (selectedVariants[type]) {
                    const stock = selectedVariants[type].stock;
                    if (stock < minStock) minStock = stock;
                } else {
                    allSelected = false;
                }
            }
        });
        
        const quantity = parseInt(document.getElementById('quantity').value);
        const stockMessage = document.getElementById('stock-message');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        
        if (!allSelected) {
            stockMessage.innerHTML = '<i class="fas fa-info-circle text-info"></i> Please select all options';
            stockMessage.className = 'ms-3 text-info small';
            addToCartBtn.disabled = true;
            addToCartBtn.style.opacity = '0.6';
            addToCartBtn.style.cursor = 'not-allowed';
            return;
        }
        
        if (minStock === Infinity) {
            stockMessage.innerHTML = '';
            addToCartBtn.disabled = false;
            addToCartBtn.style.opacity = '1';
            addToCartBtn.style.cursor = 'pointer';
            return;
        }
        
        if (quantity > minStock) {
            stockMessage.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i> Only ${minStock} items available`;
            stockMessage.className = 'ms-3 text-danger small';
            addToCartBtn.disabled = true;
        } else if (minStock > 10) {
            stockMessage.innerHTML = '<i class="fas fa-check-circle text-success"></i> In Stock';
            stockMessage.className = 'ms-3 text-success small';
            addToCartBtn.disabled = false;
        } else if (minStock > 0) {
            stockMessage.innerHTML = `<i class="fas fa-exclamation-circle text-warning"></i> Only ${minStock} left in stock`;
            stockMessage.className = 'ms-3 text-warning small';
            addToCartBtn.disabled = false;
        } else {
            stockMessage.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Out of Stock';
            stockMessage.className = 'ms-3 text-danger small';
            addToCartBtn.disabled = true;
        }
        
        addToCartBtn.style.opacity = addToCartBtn.disabled ? '0.6' : '1';
        addToCartBtn.style.cursor = addToCartBtn.disabled ? 'not-allowed' : 'pointer';
        
        // Update quantity max based on stock
        if (minStock !== Infinity) {
            document.getElementById('quantity').max = minStock;
            if (quantity > minStock) {
                document.getElementById('quantity').value = minStock;
            }
        }
    }
    
    function updatePriceAndSummary() {
        let totalPrice = basePrice;
        let summaryHtml = '';
        
        for (let type in selectedVariants) {
            const variant = selectedVariants[type];
            totalPrice += variant.priceAdjustment;
            summaryHtml += `<div><strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${variant.name}</div>`;
        }
        
        document.getElementById('total-price').innerHTML = `Rs ${totalPrice.toFixed(2)}`;
        document.getElementById('display-price').innerHTML = `Rs ${totalPrice.toFixed(2)}`;
        document.getElementById('selected-options-list').innerHTML = summaryHtml;
        
        const summaryDiv = document.getElementById('selected-summary');
        if (Object.keys(selectedVariants).length > 0) {
            summaryDiv.style.display = 'block';
        } else {
            summaryDiv.style.display = 'none';
        }
        
        updateStockMessage();
    }
    
    // Variant selection handling
    document.querySelectorAll('.variant-option').forEach(option => {
        option.addEventListener('click', function() {
            const variantType = this.dataset.variantType;
            const variantId = this.dataset.variantId;
            const variantName = this.dataset.variantName;
            const priceAdjustment = parseFloat(this.dataset.priceAdjustment);
            const stock = parseInt(this.dataset.stock);
            
            // Check if out of stock
            if (stock <= 0) {
                alert('This option is out of stock!');
                return;
            }
            
            // Remove selected class from same type variants
            document.querySelectorAll(`.variant-option[data-variant-type="${variantType}"]`).forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Store selected variant
            selectedVariants[variantType] = {
                id: variantId,
                name: variantName,
                priceAdjustment: priceAdjustment,
                stock: stock
            };
            
            // Update hidden input
            const hiddenInput = document.getElementById(`selected-${variantType}`);
            if (hiddenInput) {
                hiddenInput.value = variantId;
            }
            
            // Update price and summary
            updatePriceAndSummary();
        });
    });
    
    function buyNow() {
        const form = document.getElementById('add-to-cart-form');
        
        // Check if all required variants are selected
        const variantGroups = document.querySelectorAll('.variant-group');
        let allSelected = true;
        
        variantGroups.forEach(group => {
            const variantOptions = group.querySelectorAll('.variant-option');
            if (variantOptions.length > 0) {
                const type = group.querySelector('.row').getAttribute('data-variant-type');
                if (!selectedVariants[type]) {
                    allSelected = false;
                    alert(`Please select a ${type} option`);
                    return false;
                }
            }
        });
        
        if (!allSelected) {
            return;
        }
        
        const originalAction = form.action;
        form.action = "{{ route('cart.buynow') }}";
        form.submit();
        form.action = originalAction;
    }
    
    // Form submission handler
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        const variantGroups = document.querySelectorAll('.variant-group');
        let allSelected = true;
        
        variantGroups.forEach(group => {
            const variantOptions = group.querySelectorAll('.variant-option');
            // Only require selection if there are variants in this group
            if (variantOptions.length > 0) {
                const type = group.querySelector('.row').getAttribute('data-variant-type');
                if (!selectedVariants[type]) {
                    allSelected = false;
                    alert(`Please select a ${type} option`);
                    e.preventDefault();
                    return false;
                }
            }
        });
        
        if (!allSelected) {
            e.preventDefault();
            return false;
        }
        
        // Check stock
        const quantity = parseInt(document.getElementById('quantity').value);
        let minStock = Infinity;
        
        for (let type in selectedVariants) {
            if (selectedVariants[type].stock < minStock) {
                minStock = selectedVariants[type].stock;
            }
        }
        
        if (minStock !== Infinity && quantity > minStock) {
            e.preventDefault();
            alert(`Only ${minStock} items available in stock.`);
            return false;
        }
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set max quantity based on first variant if exists
        const firstVariant = document.querySelector('.variant-option');
        if (firstVariant && firstVariant.dataset.stock) {
            document.getElementById('quantity').max = firstVariant.dataset.stock;
        }
        
        // If there's only one variant per type, auto-select it
        document.querySelectorAll('.variant-group').forEach(group => {
            const variantOptions = group.querySelectorAll('.variant-option');
            if (variantOptions.length === 1 && variantOptions[0].dataset.stock > 0) {
                variantOptions[0].click();
            }
        });
    });
</script>

@endsection