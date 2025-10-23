<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,inprocess,ready,cancelled,completed',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()
            ->back()
            ->with('success', 'Order status updated successfully!');
    }
}
