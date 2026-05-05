<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Subcategories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{


 public function index()
{
    $products = \App\Models\Product::with(['category', 'subcategory'])->get();

    return response()->json([
        'data' => $products
    ]);
}


public function store(Request $request)
{
    try {
        Log::info('Product store request received', $request->all());

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id', // Changed from 'subcategories' to 'sub_categories'
            'description' => 'required|string',
            'image' => 'required|array|min:1',
            'image.*' => 'image|mimes:jpeg,jpg,png|max:10240',
            'variants' => 'nullable|array',
            'variants.*.type' => 'required|in:storage,color,generation',
            'variants.*.name' => 'required_with:variants.*.type|string',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Rest of your code remains the same...
        $category = Category::find($request->category_id);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        $totalQuantity = 0;
        $variantsData = [];

        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variant) {
                if ($category && strtolower($category->category_name) !== 'laptops' && $variant['type'] === 'generation') {
                    continue;
                }

                if (empty($variant['name'])) {
                    continue;
                }

                $totalQuantity += intval($variant['stock'] ?? 0);

                $variantsData[] = [
                    'variant_name' => $variant['name'],
                    'variant_type' => $variant['type'],
                    'stock_quantity' => $variant['stock'] ?? 0,
                    'price_adjustment' => $variant['price_adjustment'] ?? 0
                ];
            }
        }

        // Handle subcategory_id - if empty string or null, set to null
        $subcategoryId = $request->subcategory_id;
        if (empty($subcategoryId) || $subcategoryId === '') {
            $subcategoryId = null;
        }

        $product = Product::create([
            'product_name' => $request->product_name,
            'base_price' => $request->base_price,
            'price' => $request->base_price,
            'category_id' => $request->category_id,
            'subcategory_id' => $subcategoryId,
            'quantity' => $totalQuantity,
            'description' => $request->description,
            'image' => json_encode($imagePaths)
        ]);

        foreach ($variantsData as $variantData) {
            ProductVariant::create([
                'product_id' => $product->id,
                'variant_name' => $variantData['variant_name'],
                'variant_type' => $variantData['variant_type'],
                'stock_quantity' => $variantData['stock_quantity'],
                'price_adjustment' => $variantData['price_adjustment']
            ]);
        }

        $product->load(['category', 'subcategory', 'variants']);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
        
    } catch (\Exception $e) {
        Log::error('Product store error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        if (isset($imagePaths)) {
            foreach ($imagePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create product: ' . $e->getMessage()
        ], 500);
    }
}

    public function show($id)
    {
        try {
            $product = Product::with(['category', 'subcategory', 'variants'])->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::with('variants')->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'product_name' => 'sometimes|required|string|max:255',
                'base_price' => 'sometimes|required|numeric|min:0',
                'category_id' => 'sometimes|required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:subcategories,id',
                'description' => 'sometimes|required|string',
                'image' => 'nullable|array',
                'image.*' => 'image|mimes:jpeg,jpg,png|max:10240',
                'variants' => 'nullable|array',
                'variants.*.type' => 'required|in:storage,color,generation',
                'variants.*.name' => 'required_with:variants.*.type|string',
                'variants.*.stock' => 'nullable|integer|min:0',
                'variants.*.price_adjustment' => 'nullable|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $category = Category::find($request->category_id ?? $product->category_id);

            $updateData = [];

            if ($request->has('product_name')) {
                $updateData['product_name'] = $request->product_name;
            }
            if ($request->has('base_price')) {
                $updateData['base_price'] = $request->base_price;
                $updateData['price'] = $request->base_price;
            }
            if ($request->has('category_id')) {
                $updateData['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $updateData['subcategory_id'] = $request->subcategory_id;
            }
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }

            if ($request->hasFile('image')) {
                $oldImages = json_decode($product->image, true) ?? [];
                foreach ($oldImages as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }

                $newImagePaths = [];
                foreach ($request->file('image') as $image) {
                    $path = $image->store('products', 'public');
                    $newImagePaths[] = $path;
                }
                $updateData['image'] = json_encode($newImagePaths);
            }

            $product->update($updateData);

            if ($request->has('variants')) {
                $product->variants()->delete();

                $totalQuantity = 0;

                foreach ($request->variants as $variant) {
                    if ($category && strtolower($category->category_name) !== 'laptops' && $variant['type'] === 'generation') {
                        continue;
                    }

                    if (empty($variant['name'])) {
                        continue;
                    }

                    $totalQuantity += intval($variant['stock'] ?? 0);

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $variant['name'],
                        'variant_type' => $variant['type'],
                        'stock_quantity' => $variant['stock'] ?? 0,
                        'price_adjustment' => $variant['price_adjustment'] ?? 0
                    ]);
                }

                $product->update(['quantity' => $totalQuantity]);
            }

            $product->load(['category', 'subcategory', 'variants']);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $images = json_decode($product->image, true) ?? [];
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }

            $product->variants()->delete();
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }
}
