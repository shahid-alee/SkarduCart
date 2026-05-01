<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;

class ReviewController extends Controller
{
     public function create($order_id)
    {
        $order = Order::with('items')->findOrFail($order_id);
        return view('pages.addreview', compact('order'));
    }

    public function store(Request $request)
{
    foreach ($request->reviews as $review) {

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $review['product_id'],
            'order_id' => $review['order_id'],
            'rating' => $review['rating'],
            'review' => $review['review'] ?? null,
        ]);
    }

    return redirect()
        ->route('home')
        ->with('success', 'All reviews submitted successfully');
}



}
