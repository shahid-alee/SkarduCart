<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{


    public function checkout(Request $request)
    {

        $cart = session('cart');

        $total = 0;

        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $line_items = [];

        foreach ($cart as $id => $details) {

            $line_items[] = [
                'price_data' => [
                    'currency' => 'pkr',
                    'product_data' => [
                        'name' => $details['product_name'],
                    ],
                    'unit_amount' => $details['price'] * 100,
                ],
                'quantity' => $details['quantity'],
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',

            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }


    public function success()
    {

        session()->forget('cart');

        return "Payment Successful";
    }


    public function cancel()
    {
        return "Payment Cancelled";
    }
}
