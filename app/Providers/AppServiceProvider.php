<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

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
        // Registrar el middleware 'admin' directamente desde el AppServiceProvider
        Route::aliasMiddleware('admin', \App\Http\Middleware\IsAdmin::class);
        // Registrar el middleware 'admin' directamente desde el AppServiceProvider
        Route::aliasMiddleware('supervisor', \App\Http\Middleware\IsAdmin::class);
    }
}
