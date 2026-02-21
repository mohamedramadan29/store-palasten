<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\front\Order;
use App\Models\front\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryMovementController extends Controller
{
    private function getCurrency()
    {
        $setting = \App\Models\admin\PublicSetting::first();
        return $setting->website_currency ?? 'ريال';
    }
    
    public function index(Request $request)
    {
        $query = OrderDetails::with(['order', 'product'])
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id');

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        } else {
            // Default to last 30 days
            $query->where('orders.created_at', '>=', now()->subDays(30));
        }

        // Order status filter
        if ($request->filled('order_status') && $request->order_status != 'all') {
            $query->where('orders.order_status', $request->order_status);
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->where('order_details.product_id', $request->product_id);
        }

        $orderDetails = $query->orderBy('orders.created_at', 'desc')->get();

        // Get products for filter dropdown
        $products = Product::orderBy('name')->get();

        // Get currency
        $currency = $this->getCurrency();

        // Calculate inventory movements by status
        $movementsByStatus = $orderDetails->groupBy('order.order_status')->map(function ($items, $status) {
            return [
                'count' => $items->count(),
                'total_quantity' => $items->sum('product_qty'),
                'total_value' => $items->sum(function ($item) {
                    return $item->product_price * $item->product_qty;
                }),
                'total_cost' => $items->sum(function ($item) {
                    return ($item->product->purches_price ?? 0) * $item->product_qty;
                }),
                'total_profit' => $items->sum(function ($item) {
                    $revenue = $item->product_price * $item->product_qty;
                    $cost = ($item->product->purches_price ?? 0) * $item->product_qty;
                    return $revenue - $cost;
                })
            ];
        });

        // Calculate inventory summary
        $inventorySummary = [
            'total_movements' => $orderDetails->count(),
            'total_quantity_moved' => $orderDetails->sum('product_qty'),
            'total_sales_value' => $orderDetails->sum(function ($item) {
                return $item->product_price * $item->product_qty;
            }),
            'total_cost_value' => $orderDetails->sum(function ($item) {
                return ($item->product->purches_price ?? 0) * $item->product_qty;
            }),
            'total_profit' => $orderDetails->sum(function ($item) {
                $revenue = $item->product_price * $item->product_qty;
                $cost = ($item->product->purches_price ?? 0) * $item->product_qty;
                return $revenue - $cost;
            })
        ];

        // Group by product for detailed inventory analysis
        $productMovements = $orderDetails->groupBy('product_id')->map(function ($items, $productId) {
            $product = $items->first()->product;
            $totalSold = $items->where('order.order_status', 'مكتمل')->sum('product_qty');
            $totalCancelled = $items->where('order.order_status', 'ملغي')->sum('product_qty');
            $totalProcessing = $items->where('order.order_status', 'بداية التنفيذ')->sum('product_qty');
            $totalPending = $items->where('order.order_status', 'لم يبدا')->sum('product_qty');
            
            return [
                'product' => $product,
                'current_stock' => $product->quantity,
                'total_sold' => $totalSold,
                'total_cancelled' => $totalCancelled,
                'total_processing' => $totalProcessing,
                'total_pending' => $totalPending,
                'total_moved' => $items->sum('product_qty'),
                'stock_before' => $product->quantity + $totalSold - $totalCancelled, // Approximate
                'stock_after' => $product->quantity,
                'total_value' => $items->sum(function ($item) {
                    return $item->product_price * $item->product_qty;
                }),
                'total_cost' => $items->sum(function ($item) {
                    return ($item->product->purches_price ?? 0) * $item->product_qty;
                })
            ];
        })->sortBy('product.name');

        // Prepare chart data
        $chartData = $this->prepareChartData($orderDetails);

        return view('admin.Reports.inventory-movement', compact(
            'orderDetails',
            'products',
            'currency',
            'movementsByStatus',
            'inventorySummary',
            'productMovements',
            'chartData'
        ));
    }

    private function prepareChartData($orderDetails)
    {
        // Group by date for chart
        $dailyMovements = $orderDetails->groupBy(function ($item) {
            return $item->order->created_at->format('Y-m-d');
        });

        $chartData = $dailyMovements->map(function ($items, $date) {
            $sold = $items->where('order.order_status', 'مكتمل')->sum('product_qty');
            $cancelled = $items->where('order.order_status', 'ملغي')->sum('product_qty');
            $processing = $items->where('order.order_status', 'بداية التنفيذ')->sum('product_qty');
            $pending = $items->where('order.order_status', 'لم يبدا')->sum('product_qty');

            return [
                'date' => $date,
                'sold' => $sold,
                'cancelled' => $cancelled,
                'processing' => $processing,
                'pending' => $pending,
                'total' => $items->sum('product_qty')
            ];
        })->sortBy('date')->values();

        return $chartData;
    }

    public function export(Request $request)
    {
        // Same logic as index but for export
        $query = OrderDetails::with(['order', 'product'])
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        if ($request->filled('order_status') && $request->order_status != 'all') {
            $query->where('orders.order_status', $request->order_status);
        }

        if ($request->filled('product_id')) {
            $query->where('order_details.product_id', $request->product_id);
        }

        $orderDetails = $query->orderBy('orders.created_at', 'desc')->get();
        
        // Get currency
        $currency = $this->getCurrency();

        // Generate CSV export
        $filename = "inventory_movement_report_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orderDetails, $currency) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'تاريخ الحركة',
                'رقم الطلب',
                'المنتج',
                'الكمية',
                'سعر البيع',
                'الإجمالي',
                'سعر التكلفة',
                'التكلفة الإجمالية',
                'الربح',
                'حالة الطلب',
                'المخزون الحالي'
            ]);

            // CSV Data
            foreach ($orderDetails as $item) {
                $total = $item->product_price * $item->product_qty;
                $cost = ($item->product->purches_price ?? 0) * $item->product_qty;
                $profit = $total - $cost;

                fputcsv($file, [
                    $item->order->created_at->format('Y-m-d H:i'),
                    $item->order_id,
                    $item->product_name,
                    $item->product_qty,
                    number_format($item->product_price, 2) . ' ' . $currency,
                    number_format($total, 2) . ' ' . $currency,
                    number_format($item->product->purches_price ?? 0, 2) . ' ' . $currency,
                    number_format($cost, 2) . ' ' . $currency,
                    number_format($profit, 2) . ' ' . $currency,
                    $item->order->order_status,
                    $item->product->quantity
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
