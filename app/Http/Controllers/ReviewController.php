<?php

namespace App\Http\Controllers;

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
        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return redirect()->back()->with('success','Review submitted successfully');
    }
}
