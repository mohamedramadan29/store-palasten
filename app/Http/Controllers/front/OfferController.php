<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Offer;
use App\Models\admin\ShippingCity;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use Message_Trait;
    public function offer($slug)
    {
        $shippingCity = ShippingCity::all();
        $offer = Offer::where('slug',$slug)->first();
        return view('front.offer',compact('shippingCity','offer'));
    }
}
