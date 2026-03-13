<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
   public function checkout()
   {
      $cart = session('cart') ?? [];
      if (empty($cart)) {
         return redirect()->route('cart.list')->with('error', 'Your cart is empty.');
      }

      return view('checkout', compact('cart'));
   }

   public function stripeCheckout(Request $request)
   {
      $request->validate([
         'first_name' => 'required',
         'last_name' => 'required',
         'email' => 'required|email',
         'phone' => 'required',
         'address' => 'required',
         'city' => 'required',
         'payment' => 'required'
      ]);

      $cart = session('cart') ?? [];
      if (empty($cart)) {
         return redirect()->route('cart.list')->with('error', 'Your cart is empty.');
      }

      $subtotal = 0;
      foreach ($cart as $item) {
         $subtotal += $item['price'] * $item['quantity'];
      }

      $delivery = 150;
      $total = $subtotal + $delivery;

      // Save order first
      $order = Order::create([
         'user_id' => auth()->id() ?? null,
         'first_name' => $request->first_name,
         'last_name' => $request->last_name,
         'email' => $request->email,
         'phone' => $request->phone,
         'address' => $request->address,
         'city' => $request->city,
         'postal_code' => $request->postal_code ?? null,
         'notes' => $request->notes ?? null,
         'subtotal' => $subtotal,
         'delivery' => $delivery,
         'total' => $total,
         'payment_method' => $request->payment,
         'payment_status' => $request->payment == 'cod' ? 'pending' : 'pending', // pending until card success
      ]);

      // Save order items
      foreach ($cart as $id => $item) {
         OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $id,
            'product_name' => $item['product_name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'subtotal' => $item['price'] * $item['quantity']
         ]);
      }

      if ($request->payment == 'card') {
         Stripe::setApiKey(env('STRIPE_SECRET'));

         $paymentIntent = PaymentIntent::create([
            'amount' => $total * 100, // amount in cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'metadata' => [
               'order_id' => $order->id
            ]
         ]);

         // Clear cart only after payment
         session()->put('payment_intent_id', $paymentIntent->id);

         return view('stripe-payment', [
            'clientSecret' => $paymentIntent->client_secret,
            'order' => $order,
         ]);
      }

      // COD: clear cart
      session()->forget('cart');

      return redirect()->route('order.pay', $order->id);
   }

   public function orderSuccess($id)
   {
      $order = Order::with('items')->findOrFail($id);
      return view('order-success', compact('order'));
   }
}
