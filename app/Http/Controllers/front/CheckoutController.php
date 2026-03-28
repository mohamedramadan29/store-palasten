<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\admin\ShippingCity;
use App\Models\front\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $shippingCity = ShippingCity::where('status',1)->get();
        $cartitems = Cart::getcartitems();
        
        $selectedCityId = Session::get('shipping_city_id');
        $selectedCity = $selectedCityId ? ShippingCity::find($selectedCityId) : null;
        $publicSetting = \App\Models\admin\PublicSetting::first();
        
        $subtotal = 0;
        foreach($cartitems as $item) {
            $subtotal += ($item->price * $item->qty);
        }
        
        $freeShipping = false;
        $cityThreshold = $selectedCity ? $selectedCity->free_shipping_threshold : 0;
        $globalThreshold = $publicSetting ? $publicSetting->global_free_shipping_threshold : 0;
        
        // Check city-specific threshold
        if ($cityThreshold > 0 && $subtotal >= $cityThreshold) {
            $freeShipping = true;
        }
        
        // Check global threshold
        if ($globalThreshold > 0 && $subtotal >= $globalThreshold) {
            $freeShipping = true;
        }

        if ($cartitems ->count() > 0){
            return view('front.checkout',compact('shippingCity','cartitems', 'selectedCity', 'freeShipping', 'publicSetting'));
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
