<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.customer.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'email.required' => 'حقل البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل',
            'phone.required' => 'حقل الهاتف مطلوب',
            'password.required' => 'حقل كلمة المرور مطلوب',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Add role field to distinguish customers
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'تم إنشاء حسابك بنجاح!');
    }

    public function showLogin()
    {
        return view('auth.customer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'حقل البريد الإلكتروني مطلوب',
            'password.required' => 'حقل كلمة المرور مطلوب',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->route('customer.dashboard');
        }

        return redirect()->back()
            ->with('error', 'بيانات الدخول غير صحيحة');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('customer.login')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
