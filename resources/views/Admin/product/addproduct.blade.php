@extends('admin.layout')
@section('admin-product-add')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($product) ? 'Edit' : 'Add New' }} Product</h4>

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ isset($product) ? route('product.update', $product->id) : route('product.store') }}"
                            method="POST" enctype="multipart/form-data" id="product-form">
                            @csrf
                            @if(isset($product)) @method('PUT') @endif

                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" class="form-control" 
                                    value="{{ $product->product_name ?? old('product_name') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Base Price (Rs)</label>
                                <input type="number" step="0.01" name="base_price" class="form-control" 
                                    value="{{ $product->base_price ?? old('base_price') }}" required>
                                <small class="text-muted">Base price before variant adjustments</small>
                            </div>

                            <div class="form-group">
                                <label>Product Images</label>
                                <input type="file" name="image[]" class="form-control" multiple>
                                @if(isset($product) && $product->image)
                                    <div class="mt-2">
                                        @foreach($product->image as $img)
                                            <img src="{{ asset('storage/'.$img) }}" width="50" class="mr-1">
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        data-category-name="{{ strtolower($category->category_name) }}"
                                        {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Sub Category</label>
                                <select name="subcategory_id" class="form-control" required>
                                    <option value="">Select Sub Category</option>
                                    @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}"
                                        {{ (isset($product) && $product->subcategory_id == $subcategory->id) ? 'selected' : '' }}>
                                        {{ $subcategory->sub_category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" style="height:150px;" required>{{ $product->description ?? old('description') }}</textarea>
                            </div>

                            <hr>
                            <h4>Product Variants</h4>
                            <p class="text-muted">Add variants like storage options, generations, or colors with price adjustments</p>

                            <!-- Storage Variants -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Storage Options</h5>
                                </div>
                                <div class="card-body">
                                    <div id="storage-variants-container">
                                        @php
                                            $storageVariants = isset($variantsByType['storage']) ? $variantsByType['storage'] : collect();
                                            $variantIndex = 0;
                                        @endphp
                                        
                                        @if($storageVariants->count() > 0)
                                            @foreach($storageVariants as $variant)
                                            <div class="row mb-2 storage-variant" data-variant-index="{{ $variantIndex }}">
                                                <div class="col-md-4">
                                                    <input type="text" name="variants[{{ $variantIndex }}][name]" 
                                                        class="form-control" placeholder="Storage (e.g., 128GB)" 
                                                        value="{{ $variant->variant_name }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" step="0.01" name="variants[{{ $variantIndex }}][price_adjustment]" 
                                                        class="form-control" placeholder="Price (+Rs)" 
                                                        value="{{ $variant->price_adjustment }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" name="variants[{{ $variantIndex }}][stock]" 
                                                        class="form-control" placeholder="Stock" 
                                                        value="{{ $variant->stock_quantity }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="hidden" name="variants[{{ $variantIndex }}][type]" value="storage">
                                                    <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                                                </div>
                                            </div>
                                            @php $variantIndex++; @endphp
                                            @endforeach
                                        @else
                                            <div class="row mb-2 storage-variant" data-variant-index="0">
                                                <div class="col-md-4">
                                                    <input type="text" name="variants[0][name]" class="form-control" placeholder="Storage (e.g., 128GB)">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" step="0.01" name="variants[0][price_adjustment]" class="form-control" placeholder="Price (+Rs)">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" name="variants[0][stock]" class="form-control" placeholder="Stock">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="hidden" name="variants[0][type]" value="storage">
                                                    <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                                                </div>
                                            </div>
                                            @php $variantIndex = 1; @endphp
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addVariant('storage')">Add Storage Option</button>
                                </div>
                            </div>

                            <!-- Generation Variants (for laptops) -->
                            <div id="generation-section" style="display: {{ (isset($product) && $product->category && strtolower($product->category->category_name) == 'laptops') ? 'block' : 'none' }};">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5>Processor Generation (Laptops Only)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="generation-variants-container">
                                            @php
                                                $generationVariants = isset($variantsByType['generation']) ? $variantsByType['generation'] : collect();
                                            @endphp
                                            
                                            @if($generationVariants->count() > 0)
                                                @foreach($generationVariants as $variant)
                                                <div class="row mb-2 generation-variant" data-variant-index="{{ $variantIndex }}">
                                                    <div class="col-md-4">
                                                        <input type="text" name="variants[{{ $variantIndex }}][name]" 
                                                            class="form-control" placeholder="Generation (e.g., i5 11th Gen)" 
                                                            value="{{ $variant->variant_name }}" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][price_adjustment]" 
                                                            class="form-control" placeholder="Price (+Rs)" 
                                                            value="{{ $variant->price_adjustment }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="variants[{{ $variantIndex }}][stock]" 
                                                            class="form-control" placeholder="Stock" 
                                                            value="{{ $variant->stock_quantity }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="hidden" name="variants[{{ $variantIndex }}][type]" value="generation">
                                                        <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                                                    </div>
                                                </div>
                                                @php $variantIndex++; @endphp
                                                @endforeach
                                            @else
                                                <div class="row mb-2 generation-variant" data-variant-index="{{ $variantIndex }}">
                                                    <div class="col-md-4">
                                                        <input type="text" name="variants[{{ $variantIndex }}][name]" class="form-control" placeholder="Generation (e.g., i5 11th Gen)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][price_adjustment]" class="form-control" placeholder="Price (+Rs)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="variants[{{ $variantIndex }}][stock]" class="form-control" placeholder="Stock">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="hidden" name="variants[{{ $variantIndex }}][type]" value="generation">
                                                        <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addVariant('generation')">Add Generation Option</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Color Variants -->
<div id="color-section" class="card mb-3">
    <div class="card-header">
        <h5>Color Options</h5>
    </div>
    <div class="card-body">
        <div id="color-variants-container">
            @php
                $colorVariants = isset($variantsByType['color']) ? $variantsByType['color'] : collect();
            @endphp
            
            @if($colorVariants->count() > 0)
                @foreach($colorVariants as $variant)
                <div class="row mb-2 color-variant" data-variant-index="{{ $variantIndex }}">
                    <div class="col-md-5">
                        <input type="text" name="variants[{{ $variantIndex }}][name]" 
                            class="form-control" placeholder="Color (e.g., Black, Silver)" 
                            value="{{ $variant->variant_name }}" required>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="variants[{{ $variantIndex }}][stock]" 
                            class="form-control" placeholder="Stock" 
                            value="{{ $variant->stock_quantity }}">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="variants[{{ $variantIndex }}][type]" value="color">
                        <input type="hidden" name="variants[{{ $variantIndex }}][price_adjustment]" value="0">
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                    </div>
                </div>
                @php $variantIndex++; @endphp
                @endforeach
            @else
                <div class="row mb-2 color-variant" data-variant-index="{{ $variantIndex }}">
                    <div class="col-md-5">
                        <input type="text" name="variants[{{ $variantIndex }}][name]" class="form-control" placeholder="Color (e.g., Black, Silver)">
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="variants[{{ $variantIndex }}][stock]" class="form-control" placeholder="Stock">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="variants[{{ $variantIndex }}][type]" value="color">
                        <input type="hidden" name="variants[{{ $variantIndex }}][price_adjustment]" value="0">
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                    </div>
                </div>
            @endif
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addVariant('color')">Add Color Option</button>
    </div>
</div>

                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Track the next available index
    let nextVariantIndex = {{ isset($product) ? $product->variants->count() : 0 }};
    
    function addVariant(type) {
        const container = document.getElementById(`${type}-variants-container`);
        const currentIndex = nextVariantIndex++;
        
        let variantHtml = '';
        
        if (type === 'storage') {
            variantHtml = `
                <div class="row mb-2 ${type}-variant" data-variant-index="${currentIndex}" data-variant-type="${type}">
                    <div class="col-md-4">
                        <input type="text" name="variants[${currentIndex}][name]" 
                            class="form-control variant-name" 
                            placeholder="Storage (e.g., 128GB)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" 
                            name="variants[${currentIndex}][price_adjustment]" 
                            class="form-control variant-price" 
                            placeholder="Price (+Rs)"
                            value="0">
                    </div>
                    <div class="col-md-3">
                        <input type="number" 
                            name="variants[${currentIndex}][stock]" 
                            class="form-control variant-stock" 
                            placeholder="Stock"
                            value="0">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="variants[${currentIndex}][type]" value="${type}">
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                    </div>
                </div>
            `;
        } else {
            // For generation and color variants - no price adjustment field
            variantHtml = `
                <div class="row mb-2 ${type}-variant" data-variant-index="${currentIndex}" data-variant-type="${type}">
                    <div class="col-md-5">
                        <input type="text" name="variants[${currentIndex}][name]" 
                            class="form-control variant-name" 
                            placeholder="${type === 'generation' ? 'Generation (e.g., i5 11th Gen)' : 'Color (e.g., Black, Silver)'}" 
                            required>
                    </div>
                    <div class="col-md-5">
                        <input type="number" 
                            name="variants[${currentIndex}][stock]" 
                            class="form-control variant-stock" 
                            placeholder="Stock"
                            value="0">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="variants[${currentIndex}][type]" value="${type}">
                        <input type="hidden" name="variants[${currentIndex}][price_adjustment]" value="0">
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                    </div>
                </div>
            `;
        }
        
        container.insertAdjacentHTML('beforeend', variantHtml);
        
        // Add remove functionality to the new button
        const newRow = container.lastElementChild;
        const removeBtn = newRow.querySelector('.remove-variant');
        removeBtn.addEventListener('click', function() {
            this.closest('.row').remove();
            reindexVariants();
        });
        
        // Apply current visibility state
        updateVariantVisibility();
    }
    
    // Function to update variant visibility based on category
    function updateVariantVisibility() {
        const categorySelect = document.getElementById('category_id');
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const categoryName = selectedOption.getAttribute('data-category-name') || '';
        const isLaptop = categoryName.toLowerCase() === 'laptops';
        
        // Get all generation variants
        const generationVariants = document.querySelectorAll('.generation-variant');
        
        if (!isLaptop) {
            // Disable and mark as not required for non-laptop categories
            generationVariants.forEach(variant => {
                const inputs = variant.querySelectorAll('input');
                inputs.forEach(input => {
                    input.disabled = true;
                    input.removeAttribute('required');
                });
            });
        } else {
            // Enable for laptop categories
            generationVariants.forEach(variant => {
                const inputs = variant.querySelectorAll('input');
                inputs.forEach(input => {
                    input.disabled = false;
                });
            });
        }
    }
    
    // Function to remove disabled variants from form submission
    function prepareFormForSubmission() {
        const categorySelect = document.getElementById('category_id');
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const categoryName = selectedOption.getAttribute('data-category-name') || '';
        const isLaptop = categoryName.toLowerCase() === 'laptops';
        
        // Get all generation variants
        const generationVariants = document.querySelectorAll('.generation-variant');
        
        if (!isLaptop) {
            // Remove all generation variants for non-laptop categories
            generationVariants.forEach(variant => {
                variant.remove();
            });
            reindexVariants();
        } else {
            // Validate generation variants for laptop categories
            generationVariants.forEach(variant => {
                const nameInput = variant.querySelector('.variant-name');
                const stockInput = variant.querySelector('.variant-stock');
                
                // Set default values if empty
                if (nameInput && nameInput.value.trim() === '') {
                    nameInput.value = 'Default Generation';
                }
                if (stockInput && stockInput.value === '') {
                    stockInput.value = 0;
                }
            });
        }
        
        // Handle color variants - ensure they have hidden price adjustment
        const colorVariants = document.querySelectorAll('.color-variant');
        colorVariants.forEach(variant => {
            const nameInput = variant.querySelector('.variant-name');
            const stockInput = variant.querySelector('.variant-stock');
            
            if (nameInput && nameInput.value.trim() === '') {
                nameInput.value = 'Default Color';
            }
            if (stockInput && stockInput.value === '') {
                stockInput.value = 0;
            }
        });
    }
    
    // Function to reindex all variants to maintain sequential indices
    function reindexVariants() {
        const allVariants = document.querySelectorAll('.storage-variant, .generation-variant, .color-variant');
        let newIndex = 0;
        
        allVariants.forEach(variant => {
            // Update all input names in this variant
            variant.querySelectorAll('input, input[type="hidden"]').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/variants\[\d+\]/, `variants[${newIndex}]`);
                    input.setAttribute('name', newName);
                }
            });
            
            variant.setAttribute('data-variant-index', newIndex);
            newIndex++;
        });
        
        // Update the next index counter
        nextVariantIndex = newIndex;
    }
    
    // Add remove functionality to existing remove buttons
    document.querySelectorAll('.remove-variant').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.row').remove();
            reindexVariants();
        });
    });
    
    // Category change handler - Show/Hide generation section and update visibility
    document.getElementById('category_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categoryName = selectedOption.getAttribute('data-category-name') || '';
        const generationSection = document.getElementById('generation-section');
        const colorSection = document.getElementById('color-section');
        const isLaptop = categoryName.toLowerCase() === 'laptops';
        
        if (isLaptop) {
            generationSection.style.display = 'block';
        } else {
            generationSection.style.display = 'none';
        }
        
        // Color section is always visible
        if (colorSection) {
            colorSection.style.display = 'block';
        }
        
        // Update variant visibility (enable/disable inputs)
        updateVariantVisibility();
    });
    
    // Trigger change on page load to set initial state
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        if (categorySelect) {
            const event = new Event('change');
            categorySelect.dispatchEvent(event);
        }
        
        // Initialize variant indices if needed
        reindexVariants();
    });
    
    // Form submission handler
    document.getElementById('product-form').addEventListener('submit', function(e) {
        // Prepare form by removing disabled variants or setting defaults
        prepareFormForSubmission();
        
        // Auto-set default values for storage variants
        const storageVariants = document.querySelectorAll('.storage-variant');
        storageVariants.forEach(variant => {
            const nameInput = variant.querySelector('.variant-name');
            const priceInput = variant.querySelector('.variant-price');
            const stockInput = variant.querySelector('.variant-stock');
            
            if (nameInput && nameInput.value.trim() === '') {
                nameInput.value = 'Default Storage';
            }
            if (priceInput && priceInput.value === '') {
                priceInput.value = 0;
            }
            if (stockInput && stockInput.value === '') {
                stockInput.value = 0;
            }
        });
        
        // Reindex after any modifications
        reindexVariants();
    });
    
    // Initialize visibility on page load
    updateVariantVisibility();
</script>

@endsection