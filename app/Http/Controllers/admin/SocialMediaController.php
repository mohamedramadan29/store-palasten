<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    use Message_Trait;

    public function update(Request $request)
    {

        $socials = SocialMedia::first();
        if ($request->isMethod('post')) {
            $data = $request->all();
            $socials->update([
                'facebook' => $data['facebook'],
                'instagram' => $data['instagram'],
                'linkedin' => $data['linkedin'],
                'x-twitter' => $data['x-twitter'],
                'youtube' => $data['youtube'],
                'whatsapp' => $data['whatsapp'],
                'pinterest' => $data['pinterest'],
                'tiktok' => $data['tiktok'],
                'snapchat' => $data['snapchat'],
                'telegram' => $data['telegram'],
            ]);
            return $this->success_message(' تم اضافة منصات التواصل الخاصة بك بنجاح  ');
        }
        return view('admin.SocialMedia.update', compact('socials'));
    }
}
