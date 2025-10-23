<?php

namespace App\Http\Controllers;

use App\Models\Order_items;
use App\Models\Order;
use App\Models\Address;
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
    public function updateProfile(Request $request)
    {
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
        $addresses = Address::where('user_id', Auth::id())->get();
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
            'cartCount',
            'addresses',
        ));
    }


    //Addresses 
    public function storeAddress(Request $request)
    {
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $address = new Address();
        $address->user_id = Auth::id();
        $address->address_line1 = $request->address_line1;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->save();

        return redirect()->back()->with('success', 'Address added successfully!');
    }

    public function deleteAddress($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Address Deleted Successfully');
    }


    //Place Order
    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        $cartItems = cart::with('listing')->where('customer_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->listing->discountedprice * $item->quantity);
        $deliveryFee = 200; // Change if dynamic
        $totalPrice = $subtotal + $deliveryFee;

        $restaurantId = $cartItems->first()->listing->bakery_id;

        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId,
            'address_id' => $request->address_id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_price' => $totalPrice,
            'special_instructions' => $request->special_instructions,
        ]);

        foreach ($cartItems as $item) {
            Order_items::create([
                'listing_id' => $item->listing_id,
                'order_id' => $order->id,
                'quantity' => $item->quantity,
                'price' => $item->listing->discountedprice,
            ]);

            $item->listing->decrement('remainingitem', $item->quantity);
        }
        Cart::where('customer_id', $user->id)->delete();

        return redirect()->route('Customer.orders.show')
            ->with('success', 'Order placed successfully!');
    }

    public function showorders()
    {
        $id = Auth::id();
        $orders = Order::with(['user',  'items.listing.owner'])
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // dd('', $orders);
        return view('Customer.orders', compact('orders'));
    }



    public function submitReview(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'rating' => 'required|in:0,1,2,3,4,5',
            ]);

            $order = Order::findOrFail($request->order_id);

            // Ensure the order belongs to the authenticated user
            if ($order->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            $order->review = $request->rating;
            $order->review_status = 'Reviewed';
            $order->save();

            return response()->json(['success', 'Review submitted successfully!']);
        } catch (\Throwable $e) {
            \Log::error($e);
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }
}
