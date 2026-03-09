<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {

        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        $quantity = $request->quantity ?? 1;

        if (isset($cart[$id])) {

            $cart[$id]['quantity'] += $quantity;
        } else {

            $cart[$id] = [

                "product_name" => $product->product_name,
                "price" => $product->price,
                "image" => $product->image,
                "quantity" => $quantity

            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.list');
    }



    public function list()
    {
        $cart = session()->get('cart', []);

        return view('cartlist', compact('cart'));
    }



    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.list');
    }
}
