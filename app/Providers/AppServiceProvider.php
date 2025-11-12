<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(ThumbnailService::class, function ($app) {
            return new ThumbnailService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa semua URL pakai HTTPS
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
