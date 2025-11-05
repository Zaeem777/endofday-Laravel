<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{


    public function viewdashboard()
    {
        $restaurant_owners = User::where("role", "restaurant_owner")->count();
        $customers = User::where("role", "customer")->count();
        $orders = Order::all()->count();
        $revenues = (int) Order::where("status", "completed")->sum("total_price");

        $recentOrders = Order::with(['user', 'items'])
            // ->where('restaurant_id', $id)
            // ->where('created_at', '>=', Carbon::now()->subDay())
            // ->orderBy('id', 'asc')
            ->get();
        return view('Admin.dashboard', compact('restaurant_owners', 'customers', 'orders', 'revenues', 'recentOrders'));
    }
}
