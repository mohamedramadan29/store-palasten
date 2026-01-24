<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\admin\MainCategory;
use App\Models\admin\Product;
use App\Models\admin\SubCategory;
use App\Models\front\wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ShopCollection extends Controller
{
    public function collection()
    {
        $categories = MainCategory::where('status', 1)->paginate(6);

        return view('front.collection', compact('categories'));
    }

    public function collection_details($slug)
    {
        $category = MainCategory::where('slug', $slug)->first();
        // جلب المنتجات التي تنتمي لهذه الفئة أو الفئة الفرعية
        $products = Product::where(function ($query) use ($category) {
            $query->where('category_id', $category['id'])
                ->orWhere('sub_category_id', $category['id']);
        })->where('status', 1);

        // التحقق من وجود معيار الترتيب في الطلب
        if (request()->has('sort') && !empty(request()->get('sort'))) {
            switch (request()->get('sort')) {
                case 'latest':
                    $products = $products->orderBy('id', 'desc');
                    break;
                case 'oldest':
                    $products = $products->orderBy('id', 'asc');
                    break;
                case 'price_from_low_high':
                    $products = $products->orderBy('price', 'desc');
                    break;
                case 'price_from_high_low':
                    $products = $products->orderBy('price', 'asc');
                    break;
            }
        }

        // تنفيذ الاستعلام مع التصفية النهائية وتجزئة النتائج

        $products = $products->paginate(16);
        $cookie_id = Cookie::get('cookie_id');
        if (empty($cookie_id)) {
            $cookie_id = Session::getId();
            // تخزين session_id في cookie لمدة 30 يومًا
            Cookie::queue(Cookie::make('session_id', $cookie_id, 60 * 24 * 30));
        }

        $wishlistProducts = Wishlist::where('cookie_id', $cookie_id)->pluck('product_id')->toArray();

        $allCategories = MainCategory::with('SubCategories')->where('status', 1)->get();
        return view('front.category-details', compact('category', 'products', 'wishlistProducts', 'allCategories'));
    }

    public function SubCollectionDetails($slug, $sub_slug)
    {
        $category = MainCategory::where('slug', $slug)->first();
        $sub_category = SubCategory::where('slug', $sub_slug)->first();
        $products = Product::where(function ($query) use ($sub_category) {
            $query->where('sub_category_id', $sub_category['id']);
        })->where('status', 1);

        // التحقق من وجود معيار الترتيب في الطلب
        if (request()->has('sort') && !empty(request()->get('sort'))) {
            switch (request()->get('sort')) {
                case 'latest':
                    $products = $products->orderBy('id', 'desc');
                    break;
                case 'oldest':
                    $products = $products->orderBy('id', 'asc');
                    break;
                case 'price_from_low_high':
                    $products = $products->orderBy('price', 'desc');
                    break;
                case 'price_from_high_low':
                    $products = $products->orderBy('price', 'asc');
                    break;
            }
        }

        // تنفيذ الاستعلام مع التصفية النهائية وتجزئة النتائج

        $products = $products->paginate(16);
        $cookie_id = Cookie::get('cookie_id');
        if (empty($cookie_id)) {
            $cookie_id = Session::getId();
            // تخزين session_id في cookie لمدة 30 يومًا
            Cookie::queue(Cookie::make('session_id', $cookie_id, 60 * 24 * 30));
        }

        $wishlistProducts = Wishlist::where('cookie_id', $cookie_id)->pluck('product_id')->toArray();

        $allCategories = MainCategory::with('SubCategories')->where('status', 1)->get();
        return view('front.category-details', compact('category', 'products', 'wishlistProducts', 'sub_category', 'allCategories'));
    }
}
