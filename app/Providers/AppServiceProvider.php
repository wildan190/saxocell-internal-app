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
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\Eloquent\ProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\SupplierRepositoryInterface::class,
            \App\Repositories\Eloquent\SupplierRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\InventoryRepositoryInterface::class,
            \App\Repositories\Eloquent\InventoryRepository::class
        );
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
