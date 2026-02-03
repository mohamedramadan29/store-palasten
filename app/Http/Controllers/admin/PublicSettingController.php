<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Upload_Images;
use App\Models\admin\PublicSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicSettingController extends Controller
{
    use Message_Trait;
    use Upload_Images;

    public function update(Request $request)
    {
        $public_setting = PublicSetting::first();
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'website_name' => 'required',
            ];
            if ($request->hasFile('image')) {
                $rules['image'] = 'image|mimes:jpg,png,jpeg,webp';
            }
            $messages = [
                'website_name.required' => 'من فضلك ادخل اسم الموقع',
                'image.image' => 'من فضلك ادخل صورة فقط',
                'image.mimes' => 'نوع الصورة فقط jpg, png, jpeg, webp',
            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }

            // Handle image upload and deletion
            if ($request->hasFile('image')) {
                $old_image_path = 'assets/uploads/PublicSetting/' . $public_setting->website_logo;
               // $old_image_path = Storage::url('uploads/PublicSetting/' . $public_setting->website_logo);
                if (file_exists($old_image_path)) {
                    @unlink($old_image_path);
                }
                $file_name = $this->saveImage($request->image, 'assets/uploads/PublicSetting');
                $public_setting->website_logo = $file_name;
            }

            // Update the fields with new values
            $public_setting->update([
                'website_name' => $data['website_name'],
                'website_short_desc' => $data['website_short_desc'],
                'website_description' => $data['website_description'],
                'website_keywords' => $data['website_keywords'],
                'status' => $data['status'],
                'main_color' => $data['main_color'],
                'second_color' => $data['second_color'],
                'website_currency'=>$data['website_currency'],
                'website_address'=>$data['website_address'],
                'website_email'=>$data['website_email'],
                'website_phone'=>$data['website_phone'],
                'admin_order_email'=>$data['admin_order_email'],
            ]);

            return $this->success_message('تم تعديل الاعدادات العامة للموقع بنجاح');
        }

        return view('admin.PublicSetting.update', compact('public_setting'));
    }

}
