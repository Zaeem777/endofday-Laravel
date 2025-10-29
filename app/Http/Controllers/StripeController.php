<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function createSession(Request $request)
    {

        Stripe::setApiKey(config('services.stripe.secret'));
        $user = Auth::user();

        // 🛒 Get user's cart
        $cartItems = Cart::with('listing')->where('customer_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        $subtotal = $cartItems->sum(fn($item) => $item->listing->discountedprice * $item->quantity);
        $deliveryFee = 200;
        $totalPrice = $subtotal + $deliveryFee;

        // 🧾 Create Order first (unpaid)
        $restaurantId = $cartItems->first()->listing->bakery_id;

        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId,
            'address_id' => $request->address_id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'Card',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_price' => $totalPrice,
            'special_instructions' => $request->special_instructions,
        ]);

        // Create Stripe Checkout Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'pkr',
                    'product_data' => [
                        'name' => 'Order #' . $order->id,
                    ],
                    'unit_amount' => $totalPrice * 100, // PKR → paisa
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', $order->id),
            'cancel_url' => route('stripe.cancel', $order->id),
        ]);

        return response()->json(['url' => $session->url]);
    }

    // public function stripeSuccess($orderId)
    // {
    //     // dd('come on', $orderId);
    //     $order = Order::findOrFail($orderId);
    //     $userId = Auth::id();


    //     // 🧾 Add order items
    //     $cartItems = Cart::with('listing')->where('customer_id', $userId)->get();

    //     foreach ($cartItems as $item) {
    //         Order_items::create([
    //             'listing_id' => $item->listing_id,
    //             'order_id' => $order->id,
    //             'quantity' => $item->quantity,
    //             'price' => $item->listing->discountedprice,
    //         ]);

    //         $item->listing->decrement('remainingitem', $item->quantity);
    //     }

    //     // ✅ Update payment status
    //     $order->update([
    //         'payment_status' => 'paid',
    //     ]);

    //     Cart::where('customer_id', $userId)->delete();

    //     return redirect()->route('Customer.orders.show')
    //         ->with('success', 'Payment successful! Your order has been placed.');
    // }

    // public function stripeCancel($orderId)
    // {
    //     $order = Order::findOrFail($orderId);
    //     $order->update(['status' => 'cancelled']);

    //     return redirect()->route('Customer.checkout')
    //         ->with('error', 'Payment cancelled. Please try again.');
    // }


    public function stripeSuccess($orderId)
    {
        // Be careful: client redirect is not a guaranteed payment confirmation.
        // Here we simply mark paid and create order items — but you should verify with Stripe API or webhook.
        $order = Order::findOrFail($orderId);
        $userId = Auth::id();

        $cartItems = Cart::with('listing')->where('customer_id', $userId)->get();

        // If there are still cart items (user returned directly), add them.
        foreach ($cartItems as $item) {
            Order_items::create([
                'listing_id' => $item->listing_id,
                'order_id' => $order->id,
                'quantity' => $item->quantity,
                'price' => $item->listing->discountedprice,
            ]);

            $item->listing->decrement('remainingitem', $item->quantity);
        }

        $order->update(['payment_status' => 'paid']);

        Cart::where('customer_id', $userId)->delete();

        return redirect()->route('Customer.orders.show')
            ->with('success', 'Payment successful! Your order has been placed.');
    }

    public function stripeCancel($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'cancelled']);
        return redirect()->route('Customer.checkout')
            ->with('error', 'Payment cancelled. Please try again.');
    }
}
