<?php

namespace App\Models\front;

use App\Models\admin\ShippingCity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(ShippingCity::class,'shippingcity');
    }

    public function details()
    {
        return $this->hasMany(OrderDetails::class,'order_id');
    }
}
