<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Upload_Images;
use App\Models\admin\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\fileExists;

class BannerController extends Controller
{
    use Message_Trait;
    use Upload_Images;

    public function index()
    {
        $banners = Banner::all();
        return view('admin.Banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $data = $request->all();
                $rules = [
                    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
                ];
                $messages = [
                    'image.required' => 'من فضلك ادخل الصورة',
                    'image.image' => 'من فضلك ادخل صورة بشكل صحيح',
                    'image.mimes' => 'نوع الصورة يجب ان يكون من نوع jpg, jpeg, png, webp',
                    'image.max' => 'حجم الصورة يجب ان يكون أقل من 2 ميجابايت'
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }

                if ($request->hasFile('image')) {
                    $filename = $this->saveImage($request->image, 'assets/uploads/banners/');
                }

                $banner = new Banner();
                $banner->image = $filename;
                $banner->status = $data['status'];
                $banner->save();

                return $this->success_message('تم اضافة البانر بنجاح');
            }
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $banner = Banner::findOrFail($data['banner_id']);
            if ($request->hasFile('image')) {
                $rules = [
                    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
                ];
                $messages = [
                    'image.required' => 'من فضلك ادخل الصورة',
                    'image.image' => 'من فضلك ادخل صورة بشكل صحيح',
                    'image.mimes' => 'نوع الصورة يجب ان يكون من نوع jpg, jpeg, png, webp',
                    'image.max' => 'حجم الصورة يجب ان يكون أقل من 2 ميجابايت'
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $oldimage = 'assets/uploads/banners/' . $banner['image'];
                if (file_exists($oldimage)) {
                    unlink($oldimage);
                }
                $filename = $this->saveImage($request->image, 'assets/uploads/banners/');
                $banner->update([
                    'image' => $filename
                ]);
            }
            $banner->update([
                'status' => $data['status']
            ]);
            return $this->success_message('تم تعديل البانر بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        $banner = Banner::findOrFail($data['banner_id']);
        $oldimage = 'assets/uploads/banners/' . $banner['image'];
        if (file_exists($oldimage)) {
            unlink($oldimage);
        }

        $banner->delete();
        return $this->success_message(' تم حذف البانر بنجاح  ');

    }
}
