<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\admins;
use App\Models\admin\Offer;
use App\Models\admin\PublicSetting;
use App\Models\front\OfferOrder;
use App\Notifications\LandingOrder;
use App\Notifications\NewOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class OfferOrderController extends Controller
{
    use Message_Trait;

    public function store_offer(Request $request, $id)
    {
        try {
            $data = $request->all();
           // dd($data);
            $offer = Offer::findOrFail($id);
            $product_name = $offer['product_name'];
            $rules = [];
            $messages = [];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $order = new OfferOrder();
            $order->offer_id = $id;
            $order->product_name =$product_name;
            $order->name = $data['name'];
            $order->phone = $data['phone'];
            $order->city = $data['shippingcity'];
            $order->address = $data['address'];
            $order->ship_price = $data['shipping_price'];
            $order->total_price = $data['grand_total'];
            $order->save();
            $admin = admins::all();
            Notification::send($admin,new LandingOrder($order->id));

            ////////////////////// Send Confirmation Email ///////////////////////////////
            ///
            $public_setting = PublicSetting::first();
            $admin_email = $public_setting['website_email'];
            $email = $admin_email;
            $MessageDate = [
                'name' => $data['name'],
                "address" => $data['address'],
                'phone' => $data['phone'],
                'grand_total'=>$data['grand_total'],
            ];
            // Mail::send('front.mails.newordertoadmin', $MessageDate, function ($message) use ($email) {
            //     $message->to($email)->subject(' لديك طلب جديد علي متجرك ');
            // });
            return $this->success_message(' تمت اضافة الطلب الخاص بك بنجاح  ');

        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
