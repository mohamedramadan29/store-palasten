<?php

namespace App\Http\Middleware;

use App\Services\InventoryLogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogInventoryMovement
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get response first
        $response = $next($request);
        
        // Check if this is an order status change request
        if ($this->isOrderStatusChange($request)) {
            $this->logOrderStatusChange($request);
        }
        
        return $response;
    }
    
    private function isOrderStatusChange($request)
    {
        return $request->isMethod('POST') && 
               $request->has('order_status') && 
               $request->has('order_id');
    }
    
    private function logOrderStatusChange($request)
    {
        try {
            $orderId = $request->order_id;
            $newStatus = $request->order_status;
            
            // Get order with details
            $order = \App\Models\front\Order::with('orderDetails.product')->find($orderId);
            
            if (!$order) {
                return;
            }
            
            $oldStatus = $order->order_status;
            
            // Log inventory movements based on status change
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                if (!$product) continue;
                
                // If order is being completed - subtract from inventory
                if ($newStatus === 'مكتمل' && $oldStatus !== 'مكتمل') {
                    InventoryLogService::logSale(
                        $product->id,
                        $detail->product_qty,
                        $orderId,
                        [
                            'order_detail_id' => $detail->id,
                            'product_name' => $detail->product_name,
                            'unit_price' => $detail->product_price,
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus
                        ]
                    );
                    
                    // Update product quantity
                    $product->quantity -= $detail->product_qty;
                    $product->save();
                }
                
                // If order is being cancelled - restore to inventory
                if ($newStatus === 'ملغي' && $oldStatus !== 'ملغي') {
                    InventoryLogService::logCancellation(
                        $product->id,
                        $detail->product_qty,
                        $orderId,
                        [
                            'order_detail_id' => $detail->id,
                            'product_name' => $detail->product_name,
                            'unit_price' => $detail->product_price,
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus
                        ]
                    );
                    
                    // Update product quantity
                    $product->quantity += $detail->product_qty;
                    $product->save();
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to log order inventory movement: ' . $e->getMessage());
        }
    }
}
