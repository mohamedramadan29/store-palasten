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
}
