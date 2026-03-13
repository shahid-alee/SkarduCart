<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders()
    {
        $orders = Order::latest()->paginate(10); 

        return view('admin.order.orders', compact('orders'));
    }

    public function view($id)
{
    $order = Order::with('items')->findOrFail($id);

    return view('admin.order.orderview', compact('order'));
}
}
