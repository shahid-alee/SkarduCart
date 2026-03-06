<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategories;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{



    public function products()
    {
        $products = Product::with(['category', 'subcategory'])->paginate(10);

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
            'price' => 'required|numeric',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'quantity' => 'required|integer',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

Product::create([
    'product_name' => $request->product_name,
    'price' => $request->price,
    'category_id' => $request->category_id,
    'subcategory_id' => $request->subcategory_id,
    'quantity' => $request->quantity,
    'description' => $request->description,
    'image' => $imagePath,
]);

        return redirect()->back()->with('success', 'Product added successfully');
    }

    public function editproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategories::all();

        return view('admin.product.addproduct', compact('product', 'categories', 'subcategories'));
    }

  public function updateproduct(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'product_name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required',
        'subcategory_id' => 'required',
        'quantity' => 'required|integer',
        'description' => 'required',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10048',
    ]);

    $product->product_name = $request->product_name;
    $product->price = $request->price;
    $product->category_id = $request->category_id;
    $product->subcategory_id = $request->subcategory_id;
    $product->quantity = $request->quantity;
    $product->description = $request->description;

    if ($request->hasFile('image')) {

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $imagePath = $request->file('image')->store('products', 'public');
        $product->image = $imagePath;
    }

    $product->save();

    return redirect()->route('admin.product.products')
        ->with('success', 'Product updated successfully');
}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.product.products')
            ->with('success', 'Product deleted successfully.');
    }
}
