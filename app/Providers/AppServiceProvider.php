<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('layouts.navigation', function ($view) {
            $lowStockCount = \App\Models\Product::where('stock_quantity', '<', 5)->count();
            $view->with('lowStockCount', $lowStockCount);
        });
    }
}
