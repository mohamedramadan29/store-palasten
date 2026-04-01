@extends('front.layouts.master')

@section('title')
    الملف الشخصي للمسوق
@endsection

@section('content')
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">الملف الشخصي</div>
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

                    <form action="{{ route('marketer.profile.update') }}" method="post" class="form-login">
                        @csrf
                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="text" id="name" name="name" value="{{ old('name', $marketer->name) }}" required>
                            <label class="tf-field-label" for="name">الاسم بالكامل</label>
                        </div>

                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="email" id="email" name="email" value="{{ $marketer->email }}" disabled>
                            <label class="tf-field-label" for="email">البريد الإلكتروني (غير قابل للتعديل)</label>
                        </div>

                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="tel" id="phone" name="phone" value="{{ old('phone', $marketer->phone) }}" required>
                            <label class="tf-field-label" for="phone">رقم الهاتف</label>
                        </div>

                        <div class="mt-4 mb-3 border-top pt-3">
                            <h6 class="fw-6">تغيير كلمة المرور (اختياري)</h6>
                        </div>

                        <div class="tf-field style-1 mb_15">
                            <input class="tf-field-input tf-input" placeholder=" " type="password" id="password" name="password">
                            <label class="tf-field-label" for="password">كلمة المرور الجديدة</label>
                        </div>

                        <div class="tf-field style-1 mb_30">
                            <input class="tf-field-input tf-input" placeholder=" " type="password" id="password_confirmation" name="password_confirmation">
                            <label class="tf-field-label" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                        </div>

                        <div class="bottom">
                            <div class="w-100">
                                <button type="submit" class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">حفظ التغييرات</button>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('marketer.dashboard') }}" class="text-primary fw-6">العودة للوحة التحكم</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
