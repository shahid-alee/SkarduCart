<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Webhook;


class CheckoutController extends Controller
{
   public function checkout()
   {
      $cart = session('cart') ?? [];
     
      if (empty($cart)) {
         return redirect()->route('cart.list')->with('error', 'Your cart is empty.');
      }

      return view('pages.checkout', compact('cart'));
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
         'payment_status' => $request->payment == 'cod' ? 'pending' : 'pending', 
         'order_status' => 'pending', 
      ]);

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
            'amount' => $total * 100,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'metadata' => [
               'order_id' => $order->id
            ]
         ]);

         session()->put('payment_intent_id', $paymentIntent->id);

         return view('pages.stripe-payment', [
            'clientSecret' => $paymentIntent->client_secret,
            'order' => $order,
         ]);
      }

      session()->forget('cart');

      return redirect()->route('order.success', $order->id);
   }

   public function orderSuccess($id)
   {
      $order = Order::with('items')->findOrFail($id);
      return view('pages.order-success', compact('order'));
   }

   public function orderTracking($id)
{
    $order = Order::with('tracking')->findOrFail($id);
    return view('pages.order-tracking', compact('order'));
}





public function handleWebhook(Request $request)
{
    $payload = $request->getContent();
    $sig_header = $request->header('Stripe-Signature');
    $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload,
            $sig_header,
            $endpoint_secret
        );
    } catch (\Exception $e) {
        return response('Invalid signature', 400);
    }

    // ✅ PAYMENT SUCCESS
    if ($event->type === 'payment_intent.succeeded') {

        $paymentIntent = $event->data->object;

        $orderId = $paymentIntent->metadata->order_id;

        $order = Order::find($orderId);

        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing'
            ]);
        }
    }

    // ❌ PAYMENT FAILED
    if ($event->type === 'payment_intent.payment_failed') {

        $paymentIntent = $event->data->object;
        $orderId = $paymentIntent->metadata->order_id;

        $order = Order::find($orderId);

        if ($order) {
            $order->update([
                'payment_status' => 'failed'
            ]);
        }
    }

    return response('Webhook handled', 200);
}




// public function handleWebhook(Request $request)
// {
//     $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

//     $payload = $request->getContent();
//     $sig_header = $request->header('Stripe-Signature');

//     try {
//         $event = Webhook::constructEvent(
//             $payload,
//             $sig_header,
//             $endpoint_secret
//         );
//     } catch (\Exception $e) {
//         return response('Invalid payload', 400);
//     }

//     // ✅ Handle successful payment
//     if ($event->type === 'payment_intent.succeeded') {

//         $paymentIntent = $event->data->object;

//         $orderId = $paymentIntent->metadata->order_id;

//         $order = Order::find($orderId);

//         if ($order) {
//             $order->payment_status = 'paid';
//             $order->order_status = 'processing'; // optional
//             $order->save();
//         }
//     }

//     return response('Webhook handled', 200);
// }
}
