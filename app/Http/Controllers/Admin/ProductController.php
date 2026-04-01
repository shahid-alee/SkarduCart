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


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {

            foreach ($product->image as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $product->properties()->delete();
        $product->delete();

        return redirect()
            ->route('admin.product.products')
            ->with('success', 'Product deleted successfully');
    }


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
            'base_price' => 'required|numeric',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'description' => 'required',
            'image' => 'required|sometimes',
            'image.*' => 'image|mimes:jpg,jpeg,png|max:10048',
            'variants' => 'array',
            'variants.*.name' => 'required_if:variants.*.type,storage,generation,color',
            'variants.*.type' => 'required',
            'variants.*.price_adjustment' => 'nullable|numeric', // Make nullable
            'variants.*.stock' => 'nullable|integer'
        ]);

        // Get category to check if generation variants should be saved
        $category = Category::find($request->category_id);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                $path = $img->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        // Calculate total quantity from variants
        $totalQuantity = 0;
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Skip generation variants for non-laptop categories
                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
                    continue;
                }

                // Skip empty variant names
                if (empty($variant['name'])) {
                    continue;
                }

                $totalQuantity += intval($variant['stock'] ?? 0);
            }
        }

        $product = Product::create([
            'product_name' => $request->product_name,
            'base_price' => $request->base_price,
            'price' => $request->base_price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'quantity' => $totalQuantity, // Sum of all variant stocks
            'description' => $request->description,
            'image' => $imagePaths
        ]);

        // Save variants - filter out empty ones and invalid ones
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Skip if variant name is empty
                if (empty($variant['name'])) {
                    continue;
                }

                // Skip generation variants for non-laptop categories
                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
                    continue;
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['name'],
                    'variant_type' => $variant['type'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0,
                    'stock_quantity' => $variant['stock'] ?? 0
                ]);
            }
        }

        // Save properties only if they exist and have values
        if ($request->has('properties') && is_array($request->properties)) {
            foreach ($request->properties as $propertyName => $values) {
                // Skip empty property names or empty values
                if (empty($propertyName) || empty($values)) {
                    continue;
                }

                $property = ProductProperty::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'property_name' => $propertyName
                ]);

                foreach ($values as $value) {
                    if (!empty($value)) {
                        ProductPropertyValue::create([
                            'product_property_id' => $property->id,
                            'value' => $value
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Product added successfully');
    }

    public function updateproduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required',
            'base_price' => 'required|numeric',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'description' => 'required',
            'image' => 'required|sometimes',
            'image.*' => 'image|mimes:jpg,jpeg,png|max:10048',
            'variants' => 'array',
            'variants.*.name' => 'required_if:variants.*.type,storage,generation,color',
            'variants.*.type' => 'required',
            'variants.*.price_adjustment' => 'nullable|numeric', // Make nullable
            'variants.*.stock' => 'nullable|integer'
        ]);

        // Get category to check if generation variants should be saved
        $category = Category::find($request->category_id);

        // Calculate total quantity from variants
        $totalQuantity = 0;
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Skip generation variants for non-laptop categories
                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
                    continue;
                }

                // Skip empty variant names
                if (empty($variant['name'])) {
                    continue;
                }

                $totalQuantity += intval($variant['stock'] ?? 0);
            }
        }

        $product->update([
            'product_name' => $request->product_name,
            'base_price' => $request->base_price,
            'price' => $request->base_price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'quantity' => $totalQuantity, // Sum of all variant stocks
            'description' => $request->description,
        ]);

        // Handle images
        if ($request->hasFile('image')) {
            $oldImages = $product->image;
            if (!is_array($oldImages)) {
                $oldImages = json_decode($oldImages, true) ?? [$oldImages];
            }
            foreach ($oldImages as $img) {
                if (!empty($img) && Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }

            $imagePaths = [];
            foreach ($request->file('image') as $img) {
                $path = $img->store('products', 'public');
                $imagePaths[] = $path;
            }
            $product->image = $imagePaths;
            $product->save();
        }

        // Update variants
        if ($request->has('variants')) {
            // Delete old variants
            $product->variants()->delete();

            // Create new variants - filter out empty ones and invalid ones
            foreach ($request->variants as $variant) {
                // Skip if variant name is empty
                if (empty($variant['name'])) {
                    continue;
                }

                // Skip generation variants for non-laptop categories
                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
                    continue;
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['name'],
                    'variant_type' => $variant['type'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0,
                    'stock_quantity' => $variant['stock'] ?? 0
                ]);
            }
        }

        return redirect()->route('admin.product.products')
            ->with('success', 'Product updated successfully');
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

    // public function search(Request $request)
    // {
    //     $query = $request->input('query');

    //     $products = Product::where('product_name', 'LIKE', "%{$query}%")
    //         ->orWhere('category', 'LIKE', "%{$query}%")
    //         ->orWhere('brand', 'LIKE', "%{$query}%")
    //         ->latest()
    //         ->get();

    //     return view('pages.search-results', compact('products', 'query'));
    // }


    public function topDeals()
    {
        $products = Product::latest()->paginate(20);
        return view('products.index', compact('products'));
    }

    public function category($categorySlug)
    {
        // Adjust this based on your category structure
        $category = \App\Models\Category::where('slug', $categorySlug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->paginate(20);
        return view('products.category', compact('products', 'category'));
    }

    public function popular()
    {
        $products = Product::where('is_popular', true)->paginate(20);
        return view('products.index', compact('products'));
    }

    public function recentlyViewed()
    {
        $recentlyViewedIds = session()->get('recently_viewed', []);
        $products = Product::whereIn('id', $recentlyViewedIds)
            ->orderByRaw("FIELD(id, " . implode(',', $recentlyViewedIds) . ")")
            ->paginate(20);
        return view('products.index', compact('products'));
    }
}
