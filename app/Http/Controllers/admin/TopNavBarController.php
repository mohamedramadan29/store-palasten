<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\ShippingCity;
use App\Models\admin\TopNavBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class TopNavBarController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $navs = TopNavBar::all();
        return view('admin.TopNavBar.index', compact('navs'));
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'content' => 'required',
                ];
                $messages = [
                    'content.required' => '  من فضلك ادخل المحتوي ',
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $nav = new TopNavBar();
                $nav->create([
                    'content' => $data['content'],
                    'link' => $data['link'],
                    'button' => $data['button'],
                    'status' => $data['status'],
                ]);
                return $this->success_message(' تم اضافة المحتوي بنجاح   ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $nav = TopNavBar::findOrFail($id);

        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'content' => 'required',
                ];
                $messages = [
                    'content.required' => '  من فضلك ادخل المحتوي ',
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }

                $nav->update([
                    'content' => $data['content'],
                    'link' => $data['link'],
                    'button' => $data['button'],
                    'status' => $data['status'],
                ]);
                return $this->success_message(' تم  تعديل  المحتوي بنجاح   ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
    }

    public function delete($id)
    {
        $nav = TopNavBar::findOrFail($id);
        $nav->delete();
        return $this->success_message(' تم الحذف بنجاح  ');
    }
}
