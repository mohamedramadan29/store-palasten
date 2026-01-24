<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\Advantage;
use App\Models\admin\Brand;
use App\Models\admin\MainCategory;
use App\Models\admin\Product;
use App\Models\admin\Review;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getProducts()
    {
        $query = Product::query();
        $allproducts = $query->where('status',1)->get();
        $latest_products = $query->latest()->take(6)->get();
        $categories = MainCategory::all();
        $brands = Brand::all();
        $reviews = Review::all();
        $advantages = Advantage::all();
        $category_with_products = $categories->where('main_page',1)->map(function ($category){
            $category->products_category = $category->products()->limit(4)->get();
            return $category;
        });
        return response()->json($allproducts);
    }
}
