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
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'required|string|max:11',
            'password' => 'required|string|min:6|confirmed',
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
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
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
    // public function placeOrder(Request $request)
    // {
    //     $request->validate([
    //         'address_id' => 'required|exists:addresses,id',
    //         'special_instructions' => 'required|string|max:500',
    //         'payment_method' => 'required| in:Cash,Card'
    //     ]);

    //     $user = Auth::user();

    //     $cartItems = cart::with('listing')->where('customer_id', $user->id)->get();
    //     if ($cartItems->isEmpty()) {
    //         return redirect()->back()->with('error', 'Your cart is empty.');
    //     }

    //     $subtotal = $cartItems->sum(fn($item) => $item->listing->discountedprice * $item->quantity);
    //     $deliveryFee = 200; // Change if dynamic
    //     $totalPrice = $subtotal + $deliveryFee;

    //     $restaurantId = $cartItems->first()->listing->bakery_id;

    //     $order = Order::create([
    //         'user_id' => $user->id,
    //         'restaurant_id' => $restaurantId,
    //         'address_id' => $request->address_id,
    //         'status' => 'pending',
    //         'payment_status' => $request->payment_method === 'Card' ? 'unpaid' : 'paid',
    //         'payment_method' => ucfirst($request->payment_method),
    //         'subtotal' => $subtotal,
    //         'delivery_fee' => $deliveryFee,
    //         'total_price' => $totalPrice,
    //         'special_instructions' => $request->special_instructions,
    //     ]);

    //     foreach ($cartItems as $item) {
    //         Order_items::create([
    //             'listing_id' => $item->listing_id,
    //             'order_id' => $order->id,
    //             'quantity' => $item->quantity,
    //             'price' => $item->listing->discountedprice,
    //         ]);

    //         $item->listing->decrement('remainingitem', $item->quantity);
    //     }

    //     if ($request->payment_method === 'Cash') {
    //         Cart::where('customer_id', $user->id)->delete();

    //         return redirect()->route('Customer.orders.show')
    //             ->with('success', 'Order placed successfully');
    //     }

    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     $lineItems = [];
    //     foreach ($cartItems as $item) {
    //         $lineItems[] = [
    //             'price_data' => [
    //                 'currency' => 'pkr',
    //                 // 'product_data' => ['name' => $item->listing_name],
    //                 'product_data' => ['name' => $item->listing->name],

    //                 'unit_amount' => $item->listing->discountedprice * 100,
    //             ],
    //             'quantity' => $item->quantity,
    //         ];
    //     }

    //     $lineItems[] = [
    //         'price_data' => [
    //             'currency' => 'pkr',
    //             'product_data' => ['name' => 'Delivery Fee'],
    //             'unit_amount' => $deliveryFee * 100,
    //         ],
    //         'quantity' => 1,
    //     ];


    //     $checkoutSession = StripeSession::create([
    //         'payment_method_types' => ['card'],
    //         'line_items' => $lineItems,
    //         'mode' => 'payment',
    //         'success_url' => route('stripe.success', ['order' => $order->id]),
    //         'cancel_url' => route('stripe.cancel', ['order' => $order->id]),
    //     ]);


    //     return redirect($checkoutSession->url);
    // }


    // public function placeOrder(Request $request)
    // {
    //     $request->validate([
    //         'address_id' => 'required|exists:addresses,id',
    //         'special_instructions' => 'required|string|max:500',
    //         'payment_method' => 'required|in:Cash,Card',
    //     ]);

    //     $user = Auth::user();

    //     // 🛒 Fetch user's cart
    //     $cartItems = Cart::with('listing')->where('customer_id', $user->id)->get();
    //     if ($cartItems->isEmpty()) {
    //         return redirect()->back()->with('error', 'Your cart is empty.');
    //     }

    //     $subtotal = $cartItems->sum(fn($item) => $item->listing->discountedprice * $item->quantity);
    //     $deliveryFee = 200;
    //     $totalPrice = $subtotal + $deliveryFee;
    //     $restaurantId = $cartItems->first()->listing->bakery_id;

    //     // 🧾 Create Order Record
    //     $order = Order::create([
    //         'user_id' => $user->id,
    //         'restaurant_id' => $restaurantId,
    //         'address_id' => $request->address_id,
    //         'status' => 'pending',
    //         'payment_status' => $request->payment_method === 'Card' ? 'unpaid' : 'paid',
    //         'payment_method' => $request->payment_method,
    //         'subtotal' => $subtotal,
    //         'delivery_fee' => $deliveryFee,
    //         'total_price' => $totalPrice,
    //         'special_instructions' => $request->special_instructions,
    //     ]);

    //     foreach ($cartItems as $item) {
    //         Order_items::create([
    //             'listing_id' => $item->listing_id,
    //             'order_id' => $order->id,
    //             'quantity' => $item->quantity,
    //             'price' => $item->listing->discountedprice,
    //         ]);

    //         // Decrease inventory
    //         $item->listing->decrement('remainingitem', $item->quantity);
    //     }

    //     // 💵 Cash on Delivery Flow
    //     if ($request->payment_method === 'Cash') {
    //         // Clear Cart
    //         Cart::where('customer_id', $user->id)->delete();

    //         return redirect()->route('Customer.orders.show')
    //             ->with('success', 'Order placed successfully.');
    //     }

    //     // 💳 Stripe Payment Flow
    //     try {
    //         \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    //         $lineItems = [];

    //         // Add each product to Stripe Checkout
    //         foreach ($cartItems as $item) {
    //             $lineItems[] = [
    //                 'price_data' => [
    //                     'currency' => 'pkr',
    //                     'product_data' => [
    //                         'name' => $item->listing->name,
    //                     ],
    //                     'unit_amount' => $item->listing->discountedprice * 100, // Stripe expects amount in paisa
    //                 ],
    //                 'quantity' => $item->quantity,
    //             ];
    //         }

    //         // Add delivery fee as separate line item
    //         $lineItems[] = [
    //             'price_data' => [
    //                 'currency' => 'pkr',
    //                 'product_data' => ['name' => 'Delivery Fee'],
    //                 'unit_amount' => $deliveryFee * 100,
    //             ],
    //             'quantity' => 1,
    //         ];

    //         // ✅ Create Stripe Checkout Session
    //         $checkoutSession = \Stripe\Checkout\Session::create([
    //             'payment_method_types' => ['card'],
    //             'line_items' => $lineItems,
    //             'mode' => 'payment',
    //             'success_url' => route('stripe.success', ['order' => $order->id]),
    //             'cancel_url' => route('stripe.cancel', ['order' => $order->id]),
    //         ]);

    //         // Redirect to Stripe payment page
    //         return redirect($checkoutSession->url);
    //     } catch (\Exception $e) {
    //         // 🧯 Handle Stripe error gracefully
    //         return redirect()->back()->with('error', 'Stripe error: ' . $e->getMessage());
    //     }
    // }















    public function placeOrder(Request $request)
    {

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'special_instructions' => 'nullable|string|max:500',
            'payment_method' => 'required|in:Cash,Card',
        ]);

        $user = Auth::user();

        // fetch cart
        $cartItems = Cart::with('listing')->where('customer_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            // If AJAX (JSON) request, return JSON; else redirect back
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Cart is empty.'], 400);
            }
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->listing->discountedprice * $item->quantity);
        $deliveryFee = 200;
        $totalPrice = $subtotal + $deliveryFee;
        $restaurantId = $cartItems->first()->listing->bakery_id;

        // create order record (unpaid for both flows)
        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId,
            'address_id' => $request->address_id,
            'status' => 'pending',
            'payment_status' => 'unpaid', // ALWAYS unpaid until payment confirmed
            'payment_method' => $request->payment_method,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_price' => $totalPrice,
            'special_instructions' => $request->special_instructions,
        ]);

        // If Cash: create items immediately, decrement inventory, clear cart, redirect
        if ($request->payment_method === 'Cash') {

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

            // Normal redirect with flash (non-AJAX)
            if (! $request->wantsJson()) {
                return redirect()->route('Customer.orders.show')
                    ->with('success', 'Order placed successfully (Cash). Payment status: unpaid.');
            }

            // If AJAX requested (shouldn't happen for Cash in our frontend), respond with redirect URL
            return response()->json(['redirect' => route('Customer.orders.show')]);
        }

        // If Card: create Stripe Checkout Session and return checkout URL (order remains unpaid)
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $lineItems = [];

            foreach ($cartItems as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'pkr',
                        'product_data' => [
                            'name' => $item->listing->name,
                        ],
                        'unit_amount' => intval($item->listing->discountedprice * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Delivery fee
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'pkr',
                    'product_data' => ['name' => 'Delivery Fee'],
                    'unit_amount' => intval($deliveryFee * 100),
                ],
                'quantity' => 1,
            ];

            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                // redirect back to your site. Using route param array is important
                'success_url' => route('stripe.success', ['order' => $order->id]),
                'cancel_url' => route('stripe.cancel', ['order' => $order->id]),
            ]);
            return redirect($checkoutSession->url);

            // return response()->json(['url' => $checkoutSession->url]);
        } catch (\Exception $e) {
            Log::error('Stripe create session error: ' . $e->getMessage());
            return response()->json(['error' => 'Stripe error: ' . $e->getMessage()], 500);
        }
    }
    public function showorders(Request $request)
    {
        $id = Auth::id();
        $status = $request->query('status');

        $query = Order::with(['user',  'items.listing.owner'])
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->get()->map(function ($order) {
            $order->cancel = $order->created_at >= now()->subMinutes(10);
            return $order;
        });

        // dd('', $orders);
        return view('Customer.orders', compact('orders', 'status'));
    }

    public function cancel(Order $order)
    {

        if ($order->created_at < now()->subMinutes(10)) {
            return response()->json(['success' => false, 'message' => 'Order can no longer be cancelled.']);
        }

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'This order cannot be cancelled.']);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['success' => true, 'message' => 'Order has been cancelled successfully.']);
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
                // return redirect()->back()->with('error', 'Unauthorized action.');
                return response()->json(['success' => false, 'message' => 'Unauthorized Action.'], 403);
            }

            $order->review = $request->rating;
            $order->review_status = 'Reviewed';
            $order->save();


            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
            ]);
        } catch (\Throwable $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
