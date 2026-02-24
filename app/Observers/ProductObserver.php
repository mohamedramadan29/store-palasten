<?php

namespace App\Observers;

use App\Models\admin\Product;
use App\Services\InventoryLogService;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Log stock changes
        if ($product->isDirty('quantity')) {
            $oldQuantity = $product->getOriginal('quantity');
            $newQuantity = $product->quantity;
            $change = $newQuantity - $oldQuantity;

            // Only log if there's an actual change
            if ($change != 0) {
                try {
                    InventoryLogService::logMovement([
                        'product_id' => $product->id,
                        'quantity_before' => $oldQuantity,
                        'quantity_change' => $change,
                        'quantity_after' => $newQuantity,
                        'movement_type' => $change > 0 ? 
                            \App\Models\admin\InventoryLog::MOVEMENT_MANUAL_ADD : 
                            \App\Models\admin\InventoryLog::MOVEMENT_MANUAL_SUBTRACT,
                        'reason' => 'تعديل يدوي للمخزون من ' . $oldQuantity . ' إلى ' . $newQuantity,
                        'reference_type' => 'manual'
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't break the product update
                    \Log::error('Failed to log inventory movement: ' . $e->getMessage());
                }
            }
        }
    }
}
