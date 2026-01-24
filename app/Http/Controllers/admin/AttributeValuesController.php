<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Attributes;
use App\Models\admin\AttributeValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AttributeValuesController extends Controller
{
   use Message_Trait;

    public function index($id)
    {
        $attribute = Attributes::findOrFail($id);
        $attribute_values = AttributeValues::where('attribute_id',$attribute['id'])->get();
        return view('admin.AttributeValues.index', compact('attribute_values','attribute'));
    }

    public function store(Request $request,$id)
    {
        $attribute = Attributes::findOrFail($id);
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'value' => 'required'
            ];
            $messages = [
                'value.required' => 'من فضلك ادخل اسم السمة'
            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $attribute_value = new AttributeValues();
            $attribute_value->value = $data['value'];
            $attribute_value->attribute_id = $attribute['id'];
            $attribute_value->save();
            return $this->success_message('  تم اضافة متغير جديد بنجاح   ');
        }
    }

    public function update(Request $request, $id)
    {
        $attribute_value = AttributeValues::findOrFail($id);
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'value' => 'required'
            ];
            $messages = [
                'value.required' => 'من فضلك ادخل اسم المتغير '
            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $attribute_value->update([
                'value' => $data['value'],
            ]);
            return $this->success_message(' تم تعديل المتغير بنجاح  ');
        }
    }

    public function delete($id)
    {
        $attribute = AttributeValues::findOrFail($id);
        $attribute->delete();
        return $this->success_message(' تم حذف المتغير  ');
    }

}
