<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Subcategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
  public function index()
    {
        $products = Product::with(['category', 'subcategory', 'variants'])->paginate(10);
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'description' => 'required|string',

            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpg,jpeg,png|max:10048',

            
            'variants' => 'array',
            'variants.*.type' => 'required|string',
            'variants.*.name' => 'required|string',
            'variants.*.price_adjustment' => 'nullable|numeric',
            'variants.*.stock' => 'nullable|integer',
        ]);

        $category = Category::find($request->category_id);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                $path = $img->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

       
        $totalQuantity = 0;

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {

                if (empty($variant['name'])) continue;

                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
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
            'quantity' => $totalQuantity,
            'description' => $request->description,
            'image' => $imagePaths,
        ]);

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {

                if (empty($variant['name'])) continue;

                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
                    continue;
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['name'],
                    'variant_type' => $variant['type'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0,
                    'stock_quantity' => $variant['stock'] ?? 0,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product + Variants created successfully!',
            'product' => $product->load('variants')
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'subcategory', 'variants'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'product_name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'required|string',

            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpg,jpeg,png|max:10048',

            'variants' => 'array',
            'variants.*.type' => 'required|string',
            'variants.*.name' => 'required|string',
            'variants.*.price_adjustment' => 'nullable|numeric',
            'variants.*.stock' => 'nullable|integer',
        ]);

        $category = Category::find($request->category_id);

       
        $totalQuantity = 0;

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {

                if (empty($variant['name'])) continue;

                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
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
            'quantity' => $totalQuantity,
            'description' => $request->description,
        ]);

       
        if ($request->hasFile('image')) {

            if ($product->image) {
                foreach ($product->image as $img) {
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

        //  Update Variants
        if ($request->has('variants')) {

            // delete old variants
            $product->variants()->delete();

            foreach ($request->variants as $variant) {

                if (empty($variant['name'])) continue;

                if ($category && strtolower($category->category_name) != 'laptops' && $variant['type'] == 'generation') {
                    continue;
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variant['name'],
                    'variant_type' => $variant['type'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0,
                    'stock_quantity' => $variant['stock'] ?? 0,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product->load('variants')
        ]);
    }

    //  Delete Product
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // delete images
        if ($product->image) {
            foreach ($product->image as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        // delete variants
        $product->variants()->delete();

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!'
        ]);
    }
}
