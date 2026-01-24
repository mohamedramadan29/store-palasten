<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Slug_Trait;
use App\Http\Traits\Upload_Images;
use App\Models\admin\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use Message_Trait;
    use Slug_Trait;
    use Upload_Images;

    public function index()
    {
        $brands = Brand::all();
        return view('admin.Brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $new_brands = $request->all();
                $rules = [
                    'name' => 'required|max:50|min:3',
                ];
                if ($request->hasFile('image')){
                    $rules['image'] = 'image|mimes:jpg,png,jpeg,webp';
                }
                $custome_messages = [
                    'name.required' => 'من فضلك ادخل اسم البراند ',
                    'name.max' => 'اسم البراند يجب ان يكون اقل من 50 حرف ',
                    'name.min' => 'من فضلك ادخل اسم القسم بشكل صحيح',
                    'image.image'=>' من فضلك ادخل صورة فقط ',
                    'image.mimes'=> ' نوع الصورة فقط jpg, png , jpeg , webp ',
                ];
                $this->validate($request, $rules, $custome_messages);
                if ($request->hasFile('image')) {
                    $file_name = $this->saveImage($request->image, 'assets/uploads/brands');
                }
                $brand = new Brand();
                $brand->name = $new_brands['name'];
                $brand->image = $file_name;
                $brand->status = $new_brands['status'];
                $brand->save();
                return $this->success_message(' تم اضافه علامه تجارية بنجاح ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.Brands.add');
    }
    public function update(Request $request,$id)
    {

        $brand = Brand::findOrFail($id);
        if ($request->isMethod('post')){
            try {
                $brand_data = $request->all();
                $rules = [
                    'name' => 'required|max:50|min:3',
                ];
                if ($request->hasFile('image')){
                    $rules['image'] = 'image|mimes:jpg,png,jpeg,webp';
                }
                $custome_messages = [
                    'name.required' => 'من فضلك ادخل اسم  العلامة التجارية',
                    'name.max' => 'اسم العلامة يجب ان يكون اقل من 50 حرف ',
                    'name.min' => 'من فضلك ادخل اسم العلامة بشكل صحيح',
                    'image.image'=>' من فضلك ادخل صورة فقط ',
                    'image.mimes'=> ' نوع الصورة فقط jpg, png , jpeg , webp ',
                ];
                $this->validate($request, $rules, $custome_messages);

                // Handle image upload and deletion
                if ($request->hasFile('image')) {
                    // delete old image
                    $old_image = 'assets/uploads/brands/'.$brand['image'];
                    if (file_exists($old_image)) {
                        @unlink($old_image);
                    }
                    $file_name = $this->saveImage($request->image, 'assets/uploads/brands');

                    $brand->update([
                        'image' => $file_name,
                    ]);

                }
                $brand->update([
                    'name' => $brand_data['name'],
                    'status' => $brand_data['status']
                ]);
                return $this->success_message('تم تعديل العلامه بنجاح');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.Brands.update',compact('brand'));

    }


    public function delete($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();
            return $this->success_message('تم حذف  العلامة بنجاح');

        } catch (\Exception $e) {
            return $this->exception_message($e);
        }

    }
}
