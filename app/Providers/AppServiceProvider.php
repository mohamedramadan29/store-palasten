<?php

namespace App\Providers;

use App\Models\admin\PublicSetting;
use App\Models\front\Cart;
use App\Models\admin\Product;
use App\Models\admin\ProductVartions;
use App\Observers\ProductObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
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

        // Share stock alerts data across all admin views
        View::composer(['admin.layouts.*', 'admin.*'], function ($view) {
            if (Auth::guard('admin')->check()) {
                $stockAlerts = $this->getStockAlerts();
                $view->with('stockAlerts', $stockAlerts);
            }
        });
        
        // Share cart items with all views
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
        
        // Register observers
        Product::observe(ProductObserver::class);
        
        Paginator::useBootstrap();
    }
    
    private function getStockAlerts()
    {
        $alerts = [];
        
        try {
            // Get products with low stock (<= 10)
            $lowStockProducts = Product::with(['variations'])
                ->where('quantity', '>', 0)
                ->where('quantity', '<=', 10)
                ->limit(5)
                ->get();
                
            // Get out of stock products
            $outOfStockProducts = Product::with(['variations'])
                ->where('quantity', '<=', 0)
                ->limit(5)
                ->get();
                
            // Get variants with low stock
            $lowStockVariants = ProductVartions::with('product')
                ->where('stock', '>', 0)
                ->where('stock', '<=', 10)
                ->limit(5)
                ->get();
                
            // Get out of stock variants
            $outOfStockVariants = ProductVartions::with('product')
                ->where('stock', '<=', 0)
                ->limit(5)
                ->get();
            
            // Process low stock products
            foreach ($lowStockProducts as $product) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'مخزون منخفض',
                    'message' => "المنتج '{$product->name}' وصل للحد الأدنى",
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $product->quantity,
                    'is_variant' => false
                ];
            }
            
            // Process out of stock products
            foreach ($outOfStockProducts as $product) {
                $alerts[] = [
                    'type' => 'danger',
                    'title' => 'نفد المخزون',
                    'message' => "المنتج '{$product->name}' قد نفد",
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => 0,
                    'is_variant' => false
                ];
            }
            
            // Process low stock variants
            foreach ($lowStockVariants as $variant) {
                $variantName = isset($variant->attributes_text) ? $variant->attributes_text : $variant->product->name;
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'مخزون منخفض',
                    'message' => "متغير '{$variantName}' وصل للحد الأدنى",
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_name' => isset($variant->attributes_text) ? $variant->attributes_text : 'متغير',
                    'quantity' => $variant->stock,
                    'is_variant' => true
                ];
            }
            
            // Process out of stock variants
            foreach ($outOfStockVariants as $variant) {
                $variantName = isset($variant->attributes_text) ? $variant->attributes_text : $variant->product->name;
                $alerts[] = [
                    'type' => 'danger',
                    'title' => 'نفد المخزون',
                    'message' => "متغير '{$variantName}' قد نفد",
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_name' => isset($variant->attributes_text) ? $variant->attributes_text : 'متغير',
                    'quantity' => 0,
                    'is_variant' => true
                ];
            }
            
            // Sort alerts by type (danger first, then warning) and by quantity
            usort($alerts, function($a, $b) {
                if ($a['type'] === 'danger' && $b['type'] !== 'danger') return -1;
                if ($a['type'] !== 'danger' && $b['type'] === 'danger') return 1;
                return $a['quantity'] - $b['quantity'];
            });
            
            return array_slice($alerts, 0, 8); // Limit to 8 most important alerts
        } catch (\Exception $e) {
            return [];
        }
    }


}
