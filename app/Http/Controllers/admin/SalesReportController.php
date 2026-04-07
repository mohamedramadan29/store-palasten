<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\front\Order;
use App\Models\front\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    private function getCurrency()
    {
        $setting = \App\Models\admin\PublicSetting::first();
        return $setting->website_currency ?? 'ريال';
    }
    
    public function index(Request $request)
    {
        $query = OrderDetails::with(['order', 'product'])
            ->join('orders', 'order_details.order_id', '=', 'orders.id');

        // Debug: Show all orders without status filter first
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        } else {
            // Default to last 30 days
            $query->where('orders.created_at', '>=', now()->subDays(30));
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->where('order_details.product_id', $request->product_id);
        }

        // Order status filter
        if ($request->filled('order_status') && $request->order_status != 'all') {
            $query->where('orders.order_status', $request->order_status);
        }

        $orderDetails = $query->orderBy('orders.created_at', 'desc')->get();

        // Calculate statistics
        $totalSales = $orderDetails->sum(function ($item) {
            return $item->product_price * $item->product_qty;
        });

        $totalOrders = $orderDetails->pluck('order_id')->unique()->count();
        $totalQuantity = $orderDetails->sum('product_qty');

        // Calculate profit (using purches_price as cost price)
        $totalCost = $orderDetails->sum(function ($item) {
            return ($item->product->purches_price ?? 0) * $item->product_qty;
        });
        $netProfit = $totalSales - $totalCost;

        // Get products for filter dropdown
        $products = Product::orderBy('name')->get();

        // Get currency
        $currency = $this->getCurrency();

        // Prepare chart data
        $chartData = $this->prepareChartData($orderDetails);

        return view('admin.Reports.sales', compact(
            'orderDetails',
            'totalSales',
            'totalOrders',
            'totalQuantity',
            'netProfit',
            'totalCost',
            'products',
            'chartData',
            'currency'
        ));
    }

    private function prepareChartData($orderDetails)
    {
        // Group by date for chart
        $dailySales = $orderDetails->groupBy(function ($item) {
            return $item->order->created_at->format('Y-m-d');
        });

        $chartData = $dailySales->map(function ($items, $date) {
            $sales = $items->sum(function ($item) {
                return $item->product_price * $item->product_qty;
            });
            
            $profit = $items->sum(function ($item) {
                $itemCost = ($item->product->purches_price ?? 0) * $item->product_qty;
                $itemRevenue = $item->product_price * $item->product_qty;
                return $itemRevenue - $itemCost;
            });

            return [
                'date' => $date,
                'sales' => $sales,
                'profit' => $profit,
                'orders' => $items->pluck('order_id')->unique()->count()
            ];
        })->sortBy('date')->values();

        return $chartData;
    }

    public function export(Request $request)
    {
        // Same logic as index but for export
        $query = OrderDetails::with(['order', 'product'])
            ->join('orders', 'order_details.order_id', '=', 'orders.id');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        if ($request->filled('product_id')) {
            $query->where('order_details.product_id', $request->product_id);
        }

        if ($request->filled('order_status') && $request->order_status != 'all') {
            $query->where('orders.order_status', $request->order_status);
        }

        $orderDetails = $query->orderBy('orders.created_at', 'desc')->get();
        
        // Get currency
        $currency = $this->getCurrency();

        // Generate CSV or Excel export
        $filename = "sales_report_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orderDetails, $currency) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Order ID',
                'Date',
                'Product',
                'Quantity',
                'Unit Price',
                'Total',
                'Cost Price',
                'Profit',
                'Status'
            ]);

            // CSV Data
            foreach ($orderDetails as $item) {
                $total = $item->product_price * $item->product_qty;
                $cost = ($item->product->purches_price ?? 0) * $item->product_qty;
                $profit = $total - $cost;

                fputcsv($file, [
                    $item->order_id,
                    $item->order->created_at->format('Y-m-d H:i'),
                    $item->product_name,
                    $item->product_qty,
                    number_format($item->product_price, 2) . ' ' . $currency,
                    number_format($total, 2) . ' ' . $currency,
                    number_format($cost, 2) . ' ' . $currency,
                    number_format($profit, 2) . ' ' . $currency,
                    $item->order->order_status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
