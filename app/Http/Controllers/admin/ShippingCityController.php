<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\ShippingCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ShippingCityController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $shipingcitizen = ShippingCity::all();
        return view('admin.ShippingCity.index',compact('shipingcitizen'));
    }
    public function store(Request $request)
    {
        if ($request->isMethod('post')){
            try {

                $data = $request->all();
                $rules = [
                    'city'=>'required',
                    'price'=>'required|numeric'
                ];
                $messages = [
                    'city.required'=>' من فضلك ادخل اسم المدينة  ',
                    'price.required'=>' من فضلك ادخل سعر الشحن  ',
                    'price.numeric'=>' سعر الشحن يجب ان يكون رقم صحيح  ',
                ];
                $validator = Validator::make($data,$rules,$messages);
                if ($validator->fails())
                {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $shippingcity = new ShippingCity();
                $shippingcity->create([
                    'city'=>$data['city'],
                    'price'=>$data['price']
                ]);
                return $this->success_message(' تم اضافة مدينة شحن بنجاح  ');
            }catch (\Exception $e){
                return $this->exception_message($e);
            }
        }
    }
    public function update(Request $request,$id)
    {
        if ($request->isMethod('post')){
            try {
                $shippingcity = ShippingCity::findOrFail($id);
                $data = $request->all();
                $rules = [
                    'city'=>'required',
                    'price'=>'required|numeric'
                ];
                $messages = [
                    'city.required'=>' من فضلك ادخل اسم المدينة  ',
                    'price.required'=>' من فضلك ادخل سعر الشحن  ',
                    'price.numeric'=>' سعر الشحن يجب ان يكون رقم صحيح  ',
                ];
                $validator = Validator::make($data,$rules,$messages);
                if ($validator->fails())
                {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $shippingcity->update([
                    'city'=>$data['city'],
                    'price'=>$data['price']
                ]);
                return $this->success_message(' تم اضافة مدينة شحن بنجاح  ');
            }catch (\Exception $e){
                return $this->exception_message($e);
            }
        }
    }

    public function delete($id)
    {
        $shippingCity = ShippingCity::findOrFail($id);
        $shippingCity->delete();
        return $this->success_message(' تم حدف مدينة الشحن بنجاح  ');
    }
}
