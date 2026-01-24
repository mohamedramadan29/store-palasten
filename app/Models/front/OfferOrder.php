<?php

namespace App\Models\front;

use App\Models\admin\ShippingCity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferOrder extends Model
{
    use HasFactory;
    protected $fillable =['offer_id','product_name','name','phone','city','address','ship_price','total_price'];

    public function shipping()
    {
        return $this->belongsTo(ShippingCity::class,'city');
    }
}
