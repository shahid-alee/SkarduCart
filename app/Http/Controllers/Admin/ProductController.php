<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\ProductPropertyValue;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Subcategories;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{

   
public function viewproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategories::all();

        return view('admin.product.viewproduct', compact('product', 'categories', 'subcategories'));
    }



    public function products()
    {
        $products = Product::with(['category', 'subcategory', 'variants'])->paginate(10);
        return view('admin.product.products', compact('products'));
    }

       public function createproduct()
    {
        $categories = Category::all();
        $subcategories = Subcategories::all();
        return view('admin.product.addproduct', compact('categories', 'subcategories'));
    }

    public function storeproduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'required',
            'image' => 'required',
            'image.*' => 'image|mimes:jpg,jpeg,png|max:10048',
            'variants' => 'nullable|array',
            'variants.*.name' => 'nullable|string',
            'variants.*.type' => 'nullable|string',
            'variants.*.price_adjustment' => 'nullable|numeric',
            'variants.*.stock' => 'nullable|integer|min:0'
        ]);

        // Handle images
        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                $path = $img->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        // Create product
        $product = Product::create([
            'product_name' => $request->product_name,
            'base_price' => $request->base_price,
            'price' => $request->base_price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'quantity' => 0, // Will be updated from variants
            'description' => $request->description,
            'image' => $imagePaths
        ]);

        $totalStock = 0;

        // Save variants (only if they have name and type)
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variant) {
                // Only save if variant has a name and type
                if (!empty($variant['name']) && !empty($variant['type'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $variant['name'],
                        'variant_type' => $variant['type'],
                        'price_adjustment' => $variant['price_adjustment'] ?? 0,
                        'stock_quantity' => $variant['stock'] ?? 0
                    ]);
                    $totalStock += $variant['stock'] ?? 0;
                }
            }
        }

        // Update product total stock
        $product->quantity = $totalStock;
        $product->save();

        // Save properties (if needed)
        if ($request->properties) {
            foreach ($request->properties as $propertyName => $values) {
                $property = ProductProperty::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'property_name' => $propertyName
                ]);

                foreach ($values as $value) {
                    ProductPropertyValue::create([
                        'product_property_id' => $property->id,
                        'value' => $value
                    ]);
                }
            }
        }

        return redirect()->route('admin.product.products')->with('success', 'Product added successfully');
    }

    public function editproduct($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategories::all();
        
        // Group variants by type for easier handling in view
        $variantsByType = [
            'storage' => $product->variants->where('variant_type', 'storage'),
            'generation' => $product->variants->where('variant_type', 'generation'),
            'color' => $product->variants->where('variant_type', 'color')
        ];
        
        return view('admin.product.addproduct', compact('product', 'categories', 'subcategories', 'variantsByType'));
    }

    public function updateproduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'required',
            'image.*' => 'image|mimes:jpg,jpeg,png|max:10048',
            'variants' => 'nullable|array',
            'variants.*.name' => 'nullable|string',
            'variants.*.type' => 'nullable|string',
            'variants.*.price_adjustment' => 'nullable|numeric',
            'variants.*.stock' => 'nullable|integer|min:0'
        ]);

        $product->update([
            'product_name' => $request->product_name,
            'base_price' => $request->base_price,
            'price' => $request->base_price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'description' => $request->description,
        ]);

        // Handle images
        if ($request->hasFile('image')) {
            // Delete old images
            $oldImages = $product->image;
            if (!is_array($oldImages)) {
                $oldImages = json_decode($oldImages, true) ?? [$oldImages];
            }
            foreach ($oldImages as $img) {
                if (!empty($img) && Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }

            // Upload new images
            $imagePaths = [];
            foreach ($request->file('image') as $img) {
                $path = $img->store('products', 'public');
                $imagePaths[] = $path;
            }
            $product->image = $imagePaths;
            $product->save();
        }

        $totalStock = 0;

        // Update variants
        if ($request->has('variants') && is_array($request->variants)) {
            // Delete old variants
            $product->variants()->delete();
            
            // Create new variants (only those with name and type)
            foreach ($request->variants as $variant) {
                // Only save if variant has a name and type
                if (!empty($variant['name']) && !empty($variant['type'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $variant['name'],
                        'variant_type' => $variant['type'],
                        'price_adjustment' => $variant['price_adjustment'] ?? 0,
                        'stock_quantity' => $variant['stock'] ?? 0
                    ]);
                    $totalStock += $variant['stock'] ?? 0;
                }
            }
        }

        // Update product total stock
        $product->quantity = $totalStock;
        $product->save();

        return redirect()->route('admin.product.products')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete product images
        if ($product->image) {
            foreach ($product->image as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        
        // Delete variants (will cascade if foreign key constraint is set)
        $product->variants()->delete();
        
        // Delete properties
        $product->properties()->delete();
        
        // Delete product
        $product->delete();

        return redirect()
            ->route('admin.product.products')
            ->with('success', 'Product deleted successfully');
    }

}


