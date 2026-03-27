<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
    
   public function addCombined(Request $request)
{
    $productId = $request->product_id;
    $selectedVariants = $request->variants;
    $quantity = $request->quantity ?? 1;
    
    $product = Product::findOrFail($productId);
    
    // Calculate total price and get variant details
    $totalPrice = $product->base_price;
    $variantNames = [];
    $variantIds = [];
    $variantDetails = [];
    $minStock = PHP_INT_MAX;
    
    foreach ($selectedVariants as $type => $variantId) {
        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            $totalPrice += $variant->price_adjustment;
            $variantNames[] = $variant->variant_name;
            $variantIds[] = $variantId;
            $variantDetails[$type] = [
                'id' => $variantId,
                'name' => $variant->variant_name,
                'type' => $type,
                'price_adjustment' => $variant->price_adjustment
            ];
            
            // Track minimum stock
            if ($variant->stock_quantity < $minStock) {
                $minStock = $variant->stock_quantity;
            }
        }
    }
    
    // Sort variant IDs to create a consistent key
    sort($variantIds);
    $variantKey = implode('_', $variantIds);
    
    // Check stock
    if ($quantity > $minStock) {
        return back()->with('error', 'Not enough stock available. Only ' . $minStock . ' items left.');
    }
    
    $cart = session()->get('cart', []);
    
    // Create a unique key for cart item
    $cartKey = $productId . '_' . $variantKey;
    
    $productName = $product->product_name;
    if (!empty($variantNames)) {
        $productName .= ' (' . implode(' + ', $variantNames) . ')';
    }
    
    // If item already in cart, update quantity
    if (isset($cart[$cartKey])) {
        $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
        if ($newQuantity > $minStock) {
            return back()->with('error', 'Cannot add more than available stock. You already have ' . $cart[$cartKey]['quantity'] . ' in cart.');
        }
        $cart[$cartKey]['quantity'] = $newQuantity;
    } else {
        // Add new item to cart
        $cart[$cartKey] = [
            'product_id' => $productId,
            'variant_ids' => $variantIds, // Store as array of IDs
            'variant_details' => $variantDetails,
            'product_name' => $productName,
            'price' => $totalPrice,
            'quantity' => $quantity,
        ];
    }
    
    session()->put('cart', $cart);
    
    return redirect()->route('cart.list')->with('success', 'Product added to cart successfully!');
}

    /**
     * Buy now - Add to cart and redirect to checkout
     */
    public function buyNow(Request $request)
    {
        $response = $this->addCombined($request);
        
        if (session()->has('error')) {
            return $response;
        }
        
        return redirect()->route('checkout');
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
            
            // Get product and variants to check stock
            $productId = $cart[$id]['product_id'];
            $variantIds = $cart[$id]['variant_ids'];
            
            $minStock = PHP_INT_MAX;
            foreach ($variantIds as $variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant && $variant->stock_quantity < $minStock) {
                    $minStock = $variant->stock_quantity;
                }
            }
            
            // If no variants, check product stock
            if ($minStock === PHP_INT_MAX) {
                $product = Product::find($productId);
                $minStock = $product ? $product->quantity : 0;
            }
            
            // Check if quantity is valid
            if ($quantity <= 0) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->route('cart.list')->with('success', 'Item removed from cart.');
            }
            
            if ($quantity > $minStock) {
                return redirect()->route('cart.list')->with('error', 'Only ' . $minStock . ' items available in stock.');
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