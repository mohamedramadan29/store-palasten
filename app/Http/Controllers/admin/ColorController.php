<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $colors = Color::first();
        return view('admin.Colors.index', compact('colors'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $colors = Color::first();
        $colors->update([
            'website_background' => $data['website_background'],
            'top_navbar_background' => $data['top_navbar_background'],
            'second_navbar_background' => $data['second_navbar_background'],
            'third_navbar_background' => $data['third_navbar_background'],
            'main_title_color' => $data['main_title_color'],
            'main_title_background' => $data['main_title_background'],
            'product_title_color' => $data['product_title_color'],
            'all_button_background' => $data['all_button_background'],
            'main_price_color' => $data['main_price_color'],
            'public_add_to_cart_background' => $data['public_add_to_cart_background'],
            'public_add_to_cart_color' => $data['public_add_to_cart_color'],
            'footer_background' => $data['footer_background'],
            'footer_color' => $data['footer_color'],
            'search_category_background' => $data['search_category_background'],
            'search_background' => $data['search_background'],
            'search_icon_background' => $data['search_icon_background'],
            'search_icon_color' => $data['search_icon_color'],
        ]);

        return $this->success_message(' تم تعديل الوان الموقع بنجاح ');
    }
}
