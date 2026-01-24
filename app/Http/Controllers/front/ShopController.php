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
                case 'price_from_low_heigh';
                $productQuery->orderBy('price', 'asc');
                case 'price_from_hieght_low';
                $productQuery->orderBy('price','desc');
                case 'oldest';
                $productQuery->orderBy('created_at', 'asc');
                case 'latest';
                $productQuery->orderBy('created_at','desc');
                break;
            }
        }
        $products = $productQuery->orderBy('id','desc')->paginate(16);
        $cookie_id = Cookie::get('cookie_id');
        if (empty($cookie_id)) {
            $cookie_id = Session::getId();
            // تخزين session_id في cookie لمدة 30 يومًا
            Cookie::queue(Cookie::make('session_id', $cookie_id, 60 * 24 * 30));
        }

        $wishlistProducts = Wishlist::where('cookie_id', $cookie_id)->pluck('product_id')->toArray();
        return view('front.shop',compact('products','wishlistProducts'));
    }
}
