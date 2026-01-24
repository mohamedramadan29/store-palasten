@extends('admin.layouts.master')
@section('title')
    الالوان العامة للموقع
@endsection
@section('css')
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form method="post" action="{{url('admin/colors/update')}}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-xl-12 col-lg-12 ">
                        @if (Session::has('Success_message'))
                            @php
                                toastify()->success(\Illuminate\Support\Facades\Session::get('Success_message'));
                            @endphp
                        @endif
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                @php
                                    toastify()->error($error);
                                @endphp
                            @endforeach
                        @endif
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> الالوان العامة للموقع </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="website_background" class="form-label"> لون خلفية المتجر </label>
                                        <div class="input-group">
                                            <input type="color" id="website_background" class="form-control form-control-color" 
                                                   name="website_background"
                                                   value="#{{ ltrim($colors['website_background'] ?? '#ffffff', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['website_background'] ?? '#ffffff', '#')) }}" 
                                                   data-target="website_background" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="top_navbar_background" class="form-label"> خلفية الشريط الاعلاني </label>
                                        <div class="input-group">
                                            <input type="color" id="top_navbar_background" class="form-control form-control-color" 
                                                   name="top_navbar_background"
                                                   value="#{{ ltrim($colors['top_navbar_background'] ?? '#ffffff', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['top_navbar_background'] ?? '#ffffff', '#')) }}" 
                                                   data-target="top_navbar_background" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="second_navbar_background" class="form-label"> خلفية شريط البحث </label>
                                        <div class="input-group">
                                            <input type="color" id="second_navbar_background" class="form-control form-control-color" 
                                                   name="second_navbar_background"
                                                   value="#{{ ltrim($colors['second_navbar_background'] ?? '#ffffff', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['second_navbar_background'] ?? '#ffffff', '#')) }}" 
                                                   data-target="second_navbar_background" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="third_navbar_background" class="form-label"> خلفية النافبار </label>
                                        <div class="input-group">
                                            <input type="color" id="third_navbar_background" class="form-control form-control-color" 
                                                   name="third_navbar_background"
                                                   value="#{{ ltrim($colors['third_navbar_background'] ?? '#ffffff', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['third_navbar_background'] ?? '#ffffff', '#')) }}" 
                                                   data-target="third_navbar_background" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="main_title_color" class="form-label"> اللون الاساسي للعناوين </label>
                                        <div class="input-group">
                                            <input type="color" id="main_title_color" class="form-control form-control-color" 
                                                   name="main_title_color"
                                                   value="#{{ ltrim($colors['main_title_color'] ?? '#000000', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['main_title_color'] ?? '#000000', '#')) }}" 
                                                   data-target="main_title_color" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="all_button_background" class="form-label"> لون خلفية عرض الكل </label>
                                        <div class="input-group">
                                            <input type="color" id="all_button_background" class="form-control form-control-color" 
                                                   name="all_button_background"
                                                   value="#{{ ltrim($colors['all_button_background'] ?? '#ffffff', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['all_button_background'] ?? '#ffffff', '#')) }}" 
                                                   data-target="all_button_background" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="main_price_color" class="form-label"> لون السعر الاساسي </label>
                                        <div class="input-group">
                                            <input type="color" id="main_price_color" class="form-control form-control-color" 
                                                   name="main_price_color"
                                                   value="#{{ ltrim($colors['main_price_color'] ?? '#000000', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['main_price_color'] ?? '#000000', '#')) }}" 
                                                   data-target="main_price_color" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="public_add_to_cart_background" class="form-label"> لون خلفية زر اضف الي السلة </label>
                                        <div class="input-group">
                                            <input type="color" id="public_add_to_cart_background" class="form-control form-control-color" 
                                                   name="public_add_to_cart_background"
                                                   value="#{{ ltrim($colors['public_add_to_cart_background'] ?? '#ffffff', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['public_add_to_cart_background'] ?? '#ffffff', '#')) }}" 
                                                   data-target="public_add_to_cart_background" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="public_add_to_cart_color" class="form-label"> لون نص زر اضف الي السلة </label>
                                        <div class="input-group">
                                            <input type="color" id="public_add_to_cart_color" class="form-control form-control-color" 
                                                   name="public_add_to_cart_color"
                                                   value="#{{ ltrim($colors['public_add_to_cart_color'] ?? '#000000', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['public_add_to_cart_color'] ?? '#000000', '#')) }}" 
                                                   data-target="public_add_to_cart_color" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="footer_background" class="form-label"> لون خلفية الفوتر </label>
                                        <div class="input-group">
                                            <input type="color" id="footer_background" class="form-control form-control-color" 
                                                   name="footer_background"
                                                   value="#{{ ltrim($colors['footer_background'] ?? '#ffffff', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['footer_background'] ?? '#ffffff', '#')) }}" 
                                                   data-target="footer_background" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-3">
                                        <label for="footer_color" class="form-label"> لون نص الفوتر </label>
                                        <div class="input-group">
                                            <input type="color" id="footer_color" class="form-control form-control-color" 
                                                   name="footer_color"
                                                   value="#{{ ltrim($colors['footer_color'] ?? '#000000', '#') }}">
                                            <input type="text" class="form-control hex-input" 
                                                   value="#{{ strtoupper(ltrim($colors['footer_color'] ?? '#000000', '#')) }}" 
                                                   data-target="footer_color" style="max-width: 110px;">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light mb-3 rounded">
                        <div class="row justify-content-end g-2">
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-outline-secondary w-100"> حفظ <i class='bx bxs-save'></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Container Fluid -->
    </div>
    <!-- End Page Content -->
@endsection

@section('js')
    <script>
        // عند تغيير اللون بالـ picker → تحديث الحقل النصي
        document.querySelectorAll('input[type="color"]').forEach(function(colorInput) {
            colorInput.addEventListener('input', function() {
                const hexInput = this.parentElement.querySelector('.hex-input');
                if (hexInput) {
                    hexInput.value = this.value.toUpperCase();
                }
            });
        });

        // عند كتابة أو تعديل الكود في الحقل النصي → تحديث الـ color picker
        document.querySelectorAll('.hex-input').forEach(function(hexInput) {
            hexInput.addEventListener('input', function() {
                let value = this.value.trim().toLowerCase();

                // إزالة أي شيء غير مسموح (غير أرقام و a-f و #)
                value = value.replace(/[^0-9a-f#]/g, '');

                // التأكد من وجود # في البداية
                if (value.length > 0 && value[0] !== '#') {
                    value = '#' + value;
                }

                // قص إلى 7 أحرف فقط (# + 6 hex)
                if (value.length > 7) {
                    value = value.substring(0, 7);
                }

                // تحديث القيمة في الحقل النصي (بعد التنظيف)
                this.value = value.toUpperCase();

                // إذا كان الكود صالح (7 أحرف) → تحديث الـ color input
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    const targetId = this.getAttribute('data-target');
                    const colorInput = document.getElementById(targetId);
                    if (colorInput) {
                        colorInput.value = value;
                    }
                }
            });
        });
    </script>
@endsection