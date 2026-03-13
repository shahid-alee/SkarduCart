<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductdetailController extends Controller
{
public function detail($id)
{
    $product = Product::findOrFail($id);
    $products = Product::where('category_id', $product->category_id)
                ->where('id','!=',$id)
                ->take(4)
                ->get();

    return view('productdetail', compact('product','products'));
}
}
