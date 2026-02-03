<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\ProductVartions;
use App\Models\admin\admins;
use App\Models\admin\Product;
use App\Models\admin\PublicSetting;
use App\Models\front\Cart;
use App\Models\front\Order;
use App\Models\front\OrderDetails;
use App\Notifications\NewOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use Message_Trait;

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $coupon_amount =  Session::has('coupon_amount') ? Session::get('coupon_amount') : 0;
        $coupon = Session::has('coupon_code') ? Session::get('coupon_code') : null;
        $cartItems = Cart::getcartitems();
        $rules = [
            'name' => 'required',
            'phone' => 'required',

            'shippingcity' => 'required',
            'address' => 'required',
        ];
        $messages = [
            'name.required' => ' من فضلك ادخل الاسم  ',
            'phone.required' => ' من فضلك ادخل رقم الهاتف  ',

            'shippingcity.required' => ' من فضلك حدد المدينة للشحن  ',
            'address.required' => ' من فضلك حدد العنوان  '
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        // Check stock availability for all items before starting
        foreach ($cartItems as $item) {
            if ($item->product_variation_id) {
                $variation = ProductVartions::find($item->product_variation_id);
                if (!$variation || $variation->stock < $item->qty) {
                    return Redirect::back()->withInput()->withErrors(['stock_error' => 'الكمية المطلوبة من المنتج ' . ($variation->product->name ?? '') . ' غير متوفرة حالياً']);
                }
            } else {
                $product = Product::find($item->product_id);
                if (!$product || $product->quantity < $item->qty) {
                    return Redirect::back()->withInput()->withErrors(['stock_error' => 'الكمية المطلوبة من المنتج ' . ($product->name ?? '') . ' غير متوفرة حالياً']);
                }
            }
        }

        DB::beginTransaction();
        $order = new Order();
        $order->name = $data['name'] . '' . $data['name2'];
        $order->address = $data['address'];
        $order->shippingcity = $data['shippingcity'];
        $order->phone = $data['phone'];
        $order->phone2 = $data['phone2'] ?? null;
        $order->note = $data['note'] ?? null;
        $order->shipping_price = $data['shipping_price'];
        $order->coupon_code = $coupon;
        $order->coupon_amount = $coupon_amount;
        $order->order_status = 'لم يبدا';
        $order->payment_method = 'الدفع عند الاستلام';
        $order->grand_total = $data['grand_total'];
        $order->save();
        foreach ($cartItems as $item) {
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $item->product_id;
            $getproductdata = Product::where('id', $item->product_id)->first();
            $order_details->product_name = $getproductdata->name;
            $order_details->product_price = $item->price;
            $order_details->product_qty = $item->qty;
            $order_details->product_variation_id = $item->product_variation_id;
            $order_details->save();
        }

        ////////////////////// Send Confirmation Email ///////////////////////////////
        ///
        $public_setting = PublicSetting::first();
        $admin_email = $public_setting['admin_order_email'] ?? $public_setting['website_email'];
        $email = $admin_email;

        $MessageDate = [
            'name' => $data['name'],
            "address" => $data['address'],
            'phone' => $data['phone'],
            'grand_total' => $data['grand_total'],
        ];
        // Mail::send('front.mails.newordertoadmin', $MessageDate, function ($message) use ($email) {
        //     $message->to($email)->subject(' لديك طلب جديد علي متجرك ');
        // });

        DB::commit();
        $admin = admins::all();
        Notification::send($admin, new NewOrder($order->id));
        Session::put('order_id', $order->id);
        return redirect('thanks');
        // return $this->success_message(' تم اضافة الطلب الخاص بك بنجاح  ');
    }

    public function thanks()
    {
        $session_id = Session::get('session_id');
        if (Session::has('order_id')) {
            // Empty The Cart
            Cart::where('session_id', $session_id)->delete();
            return view('front.thanks');
        } else {
            return redirect('/');
        }
    }
}
