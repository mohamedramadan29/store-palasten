<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MarketerAuthController extends Controller
{
    public function showRegister()
    {
        $publicSetting = \App\Models\admin\PublicSetting::first();
        if (!$publicSetting || !$publicSetting->marketer_system_status) {
            return redirect('/')->with('error', 'نظام المسوقين غير مفعل حالياً');
        }
        return view('front.marketer.register');
    }

    public function register(Request $request)
    {
        $publicSetting = \App\Models\admin\PublicSetting::first();
        if (!$publicSetting || !$publicSetting->marketer_system_status) {
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|string|max:20',
            'password'              => 'required|string|min:6|confirmed',
        ], [
            'name.required'         => 'من فضلك ادخل الاسم',
            'email.required'        => 'من فضلك ادخل البريد الإلكتروني',
            'email.unique'          => 'هذا البريد الإلكتروني مسجل مسبقاً',
            'phone.required'        => 'من فضلك ادخل رقم الهاتف',
            'password.required'     => 'من فضلك ادخل كلمة المرور',
            'password.min'          => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.confirmed'    => 'كلمة المرور وتأكيدها غير متطابقتين',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'user_type' => 'marketer',
            'status'    => 'inactive', // requires admin approval
        ]);

        return redirect()->route('marketer.login')
            ->with('success', 'تم تسجيل طلبك بنجاح، يرجى انتظار تفعيل الحساب من الإدارة.');
    }

    public function showLogin()
    {
        if (Auth::guard('marketer')->check()) {
            return redirect()->route('marketer.dashboard');
        }
        return view('front.marketer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => 'required|string',
            'password' => 'required',
        ], [
            'login.required'    => 'من فضلك ادخل البريد الإلكتروني أو رقم الهاتف',
            'password.required' => 'من فضلك ادخل كلمة المرور',
        ]);

        // Check if login input is email or phone
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        $user = User::where($loginField, $request->login)->where('user_type', 'marketer')->first();

        if (!$user) {
            $fieldType = $loginField === 'email' ? 'البريد الإلكتروني' : 'رقم الهاتف';
            return redirect()->back()->withInput()->withErrors(['login' => $fieldType . ' غير مسجل كمسوق']);
        }

        if ($user->status !== 'active') {
            return redirect()->back()->withInput()->withErrors(['login' => 'حسابك لم يتم تفعيله بعد من الإدارة']);
        }

        if (Auth::guard('marketer')->attempt([$loginField => $request->login, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('marketer.dashboard');
        }

        return redirect()->back()->withInput()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
    }

    public function logout(Request $request)
    {
        Auth::guard('marketer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('marketer.login');
    }
}
