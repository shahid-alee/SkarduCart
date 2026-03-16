<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductdetailController extends Controller
{
public function detail($id)
{
    $product = Product::with(['reviews.user'])->findOrFail($id);
    $products = Product::where('category_id', $product->category_id)
                ->where('id','!=',$id)
                ->take(4)
                ->get();

    return view('pages.productdetail', compact('product','products'));
}
}
