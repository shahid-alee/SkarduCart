<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
 public function detail($slug)
{
   
    return view('productdetail', compact('product','products'));
}
}
