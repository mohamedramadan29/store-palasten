<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\PublicSetting;
use App\Models\admin\Product;
use App\Models\admin\ProductVartions;
use App\Models\front\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use Message_Trait;
    public function index()
    {
       $orders = Order::orderBy('id','desc')->get();
        return view('admin.orders.index',compact('orders'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('details','city')->where('id',$id)->first();
        if (!isset($order)){
            return redirect()->route('orders');
        }
        $user  = Auth::guard('admin')->user();
        $notification = $user->unreadNotifications->where('data.order_id', $id)->first();

        // التأكد من وجود الإشعار وجعله مقروءًا
        if ($notification) {
            $notification->markAsRead();
        }
        if ($request->isMethod('post')){
            try {
                DB::beginTransaction();
                $data = $request->all();
                $newStatus = $data['order_status'];

                $order->update([
                    'order_status'=>$newStatus
                ]);

                // Stock Management Logic
                $deductStatuses = ['بداية التنفيذ', 'مكتمل'];
                $restoreStatus = 'ملغي';

                if (in_array($newStatus, $deductStatuses) && !$order->stock_deducted) {
                    // Deduct Stock
                    foreach ($order->details as $detail) {
                        if ($detail->product_variation_id) {
                            $variation = ProductVartions::find($detail->product_variation_id);
                            if ($variation) {
                                $variation->deductStock($detail->product_qty);
                            }
                        } else {
                            $product = Product::find($detail->product_id);
                            if ($product) {
                                $product->deductStock($detail->product_qty);
                            }
                        }
                    }
                    $order->update(['stock_deducted' => true]);
                } elseif ($newStatus == $restoreStatus && $order->stock_deducted) {
                    // Restore Stock
                    foreach ($order->details as $detail) {
                        if ($detail->product_variation_id) {
                            $variation = ProductVartions::find($detail->product_variation_id);
                            if ($variation) {
                                $variation->restoreStock($detail->product_qty);
                            }
                        } else {
                            $product = Product::find($detail->product_id);
                            if ($product) {
                                $product->restoreStock($detail->product_qty);
                            }
                        }
                    }
                    $order->update(['stock_deducted' => false]);
                }

                DB::commit();
                return $this->success_message(' تم تعديل حالة الطلب بنجاح  ');
            }catch (\Exception $e){
                DB::rollBack();
                return $this->exception_message($e);
            }
        }

        return view('admin.orders.update',compact('order'));
    }

    public function print($id)
    {
        $order = Order::with('details','city')->where('id',$id)->first();
        $publicsetting = PublicSetting::first();
        return view('admin.orders.print',compact('order','publicsetting'));
    }

    public function delete($id)
    {
        $order = Order::findORFail($id);
        $order->delete();
        return $this->success_message(' تم حذف الطلب ');
    }

}
