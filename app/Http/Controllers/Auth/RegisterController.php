<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Restaurant Owner Signup
    public function registerRestaurant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // bakery/restaurant name
            'address' => 'required|string|max:500',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:11',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('restaurant_images', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'image' => $imagePath,
            'role' => 'restaurant_owner', // 👈 auto-assign role
        ]);

        Auth::login($user);

        return redirect('/Restaurant/dashboard')->with(
            'success', 'Restaurant Owner registered successfully!');
        }


    // Customer Signup
    public function registerCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:11',
            'password' => 'required|string|min:6|confirmed',
        ]);     
   
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer', // 👈 auto-assign role
        ]);

        Auth::login($user);

            return redirect('/Customer/dashboard')->with(
            'success', 'Customer registered successfully!');

}
};
