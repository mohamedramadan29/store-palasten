<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Slug_Trait;
use App\Http\Traits\Upload_Images;
use App\Models\admin\MainCategory;
use App\Models\admin\Product;
use App\Models\admin\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{

    use Message_Trait;
    use Slug_Trait;
    use Upload_Images;

    public function index($id)
    {
        $categories = SubCategory::where('parent_id',$id)->orderby('id', 'desc')->get();
        $maincategory = MainCategory::findOrFail($id);
        return view('admin.SubCategory.index', compact('categories','maincategory'));
    }

    public function store(Request $request,$id)
    {
        $MainCategory = MainCategory::findOrFail($id);
        if ($request->isMethod('post')) {
            if ($request->isMethod('post')) {
                try {
                    $alldata = $request->all();
                    $parentcategory = $alldata['parent_category'];
                    // Make Validation
                    $rules = [
                        'name' => 'required',
                        'slug' => 'required|unique:sub_categories',
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
                        $file_name = $this->saveImage($request->image, 'assets/uploads/Subcategory_images');
                    }
                    $new_category = new SubCategory();
                    $new_category->name = $alldata['name'];
                    $new_category->parent_id = $parentcategory;
                    $new_category->slug = $alldata['slug'];
                    $new_category->description = $alldata['description'];
                    $new_category->status = $alldata['status'];
                    $new_category->meta_title = $alldata['meta_title'];
                    $new_category->meta_description = $alldata['meta_description'];
                    $new_category->meta_keywords = $alldata['meta_keywords'];
                    $new_category->image = $file_name;
                    $new_category->save();
                    return $this->success_message(' تمت اضافة قسم فرعي بنجاح  ');
                } catch (\Exception $e) {
                    return $this->exception_message($e);
                }
            }
        }
        return view('admin.SubCategory.add',compact('MainCategory'));
    }

    public function update(Request $request, $id)
    {
        $category = SubCategory::findOrFail($id);

        if ($request->isMethod('post')) {
            try {
                $alldata = $request->all();
                // Make Validation
                $rules = [
                    'name' => 'required',
                    'slug' => 'required|unique:sub_categories,slug,'.$category->id,
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
                    $old_image = 'assets/uploads/Subcategory_images/' . $category['image'];
                    if (isset($old_image) && $old_image != '') {
                        unlink($old_image);
                    }
                    $file_name = $this->saveImage($request->image, 'assets/uploads/Subcategory_images');
                    $category->update([
                        'image' => $file_name,
                    ]);
                }
                $category->update([
                    "name" => $alldata['name'],
                    "slug" => $alldata['slug'],
                    "description" => $alldata['description'],
                    "status" => $alldata['status'],
                    "meta_title" => $alldata['meta_title'],
                    "meta_description" => $alldata['meta_description'],
                    "meta_keywords" => $alldata['meta_keywords'],
                ]);
                return $this->success_message(' تم تعديل القسم بنجاح  ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }

        }
        return view('admin.SubCategory.update', compact('category'));
    }



    // Method to delete a subcategory and its associated products
    public function delete($id)
    {
        try {
            // Find the subcategory
            $category = SubCategory::findOrFail($id);

            // Delete all products associated with this subcategory
            $products = Product::where('sub_category_id', $id)->get();
            foreach ($products as $product) {
                // Delete product images if any
                $productImage = 'assets/uploads/product_images/' . $product['image'];
                if (file_exists($productImage)) {
                    @unlink($productImage);
                }
                // Delete the product
                $product->delete();
            }

            // Delete the subcategory image
            $old_image = 'assets/uploads/Subcategory_images/' . $category['image'];
            if (file_exists($old_image)) {
                @unlink($old_image);
            }

            // Delete the subcategory
            $category->delete();
            return $this->success_message('تم حذف القسم الفرعي وجميع المنتجات المرتبطة بنجاح');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
