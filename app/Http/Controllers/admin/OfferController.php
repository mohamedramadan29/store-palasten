<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Slug_Trait;
use App\Http\Traits\Upload_Images;
use App\Models\admin\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    use Message_Trait;
    use Upload_Images;
    use Slug_Trait;

    public function index()
    {
        $offers = Offer::all();
        return view('admin.Offers.index', compact('offers'));
    }
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'product_name' => 'required',
                    'price'=>'required',
                    'image'=> 'required|image|mimes:jpg,png,jpeg,webp',
                ];
                $messages = [
                    'product_name.required' => ' من فضلك ادخل اسم المنتج  ',
                    'price.required'=>' من فضلك ادخل سعر المنتج  ',
                    'image.mimes' =>
                        'من فضلك يجب ان يكون نوع الصورة jpg,png,jpeg,webp',
                    'image.image' => 'من فضلك ادخل الصورة بشكل صحيح',
                ];
                $validator = Validator::make($data,$rules,$messages);
                if ($validator->fails()){
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                if ($request->hasFile('image')) {
                    $file_name = $this->saveImage($request->image, public_path('assets/uploads/product_offers'));
                }
                $offer = new Offer();
                $offer->product_name = $data['product_name'];
                if ($data['slug'] && $data['slug'] != '') {
                    $slug = $this->CustomeSlug($data['slug']);
                } else {
                    $slug = $this->CustomeSlug($data['product_name']);
                }
                $offer->slug = $slug;
                $offer->price = $data['price'];
                $offer->image = $file_name;
                $offer->save();
                return $this->success_message(' تمت الاضافة بنجاح  ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.Offers.store');
    }
    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'product_name' => 'required',
                    'price'=>'required',
                ];
                if ($request->hasFile('image')){
                    $rules['image'] = 'required|image|mimes:jpg,png,jpeg,webp';
                }
                $messages = [
                    'product_name.required' => ' من فضلك ادخل اسم المنتج  ',
                    'price.required'=>' من فضلك ادخل سعر المنتج  ',
                    'image.mimes' =>
                        'من فضلك يجب ان يكون نوع الصورة jpg,png,jpeg,webp',
                    'image.image' => 'من فضلك ادخل الصورة بشكل صحيح',
                ];
                $validator = Validator::make($data,$rules,$messages);
                if ($validator->fails()){
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                if ($request->hasFile('image')) {
                    $file_name = $this->saveImage($request->image, public_path('assets/uploads/product_offers'));
                    $offer->update([
                        'image'=>$file_name
                    ]);
                }
                if ($data['slug'] && $data['slug'] != '') {
                    $slug = $this->CustomeSlug($data['slug']);
                } else {
                    $slug = $this->CustomeSlug($data['product_name']);
                }
                $offer->update([
                    'product_name'=>$data['product_name'],
                    'slug'=>$slug,
                    'price'=>$data['price'],
                ]);
                return $this->success_message(' تمت التعديل  بنجاح  ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }

        return view('admin.Offers.update',compact('offer'));
    }

    public function delete($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();
        return $this->success_message(' تم الحذف بنجاح  ');
    }
}
