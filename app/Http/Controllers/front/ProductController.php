<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\admin\ProductVartions;
use App\Models\admin\VartionsValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\front\wishlist;

class ProductController extends Controller
{
    public function product($slug)
    {

        $product = Product::with('Main_Category', 'gallary')->where('slug', $slug)->first();
        $similar_products = Product::with('gallary')->where('id', '!=', $product['id'])->where(function ($query) use ($product) {
                $query->where('category_id', $product['category_id'])
                    ->orWhere('sub_category_id', $product['sub_category_id']);
            })
            ->limit(6)->get();

    
            $gallary = $product->gallary;
         


        // جلب جميع المتغيرات الخاصة بالمنتج


        $productVariations = ProductVartions::where('product_id', $product->id)->get();

        // جمع السمات مع القيم بناءً على attribute_id
        $variationAttributes = [];

        foreach ($productVariations as $variation) {
            // جلب القيم من جدول vartions_values بناءً على المتغير
            $attributes = VartionsValues::where('product_variation_id', $variation->id)->get();

            foreach ($attributes as $attribute) {
                // التأكد من أن العلاقة مع attributes تجلب الاسم
                $attributeName = $attribute->attribute->name;
                // تنظيم القيم حسب attribute_id
                if (!isset($variationAttributes[$attribute->attribute_id])) {
                    $variationAttributes[$attribute->attribute_id] = [
                        'name' => $attributeName, // اسم السمة من جدول attributes
                        'values' => []
                    ];
                }
                // إضافة القيم إلى السمة المحددة إذا لم تكن موجودة مسبقاً
                if (!in_array($attribute->attribute_value_name, $variationAttributes[$attribute->attribute_id]['values'])) {
                    $variationAttributes[$attribute->attribute_id]['values'][] = $attribute->attribute_value_name;
                }
            }
        }

        return view('front.product-details', compact('product', 'productVariations', 'variationAttributes', 'similar_products','gallary'));
    }

    public function search(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $query = $request->input('query');
        $category = $request->input('category');
        $products = Product::where('name', 'LIKE', "%{$query}%")->select('name', 'slug');
        if ($category) {
            $products->where('category_id', $category);
        }
        $results = $products->get();
        // إرجاع النتائج كـ HTML (قائمة منسدلة)
        $output = '';
        if ($results->count() > 0) {
            foreach ($results as $product) {
                $output .= "<a href='/product/{$product->slug}' class='dropdown-item'>{$product->name}</a>";
            }
        } else {
            $output = '<p class="dropdown-item">لا توجد نتائج</p>';
        }

        return response()->json($output);
    }

    public function main_search(Request $request)
    {
        $data = $request->all();
       //  dd($data);
        $product_name = $request->input('product_name');
      //  dd  ($query);
        $category = $request->input('category');
        // $products = Product::where('name', 'LIKE', "%{$query}%");

        $product_name = trim($product_name);
        $products = Product::whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($product_name) . "%"]);

        if ($category) {
            $products->where('category_id', $category);
        }
        $products = $products->paginate(16);
        $cookie_id = Cookie::get('cookie_id');
        if (empty($cookie_id)) {
            $cookie_id = Session::getId();
            // تخزين session_id في cookie لمدة 30 يومًا
            Cookie::queue(Cookie::make('session_id', $cookie_id, 60 * 24 * 30));
        }

        $wishlistProducts = Wishlist::where('cookie_id', $cookie_id)->pluck('product_id')->toArray();

        return view('front.search', compact('products', 'wishlistProducts'));
    }


    public function getPrice(Request $request, $productId)
    {
        // جلب جميع المتغيرات الخاصة بالمنتج
        $productVariations = ProductVartions::where('product_id', $productId)->get();

        // جلب قيم السمات من الطلب
        $selectedAttributes = $request->attribute_values;

        // البحث عن المتغير الذي يحتوي على نفس القيم
        foreach ($productVariations as $variation) {
            $matched = true;
            $variationAttributes = VartionsValues::where('product_variation_id', $variation->id)->get();
            // تحقق من مطابقة القيم
            foreach ($variationAttributes as $attribute) {
                $attributeValue = trim($attribute->attribute_value_name);
                $selectedPriceValue = isset($selectedAttributes[$attribute->attribute_id]) ? trim($selectedAttributes[$attribute->attribute_id]) : null;

                if (!$selectedPriceValue || $selectedPriceValue !== $attributeValue) {
                    $matched = false;
                    break;
                }
            }
            // إذا كانت القيم متطابقة، نعيد السعر والتخفيض إذا وجد
            if ($matched) {
                return response()->json([
                    'variation_id' => $variation->id,
                    'price' => $variation->price,
                    'discount' => $variation->discount > 0 ? $variation->discount : null,
                    'image' => $variation->image ? asset('assets/uploads/product_images/' . $variation->image) : null,
                    'stock' => $variation->stock
                ]);
            }
        }

        // إذا لم يتم العثور على متغير مطابق
        return response()->json(['price' => null, 'discount' => null]);
    }

    public function quickView($id)
    {
        $product = Product::with('Main_Category', 'gallary')->findOrFail($id);

        // جلب جميع المتغيرات الخاصة بالمنتج
        $productVariations = ProductVartions::where('product_id', $id)->get();

        // جمع السمات مع القيم بناءً على attribute_id
        $variationAttributes = [];

        foreach ($productVariations as $variation) {
            // جلب القيم من جدول vartions_values بناءً على المتغير
            $attributes = VartionsValues::where('product_variation_id', $variation->id)->get();

            foreach ($attributes as $attribute) {
                // التأكد من أن العلاقة مع attributes تجلب الاسم
                $attributeName = $attribute->attribute->name;
                // تنظيم القيم حسب attribute_id
                if (!isset($variationAttributes[$attribute->attribute_id])) {
                    $variationAttributes[$attribute->attribute_id] = [
                        'name' => $attributeName, // اسم السمة من جدول attributes
                        'values' => []
                    ];
                }
                // إضافة القيم إلى السمة المحددة إذا لم تكن موجودة مسبقاً
                if (!in_array($attribute->attribute_value_name, $variationAttributes[$attribute->attribute_id]['values'])) {
                    $variationAttributes[$attribute->attribute_id]['values'][] = $attribute->attribute_value_name;
                }
            }
        }
        return view('front.partials.quick-view', compact('product', 'productVariations', 'variationAttributes',));
    }
}
