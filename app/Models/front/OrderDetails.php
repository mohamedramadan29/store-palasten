<?php

namespace App\Models\front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    public function variation()
    {
        return $this->belongsTo(\App\Models\admin\ProductVartions::class, 'product_variation_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\admin\Product::class, 'product_id');
    }
}
