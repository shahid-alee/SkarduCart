<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Illuminate\Http\Response;

class CheckoutController extends Controller
{
   
    public function checkout()
{
    $cart = session('cart') ?? [];
    
    // Calculate subtotal
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    $delivery = 150; // Fixed delivery charge
    $total = $subtotal + $delivery;
    
    return view('pages.checkout', compact('cart', 'subtotal', 'delivery', 'total'));
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

    // Store order items with variant information
    foreach ($cart as $key => $item) {
        $productId = $item['product_id'];
        
        if (isset($item['variant_ids']) && is_array($item['variant_ids']) && count($item['variant_ids']) > 0) {
            // Store variant IDs as comma-separated string
            $variantIdsString = implode(',', $item['variant_ids']);
            
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'variant_id' => $variantIdsString,
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
            
            // Update stock for each variant
            foreach ($item['variant_details'] as $type => $variant) {
                if (isset($variant['id'])) {
                    $productVariant = ProductVariant::find($variant['id']);
                    if ($productVariant) {
                        $productVariant->stock_quantity -= $item['quantity'];
                        $productVariant->save();
                    }
                }
            }
        } else {
            // No variants - single product
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'variant_id' => null,
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
            
            // Update product stock if no variants
            $product = Product::find($productId);
            if ($product) {
                $product->quantity -= $item['quantity'];
                $product->save();
            }
        }
    }

    // If payment method is Cash on Delivery
    if ($request->payment == 'cod') {
        session()->forget('cart');
        return redirect()->route('order.success', $order->id)
            ->with('success', 'Order placed successfully! You will pay cash on delivery.');
    }

    // If payment method is Card (Stripe)
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
        session()->put('order_id', $order->id);

        return view('pages.stripe-payment', [
            'clientSecret' => $paymentIntent->client_secret,
            'order' => $order,
        ]);
    }

    return redirect()->route('checkout')->with('error', 'Invalid payment method selected.');
}

    public function stripeWebhook(Request $request)
    {
        Log::info('Webhook received', [
            'method' => $request->method(),
            'path' => $request->path(),
            'headers' => $request->headers->all(),
            'has_signature' => $request->hasHeader('Stripe-Signature')
        ]);
        
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        
        Log::info('Webhook details', [
            'payload_length' => strlen($payload),
            'has_signature' => !empty($sig_header),
            'secret_exists' => !empty($endpoint_secret)
        ]);
        
        if (!$endpoint_secret) {
            Log::error('STRIPE_WEBHOOK_SECRET is not set in .env');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
            
            Log::info('Webhook verified successfully', ['type' => $event->type]);
            
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid webhook payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid webhook signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook error'], 400);
        }
        
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            
            Log::info('Processing payment_intent.succeeded', [
                'payment_intent_id' => $paymentIntent->id,
                'order_id' => $paymentIntent->metadata->order_id ?? 'not set'
            ]);
            
            $orderId = $paymentIntent->metadata->order_id ?? null;
            
            if (!$orderId) {
                Log::error('Order ID missing in payment intent metadata');
                return response()->json(['error' => 'Order ID missing'], 200);
            }
            
            $order = Order::find($orderId);
            
            if (!$order) {
                Log::error("Order not found with ID: {$orderId}");
                return response()->json(['error' => 'Order not found'], 200);
            }
            
            $order->payment_status = 'paid';
            $order->order_status = 'pending';
            $order->transaction_id = $paymentIntent->id;
            $order->save();
            
            Log::info("Order {$orderId} updated successfully", [
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'transaction_id' => $order->transaction_id
            ]);
            
            // Clear the cart if it exists (optional)
            if (session()->has('cart')) {
                session()->forget('cart');
                Log::info("Cart cleared for order {$orderId}");
            }
        }
        
        return response()->json(['status' => 'success'], 200);
    }

    public function stripePaymentSuccess(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if payment was successful (you might want to verify with Stripe)
        if ($order->payment_status === 'paid') {
            session()->forget('cart');
            return redirect()->route('order.success', $order->id)
                ->with('success', 'Payment successful!');
        }
        
        return redirect()->route('checkout')
            ->with('error', 'Payment not completed. Please try again.');
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
}