<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\RestaurantOwnerMiddleware;
use Pest\ArchPresets\Custom;

//Signup Pages

//Restaurant Signup Page
Route::get('/Restaurant/register', function () {
    return view('Restaurant.register');
});

Route::post('/Restaurantowner/register', [RegisterController::class, 'registerRestaurant']);

//Customer Signup Page

Route::get('/Customer/register', function () {
    return view('Customer.register');
});

Route::post('/Customer/register', [RegisterController::class, 'registerCustomer'])->name('customer.register');

//Rider Signup Page 


//Login page 
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

//logout page 
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


//All Restaurant Routes

//Dashboard Routes
Route::middleware([RestaurantOwnerMiddleware::class])->group(function () {
    Route::get('/Restaurant/dashboard', [RestaurantController::class, 'viewRestaurantDashboard'])->name('Restaurant.dashboard');
    Route::get('/Restaurant/chartdata', [RestaurantController::class, 'getChartData'])->name('Restaurant.chartdata');
    Route::get('/Restaurant/orderDetail/{id}', [RestaurantController::class, 'showorder'])->name('Restaurant.show.order');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/Restaurant/allorders', [RestaurantController::class, 'allorders'])->name('Restaurant.allorders');
});

//View, Edit Restaurant Profile
Route::middleware([RestaurantOwnerMiddleware::class])->group(function () {
    Route::get('/Restaurant/profile', [RestaurantController::class, 'profile'])->name('Restaurant.profile');
    Route::post('/Restaurant/profile', [RestaurantController::class, 'updateProfile'])->name('Restaurant.updateProfile');
});


//Restaurant Listings 

//Create listings
Route::middleware([RestaurantOwnerMiddleware::class])->group(function () {
    Route::get('/Restaurant/createlisting', function () {
        return view('Restaurant.createlisting');
    });

    Route::post('/Restaurant/createlisting', [RestaurantController::class, 'createlisting'])->name('Restaurant.createlisting');
});

//View, Edit ,Update , Delete Listings
Route::middleware([RestaurantOwnerMiddleware::class])->group(function () {
    Route::get('/Restaurant/showlistings', [RestaurantController::class, 'showlistings'])->name('Restaurant.showlistings');
    Route::get('/Restaurant/listings/{id}/edit', [RestaurantController::class, 'editform'])->name('listing.edit');
    Route::put('/Restaurant/listings/{id}', [RestaurantController::class, 'update'])->name('listing.update');
    Route::delete('/Restaurant/listings/{id}', [RestaurantController::class, 'deletelisting'])->name('listing.destroy');
});



//All Customer Routes

//Customer Dashboard
Route::get('/Customer/dashboard', function () {
    return view('Customer.dashboard');
})->middleware([CustomerMiddleware::class]);

//Customer Profile 
Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/Customer/profile', [CustomerController::class, 'profile'])->name('Customer.profile');
    Route::put('/Customer/profile', [CustomerController::class, 'updateProfile'])->name('Customer.updateProfile');
});

//View Restaurants and Restaurant Menu 
Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/Customer/restaurants', [CustomerController::class, 'viewRestaurants'])->name('Customer.restaurants');
    Route::get('/Customer/restaurant/{id}/menu', [CustomerController::class, 'viewRestaurantMenu'])->name('Customer.restaurant.menu');
});


//Cart Routes
Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
});

//Customer Checkout Routes
Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/Customer/checkout', [CustomerController::class, 'checkout'])->name('Customer.checkout');
    Route::post('/Customer/placeorder', [CustomerController::class, 'placeOrder'])->name('Customer.placeorder');
    Route::patch('/Customer/checkout/update/{cart}', [CartController::class, 'checkoutupdate'])->name('Customer.checkout.update');
});

// Customer Address Routes
Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::post('/Customer/addaddresses', [CustomerController::class, 'storeAddress'])->name('Customer.address.store');
    Route::delete('/Customer/deleteaddress/{id}', [CustomerController::class, 'deleteAddress'])->name('Customer.address.destroy');
});

//Customer Order Routes and Review Route
Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::post('/Customer/placeorder', [CustomerController::class, 'placeorder'])->name('Customer.placeorder');
    Route::get('/Customer/showorders', [CustomerController::class, 'showorders'])->name('Customer.orders.show');
    Route::post('/Customer/review', [CustomerController::class, 'submitReview'])->name('Customer.orders.review');
});
