<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Order;

class StripeWebhookController extends Controller
{
   public function handleWebhook(Request $request)
{
    \Log::info('Webhook HIT');

    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = Webhook::constructEvent(
            $payload,
            $sigHeader,
            $endpoint_secret
        );
    } catch (\Exception $e) {
        \Log::error('Webhook Error: '.$e->getMessage());
        return response('Invalid signature', 400);
    }

    \Log::info('Event Type: '.$event->type);

    if ($event->type == 'payment_intent.succeeded') {

        $paymentIntent = $event->data->object;

        $orderId = $paymentIntent->metadata->order_id;

        \Log::info('Order ID: '.$orderId);

        $order = Order::find($orderId);

        if ($order) {
            $order->payment_status = 'paid';
            $order->save();

            \Log::info('Payment Updated to PAID');
        }
    }

    return response('Webhook handled', 200);
}
}