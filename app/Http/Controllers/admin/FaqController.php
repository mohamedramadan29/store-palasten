<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $faqs = Faq::all();
        return view('admin.Faqs.index', compact('faqs'));
    }


    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'title' => 'required',
                    'content' => 'required',
                ];
                $messages = [
                    'title.required' => ' من فضلك ادخل السوال  ',
                    'content.required' => ' من فضلك ادخل الاجابة   ',
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $faq = new Faq();
                $faq->create([
                    'title' => $data['title'],
                    'content' => $data['content'],
                ]);
                return $this->success_message(' تم اضافة السوال بنجاح  ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.Faqs.add');
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'title' => 'required',
                    'content' => 'required',
                ];
                $messages = [
                    'title.required' => ' من فضلك ادخل السوال  ',
                    'content.required' => ' من فضلك ادخل الاجابة   ',
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $faq->update([
                    'title' => $data['title'],
                    'content' => $data['content'],
                ]);
                return $this->success_message(' تم تعديل السوال بنجاح  ');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.Faqs.update', compact('faq'));
    }

    public function delete($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return $this->success_message(' تم حذف السوال   ');
    }
}
