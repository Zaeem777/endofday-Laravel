<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Listings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show all cart items for the logged-in user (for a full cart page if needed)
    public function index()
    {
        $cartItems = Cart::where('customer_id', auth()->id())
            ->with('listing')
            ->get();

        return view('cart.index', compact('cartItems'));
    }
    public function store(Request $request)
    {
        try {
            $listing = Listings::findOrFail($request->listing_id);
            $restaurantId = $listing->bakery_id;

            $cartItems = Cart::with('listing')
                ->where('customer_id', Auth::id())
                ->get();

            if ($cartItems->isNotEmpty()) {
                $currentRestaurantId = $cartItems->first()->listing->bakery_id;

                if ($currentRestaurantId !== $restaurantId) {
                    return response()->json([
                        'success' => false,
                        'conflict' => true,
                        'message' => 'Your cart has items from another restaurant. Clear cart to continue?',
                    ]);
                }
            }

            // Increment quantity or create new
            $cart = Cart::where('customer_id', auth()->id())
                ->where('listing_id', $listing->id)
                ->first();

            $newQuantity = $cart ? $cart->quantity + 1 : 1;

            if ($newQuantity > $listing->remainingitem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more items than available in stock.',
                ], 400);
            }
            if ($cart) {
                $cart->increment('quantity');
            } else {
                $cart = Cart::create([
                    'customer_id' => auth()->id(),
                    'listing_id'  => $listing->id,
                    'price'       => $listing->discountedprice,
                    'quantity'    => 1,
                ]);
            }

            $cartItems = Cart::with('listing')
                ->where('customer_id', auth()->id())
                ->get();

            $cartTotal = $cartItems->sum(fn($i) => $i->listing->discountedprice * $i->quantity);
            $cartCount = $cartItems->sum('quantity');

            return response()->json([
                'success'   => true,
                'message'   => 'Item added to cart!',
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
                'cartItems' => view('partials.cart-items', compact('cartItems'))->render()

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }


    // Update quantity (AJAX only)
    public function update(Request $request, Cart $cart)
    {
        $listing = $cart->listing;

        if ($request->quantity > $listing->remainingitem) {
            return response()->json([
                'success'   => false,
                'message'   => 'Not enough stock available!',
            ], 400);
        }
        $cart->update([
            'quantity' => $request->input('quantity'),
        ]);
        $cart->load('listing');
        $cartItems = Cart::with('listing')
            ->where('customer_id', auth()->id())->get();
        $total     = $cartItems->sum(fn($i) => $i->listing->discountedprice * $i->quantity);
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'success'   => true,
            'message'   => 'Cart updated!',
            'quantity'  => $cart->quantity,
            'itemTotal' => $cart->listing->discountedprice * $cart->quantity,
            'cartTotal' => $total,
            'cartCount' => $cartCount,
        ]);
    }

    // Remove item (AJAX only)
    public function destroy(Cart $cart, Request $request)
    {
        $cart->delete();

        $cartItems = Cart::where('customer_id', auth()->id())->get();
        $total     = $cartItems->sum(fn($i) => $i->price * $i->quantity);
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'success'   => true,
            'message'   => 'Item removed from cart!',
            'cartTotal' => $total,
            'cartCount' => $cartCount,
        ]);
    }

    //clear cart
    public function clear()
    {
        Cart::where('customer_id', auth()->id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared!',
        ]);
    }


    //update cart in checkout page
    public function checkoutupdate(Request $request, Cart $cart)
    {
        if (!$cart) {
            return response()->json([
                'success'   => false,
                'message'   => 'Item not found in cart!',
            ]);
        }
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        // If quantity is 0, delete the cart item
        if ($request->quantity == 0) {
            $cart->delete();

            $cartItems = Cart::where('customer_id', auth()->id())->get();
            $total     = $cartItems->sum(fn($i) => $i->price * $i->quantity);
            $cartCount = $cartItems->sum('quantity');

            return response()->json([
                'success'   => true,
                'removed'   => true,
                'cartTotal' => $total,
                'cartCount' => $cartCount,
            ]);
        }

        // Otherwise, update quantity
        $cart->update([
            'quantity' => $request->quantity,
        ]);

        $cartItems = Cart::where('customer_id', auth()->id())->get();
        $total     = $cartItems->sum(fn($i) => $i->price * $i->quantity);
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'success'   => true,
            'removed'   => false,
            'quantity'  => $cart->quantity,
            'itemTotal' => $cart->price * $cart->quantity,
            'cartTotal' => $total,
            'cartCount' => $cartCount,
        ]);
    }
}
