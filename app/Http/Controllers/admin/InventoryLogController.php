<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\InventoryLog;
use App\Models\admin\Product;
use App\Services\InventoryLogService;
use Illuminate\Http\Request;

class InventoryLogController extends Controller
{
    private function getCurrency()
    {
        $setting = \App\Models\admin\PublicSetting::first();
        return $setting->website_currency ?? 'ريال';
    }

    public function index(Request $request)
    {
        $filters = [
            'product_id' => $request->product_id,
            'movement_type' => $request->movement_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id
        ];

        // Set default date range (last 30 days)
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            $filters['start_date'] = now()->subDays(30)->startOfDay();
            $filters['end_date'] = now()->endOfDay();
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $filters['start_date'] = $request->start_date . ' 00:00:00';
            $filters['end_date'] = $request->end_date . ' 23:59:59';
        }

        $logs = InventoryLogService::getLogs($filters)->paginate(50);
        $products = Product::orderBy('name')->get();
        $currency = $this->getCurrency();
        $statistics = InventoryLogService::getStatistics($filters);

        // Movement types for filter
        $movementTypes = [
            InventoryLog::MOVEMENT_SALE => 'بيع',
            InventoryLog::MOVEMENT_CANCEL => 'إلغاء طلب',
            InventoryLog::MOVEMENT_MANUAL_ADD => 'إضافة يدوية',
            InventoryLog::MOVEMENT_MANUAL_SUBTRACT => 'خصم يدوي',
            InventoryLog::MOVEMENT_ADJUSTMENT => 'تعديل مخزون'
        ];

        return view('admin.inventory.logs', compact(
            'logs',
            'products',
            'currency',
            'statistics',
            'movementTypes',
            'filters'
        ));
    }

    public function show($id)
    {
        $log = InventoryLog::with(['product', 'user'])->findOrFail($id);
        $currency = $this->getCurrency();

        return view('admin.inventory.log-details', compact('log', 'currency'));
    }

    public function export(Request $request)
    {
        $filters = [
            'product_id' => $request->product_id,
            'movement_type' => $request->movement_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $filters['start_date'] = $request->start_date . ' 00:00:00';
            $filters['end_date'] = $request->end_date . ' 23:59:59';
        } else {
            $filters['start_date'] = now()->subDays(30)->startOfDay();
            $filters['end_date'] = now()->endOfDay();
        }

        $logs = InventoryLogService::getLogs($filters)->get();
        $currency = $this->getCurrency();

        $filename = "inventory_logs_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs, $currency) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'التاريخ والوقت',
                'المرجع',
                'المنتج',
                'نوع الحركة',
                'الكمية قبل',
                'تغير الكمية',
                'الكمية بعد',
                'سعر التكلفة',
                'إجمالي التكلفة',
                'المستخدم',
                'السبب',
                'عنوان IP'
            ]);

            // CSV Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $this->getReferenceDisplay($log),
                    $log->product_name,
                    $log->movement_type_label,
                    $log->quantity_before,
                    $log->formatted_quantity_change,
                    $log->quantity_after,
                    number_format($log->unit_cost, 2) . ' ' . $currency,
                    number_format($log->total_cost, 2) . ' ' . $currency,
                    $log->user_name,
                    $log->reason ?? '-',
                    $log->ip_address ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getReferenceDisplay($log)
    {
        switch ($log->reference_type) {
            case InventoryLog::REFERENCE_ORDER:
                return 'طلب #' . $log->reference_id;
            case InventoryLog::REFERENCE_MANUAL:
                return 'تعديل يدوي';
            case InventoryLog::REFERENCE_ADJUSTMENT:
                return 'تعديل نظامي';
            default:
                return $log->reference_type;
        }
    }

    public function productHistory($productId)
    {
        $product = Product::findOrFail($productId);
        $logs = InventoryLogService::getProductHistory($productId, 100);
        $currency = $this->getCurrency();

        return view('admin.inventory.product-history', compact('product', 'logs', 'currency'));
    }

    public function statistics(Request $request)
    {
        $filters = [
            'start_date' => $request->start_date ?? now()->subDays(30)->startOfDay(),
            'end_date' => $request->end_date ?? now()->endOfDay()
        ];

        $statistics = InventoryLogService::getStatistics($filters);
        $currency = $this->getCurrency();

        // Get daily movements for chart
        $dailyMovements = InventoryLog::selectRaw('DATE(created_at) as date, COUNT(*) as movements, SUM(CASE WHEN quantity_change > 0 THEN quantity_change ELSE 0 END) as additions, SUM(CASE WHEN quantity_change < 0 THEN ABS(quantity_change) ELSE 0 END) as subtractions')
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date']])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.inventory.statistics', compact(
            'statistics',
            'currency',
            'dailyMovements',
            'filters'
        ));
    }
}
