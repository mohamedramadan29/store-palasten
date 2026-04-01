<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\front\Order;
use App\Models\front\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MarketerDashboardController extends Controller
{
    private function marketer()
    {
        return Auth::guard('marketer')->user();
    }

    public function dashboard()
    {
        $marketer = $this->marketer();
        $orders = Order::where('marketer_id', $marketer->id)->get();

        $totalOrders    = $orders->count();
        $completedOrders = $orders->where('order_status', 'مكتمل')->count();
        $pendingOrders  = $orders->whereNotIn('order_status', ['مكتمل', 'ملغي'])->count();
        $cancelledOrders = $orders->where('order_status', 'ملغي')->count();

        $totalProfit    = $orders->where('order_status', 'مكتمل')->sum('total_profit');
        $pendingProfit  = $orders->whereNotIn('order_status', ['مكتمل', 'ملغي'])->sum('total_profit');

        return view('front.marketer.dashboard', compact(
            'marketer', 'totalOrders', 'completedOrders', 'pendingOrders',
            'cancelledOrders', 'totalProfit', 'pendingProfit'
        ));
    }

    public function orders()
    {
        $marketer = $this->marketer();
        $orders = Order::where('marketer_id', $marketer->id)
            ->orderBy('id', 'desc')->get();
        return view('front.marketer.orders', compact('orders'));
    }

    public function cancelOrder($id)
    {
        $marketer = $this->marketer();
        $order = Order::where('id', $id)->where('marketer_id', $marketer->id)->firstOrFail();

        if (in_array($order->order_status, ['مكتمل', 'ملغي'])) {
            return redirect()->route('marketer.orders')
                ->with('error', 'لا يمكن إلغاء هذا الطلب');
        }

        $order->update(['order_status' => 'ملغي']);
        return redirect()->route('marketer.orders')
            ->with('success', 'تم إلغاء الطلب بنجاح');
    }

    public function profile()
    {
        $marketer = $this->marketer();
        return view('front.marketer.profile', compact('marketer'));
    }

    public function updateProfile(Request $request)
    {
        $marketer = $this->marketer();
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ], [
            'name.required'  => 'من فضلك ادخل الاسم',
            'phone.required' => 'من فضلك ادخل رقم الهاتف',
        ]);

        $data = ['name' => $request->name, 'phone' => $request->phone];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed'], [
                'password.min'       => 'كلمة المرور يجب 6 أحرف على الأقل',
                'password.confirmed' => 'كلمة المرور وتأكيدها غير متطابقتين',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $marketer->update($data);
        return redirect()->route('marketer.profile')
            ->with('success', 'تم تحديث البيانات بنجاح');
    }

    public function reports()
    {
        $marketer = $this->marketer();
        $orders = Order::where('marketer_id', $marketer->id)
            ->orderBy('id', 'desc')->get();

        $totalProfit     = $orders->where('order_status', 'مكتمل')->sum('total_profit');
        $pendingProfit   = $orders->whereNotIn('order_status', ['مكتمل', 'ملغي'])->sum('total_profit');
        $cancelledOrders = $orders->where('order_status', 'ملغي');
        $completedOrders = $orders->where('order_status', 'مكتمل');
        $totalOrders     = $orders->count();

        return view('front.marketer.reports', compact(
            'orders', 'totalProfit', 'pendingProfit', 'cancelledOrders', 'completedOrders', 'marketer', 'totalOrders'
        ));
    }
}
