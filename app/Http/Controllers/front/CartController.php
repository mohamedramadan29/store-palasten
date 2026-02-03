<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Coupon;
use App\Models\admin\ShippingCity;
use App\Models\front\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class CartController extends Controller
{
    use Message_Trait;

    public function cart()
    {
        $cartItems = Cart::getcartitems();
        $cartcount = $cartItems->count();
        $shippingCity = ShippingCity::where('status', 1)->get();
        return view('front.cart', compact('cartItems', 'cartcount', 'shippingCity'));
    }

    public function add(Request $request)
    {
        $cartData = $request->all();
        //  dd($cartData);
        // Generate Session Id If Not Exists
        $session_id = Session::get('session_id');
        if (empty($session_id)) {
            $session_id = Session::getId();
            Session::put('session_id', $session_id);
            Session::regenerate(); // تأكد من تحديث الجلسة
        }

        //Check If This Product Already Exist Or Not
        if (Auth::check()) {
            // User Is Login
            $user_id = Auth::user()->id;
            $countProducts = Cart::where(['product_id' => $cartData['product_id'], 'user_id' => $user_id, 'product_variation_id' => $cartData['hidden-variation']])->count();
        } else {
            // User Not Login
            $user_id = 0;
            $countProducts = Cart::where(['product_id' => $cartData['product_id'], 'session_id' => $session_id, 'product_variation_id' => $cartData['hidden-variation']])->count();
        }

        // Stock Validation
        $qty = $cartData['number'] ?? 1;
        if (isset($cartData['hidden-variation']) && $cartData['hidden-variation']) {
            $variation = \App\Models\admin\ProductVartions::find($cartData['hidden-variation']);
            if (!$variation || $variation->stock < $qty) {
                return response()->json(['status' => false, 'message' => 'الكمية المطلوبة غير متوفرة حالياً في المخزون'], 422);
            }
        } else {
            $product = \App\Models\admin\Product::find($cartData['product_id']);
            if (!$product || $product->quantity < $qty) {
                return response()->json(['status' => false, 'message' => 'الكمية المطلوبة غير متوفرة حالياً في المخزون'], 422);
            }
        }
        if ($countProducts > 0) {
            return response()->json(['message' => 'تم اضافة المنتج الي السلة من قبل ']);
        }
        // Save Product In Cart Tabel
        if (isset($cartData['discount']) && $cartData['discount'] > 0) {
            $price = $cartData['discount'];
        } else {
            $price = $cartData['price'];
        }
        if (isset($cartData['hidden-variation']) && $cartData['hidden-variation']) {
            $hidden_vartion = $cartData['hidden-variation'];
        } else {
            $hidden_vartion = null;
        }
        $item = new Cart();
        $item->session_id = $session_id;
        $item->user_id = $user_id;
        $item->product_id = $cartData['product_id'];
        $item->qty = $cartData['number'];
        $item->price = $price;
        $item->product_variation_id = $hidden_vartion;
        $item->save();
        $cartCount = Cart::getcartitems()->count();
        return response()->json([
            'message' => ' تم اضافه المنتج الي السله',
            'cartCount' => $cartCount // إرسال العدد إلى الـ Frontend
        ]);

        //return $this->success_message(' تم اضافه المنتج الي السله');
    }

    public function getCartItems()
    {
        $cartItems = [];

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

        // إرجاع البيانات المحدثة كـ JSON
        return response()->json([
            'html' => view('front.partials.cart_items', compact('cartItems'))->render(), // رندر الـ HTML
            'cartCount' => $cartItems->count(), // تحديث عداد السلة
        ]);
    }


    public function delete($id)
    {
        try {
            $item = Cart::findOrFail($id);
            $item->delete();
            return $this->success_message(' تم حذف المنتج من سلة المشتريات  ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function updateCart(Request $request)
    {
        $cartItem = Cart::find($request->item_id); // إيجاد العنصر في السلة
        if ($cartItem) {
            // Stock Validation for update
            if ($cartItem->product_variation_id) {
                $variation = \App\Models\admin\ProductVartions::find($cartItem->product_variation_id);
                if (!$variation || $variation->stock < $request->quantity) {
                    return response()->json(['error' => 'الكمية المطلوبة غير متوفرة'], 422);
                }
            } else {
                $product = \App\Models\admin\Product::find($cartItem->product_id);
                if (!$product || $product->quantity < $request->quantity) {
                    return response()->json(['error' => 'الكمية المطلوبة غير متوفرة'], 422);
                }
            }

            $cartItem->qty = $request->quantity; // تحديث الكمية
            $cartItem->save(); // حفظ التحديثات

            // حساب المجموع للمنتج الواحد
            $itemTotal = $cartItem->qty * $cartItem->price;

            // حساب المجموع الفرعي (Subtotal)
            $subtotal = Cart::getcartitems()->sum(function ($item) {
                return $item['price'] * $item['qty'];
            });

            return response()->json([
                'itemTotal' => $itemTotal,
                'subtotal' => $subtotal
            ]);
        }

        return response()->json(['error' => 'Item not found'], 404);
    }


    // Start Apply Coupon To Users
    public function apply_coupon(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            Session::forget('coupon_code');
            Session::forget('coupon_amount');
            // dd($data);
            $cartItems = Cart::getcartitems();

            //dd($cartItems);
            $item_counts = $cartItems->count();

            $couponCount = Coupon::where('coupon_code', $data['code'])->count();
            if ($couponCount > 0) {
                // Check Other Coupon Conditions
                // Get the Coupon Data
                $coupondata = Coupon::where('coupon_code', $data['code'])->first();
                // check the code is active or not
                if ($coupondata['status'] == 0) {
                    $message = '  كود الخصم غير فعال ';
                }
                // check if this coupon is expired or not
                $current_date = date("Y-m-d");
                if ($coupondata['expire_date'] < $current_date) {
                    $message = ['لقد انتهي وقت هذا الكود '];
                }

                // Check If this Coupon in selected Categories Or Not All Product
                if ($coupondata['categories'] != 'all') {
                    $catarray = explode(',', $coupondata['categories']);
                    foreach ($cartItems as $key => $item) {
                        if (!in_array($item['productdata']['category_id'], $catarray)) {
                            $message = 'هذا الكود غير متاح مع هذه المنتجات ';
                        }
                    }
                }
                // Error Message Here
                if (isset($message)) {
                    return response()->json([
                        'status' => 'false',
                        'message' => $message,
                        'View' => (string)View::make('front.partials.cart_items')->with(compact('cartItems', 'item_counts')),
                    ]);
                } else {
                    $total_amount = 0;
                    // Coupon Code Is Correct
                    foreach ($cartItems as $item) {
                        $price = floatval($item->price); // تأكد من أن السعر رقم
                        $qty = intval($item->qty); // تأكد من أن الكمية رقم
                        $sub_total = $price * $qty;
                        $total_amount += $sub_total;
                    }
                    // Check If The Coupon Type Is Fixed Or Percentage

                    if ($coupondata['amount_type'] == 'خصم ثابت') {
                        $couponamount = $coupondata['amount'];
                    } else {
                        $couponamount = $total_amount * ($coupondata['amount'] / 100);
                    }
                    $grand_total = $total_amount - $couponamount;
                    // Add Coupon Code And Amount In Session
                    Session::put('coupon_code', $data['code']);
                    Session::put('coupon_amount', $couponamount);
                    $message = 'تم تطبيق الكوبون بنجاح ';
                    return response()->json([
                        'status' => true,
                        'coupon_amount' => $couponamount,
                        'grand_total' => $grand_total,
                        'message' => $message,
                        'View' => (string)View::make('front.partials.cart_items')->with(compact('cartItems', 'item_counts', 'couponamount', 'grand_total')),
                    ]);
                }

            } else {
                return response()->json([
                    'status' => 'false',
                    'message' => 'كود الخصم غير متاح ',
                    'View' => (string)View::make('front.partials.cart_items')->with(compact('cartItems', 'item_counts')),
                ]);
            }
        }
    }


}
