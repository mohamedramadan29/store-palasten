<?php

namespace App\Providers;

use App\Models\admin\PublicSetting;
use App\Models\front\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https'); // فرض استخدام HTTPS
        }
        
        if (Schema::hasTable('public_settings')) {
            $settings = PublicSetting::first();
            $currency = $settings['website_currency'];
            View::share('storeCurrency', $currency);
        }

        View::composer('*', function ($view) {
            $cartItems = [];
            $cartCount = 0;

            if (Auth::check()) {
                $user_id = Auth::user()->id;
                $cartItems = Cart::with('productdata')->where('user_id', $user_id)->get();
            } else {
                $session_id = Session::get('session_id');
                if (empty($session_id)) {
                    $session_id = Session::getId();
                    Session::put('session_id', $session_id);
                }
                $cartItems = Cart::with('productdata')->where('session_id', $session_id)->get();
            }

            // حساب عدد العناصر في السلة
            $cartCount = $cartItems->count();

            // مشاركة عناصر السلة وعددها مع جميع الفيوهات
            View::share('cartItems', $cartItems);
            View::share('cartCount', $cartCount);
        });
    }


}
