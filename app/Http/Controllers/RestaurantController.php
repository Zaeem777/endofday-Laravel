<?php

namespace App\Http\Controllers;

use App\Models\Listings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{

    //view profile
    public function profile()
    {
        $restaurant = Auth::user();
        
         if ($restaurant->role !== 'restaurant_owner') {
            return redirect()->route('login');
        }
        return view('Restaurant.profile', compact('restaurant'));
    }
    
    //edit profile 
    public function updateProfile(Request $request)
{
    $restaurant = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $restaurant->id,
        'phone' => 'required|string|max:11',
        'address' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $restaurant->name = $request->name;
    $restaurant->email = $request->email;
    $restaurant->phone = $request->phone;
    $restaurant->address = $request->address;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('restaurant_images', 'public');
        $restaurant->image = $path;
    }

    if ($request->filled('password')) {
        $restaurant->password = Hash::make($request->password);
    }
    $restaurant->save();

    return redirect()->route('Restaurant.profile')->with('success', 'Profile updated successfully!');
}


//create listing
    public function createlisting(Request $request)
{
    $request->validate([

        'name' => 'required|string|max:255',
        'price' => 'required|integer|min:0',
        'discountedprice' => 'nullable|integer|min:0',
        'category' => 'required|string|max:100',
        'remainingitem' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'manufacturedate' => 'required|date',
        'description' => 'nullable|string',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('listing_images', 'public');
    }

    $listing = Listings::create([
        'name'=>$request->name,
        'address'=>$request->address,
        'price'=>$request->price,
        'discountedprice'=>$request->discountedprice,
        'category'=>$request->category,
        'remainingitem'=>$request->remainingitem,
        'image'=>$imagePath,
        'manufacturedate'=>$request->manufacturedate,
        'description'=>$request->description,
        'bakery_id'=>Auth::id(),
    ]);

    return redirect('/Restaurant/dashboard')->with('success', 'Listing created successfully!');
}

//view all listings
public function showlistings()
{ 
    $restaurantId = Auth::id();
    $listings = Listings::where('bakery_id', $restaurantId)->get();

    // dd($listings);
    return view('Restaurant.showlistings', compact('listings'));

}

//delete listing
public function deletelisting($id)
{
    $listing = Listings::findOrFail($id);

    // Ensure the authenticated user owns the listing
    if ($listing->bakery_id !== Auth::id()) {
        return redirect()->route('Restaurant.showlistings')->with('error', 'Unauthorized action.');
    }

    $listing->delete();

    return redirect()->route('Restaurant.showlistings')->with('success', 'Listing deleted successfully.');
}

//view edit form
public function editform($id)
{
    $listing = Listings::findOrFail($id);

    // Ensure the authenticated user owns the listing
    if ($listing->bakery_id !== Auth::id()) {
        return redirect()->route('Restaurant.showlistings')->with('error', 'Unauthorized action.');
    }

    return view('Restaurant.editlisting', compact('listing'));

}

//Update listing 
public function update(Request $request, $id)
{
    $listing = Listings::findOrFail($id);

    // Ensure the authenticated user owns the listing
    if ($listing->bakery_id !== Auth::id()) {
        return redirect()->route('Restaurant.showlistings')->with('error', 'Unauthorized action.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|integer|min:0',
        'discountedprice' => 'nullable|integer|min:0',
        'category' => 'required|string|max:100',
        'remainingitem' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'manufacturedate' => 'required|date',
        'description' => 'nullable|string',
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('listing_images', 'public');
        $listing->image = $imagePath;
    }

    $listing->name = $request->name;
    $listing->price = $request->price;
    $listing->discountedprice = $request->discountedprice;
    $listing->category = $request->category;
    $listing->remainingitem = $request->remainingitem;
    $listing->manufacturedate = $request->manufacturedate;
    $listing->description = $request->description;

    $listing->save();

    return redirect()->route('Restaurant.showlistings')->with('success', 'Listing updated successfully!');
}

};
