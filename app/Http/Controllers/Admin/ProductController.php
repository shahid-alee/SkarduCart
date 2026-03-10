<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\ProductPropertyValue;
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

        $imagePaths = [];

        if ($request->hasFile('image')) {

            foreach ($request->file('image') as $img) {

                $path = $img->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        Product::create([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'image' => $imagePaths
        ]);

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

        return redirect()->back()->with('success', 'Product added successfully');
    }


    public function viewproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategories::all();

        return view('admin.product.viewproduct', compact('product', 'categories', 'subcategories'));
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

        $product->update([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'quantity' => $request->quantity,
            'description' => $request->description,
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();
        }

        if ($request->properties) {
            $product->properties()->delete();

            foreach ($request->properties as $propertyName => $values) {
                $property = ProductProperty::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'property_name' => $propertyName,
                ]);

                foreach ($values as $value) {
                    ProductPropertyValue::create([
                        'product_property_id' => $property->id,
                        'value' => $value,
                    ]);
                }
            }
        }

        return redirect()->route('admin.product.products')->with('success', 'Product updated successfully');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->properties()->delete();
        $product->delete();

        return redirect()->route('admin.product.products')->with('success', 'Product deleted successfully.');
    }
}
