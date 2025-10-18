<?php

namespace App\Providers;
use App\Models\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         View::composer('components.customer-layout', function ($view) {
        $cartItems = auth()->check()
            ? Cart::where('customer_id', auth()->id())->with('listing')->get()
            : collect();

        $view->with([
            'cartItems' => $cartItems,
            'cartCount' => $cartItems->sum('quantity'),
        ]);
    });
    }
}
