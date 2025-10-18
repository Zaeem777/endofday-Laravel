<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cart;
use App\Models\Listings;
use Illuminate\Http\Request;

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
    $listingId = $request->input('listing_id');
    $listing   = Listings::findOrFail($listingId);

    // find or create the cart item
    $cartItem = Cart::firstOrCreate(
        [
            'customer_id' => auth()->id(),
            'listing_id'  => $listingId,
        ],
        [
            'quantity' => 0, // start with 0 so we can add below
            'price'    => $listing->price,
        ]
    );

    // always increment properly
    $cartItem->quantity += $request->input('quantity', 1);
    $cartItem->save();

    // get full cart again
    $cartItems = Cart::where('customer_id', auth()->id())->get();
    $cartTotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);

    return response()->json([
        'success'   => true,
        'message'   => 'Item added to cart!',
        'cartCount' => $cartItems->sum('quantity'),
        'cartTotal' => $cartTotal,
        'cartItems' => view('partials.cart-items', compact('cartItems'))->render()
    ]);
}




    // Update quantity (AJAX only)
    public function update(Request $request, Cart $cart)
    {
        $cart->update([
            'quantity' => $request->input('quantity'),
        ]);

        $cartItems = Cart::where('customer_id', auth()->id())->get();
        $total     = $cartItems->sum(fn($i) => $i->price * $i->quantity);
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'success'   => true,
            'message'   => 'Cart updated!',
            'quantity'  => $cart->quantity,
            'itemTotal' => $cart->price * $cart->quantity,
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


    //update cart in checkout page
public function checkoutupdate(Request $request, Cart $cart)
{
if(!$cart){
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
