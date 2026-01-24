<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Slug_Trait;
use App\Models\admin\Attributes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AttributesController extends Controller
{
    use Message_Trait;
    use Slug_Trait;

    public function index()
    {
        $attributes = Attributes::all();
        return view('admin.Attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'name' => 'required'
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل اسم السمة'
            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $attribute = new Attributes();
            $attribute->name = $data['name'];
            $attribute->slug = $this->CustomeSlug($data['name']);
            $attribute->save();
            return $this->success_message(' تم اضافة سمة جديدة بنجاح  ');
        }
    }

    public function update(Request $request, $id)
    {
        $attribute = Attributes::findOrFail($id);
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'name' => 'required'
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل اسم السمة'
            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $attribute->update([
                'name' => $data['name'],
                'slug' => $this->CustomeSlug($data['name'])
            ]);
            return $this->success_message(' تم تعديل السمة بنجاح  ');
        }
    }



    public function delete($id)
    {
        $attribute = Attributes::findOrFail($id);
        $attribute->delete();
        return $this->success_message(' تم حذف السمة  ');
    }
}
