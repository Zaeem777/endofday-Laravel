<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Listings;
use App\Models\Order;
use App\Models\Order_items;
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
            'name' => $request->name,
            'address' => $request->address,
            'price' => $request->price,
            'discountedprice' => $request->discountedprice,
            'category' => $request->category,
            'remainingitem' => $request->remainingitem,
            'image' => $imagePath,
            'manufacturedate' => $request->manufacturedate,
            'description' => $request->description,
            'bakery_id' => Auth::id(),
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

    public function viewRestaurantDashboard()
    {
        $id = Auth::id();

        // Total listings
        $totalListings = Listings::where('bakery_id', $id)->count();

        // Recent orders (past 24 hours) with user + items
        $recentOrders = Order::with(['user', 'items'])
            ->where('restaurant_id', $id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('id', 'asc')
            ->get();

        // Revenue from completed orders
        $revenue = Order::where('restaurant_id', $id)
            ->where('status', 'completed')
            ->sum('total_price');

        // Count of pending orders
        $pendingOrders = Order::where('restaurant_id', $id)
            ->where('status', 'pending')
            ->count();

        $averageReview = Order::where('restaurant_id', $id)
            ->where('review_status', 'Reviewed')
            ->selectRaw('AVG(CAST(review AS INT)) as avg_review')
            ->value('avg_review');

        $averageReview = round($averageReview ?? 0,);

        return view('Restaurant.dashboard', [
            'totalListings' => $totalListings,
            'recentOrders' => $recentOrders,
            'revenue' => $revenue,
            'pendingOrders' => $pendingOrders,
            'averageReview' => $averageReview
        ]);
    }

    // public function getChartData()
    // {
    //     $restaurantId = Auth::id();

    //     // === Weekly Sales Data ===
    //     // Postgres does not support DAYNAME(), use TO_CHAR() instead
    //     $salesData = Order::where('restaurant_id', $restaurantId)
    //         ->where('created_at', '>=', Carbon::now()->subDays(7))
    //         ->selectRaw("TO_CHAR(created_at, 'Day') as day, SUM(total_price) as total")
    //         ->groupBy('day')
    //         ->pluck('total', 'day');

    //     // Normalize weekday names and remove trailing spaces (Postgres pads 'Day' output)
    //     $cleanSales = [];
    //     $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    //     foreach ($allDays as $day) {
    //         $found = null;
    //         foreach ($salesData as $key => $value) {
    //             if (trim($key) === $day) {
    //                 $found = $value;
    //                 break;
    //             }
    //         }
    //         $cleanSales[] = $found ?? 0;
    //     }

    //     // === Top Categories ===
    //     $categoryData = Order_items::whereHas('order', function ($query) use ($restaurantId) {
    //         $query->where('restaurant_id', $restaurantId);
    //     })
    //         ->with('listing')
    //         ->get()
    //         ->groupBy(fn($item) => $item->listing->category ?? 'Uncategorized')
    //         ->map(fn($group) => $group->sum('quantity'))
    //         ->sortDesc()
    //         ->take(5);

    //     // === JSON Response ===
    //     return response()->json([
    //         'sales' => [
    //             'labels' => $allDays,
    //             'data' => array_values($cleanSales),
    //         ],
    //         'categories' => [
    //             'labels' => $categoryData->keys(),
    //             'data' => $categoryData->values(),
    //         ],
    //     ]);
    // }



    public function getChartData()
    {
        $restaurantId = Auth::id();

        // === Weekly Sales Data (exclude cancelled orders) ===
        $salesData = Order::where('restaurant_id', $restaurantId)
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay()) // last 7 days including today
            ->where('status', '=', 'completed')
            ->selectRaw("TO_CHAR(created_at, 'Day') as day, SUM(total_price) as total")
            ->groupBy('day')
            ->pluck('total', 'day');

        // === Build dynamic day order ===
        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Find today
        $today = Carbon::now()->format('l'); // e.g. "Wednesday"

        // Reorder days so today is at the END (and 6 previous days come before)
        $todayIndex = array_search($today, $allDays);
        $orderedDays = array_merge(
            array_slice($allDays, $todayIndex + 1), // days after today
            array_slice($allDays, 0, $todayIndex + 1) // up to today
        );

        // Normalize data (trim spaces from Postgres TO_CHAR output)
        $cleanSales = [];
        foreach ($orderedDays as $day) {
            $found = null;
            foreach ($salesData as $key => $value) {
                if (trim($key) === $day) {
                    $found = $value;
                    break;
                }
            }
            $cleanSales[] = $found ?? 0;
        }

        // === Top Categories (exclude cancelled orders) ===
        $categoryData = Order_items::whereHas('order', function ($query) use ($restaurantId) {
            $query->where('restaurant_id', $restaurantId)
                ->where('status', '!=', 'cancelled');
        })
            ->with('listing')
            ->get()
            ->groupBy(fn($item) => $item->listing->category ?? 'Uncategorized')
            ->map(fn($group) => $group->sum('quantity'))
            ->sortDesc()
            ->take(5);

        // === JSON Response ===
        return response()->json([
            'sales' => [
                'labels' => $orderedDays,
                'data' => array_values($cleanSales),
            ],
            'categories' => [
                'labels' => $categoryData->keys(),
                'data' => $categoryData->values(),
            ],
        ]);
    }

    public function showorder($id)
    {
        $order = Order::with(['items.listing', 'user', 'address'])->findOrFail($id);
        return view('Restaurant.showorder', compact('order'));
    }

    public function allorders(Request $request)
    {
        $restaurantId = Auth::id();
        $status = $request->get('status');

        $ordersQuery = Order::with(['items.listing', 'user', 'address'])
            ->where('restaurant_id', $restaurantId)
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->get();

        $statuses = Order::select('status')
            ->where('restaurant_id', $restaurantId)
            ->distinct()
            ->pluck('status');
        return view('Restaurant.allorders', compact(
            'orders',
            'statuses',
            'status'
        ));
    }
};
