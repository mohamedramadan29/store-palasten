@extends('admin.layouts.login_master')
@section('content')

<div class="min-vh-100 d-flex align-items-center justify-content-center position-relative overflow-hidden"
     style="background: linear-gradient(135deg, #f0f7ff 0%, #d8ebff 100%)">

    <div class="w-100" style="max-width: 380px;">

        <div class="card border-0 shadow-lg rounded-4">

            <div class="text-center pt-5 pb-3 bg-white">
                @php $setting = \App\Models\admin\PublicSetting::first(); @endphp
                <img src="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}" 
                     width="110" class="img-fluid" alt="Lam3.ps">
            </div>

            <div class="card-body p-4">

                @if(\Illuminate\Support\Facades\Session::has('Error_Message'))
                    <div class="alert alert-danger rounded mb-3 small text-center py-2">
                        {{\Illuminate\Support\Facades\Session::get('Error_Message')}}
                    </div>
                @endif

                <form method="post" action="{{route('admin_login')}}" class="authentication-form">
                    @csrf

                    <div class="mb-3">
                        <input type="email" name="email" required autofocus
                               class="form-control rounded text-center py-3"
                               placeholder="البريد الإلكتروني" style="height: 50px;">
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" required
                               class="form-control rounded text-center py-3"
                               placeholder="كلمة المرور" style="height: 50px;">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox-signin">
                            <label class="form-check-label small" for="checkbox-signin">تذكرني</label>
                        </div>
                        <a href="#" class="small text-primary text-decoration-none">نسيت كلمة المرور ؟</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded fw-bold py-3"
                            style="height: 52px; background: #1976d2;">
                        تسجيل الدخول
                    </button>
                </form>

                <div class="text-center mt-4">
                    <small class="text-muted">© {{ date('Y') }} Lam3.ps</small>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection