<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Order;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
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
            return response('Invalid signature', 400);
        }

        if ($event->type == 'payment_intent.succeeded') {

            $paymentIntent = $event->data->object;

            $orderId = $paymentIntent->metadata->order_id;

            $order = Order::find($orderId);

            if ($order) {
                $order->payment_status = 'paid';
                $order->save();
            }
        }

        if ($event->type == 'payment_intent.payment_failed') {

            $paymentIntent = $event->data->object;

            $orderId = $paymentIntent->metadata->order_id;

            $order = Order::find($orderId);

            if ($order) {
                $order->payment_status = 'failed';
                $order->save();
            }
        }

        return response('Webhook handled', 200);
    }
}