@extends('front.layouts.offer_master')
@section('title')
    {{$offer['product_name']}}
@endsection
<style>
    footer {
        display: none !important;
    }
</style>
@section('content')
    <div class="page_content offer_page">
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

            @php
                $settings = \App\Models\admin\PublicSetting::select('website_logo')->first();
            @endphp
        <div class="container ">
            <div class="data">
                <div class="logo">
                    <img style="max-width: 120px" src="{{asset('assets/uploads/PublicSetting/'.$settings->website_logo)}}" alt="">
                </div>
                <div class="advantage">
                    <div class="adv">
                        <i class="fas fa-truck"></i>
                        <h4> شحن سريع </h4>
                    </div>
                    <div class="adv">
                        <i class="fas fa-hand-holding-usd"></i>
                        <h4> الدفع عند الاستلام </h4>
                    </div>
                    <div class="adv">
                        <i class="fas fa-box"></i>
                        <h4> منتج اصلي </h4>
                    </div>
                </div>
                <div class="product_info">
                    <h2>  {{$offer['product_name']}} </h2>
                    <img src="{{asset('assets/uploads/product_offers/'.$offer['image'])}}">
                </div>
                <div class="order_info">
                    <h6> اجمالي الطلب </h6>
                    <table class="table table-bordered table-active">
                        <thead>
                        <tr>
                            <th> سعر المنتج</th>
                            <th> تكلفة التوصيل</th>
                            <th> الاجمالي</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="total-products"
                                style="color:  #D46D5C; font-weight: bold"> {{$offer['price']}} {{ $storeCurrency }} </td>
                            <td class="shipping-price" style="color:  #D46D5C; font-weight: bold"> حدد المدينة</td>
                            <td class="grand-total" style="color:  #D46D5C; font-weight: bold"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="order_form">

                    <form action="{{url('offer_order/store/'.$offer['id'])}}" method="post" class="form-checkout tf-page-cart-checkout widget-wrap-checkout">
                        <h6> للطلب املئ الاستمارة أدناه : </h6>
                        @csrf
                        <div class="box">
                            <fieldset class="fieldset">
                                <label for="name"> الاسم </label>
                                <input type="text" id="name" placeholder="" name="name" required
                                       value="{{old('name')}}">
                            </fieldset>

                            <fieldset class="box fieldset">
                                <label for="phone"> رقم الهاتف </label>
                                <input type="number" id="phone" name="phone" required value="{{old('phone')}}">
                            </fieldset>
                            <fieldset class="box fieldset">
                                <label for="country"> حدد المدينة </label>
                                <div class="select-custom">
                                    <select required class="form-select w-100" id="shippingcity" name="shippingcity">
                                        <option value="" disabled selected> -- حدد --</option>
                                        @foreach($shippingCity as $city)
                                            <option
                                                {{old('shippingcity') == $city['id'] ? 'selected' : ''}} value="{{$city['id']}}">{{$city['city']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset class="box fieldset">
                                <label for="address"> العنوان كاملا ( مدينة - حي - عنوان تفصيلي ) </label>
                                <textarea required name="address" id="address">{{old('address')}}</textarea>
                            </fieldset>
                            <p> - تطبق الشروط والأحكام </p>
                            <br>
                            <input required type="hidden" id="grand_total" name="grand_total" value="">
                            <input required type="hidden" id="shipping-price" name="shipping_price" value="">
                            <button style="display: block;width:100%"
                                    class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center">
                                اتمام الطلب
                            </button>
                        </div>


                    </form>

                </div>
                <div class="last_section">
                    <p>
                        عند تسجيلك للطلب سنقوم بتحضيره بكل الحب
                        <br>
                        وإرساله لك في أسرع وقت
                    </p>
                    <p> تسوق إلكتروني آمن ١٠٠٪ </p>
                    <div class="logos">
                        <div>
                            <img src="{{asset('assets/front/images/img1.svg')}}" alt="">
                        </div>
                        <div>
                            <img src="{{asset('assets/front/images/img2.svg')}}" alt="">
                        </div>
                        <div>
                            <img src="{{asset('assets/front/images/1.svg')}}" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#shippingcity').on('change', function () {
                var cityId = $(this).val(); // احصل على ID المدينة المحددة
                if (cityId) {
                    $.ajax({
                        url: '/get-shipping-price', // رابط API لجلب سعر الشحن
                        type: 'GET',
                        data: {city_id: cityId},
                        success: function (response) {
                            // تحديث قيمة الشحن في الواجهة
                            $('.shipping-price').text(response.price + ' {{ $storeCurrency }}');

                            // حساب المجموع الكلي: مجموع المنتجات + قيمة الشحن
                            var subtotal = parseFloat($('.total-products').text().replace(',', ''));
                            var shippingPrice = parseFloat(response.price);

                            // جلب قيم الخصم وقيمة الشحن
                            var couponAmount = parseFloat($('#coupon_amount').val()) || 0;
                            var shipping_price_input = document.getElementById('shipping-price');
                            shipping_price_input.value = shippingPrice;

                            // حساب المجموع الكلي
                            var grandTotal = subtotal + shippingPrice;
                            $('.grand-total').text(grandTotal.toFixed(2) + ' {{ $storeCurrency }}');
                            var grand_total = document.getElementById('grand_total');
                            grand_total.value = grandTotal;
                        },
                        error: function () {
                            alert('خطأ أثناء جلب سعر الشحن');
                        }
                    });
                }
            });
        });


    </script>

@endsection
