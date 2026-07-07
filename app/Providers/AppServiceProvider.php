<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
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
        if (! $this->app->runningInConsole() && Schema::hasTable('settings')) {
            $socialLinks = [
                'social_instagram' => Setting::getValue('social_instagram', 'https://instagram.com'),
                'social_facebook'  => Setting::getValue('social_facebook', 'https://facebook.com'),
                'social_twitter'   => Setting::getValue('social_twitter', 'https://twitter.com'),
                'social_whatsapp'  => Setting::getValue('social_whatsapp', 'https://wa.me/6281234567890'),
            ];

            view()->share('socialLinks', $socialLinks);
        }
    }
}
