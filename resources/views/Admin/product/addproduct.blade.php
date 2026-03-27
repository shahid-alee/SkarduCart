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
        
        const variantHtml = `
            <div class="row mb-2 ${type}-variant" data-variant-index="${currentIndex}">
                <div class="col-md-4">
                    <input type="text" name="variants[${currentIndex}][name]" 
                        class="form-control" 
                        placeholder="${type === 'storage' ? 'Storage (e.g., 128GB)' : 'Generation (e.g., i5 11th Gen)'}" 
                        required>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" 
                        name="variants[${currentIndex}][price_adjustment]" 
                        class="form-control" 
                        placeholder="Price (+Rs)">
                </div>
                <div class="col-md-3">
                    <input type="number" 
                        name="variants[${currentIndex}][stock]" 
                        class="form-control" 
                        placeholder="Stock">
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="variants[${currentIndex}][type]" value="${type}">
                    <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', variantHtml);
        
        // Add remove functionality to the new button
        const newRow = container.lastElementChild;
        const removeBtn = newRow.querySelector('.remove-variant');
        removeBtn.addEventListener('click', function() {
            this.closest('.row').remove();
            // Optional: reindex after removal
            reindexVariants();
        });
    }
    
    // Function to reindex all variants to maintain sequential indices
    function reindexVariants() {
        const allVariants = document.querySelectorAll('.storage-variant, .generation-variant');
        let newIndex = 0;
        
        allVariants.forEach(variant => {
            const oldIndex = variant.getAttribute('data-variant-index');
            
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
    
    // Category change handler - Show/Hide generation section
    document.getElementById('category_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categoryName = selectedOption.getAttribute('data-category-name') || '';
        const generationSection = document.getElementById('generation-section');
        
        if (categoryName.toLowerCase() === 'laptops') {
            generationSection.style.display = 'block';
        } else {
            generationSection.style.display = 'none';
        }
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
    
    // Validate form before submission
    document.getElementById('product-form').addEventListener('submit', function(e) {
        // Optional: Add custom validation here
        const variants = document.querySelectorAll('.storage-variant, .generation-variant');
        
        variants.forEach(variant => {
            const nameInput = variant.querySelector('input[name*="[name]"]');
            const priceInput = variant.querySelector('input[name*="[price_adjustment]"]');
            const stockInput = variant.querySelector('input[name*="[stock]"]');
            
            // Auto-set default values if empty
            if (nameInput && nameInput.value.trim() === '') {
                nameInput.value = 'Default';
            }
            
            if (priceInput && priceInput.value === '') {
                priceInput.value = 0;
            }
            
            if (stockInput && stockInput.value === '') {
                stockInput.value = 0;
            }
        });
    });
</script>

@endsection