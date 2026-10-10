<?php

namespace App\Providers;

use App\Models\StoreSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
        if (request()->header('X-Forwarded-Proto') === 'https' || str_contains(request()->header('Host', ''), 'trycloudflare.com')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('store_settings')) {
                    $settings = StoreSetting::getAll();
                    $view->with('storeSettings', $settings);
                    $view->with('storeMapsUrl', StoreSetting::getMapsUrl($settings));
                }
            } catch (\Throwable $e) {
                // Graceful fallback during migration or console commands
            }
        });
    }
}
