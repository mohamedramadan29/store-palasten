<?php

namespace App\Models\front;

use App\Models\admin\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function productdata()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(\App\Models\admin\ProductVartions::class, 'product_variation_id');
    }

    public static function getcartitems()
    {
        if (Auth::check()) {
            // if User Logged In // Pick The User Id
            $user_id = Auth::user()->id;
            $getcartItems = Cart::with('productdata')->where('user_id', $user_id)->get();
        } else {
            // If User Not Login // Pick The Session ID
            $session_id = Session::get('session_id');
            $getcartItems = Cart::with(['productdata' => function ($query) {
                $query->select('name', 'id', 'price', 'image', 'discount','slug');
            }])->where('session_id', $session_id)->get();
        }
        return $getcartItems;
    }
}
