<?php

namespace App\Models\front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'product_qty',
        'product_variation_id'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    public function variation()
    {
        return $this->belongsTo(\App\Models\admin\ProductVartions::class, 'product_variation_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\admin\Product::class, 'product_id');
    }
}
