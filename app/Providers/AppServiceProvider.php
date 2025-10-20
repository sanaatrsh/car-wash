<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

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
    public function boot(Request $request): void
    {
        $locale = $request->header('Accept-Language', config('app.locale'));
        $locale = strtolower(explode(',', $locale)[0]); // 'en_US' => 'en_us'
        $locale = substr($locale, 0, 2); // 'en_us' => 'en'
        App::setLocale($locale);
    }
}
