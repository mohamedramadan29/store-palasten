<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $reviews = Review::all();
        return view('admin.Reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')){
            try {

                $data = $request->all();

               // dd($data);
                $rules = [
                    'rating'=>'required',
                    'name'=>'required',
                    'content'=>'required'
                ];
                $messages = [
                    'rating.required'=>' من فضلك ادخل التقيم  ',
                    'rating.name'=>' من فضلك ادخل الاسم   ',
                    'content.required'=>' من فضلك ادخل التقيم  ',
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {

                    return Redirect::back()->withInput()->withErrors($validator);
                }

                $review = new Review();
                $review->create([
                    'name' => $data['name'],
                    'description' => $data['content'],
                    'star'=>$data['rating']
                ]);

                return $this->success_message(' تم اضافة التقيم بنجاح  ');

            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }

        return view('admin.Reviews.add');

    }


    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        if ($request->isMethod('post')){
            try {
                $data = $request->all();

                $rules = [];
                $messages = [];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $review->update([
                    'name' => $data['name'],
                    'description' => $data['content'],
                ]);

                return $this->success_message(' تم تعديل  التقيم بنجاح  ');

            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }

        return view('admin.Reviews.update',compact('review'));

    }

    public function delete($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return $this->success_message(' تم حذف التقيم بنجاح  ');
    }

}
