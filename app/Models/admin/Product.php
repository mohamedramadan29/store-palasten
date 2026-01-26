<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    // علاقة المتغيرات
    public function variations()
    {
        return $this->hasMany(ProductVartions::class);
    }

    // علاقة قيم المتغيرات
    public function variationValues()
    {
        return $this->hasMany(VartionsValues::class, 'product_variation_id'); // التأكد من استخدام العمود الصحيح
    }

    // علاقة مع الفئة الرئيسية
    public function Main_Category()
    {
        return $this->belongsTo(MainCategory::class, 'category_id');
    }

    // علاقة مع الفئة الفرعية
    public function Sub_Category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // علاقة مع الجاليري
    public function gallary()
    {
        return $this->hasMany(ProductGallary::class, 'product_id');
    }

    /**
     * Deduct stock for the product.
     */
    public function deductStock($qty)
    {
        $this->quantity -= $qty;
        if ($this->quantity <= 0) {
            $this->quantity = 0;
            $this->status = 0; // Out of stock / Archived
        }
        $this->save();
    }

    /**
     * Restore stock for the product.
     */
    public function restoreStock($qty)
    {
        $this->quantity += $qty;
        if ($this->quantity > 0) {
            $this->status = 1; // Back in stock
        }
        $this->save();
    }
}
