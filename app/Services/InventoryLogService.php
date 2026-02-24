<?php

namespace App\Services;

use App\Models\admin\InventoryLog;
use App\Models\admin\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class InventoryLogService
{
    /**
     * Log inventory movement
     */
    public static function logMovement(array $data)
    {
        $product = Product::find($data['product_id']);
        if (!$product) {
            throw new \Exception('المنتج غير موجود');
        }

        $logData = [
            'reference_type' => $data['reference_type'] ?? 'manual',
            'reference_id' => $data['reference_id'] ?? null,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity_before' => $data['quantity_before'] ?? $product->quantity,
            'quantity_change' => $data['quantity_change'],
            'quantity_after' => $data['quantity_after'] ?? ($product->quantity + $data['quantity_change']),
            'unit_cost' => $product->purches_price ?? 0,
            'total_cost' => abs($data['quantity_change']) * ($product->purches_price ?? 0),
            'movement_type' => $data['movement_type'],
            'reason' => $data['reason'] ?? null,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'System',
            'ip_address' => Request::ip(),
            'metadata' => $data['metadata'] ?? null
        ];

        return InventoryLog::create($logData);
    }

    /**
     * Log sale movement (when order is completed)
     */
    public static function logSale($productId, $quantity, $orderId, $orderDetails = [])
    {
        $product = Product::find($productId);
        if (!$product) {
            return null;
        }

        return self::logMovement([
            'reference_type' => InventoryLog::REFERENCE_ORDER,
            'reference_id' => $orderId,
            'product_id' => $productId,
            'quantity_change' => -$quantity,
            'movement_type' => InventoryLog::MOVEMENT_SALE,
            'metadata' => [
                'order_id' => $orderId,
                'order_details' => $orderDetails,
                'sale_price' => $product->price ?? 0
            ]
        ]);
    }

    /**
     * Log order cancellation (restore stock)
     */
    public static function logCancellation($productId, $quantity, $orderId, $orderDetails = [])
    {
        $product = Product::find($productId);
        if (!$product) {
            return null;
        }

        return self::logMovement([
            'reference_type' => InventoryLog::REFERENCE_ORDER,
            'reference_id' => $orderId,
            'product_id' => $productId,
            'quantity_change' => $quantity,
            'movement_type' => InventoryLog::MOVEMENT_CANCEL,
            'reason' => 'إلغاء الطلب #' . $orderId,
            'metadata' => [
                'order_id' => $orderId,
                'order_details' => $orderDetails,
                'original_sale_price' => $product->price ?? 0
            ]
        ]);
    }

    /**
     * Log manual stock addition
     */
    public static function logManualAddition($productId, $quantity, $reason = null)
    {
        return self::logMovement([
            'product_id' => $productId,
            'quantity_change' => $quantity,
            'movement_type' => InventoryLog::MOVEMENT_MANUAL_ADD,
            'reason' => $reason
        ]);
    }

    /**
     * Log manual stock subtraction
     */
    public static function logManualSubtraction($productId, $quantity, $reason = null)
    {
        return self::logMovement([
            'product_id' => $productId,
            'quantity_change' => -$quantity,
            'movement_type' => InventoryLog::MOVEMENT_MANUAL_SUBTRACT,
            'reason' => $reason
        ]);
    }

    /**
     * Log stock adjustment
     */
    public static function logAdjustment($productId, $newQuantity, $reason = null)
    {
        $product = Product::find($productId);
        if (!$product) {
            throw new \Exception('المنتج غير موجود');
        }

        $change = $newQuantity - $product->quantity;

        return self::logMovement([
            'product_id' => $productId,
            'quantity_before' => $product->quantity,
            'quantity_change' => $change,
            'quantity_after' => $newQuantity,
            'movement_type' => InventoryLog::MOVEMENT_ADJUSTMENT,
            'reason' => $reason ?? 'تعديل المخزون إلى ' . $newQuantity . ' وحدة'
        ]);
    }

    /**
     * Get inventory logs with filtering
     */
    public static function getLogs(array $filters = [])
    {
        $query = InventoryLog::with(['product', 'user']);

        // Filter by product
        if (!empty($filters['product_id'])) {
            $query->byProduct($filters['product_id']);
        }

        // Filter by movement type
        if (!empty($filters['movement_type'])) {
            $query->byMovementType($filters['movement_type']);
        }

        // Filter by date range
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }

        // Filter by reference
        if (!empty($filters['reference_type']) && !empty($filters['reference_id'])) {
            $query->byReference($filters['reference_type'], $filters['reference_id']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get product movement history
     */
    public static function getProductHistory($productId, $limit = 50)
    {
        return self::getLogs(['product_id' => $productId])
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get movement statistics
     */
    public static function getStatistics(array $filters = [])
    {
        $query = InventoryLog::query();

        // Apply filters
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        $stats = [
            'total_movements' => $query->count(),
            'total_additions' => $query->where('quantity_change', '>', 0)->sum('quantity_change'),
            'total_subtractions' => abs($query->where('quantity_change', '<', 0)->sum('quantity_change')),
            'total_cost_additions' => $query->where('quantity_change', '>', 0)->sum('total_cost'),
            'total_cost_subtractions' => $query->where('quantity_change', '<', 0)->sum('total_cost'),
            'movements_by_type' => $query->selectRaw('movement_type, COUNT(*) as count, SUM(quantity_change) as total_quantity')
                                ->groupBy('movement_type')
                                ->get()
        ];

        return $stats;
    }
}
