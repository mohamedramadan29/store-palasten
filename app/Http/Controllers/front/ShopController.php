<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\front\wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    public function shop(Request $request)
    {
      //  $products = Product::where('status',1)->paginate(16);
        $productQuery = Product::where('status',1);
        if ($request->has('sort')){
            switch ($request->input('sort')){
                case 'price_asc':
                    $productQuery->orderByRaw('CASE WHEN discount > 0 THEN discount ELSE price END ASC');
                    break;
                case 'price_desc':
                    $productQuery->orderByRaw('CASE WHEN discount > 0 THEN discount ELSE price END DESC');
                    break;
                case 'oldest':
                    $productQuery->orderBy('created_at', 'asc');
                    break;
                case 'latest':
                    $productQuery->orderBy('created_at', 'desc');
                    break;
            }
        }
        $products = $productQuery->orderBy('id', 'desc')->paginate(16);
        $cookie_id = Cookie::get('cookie_id');
        if (empty($cookie_id)) {
            $cookie_id = Session::getId();
            // تخزين session_id في cookie لمدة 30 يومًا
            Cookie::queue(Cookie::make('session_id', $cookie_id, 60 * 24 * 30));
        }

        $wishlistProducts = wishlist::where('cookie_id', $cookie_id)->pluck('product_id')->toArray();
        return view('front.shop',compact('products','wishlistProducts'));
    }

    public function marketerShop(Request $request)
    {
        // Only marketers can access this page
        if (!auth()->guard('marketer')->check()) {
            return redirect('/marketer/login')->with('error', 'يجب تسجيل الدخول كمسوق للوصول إلى هذه الصفحة');
        }

        $productQuery = Product::where('status',1)->with('variations');
        
        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $productQuery->where('category_id', $request->category_id);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $productQuery->where('name', 'LIKE', '%' . $request->search . '%');
        }
        
        // Sort functionality
        if ($request->has('sort')){
            switch ($request->input('sort')){
                case 'price_asc':
                    $productQuery->orderByRaw('CASE WHEN marketer_price > 0 THEN marketer_price ELSE price END ASC');
                    break;
                case 'price_desc':
                    $productQuery->orderByRaw('CASE WHEN marketer_price > 0 THEN marketer_price ELSE price END DESC');
                    break;
                case 'oldest':
                    $productQuery->orderBy('created_at', 'asc');
                    break;
                case 'latest':
                    $productQuery->orderBy('created_at', 'desc');
                    break;
            }
        }
        
        $products = $productQuery->orderBy('id', 'desc')->paginate(16);
        
        // Get categories for filter dropdown
        $categories = \App\Models\admin\MainCategory::where('status', 1)->get();
        
        // Get wishlist products for the current marketer
        $cookie_id = \Illuminate\Support\Facades\Cookie::get('cookie_id');
        if (empty($cookie_id)) {
            $cookie_id = \Illuminate\Support\Facades\Session::getId();
            \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::make('cookie_id', $cookie_id, 60 * 24 * 30));
        }
        $wishlistProducts = wishlist::where('cookie_id', $cookie_id)->pluck('product_id')->toArray();
        
        return view('front.marketer-shop', compact('products', 'categories', 'wishlistProducts'));
    }
}
