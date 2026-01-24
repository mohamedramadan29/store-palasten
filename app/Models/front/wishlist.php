<?php

namespace App\Models\front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wishlist extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function wishlistitems()
    {
        $cookie_id = \Illuminate\Support\Facades\Cookie::get('cookie_id');
        $wishlistitem = wishlist::where('cookie_id', $cookie_id)->get();
        return $wishlistitem;
    }
}
