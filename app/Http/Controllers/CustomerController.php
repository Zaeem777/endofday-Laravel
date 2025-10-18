<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\cart;
use App\Models\Listings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    //View Profile 
    public function profile()
    {
        $customer = Auth::user();

        if ($customer->role !== 'customer') {
            return redirect()->route('login');
        }

        return view('Customer.profile', compact('customer'));
    }

    // update profile
    public function updateProfile(Request $request){
        $customer = Auth::user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:11',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

   if ($request->filled('name')) {
        $customer->name = $request->name;
    }

    if ($request->filled('email')) {
        $customer->email = $request->email;
    }

    if ($request->filled('phone')) {
        $customer->phone = $request->phone;
    }

    if ($request->filled('address')) {
        $customer->address = $request->address;
    }

    if ($request->filled('password')) {
        $customer->password = Hash::make($request->password);
    }

        $customer->save();

        return redirect()->route('Customer.profile')->with('success', 'Profile updated successfully!');
    }

    //view Restaurants
    public function viewRestaurants()
    {

        $restaurants = User::where('role', 'restaurant_owner')->get();

        return view('Customer.restaurants', compact('restaurants'));
    }

    //view Restaurant Listings
    public function viewRestaurantMenu($id)
    {
        $restaurants = User::FindOrFail($id);

        $listings = Listings::where('bakery_id', $id)->get();

        return view('Customer.restaurantmenu', compact('restaurants', 'listings'));
    }


// Checkout
    public function checkout()
    {
     $user = Auth::user();
    $cartItems = Cart::with('listing')->where('customer_id', $user->id)->get();
    $cartSubtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
    $deliveryFee  = 200; // change if dynamic
    $cartTotal     = $cartSubtotal + $deliveryFee;
    $cartCount     = $cartItems->sum('quantity');

    return view('Customer.checkout', compact(
        'cartItems',
        'cartSubtotal',
        'deliveryFee',
        'cartTotal',
        'cartCount'
    ));
}
}