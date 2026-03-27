@extends('admin.layout')
@section('admin-product-view')

<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Product Details</h4>

                        <div class="row">

                            <!-- Product Images -->
                            <div class="col-lg-4">

                                @if(!empty($product->image))

                                <div class="mb-3">
                                    <img id="mainImage"
                                        src="{{ asset('storage/'.$product->image[0]) }}"
                                        class="img-fluid rounded border"
                                        style="width:100%; height: 300px; object-fit: contain;">
                                </div>

                                <div class="d-flex flex-wrap gap-2">

                                    @foreach($product->image as $img)

                                    <img src="{{ asset('storage/'.$img) }}"
                                        class="img-thumbnail"
                                        style="width:70px; height: 70px; object-fit: cover; cursor:pointer"
                                        onclick="changeImage(this)">

                                    @endforeach

                                </div>

                                @endif

                            </div>

                            <!-- Product Info -->
                            <div class="col-lg-8">

                                <table class="table table-bordered">

                                    <tr>
                                        <th style="width:200px;">Product Name</th>
                                        <td><strong>{{ $product->product_name }}</strong></td>
                                    </tr>

                                    <tr>
                                        <th>Base Price</th>
                                        <td>Rs {{ number_format($product->base_price ?? $product->price, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th>Category</th>
                                        <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Sub Category</th>
                                        <td>{{ $product->subcategory->sub_category_name ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Total Stock</th>
                                        <td>
                                            @php
                                                $totalStock = $product->quantity;
                                                if($product->variants && $product->variants->count() > 0) {
                                                    $totalStock = $product->variants->sum('stock_quantity');
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $totalStock > 0 ? 'success' : 'danger' }}">
                                                {{ $totalStock > 0 ? $totalStock . ' units' : 'Out of Stock' }}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $product->description }}</td>
                                    </tr>

                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $product->created_at->format('d-m-Y H:i:s') }}</td>
                                    </tr>

                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $product->updated_at->format('d-m-Y H:i:s') }}</td>
                                    </tr>

                                </table>

                            </div>

                        </div>

                        <!-- Product Variants Section -->
                        @if($product->variants && $product->variants->count() > 0)
                        <hr>
                        <h4 class="mt-4">Product Variants</h4>
                        
                        @php
                            $groupedVariants = $product->variants->groupBy('variant_type');
                        @endphp
                        
                        @foreach($groupedVariants as $type => $variants)
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">{{ ucfirst($type) }} Options</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Variant Name</th>
                                                <th>Price Adjustment</th>
                                                <th>Final Price</th>
                                                <th>Stock Quantity</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($variants as $variant)
                                            <tr>
                                                <td>
                                                    <strong>{{ $variant->variant_name }}</strong>
                                                </td>
                                                <td>
                                                    @if($variant->price_adjustment > 0)
                                                        <span class="text-success">+Rs {{ number_format($variant->price_adjustment, 2) }}</span>
                                                    @elseif($variant->price_adjustment < 0)
                                                        <span class="text-danger">-Rs {{ number_format(abs($variant->price_adjustment), 2) }}</span>
                                                    @else
                                                        <span class="text-muted">No adjustment</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="text-success">
                                                        Rs {{ number_format(($product->base_price ?? $product->price) + $variant->price_adjustment, 2) }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    @if($variant->stock_quantity > 10)
                                                        <span class="badge bg-success">{{ $variant->stock_quantity }} in stock</span>
                                                    @elseif($variant->stock_quantity > 0)
                                                        <span class="badge bg-warning text-dark">{{ $variant->stock_quantity }} left</span>
                                                    @else
                                                        <span class="badge bg-danger">Out of stock</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($variant->stock_quantity > 0)
                                                        <span class="badge bg-success">Available</span>
                                                    @else
                                                        <span class="badge bg-danger">Unavailable</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif

                        <!-- Product Properties Section -->
                        @if($product->properties && $product->properties->count() > 0)
                        <hr>
                        <h4 class="mt-4">Product Properties</h4>

                        <div class="card">
                            <div class="card-body">
                                @foreach($product->properties as $property)
                                <div class="mb-3">
                                    <strong>{{ ucfirst($property->property_name) }} :</strong>
                                    <div class="mt-2">
                                        @foreach($property->values as $value)
                                        <span class="badge bg-primary me-1">
                                            {{ $value->value }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Pricing Summary -->
                        <hr>
                        <h4 class="mt-4">Pricing Summary</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Base Price</h6>
                                        <h4 class="text-primary">Rs {{ number_format($product->base_price ?? $product->price, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Price Range</h6>
                                        @if($product->variants && $product->variants->count() > 0)
                                            @php
                                                $minPrice = $product->base_price + $product->variants->min('price_adjustment');
                                                $maxPrice = $product->base_price + $product->variants->max('price_adjustment');
                                            @endphp
                                            <h4 class="text-success">Rs {{ number_format($minPrice, 2) }} - {{ number_format($maxPrice, 2) }}</h4>
                                        @else
                                            <h4 class="text-success">Rs {{ number_format($product->base_price ?? $product->price, 2) }}</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Variants</h6>
                                        <h4 class="text-info">{{ $product->variants->count() }} Options</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-info btn-rounded btn-fw">
                                <i class="fas fa-edit"></i> Edit Product
                            </a>
                            <a href="{{ route('admin.product.products') }}" class="btn btn-secondary btn-rounded btn-fw">
                                <i class="fas fa-arrow-left"></i> Back to Products
                            </a>
                            <button type="button" class="btn btn-danger btn-rounded btn-fw" onclick="confirmDelete({{ $product->id }})">
                                <i class="fas fa-trash"></i> Delete Product
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone and will also delete all variants and properties associated with this product.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function changeImage(element) {
        document.getElementById('mainImage').src = element.src;
    }
    
    function confirmDelete(productId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/product/${productId}/delete`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Add tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>

<style>
    .badge {
        padding: 5px 10px;
        font-size: 12px;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .img-thumbnail {
        transition: transform 0.2s;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.1);
        border-color: #007bff;
    }
    
    .btn-rounded {
        border-radius: 50px;
    }
    
    .btn-fw {
        min-width: 120px;
    }
</style>

@endsection