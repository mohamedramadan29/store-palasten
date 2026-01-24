<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Slug_Trait;
use App\Http\Traits\Upload_Images;
use App\Models\admin\MainCategory;
use App\Models\admin\Product;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{

    use Message_Trait;
    use Slug_Trait;
    use Upload_Images;

    public function index()
    {
        $categories = MainCategory::orderby('id', 'desc')->get();
        return view('admin.MainCategory.index', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->isMethod('post')) {
                try {
                    $alldata = $request->all();
                    // Make Validation
                    $rules = [
                        'name' => 'required',
                        'slug' => 'required|unique:main_categories,slug',
                        'status' => 'required',
                        'image' => 'image|required|mimes:jpg,png,jpeg,webp',
                    ];
                    $customeMessage = [
                        'name.required' => 'من فضلك ادخل اسم القسم',
                        'status.required' => 'حدد حالة القسم ',
                        'image.mimes' =>
                        'من فضلك يجب ان يكون نوع الصورة jpg,png,jpeg,webp',
                        'image.image' => 'من فضلك ادخل الصورة بشكل صحيح',
                    ];
                    $this->validate($request, $rules, $customeMessage);
                    /// Upload Admin Photo
                    if ($request->hasFile('image')) {
                        $file_name = $this->saveImage($request->image, 'assets/uploads/category_images');
                    }
                    $new_category = new MainCategory();
                    $new_category->name = $alldata['name'];
                    $new_category->slug = $alldata['slug'];
                    $new_category->description = $alldata['description'];
                    $new_category->status = $alldata['status'];
                    $new_category->main_page = $alldata['main_page'];
                    $new_category->meta_title = $alldata['meta_title'];
                    $new_category->meta_description = $alldata['meta_description'];
                    $new_category->meta_keywords = $alldata['meta_keywords'];
                    $new_category->image = $file_name;
                    $new_category->save();
                    return $this->success_message(' تمت اضافة القسم بنجاح ');
                } catch (\Exception $e) {
                    return $this->exception_message($e);
                }
            }
        }
        return view('admin.MainCategory.add');
    }

    public function update(Request $request, $id)
    {
        $category = MainCategory::findOrFail($id);

        if ($request->isMethod('post')) {
            try {
                $alldata = $request->all();
                // Make Validation
                $rules = [
                    'name' => 'required',
                    'slug' => 'required|unique:main_categories,slug,' . $category->id,
                    'status' => 'required',
                ];
                if ($request->hasFile('image')) {
                    $rules['image'] = 'image|mimes:jpg,png,jpeg,webp';
                }
                $customeMessage = [
                    'name.required' => 'من فضلك ادخل اسم القسم',
                    'status.required' => 'حدد حالة القسم ',
                    'image.mimes' =>
                    'من فضلك يجب ان يكون نوع الصورة jpg,png,jpeg,webp',
                    'image.image' => 'من فضلك ادخل الصورة بشكل صحيح',
                ];
                $this->validate($request, $rules, $customeMessage);
                /// Upload Category Image
                if ($request->hasFile('image')) {
                    ////// Delete Old Image
                    $old_image = 'assets/uploads/category_images/' . $category['image'];
                    if (file_exists($old_image)) {
                        @unlink($old_image);
                    }
                    $file_name = $this->saveImage($request->image, 'assets/uploads/category_images');
                    $category->update([
                        'image' => $file_name,
                    ]);
                }
                $category->update([
                    "name" => $alldata['name'],
                    "slug" => $alldata['slug'],
                    "description" => $alldata['description'],
                    "status" => $alldata['status'],
                    'main_page' => $alldata['main_page'],
                    "meta_title" => $alldata['meta_title'],
                    "meta_description" => $alldata['meta_description'],
                    "meta_keywords" => $alldata['meta_keywords'],
                ]);
                return $this->success_message(' تم تعديل القسم بنجاح  ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.MainCategory.update', compact('category'));
    }


    public function delete($id)
    {
        try {
            // Find the main category
            $category = MainCategory::findOrFail($id);

            // Delete all products associated with this main category
            $products = Product::where('category_id', $id)->get();
            foreach ($products as $product) {
                // Delete product images if any
                $productImage = 'assets/uploads/product_images/' . $product['image'];
                if (file_exists($productImage)) {
                    @unlink($productImage);
                }
                // Delete the product
                $product->delete();
            }

            // Delete the main category image
            $old_image = 'assets/uploads/category_images/' . $category['image'];
            if (file_exists($old_image)) {
                @unlink($old_image);
            }

            // Delete the main category
            $category->delete();
            return $this->success_message('تم حذف القسم الرئيسي وجميع المنتجات المرتبطة بنجاح');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
