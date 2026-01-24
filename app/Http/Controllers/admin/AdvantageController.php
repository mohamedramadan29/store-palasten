<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Advantage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdvantageController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $advantages = Advantage::all();
        return view('admin.Advantage.index',compact('advantages'));
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')){
            $data = $request->all();
            $rules = [];
            $messages = [];
            $validator = Validator::make($data,$rules,$messages);
            if ($validator->fails()){
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $advantage = new Advantage();
            $advantage->create([
                'name'=>$data['name'],
                'description'=>$data['description'],
                'icon'=>$data['icon']
            ]);
            return $this->success_message(' تمت اضافة الميزة بنجاح  ');
        }
        return view('admin.Advantage.add');
    }

    public function update(Request $request,$id)
    {
        $advantage = Advantage::where('id',$id)->first();
        if ($request->isMethod('post')){
            $data = $request->all();
            $rules = [];
            $messages = [];
            $validator = Validator::make($data,$rules,$messages);
            if ($validator->fails()){
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $advantage->update([
                'name'=>$data['name'],
                'description'=>$data['description'],
                'icon'=>$data['icon']
            ]);
            return $this->success_message(' تمت تعديل  الميزة بنجاح  ');
        }
        return view('admin.Advantage.update',compact('advantage'));
    }

    public function delete($id)
    {
        $adv = Advantage::findOrFail($id);
        $adv->delete();
        return $this->success_message(' تم حذف الميزة بنجاح   ');
    }
}
