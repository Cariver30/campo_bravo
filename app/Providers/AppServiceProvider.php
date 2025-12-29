<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Throwable;

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
        if ($this->app->runningInConsole()) {
            return;
        }

        try {
            if (Schema::hasTable('settings')) {
                View::composer('*', function ($view) {
                    if (!View::shared('settings')) {
                        $view->with('settings', Setting::first());
                    }
                });
            }
        } catch (Throwable) {
            // Ignore DB connectivity issues until migrations/seeds are ready.
        }
    }
}
