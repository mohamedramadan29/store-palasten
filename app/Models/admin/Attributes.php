<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    use HasFactory;
    protected $guarded =[];

    public static function findByName($name)
    {
        return self::where('name', $name)->first();
    }
}
