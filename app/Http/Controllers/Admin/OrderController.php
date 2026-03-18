<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
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

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.orderedit', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $order = Order::findOrFail($id);

        $order->order_status = $request->status;
        $order->save();

        OrderTracking::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'message' => 'Order updated to ' . ucfirst($request->status)
        ]);

        return redirect()->route('admin.order.orders')
            ->with('success', 'Order status updated successfully');
    }

// public function updateStatus(Request $request, $id)
// {
//     $order = Order::findOrFail($id);

//     $order->order_status = $request->status;
//     $order->save();

//     OrderTracking::create([
//         'order_id' => $order->id,
//         'status' => $request->status,
//         'message' => 'Order updated to ' . $request->status
//     ]);

//     return back()->with('success', 'Order status updated');
// }
}
