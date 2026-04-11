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
    public function report(Request $request)
    {
        $query = \App\Models\front\Order::query()->where('is_marketer_order', 1);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('order_status') && $request->order_status != '') {
            $query->where('order_status', $request->order_status);
        }

        $orders = $query->with('marketer')->get();

        // Group by marketer
        $report = $orders->groupBy('marketer_id')->map(function ($group) {
            $marketer = $group->first()->marketer;
            $totalOrders = $group->count();
            $completedOrders = $group->where('order_status', 'مكتمل')->count();
            $pendingOrders = $group->whereNotIn('order_status', ['مكتمل', 'ملغي'])->count();
            $cancelledOrders = $group->where('order_status', 'ملغي')->count();

            $totalProfit = $group->where('order_status', 'مكتمل')->sum('total_profit');
            $pendingProfit = $group->whereNotIn('order_status', ['مكتمل', 'ملغي'])->sum('total_profit');
            $cancelledProfit = $group->where('order_status', 'ملغي')->sum('total_profit');

            $totalSales = $group->where('order_status', 'مكتمل')->sum('grand_total');
            $pendingSales = $group->whereNotIn('order_status', ['مكتمل', 'ملغي'])->sum('grand_total');

            return [
                'marketer' => $marketer,
                'totalOrders' => $totalOrders,
                'completedOrders' => $completedOrders,
                'pendingOrders' => $pendingOrders,
                'cancelledOrders' => $cancelledOrders,
                'totalProfit' => $totalProfit,
                'pendingProfit' => $pendingProfit,
                'cancelledProfit' => $cancelledProfit,
                'totalSales' => $totalSales,
                'pendingSales' => $pendingSales,
            ];
        })->values();

        // Overall totals
        $overall = [
            'totalOrders' => $orders->count(),
            'totalProfit' => $orders->where('order_status', 'مكتمل')->sum('total_profit'),
            'totalSales' => $orders->where('order_status', 'مكتمل')->sum('grand_total'),
        ];

        return view('admin.marketers.report', compact('report', 'overall'));
    }

    public function profitSummary(Request $request)
    {
        $query = \App\Models\front\Order::query()->where('is_marketer_order', 1);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $orders = $query->with('marketer')->get();

        // Calculate summary statistics
        $allMarketers = \App\Models\User::where('user_type', 'marketer')->get();
        $totalMarketers = $allMarketers->count();
        
        $marketersWithOrders = $orders->pluck('marketer_id')->unique();
        $activeMarketers = $marketersWithOrders->count();
        $inactiveMarketers = $totalMarketers - $activeMarketers;

        $totalProfit = $orders->where('order_status', ' <span> </span> ')->sum('total_profit');
        $totalSales = $orders->where('order_status', ' <span> </span> ')->sum('grand_total');
        $averageProfit = $activeMarketers > 0 ? $totalProfit / $activeMarketers : 0;

        // Get top marketers
        $topMarketers = $orders->groupBy('marketer_id')->map(function ($group) {
            $marketer = $group->first()->marketer;
            $totalOrders = $group->count();
            $completedOrders = $group->where('order_status', ' <span> </span> ')->count();
            $pendingOrders = $group->whereNotIn('order_status', [' <span> </span> ', ' <span> </span> '])->count();

            $totalProfit = $group->where('order_status', ' <span> </span> ')->sum('total_profit');
            $totalSales = $group->where('order_status', ' <span> </span> ')->sum('grand_total');

            return [
                'marketer' => $marketer,
                'totalOrders' => $totalOrders,
                'completedOrders' => $completedOrders,
                'pendingOrders' => $pendingOrders,
                'totalProfit' => $totalProfit,
                'totalSales' => $totalSales,
            ];
        })->sortByDesc('totalProfit')->take(10)->values();

        $summary = [
            'totalMarketers' => $totalMarketers,
            'activeMarketers' => [
                'count' => $activeMarketers,
                'percentage' => $totalMarketers > 0 ? ($activeMarketers / $totalMarketers) * 100 : 0
            ],
            'inactiveMarketers' => [
                'count' => $inactiveMarketers,
                'percentage' => $totalMarketers > 0 ? ($inactiveMarketers / $totalMarketers) * 100 : 0
            ],
            'topMarketers' => [
                'count' => $topMarketers->count(),
                'percentage' => $totalMarketers > 0 ? ($topMarketers->count() / $totalMarketers) * 100 : 0
            ],
            'totalProfit' => $totalProfit,
            'totalSales' => $totalSales,
            'averageProfit' => $averageProfit
        ];

        return view('admin.marketers.profit-summary', compact('summary', 'topMarketers'));
    }
}

