<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Product;
use App\Models\admin\ProductVartions;
use App\Models\admin\VartionsValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    use Message_Trait;

    public function index(Request $request)
    {
        $query = Product::with(['variations', 'variations.variationValues', 'Main_Category']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Search in product name (Arabic and English)
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    // Search in SKU (numeric and alphanumeric)
                    // ->orWhere('sku', 'LIKE', "%{$searchTerm}%")
                    // Search by exact number match for quantity
                    ->orWhere('quantity', '=', is_numeric($searchTerm) ? (int)$searchTerm : null)
                    // Search in variations stock
                    ->orWhereHas('variations', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('stock', '=', is_numeric($searchTerm) ? (int)$searchTerm : null);
                    });
            });
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('name')->paginate(50);

        // Calculate stock status for each product and variant
        $products->getCollection()->transform(function ($product) {
            // For products with variants, calculate total stock from variants
            if ($product->variations->count() > 0) {
                $totalVariantStock = $product->variations->sum('stock');
                $product->quantity = $totalVariantStock; // Update displayed quantity to show total variant stock
                $product->stock_status = $this->getStockStatus($totalVariantStock);
            } else {
                // For simple products, use the product quantity directly
                $product->stock_status = $this->getStockStatus($product->quantity);
            }

            // Process variations
            foreach ($product->variations as $variation) {
                $variation->stock_status = $this->getStockStatus($variation->stock);

                // Get variation attributes for display
                $attributes = [];
                foreach ($variation->variationValues as $value) {
                    $attributes[] = $value->attribute->name . ': ' . $value->attribute_value_name;
                }
                $variation->attributes_text = implode(' | ', $attributes);
            }

            return $product;
        });

        // Apply stock status filtering after calculating statuses
        if ($request->has('stock_status') && !empty($request->stock_status)) {
            $stockStatus = $request->stock_status;
            
            $products->setCollection(
                $products->getCollection()->filter(function ($product) use ($stockStatus) {
                    // For products without variants, check product status directly
                    if ($product->variations->count() === 0) {
                        return $product->stock_status['status'] === $stockStatus;
                    }
                    
                    // For products with variants, check if any variant matches the filter
                    $hasMatchingVariant = $product->variations->contains(function ($variation) use ($stockStatus) {
                        return $variation->stock_status['status'] === $stockStatus;
                    });
                    
                    // Filter variants to show only matching ones
                    if ($hasMatchingVariant) {
                        $product->variations = $product->variations->filter(function ($variation) use ($stockStatus) {
                            return $variation->stock_status['status'] === $stockStatus;
                        });
                    }
                    
                    return $hasMatchingVariant;
                })
            );
        }

        // Get categories for filter dropdown
        $categories = \App\Models\admin\MainCategory::where('status', '1')->orderBy('name')->get();

        // Calculate statistics including variants
        $allVariants = $products->getCollection()->flatMap(function ($product) {
            return $product->variations;
        });

        $stats = [
            'total_products' => $products->total(),
            'total_variants' => $allVariants->count(),
            'available_products' => $products->getCollection()->where('stock_status.status', 'available')->count(),
            'low_stock_products' => $products->getCollection()->where('stock_status.status', 'low')->count(),
            'out_of_stock_products' => $products->getCollection()->where('stock_status.status', 'out_of_stock')->count(),
            'available_variants' => $allVariants->where('stock_status.status', 'available')->count(),
            'low_stock_variants' => $allVariants->where('stock_status.status', 'low')->count(),
            'out_of_stock_variants' => $allVariants->where('stock_status.status', 'out_of_stock')->count(),
        ];

        return view('admin.Inventory.index', compact('products', 'categories', 'stats'));
    }

    public function updateStock(Request $request)
    {
        // dd($request);
        // Debug: Log incoming request
        Log::info('Stock Update Request:', [
            'type' => $request->type,
            'id' => $request->id,
            'operation' => $request->operation,
            'quantity' => $request->quantity,
            'reason' => $request->reason
        ]);

        $request->validate([
            'type' => 'required|in:product,variant',
            'id' => 'required|integer',
            'operation' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            if ($request->type == 'product') {
                $item = Product::findOrFail($request->id);
                $oldQuantity = $item->quantity;

                if ($request->operation == 'add') {
                    Log::info('ADD operation - Before:', [
                        'oldQuantity' => $oldQuantity,
                        'request_quantity' => $request->quantity,
                        'request_quantity_type' => gettype($request->quantity),
                        'intval_request_quantity' => intval($request->quantity)
                    ]);
                    $newQuantity = $oldQuantity + intval($request->quantity);
                    Log::info('ADD operation - Calculation:', [
                        'calculation' => $oldQuantity . ' + ' . intval($request->quantity) . ' = ' . $newQuantity
                    ]);
                    $item->quantity = $newQuantity;
                    Log::info('ADD operation - After:', ['new_quantity' => $item->quantity]);
                } elseif ($request->operation == 'subtract') {
                    Log::info('SUBTRACT operation - Before:', ['quantity' => $item->quantity, 'subtracting' => $request->quantity]);
                    $item->quantity = max(0, $item->quantity - $request->quantity);
                    Log::info('SUBTRACT operation - After:', ['new_quantity' => $item->quantity]);
                } else { // set
                    Log::info('SET operation - Before:', ['quantity' => $item->quantity, 'setting_to' => $request->quantity]);
                    $item->quantity = $request->quantity;
                    Log::info('SET operation - After:', ['new_quantity' => $item->quantity]);
                }

                $item->save();

                Log::info('After save - Product:', [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity_before_save' => $newQuantity ?? $item->quantity,
                    'quantity_after_save' => $item->quantity,
                    'database_quantity' => Product::find($item->id)->quantity
                ]);

                Log::info('After update - Product:', [
                    'id' => $item->id,
                    'new_quantity' => $item->quantity
                ]);

                // Update product status based on stock
                $this->updateProductStatus($item);

                $itemName = $item->name;
                $itemType = 'منتج';
            } else { // variant
                $item = ProductVartions::findOrFail($request->id);
                $oldQuantity = $item->stock;

                Log::info('Before update - Variant:', [
                    'id' => $item->id,
                    'old_quantity' => $oldQuantity,
                    'operation' => $request->operation,
                    'new_quantity_input' => $request->quantity
                ]);

                if ($request->operation == 'add') {
                    Log::info('ADD operation - Variant Before:', ['quantity' => $item->stock, 'adding' => $request->quantity]);
                    $item->stock += $request->quantity;
                    Log::info('ADD operation - Variant After:', ['new_quantity' => $item->stock]);
                } elseif ($request->operation == 'subtract') {
                    Log::info('SUBTRACT operation - Variant Before:', ['quantity' => $item->stock, 'subtracting' => $request->quantity]);
                    $item->stock = max(0, $item->stock - $request->quantity);
                    Log::info('SUBTRACT operation - Variant After:', ['new_quantity' => $item->stock]);
                } else { // set
                    Log::info('SET operation - Variant Before:', ['quantity' => $item->stock, 'setting_to' => $request->quantity]);
                    $item->stock = $request->quantity;
                    Log::info('SET operation - Variant After:', ['new_quantity' => $item->stock]);
                }

                $item->save();

                Log::info('After update - Variant:', [
                    'id' => $item->id,
                    'new_quantity' => $item->stock
                ]);

                // Update parent product stock and status
                $this->updateParentProductStock($item->product_id);

                $itemName = $item->product->name . ' - ' . $this->getVariantAttributes($item);
                $itemType = 'متغير';
            }

            // Log the stock adjustment
            $this->logStockAdjustment($request, $item, $oldQuantity, $itemName, $itemType);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "تم تحديث المخزون بنجاح",
                'new_quantity' => $item->quantity ?? $item->stock,
                'stock_status' => $this->getStockStatus($item->quantity ?? $item->stock)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المخزون: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdateStock(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.type' => 'required|in:product,variant',
            'items.*.id' => 'required|integer',
            'items.*.operation' => 'required|in:add,subtract,set',
            'items.*.quantity' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();
            $updatedCount = 0;

            foreach ($request->items as $itemData) {
                if ($itemData['type'] == 'product') {
                    $item = Product::findOrFail($itemData['id']);
                    $oldQuantity = $item->quantity;

                    if ($itemData['operation'] == 'add') {
                        $item->quantity += $itemData['quantity'];
                    } elseif ($itemData['operation'] == 'subtract') {
                        $item->quantity = max(0, $item->quantity - $itemData['quantity']);
                    } else { // set
                        $item->quantity = $itemData['quantity'];
                    }

                    $item->save();
                    $this->updateProductStatus($item);
                } else { // variant
                    $item = ProductVartions::findOrFail($itemData['id']);

                    if ($itemData['operation'] == 'add') {
                        $item->stock += $itemData['quantity'];
                    } elseif ($itemData['operation'] == 'subtract') {
                        $item->stock = max(0, $item->stock - $itemData['quantity']);
                    } else { // set
                        $item->stock = $itemData['quantity'];
                    }

                    $item->save();
                    $this->updateParentProductStock($item->product_id);
                }

                $updatedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "تم تحديث {$updatedCount} عناصر بنجاح"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحديث المجمع: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getStockStatus($quantity)
    {
        if ($quantity <= 0) {
            return [
                'status' => 'out_of_stock',
                'text' => 'نفد',
                'color' => 'danger',
                'icon' => 'ti ti-x-circle'
            ];
        } elseif ($quantity <= 10) {
            return [
                'status' => 'low',
                'text' => 'منخفض',
                'color' => 'warning',
                'icon' => 'ti ti-alert-triangle'
            ];
        } else {
            return [
                'status' => 'available',
                'text' => 'متوفر',
                'color' => 'success',
                'icon' => 'ti ti-check-circle'
            ];
        }
    }

    private function updateProductStatus($product)
    {
        if ($product->quantity <= 0) {
            $product->status = 0;
        } else {
            $product->status = 1;
        }
        $product->save();
    }

    private function updateParentProductStock($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $totalStock = ProductVartions::where('product_id', $productId)->sum('stock');
            $product->quantity = $totalStock;
            $this->updateProductStatus($product);
        }
    }

    private function getVariantAttributes($variation)
    {
        $attributes = [];
        foreach ($variation->variationValues as $value) {
            $attributes[] = $value->attribute_value_name;
        }
        return implode(' - ', $attributes);
    }

    private function logStockAdjustment($request, $item, $oldQuantity, $itemName, $itemType)
    {
        // You can implement logging here
        // For example, save to a stock_adjustments table
        $newQuantity = $item->quantity ?? $item->stock;
        $operation = $request->operation;
        $quantity = $request->quantity;

        Log::info('Stock Adjustment', [
            'item_type' => $itemType,
            'item_name' => $itemName,
            'operation' => $operation,
            'quantity_changed' => $quantity,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'reason' => $request->reason,
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
    }

    public function stockReport(Request $request)
    {
        $query = Product::with(['variations', 'Main_Category']);

        // Apply same filters as index
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%");
                    // ->orWhere('sku', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->has('stock_status') && !empty($request->stock_status)) {
            $stockStatus = $request->stock_status;

            if ($stockStatus == 'available') {
                $query->where('quantity', '>', 10);
            } elseif ($stockStatus == 'low') {
                $query->where('quantity', '>', 0)->where('quantity', '<=', 10);
            } elseif ($stockStatus == 'out_of_stock') {
                $query->where('quantity', '<=', 0);
            }
        }

        $products = $query->orderBy('name')->get();

        // Calculate statistics
        $stats = [
            'total_products' => $products->count(),
            'available_products' => $products->where('quantity', '>', 10)->count(),
            'low_stock_products' => $products->where('quantity', '>', 0)->where('quantity', '<=', 10)->count(),
            'out_of_stock_products' => $products->where('quantity', '<=', 0)->count(),
            'total_stock_value' => $products->sum(function ($product) {
                return $product->quantity * $product->price;
            })
        ];

        return view('admin.Inventory.report', compact('products', 'stats'));
    }
}
