@extends('front.layouts.master')

@section('title')
    تسجيل مسوق جديد
@endsection

@section('content')
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">انضم إلينا كمسوق</div>
    </div>
</div>

<section class="flat-spacing-10">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="tf-login-form">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('marketer.register.submit') }}" method="post" class="form-login">
                        @csrf
                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="text" id="name" name="name" value="{{ old('name') }}" required>
                            <label class="tf-field-label" for="name">الاسم بالكامل</label>
                        </div>

                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="email" id="email" name="email" value="{{ old('email') }}" required>
                            <label class="tf-field-label" for="email">البريد الإلكتروني</label>
                        </div>

                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                            <label class="tf-field-label" for="phone">رقم الهاتف</label>
                        </div>

                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="password" id="password" name="password" required>
                            <label class="tf-field-label" for="password">كلمة المرور</label>
                        </div>

                        <div class="tf-field style-1 mb_30">
                            <input class="tf-field-input tf-input" placeholder=" " type="password" id="password_confirmation" name="password_confirmation" required>
                            <label class="tf-field-label" for="password_confirmation">تأكيد كلمة المرور</label>
                        </div>

                        <div class="bottom">
                            <div class="w-100">
                                <button type="submit" class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">تسجيل الآن</button>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            لديك حساب بالفعل؟ <a href="{{ route('marketer.login') }}" class="text-primary fw-6">تسجيل الدخول</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
