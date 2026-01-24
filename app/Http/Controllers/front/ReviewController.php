<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use Message_Trait;

    public function review(Request $request)
    {
        if ($request->isMethod('post')){
            try {

                $data = $request->all();
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

                return $this->success_message(' شكرا لك ، تم اضافة تقيمك  بنجاح  ');

            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('front.review');
    }
}
