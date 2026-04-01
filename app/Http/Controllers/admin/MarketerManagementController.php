<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\User;
use Illuminate\Http\Request;

class MarketerManagementController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $marketers = User::where('user_type', 'marketer')->orderBy('id', 'desc')->get();
        return view('admin.marketers.index', compact('marketers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return $this->success_message('تم تحديث حالة المسوق بنجاح');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->success_message('تم حذف المسوق بنجاح');
    }

    public function show($id)
    {
        $marketer = User::findOrFail($id);
        
        // Get all orders for this marketer
        $orders = \App\Models\front\Order::where('marketer_id', $marketer->id)
            ->orderBy('id', 'desc')
            ->get();
        
        // Calculate statistics
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('order_status', 'مكتمل');
        $pendingOrders = $orders->whereNotIn('order_status', ['مكتمل', 'ملغي']);
        $cancelledOrders = $orders->where('order_status', 'ملغي');
        
        // Calculate profits
        $totalProfit = $completedOrders->sum('total_profit');
        $pendingProfit = $pendingOrders->sum('total_profit');
        $cancelledProfit = $cancelledOrders->sum('total_profit');
        
        // Calculate totals
        $totalSales = $completedOrders->sum('grand_total');
        $pendingSales = $pendingOrders->sum('grand_total');
        
        return view('admin.marketers.show', compact(
            'marketer', 'orders', 'totalOrders', 'completedOrders', 
            'pendingOrders', 'cancelledOrders', 'totalProfit', 
            'pendingProfit', 'cancelledProfit', 'totalSales', 'pendingSales'
        ));
    }

    public function marketerOrders()
    {
        $orders = \App\Models\front\Order::with('marketer')
            ->where('is_marketer_order', 1)
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.marketers.orders', compact('orders'));
    }
}
