<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $variantId = $request->variant_id;
        $quantity = $request->quantity ?? 1;
        
        $cart = session()->get('cart', []);
        
        $price = $product->base_price;
        $variantName = '';
        $stockQuantity = $product->quantity;
        
        // If variant is selected, get variant details
        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            $price = $product->base_price + $variant->price_adjustment;
            $variantName = ' (' . $variant->variant_name . ')';
            $stockQuantity = $variant->stock_quantity;
        }
        
        // Check stock
        if ($quantity > $stockQuantity) {
            return back()->with('error', 'Not enough stock available. Only ' . $stockQuantity . ' items left.');
        }
        
        // Create a unique key for cart item
        $cartKey = $variantId ? $productId . '_' . $variantId : (string)$productId;
        
        // If item already in cart, update quantity
        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQuantity > $stockQuantity) {
                return back()->with('error', 'Cannot add more than available stock. You already have ' . $cart[$cartKey]['quantity'] . ' in cart.');
            }
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            // Add new item to cart
            $cart[$cartKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'product_name' => $product->product_name . $variantName,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->route('cart.list')->with('success', 'Product added to cart successfully!');
    }

    /**
     * Display cart list
     */
    public function cartList()
    {
        $cart = session()->get('cart', []);
        
        // Calculate cart totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $delivery = 150;
        $total = $subtotal + $delivery;
        
        return view('pages.cartlist', compact('cart', 'subtotal', 'delivery', 'total'));
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart');
        
        if (isset($cart[$id])) {
            $quantity = $request->quantity;
            
            // Get product and variant to check stock
            $productId = $cart[$id]['product_id'];
            $variantId = $cart[$id]['variant_id'];
            
            $stockQuantity = 0;
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $stockQuantity = $variant->stock_quantity;
                }
            } else {
                $product = Product::find($productId);
                if ($product) {
                    $stockQuantity = $product->quantity;
                }
            }
            
            // Check if quantity is valid
            if ($quantity <= 0) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->route('cart.list')->with('success', 'Item removed from cart.');
            }
            
            if ($quantity > $stockQuantity) {
                return redirect()->route('cart.list')->with('error', 'Only ' . $stockQuantity . ' items available in stock.');
            }
            
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.list')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart');
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.list')->with('success', 'Item removed from cart successfully!');
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('cart.list')->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get cart count (for AJAX/header)
     */
    public function cartCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get cart details (for AJAX)
     */
    public function cartDetails()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $delivery = 150;
        $total = $subtotal + $delivery;
        
        return response()->json([
            'cart' => $cart,
            'subtotal' => $subtotal,
            'delivery' => $delivery,
            'total' => $total,
            'count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }
}