<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactUsController extends Controller
{

    public function contactus()
    {
        $user = Auth::user();

        // Default layout if not logged in
        $layout = 'customer-layout';

        if ($user && $user->role === 'restaurant_owner') {
            $layout = 'layout';
        } elseif ($user && $user->role === 'customer') {
            $layout = 'customer-layout';
        }

        return view('contactus', compact('layout'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone' => 'nullable|string|max:11',
            'email' => 'nullable|email',
            'topic' => 'nullable|string',
            'body' => 'nullable|string',
        ]);

        Contact::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'email' => $request->email,
            'topic' => $request->topic,
            'body' => $request->body,
        ]);

        session()->flash('success', 'Your message has been sent to the admin.');

        return redirect()->route('ContactUs');
    }
}
