<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Product;
use App\Models\front\wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $cookie_id = Cookie::get('cookie_id');

// جلب جميع العناصر في المفضلة حسب cookie_id
        $wishlists = Wishlist::where('cookie_id', $cookie_id)->get();

// جلب المنتجات المرتبطة بالمفضلة
        $wishlistProductIds = $wishlists->pluck('product_id')->toArray();

// جلب المنتجات من جدول products بناءً على IDs
        $productsInWishlist = Product::whereIn('id', $wishlistProductIds)->get();
        return view('front.wishlist', compact('wishlists', 'productsInWishlist'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // الحصول على cookie_id أو إنشاءه في حال عدم وجوده
        $cookie_id = Cookie::get('cookie_id');
        if (empty($cookie_id)) {
            $cookie_id = (string) Str::uuid(); // إنشاء معرف فريد
            Cookie::queue(Cookie::make('cookie_id', $cookie_id, 60 * 24 * 30)); // حفظه في الكوكيز لمدة 30 يوم
        }

        // التحقق من أن المعرف الفريد موجود
        if (!empty($cookie_id)) {
            // البحث عن المنتج في المفضلة بناءً على cookie_id و product_id
            $wishlist = Wishlist::where('cookie_id', $cookie_id)
                ->where('product_id', $data['product_id'])
                ->first();

            if ($wishlist) {
                // إذا كان المنتج موجودًا، نحذفه
                $wishlist->delete();
                // حساب عدد المنتجات في المفضلة بعد الحذف
                $wishlistCount = Wishlist::where('cookie_id', $cookie_id)->count();
                return response()->json([
                    'message' => 'تم إزالة المنتج من المفضلة بنجاح!',
                    'wishlistCount' => $wishlistCount, // إرسال العدد إلى الواجهة الأمامية
                    'isFavorited' => false // إشارة بأن المنتج لم يعد في المفضلة
                ]);
            } else {
                // إذا لم يكن المنتج موجودًا، نضيفه
                Wishlist::create([
                    'cookie_id' => $cookie_id,
                    'product_id' => $data['product_id'],
                ]);
                // حساب عدد المنتجات في المفضلة بعد الإضافة
                $wishlistCount = Wishlist::where('cookie_id', $cookie_id)->count();
                return response()->json([
                    'message' => 'تم إضافة المنتج إلى المفضلة بنجاح!',
                    'wishlistCount' => $wishlistCount, // إرسال العدد إلى الواجهة الأمامية
                    'isFavorited' => true // إشارة بأن المنتج الآن في المفضلة
                ]);
            }
        }

        // في حال عدم وجود cookie_id، يمكن إرجاع رسالة خطأ
        return response()->json(['message' => 'تعذر إتمام العملية. يرجى المحاولة مرة أخرى.']);
    }

    public function delete($id)
    {
        try {
            $cookie_id = Cookie::get('cookie_id');
            if (empty($cookie_id)) {
                $cookie_id = Session::getId();
                // تخزين session_id في cookie لمدة 30 يومًا
                Cookie::queue(Cookie::make('session_id', $cookie_id, 60 * 24 * 30));
            }
            $wishlist = wishlist::where('product_id', $id)->where('cookie_id', $cookie_id)->first();
            $wishlist->delete();
            return $this->success_message(' تم حذف المنتج من المفضلة بنجاح  ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
