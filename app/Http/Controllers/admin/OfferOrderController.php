<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\PublicSetting;
use App\Models\front\OfferOrder;
use App\Models\front\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferOrderController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $orders = OfferOrder::with('shipping')->get();
        return view('admin.OfferOrders.index',compact('orders'));
    }

    public function update(Request $request, $id)
    {
        $order = OfferOrder::with('shipping')->where('id',$id)->first();
        if ($request->isMethod('post')){
            try {
                $data = $request->all();
              //  dd($data);
//                $order->update([
//                    'order_status'=>$data['order_status']
//                ]);
                $order->order_status = $data['order_status'];
                $order->save();
                return $this->success_message(' تم تعديل حالة الطلب بنجاح  ');
            }catch (\Exception $e){
                return $this->exception_message($e);
            }
        }

        $user  = Auth::guard('admin')->user();
        $notification = $user->unreadNotifications->where('data.order_id', $id)->first();

        // التأكد من وجود الإشعار وجعله مقروءًا
        if ($notification) {
            $notification->markAsRead();
        }
        return view('admin.OfferOrders.update',compact('order'));
    }

    public function print($id)
    {
        $order = OfferOrder::with('shipping')->where('id',$id)->first();
        $publicsetting = PublicSetting::first();
        return view('admin.OfferOrders.print',compact('order','publicsetting'));
    }

    public function delete($id)
    {
        $order = OfferOrder::findORFail($id);
        $order->delete();
        return $this->success_message(' تم حذف الطلب ');
    }

}
