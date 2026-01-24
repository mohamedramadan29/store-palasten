<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\admin\ShippingCity;
use App\Models\front\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $shippingCity = ShippingCity::where('status',1)->get();
        $cartitems = Cart::getcartitems();
        if ($cartitems ->count() > 0){
            return view('front.checkout',compact('shippingCity','cartitems'));
        }else{
            return view('front.shop');
        }

    }
    public function getShippingPrice(Request $request)
    {
        $cityId = $request->input('city_id');
        $shippingCity = ShippingCity::where('id', $cityId)->first(); // تأكد من وجود المدينة

        if ($shippingCity) {
            return response()->json(['price' => $shippingCity->price]); // إرسال سعر الشحن
        }

        return response()->json(['price' => 0]); // إذا لم يكن هناك مدينة، السعر هو 0
    }
}
