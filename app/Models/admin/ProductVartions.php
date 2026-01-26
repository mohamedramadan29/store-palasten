<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVartions extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function variationValues()
    {
        return $this->hasMany(VartionsValues::class,	'product_variation_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Deduct stock for the variation.
     */
    public function deductStock($qty)
    {
        $this->stock -= $qty;
        if ($this->stock < 0) $this->stock = 0;
        $this->save();

        // Check if all variations are out of stock to update product status
        $this->checkProductAvailability();
    }

    /**
     * Restore stock for the variation.
     */
    public function restoreStock($qty)
    {
        $this->stock += $qty;
        $this->save();

        // Ensure product is back in stock if this variation is now available
        if ($this->stock > 0) {
            $this->product->update(['status' => 1]);
        }
    }

    /**
     * Check if product should be out of stock.
     */
    public function checkProductAvailability()
    {
        $totalStock = ProductVartions::where('product_id', $this->product_id)->sum('stock');
        if ($totalStock <= 0) {
            $this->product->update(['status' => 0]);
        }
    }
}
