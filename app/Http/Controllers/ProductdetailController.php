<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductdetailController extends Controller
{
  public function detail($id)
{
    $product = Product::findOrFail($id);

    return view('product.detail', compact('product'));
}
}
