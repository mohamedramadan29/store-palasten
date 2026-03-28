@extends('front.layouts.master')
@section('title')
      سلة الشراء
@endsection

@section('content')
    <div class="page_content">
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
        <!-- page-title -->
        <div class="tf-page-title">
            <div class="container-full">
                <div class="heading text-center"> سلة الشراء</div>
            </div>
        </div>
        <!-- /page-title -->

        <!-- page-cart -->
        <section class="flat-spacing-11">
            <div class="container" id="cart-container">
                @if($cartcount > 0 )
                    @php
                        $subtotal = 0;
                        foreach($cartItems as $item) {
                            $subtotal += ($item['price'] * $item['qty']);
                        }
                        
                        $cityThreshold = $selectedCity ? $selectedCity->free_shipping_threshold : null;
                        $globalThreshold = $publicSetting ? $publicSetting->global_free_shipping_threshold : null;
                        
                        // Pick the active threshold (whichever is lower and > 0)
                        $thresholds = array_filter([$cityThreshold, $globalThreshold], function($v) { return $v > 0; });
                        $threshold = !empty($thresholds) ? min($thresholds) : null;
                        
                        $remaining = $threshold ? $threshold - $subtotal : 0;
                        $percent = $threshold ? min(100, ($subtotal / $threshold) * 100) : 0;
                    @endphp

                    <div id="free-shipping-threshold-wrapper" style="{{ $threshold ? '' : 'display:none;' }}">
                        <div class="tf-free-shipping-bar pb-20">
                            <div class="progress-bar-container" style="position: relative; width: 100%; height: 12px; background: #f0f0f0; border-radius: 20px; margin-bottom: 25px;">
                                <div class="progress-bar-fill" style="width: {{ $percent }}%; height: 100%; background: #28a745; border-radius: 20px; transition: width 0.3s ease;"></div>
                                <div class="progress-star" style="position: absolute; left: calc({{ $percent }}% - 15px); top: -10px; width: 32px; height: 32px; background: #fff; border: 2px solid #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10; transition: left 0.3s ease;">
                                    <i class="bi bi-star-fill" style="color: #28a745; font-size: 16px;"></i>
                                </div>
                            </div>
                            <div class="free-shipping-text text-center fw-6" style="color: #333; font-size: 15px;">
                                <i class="bi bi-box-seam me-2" style="color: #666;"></i>
                                @if($remaining > 0)
                                    أضف مشتريات بقيمة <span class="remaining-amount" style="color: #28a745;">{{ number_format($remaining, 2) }}</span> {{ $storeCurrency }} للحصول على شحن مجاني
                                @else
                                    <span style="color: #28a745;">لقد حصلت على شحن مجاني!</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @php
                        $anyCityHasThreshold = $shippingCity->contains(function($city) {
                            return ($city->free_shipping_threshold ?? 0) > 0;
                        });
                        $anyThresholdExists = ($globalThreshold > 0) || $anyCityHasThreshold;
                    @endphp

                    @if($anyThresholdExists)
                    <div class="mb-4">
                        <label for="cart-shipping-city" class="fw-6 mb-2">
                            @if($globalThreshold > 0)
                                مدينة الشحن:
                            @else
                                اختر المدينة لمعرفة حد الشحن المجاني:
                            @endif
                        </label>
                        <select id="cart-shipping-city" class="form-select w-100" data-global-threshold="{{ $globalThreshold }}">
                            <option value="">-- اختر المدينة --</option>
                            @foreach($shippingCity as $city)
                                <option value="{{ $city->id }}" {{ (isset($selectedCity) && $selectedCity->id == $city->id) ? 'selected' : '' }} data-threshold="{{ $city->free_shipping_threshold }}">
                                    {{ $city->city }} 
                                    @if(($city->free_shipping_threshold ?? 0) > 0)
                                        (الشحن المجاني فوق {{ number_format($city->free_shipping_threshold, 0) }} {{ $storeCurrency }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="tf-page-cart-wrap">
                        <div class="tf-page-cart-item">
                            <table class="tf-table-page-cart">
                                <thead>
                                <tr>
                                    <th> المنتج</th>
                                    <th> السعر</th>
                                    <th> الكمية</th>
                                    <th> المجموع</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $subtotal = 0 ; @endphp
                                @foreach($cartItems as $item)
                                    @php  $subtotal = $subtotal + ($item['price'] * $item['qty']) @endphp
                                    <tr class="tf-cart-item file-delete">
                                        <td class="tf-cart-item_product">
                                            <a href="{{url('product/'.$item->productdata->slug)}}" class="img-box">
                                                <img
                                                    src="{{asset('assets/uploads/product_images/'.($item->variation->image ?? $item->productdata->image))}}"
                                                    alt="img-product">
                                            </a>
                                            <div class="cart-info">
                                                <a href="{{url('product/'.$item['productdata']['slug'])}}"
                                                   class="cart-title link"> {{$item['productdata']['name']}} </a>
                                                @if($item->product_variation_id !=null)
                                                    @php
                                                        $variationValues = \App\Models\admin\VartionsValues::with('attribute')->where('product_variation_id', $item->product_variation_id)->get();
                                                    @endphp
                                                    <div class="cart-meta-variant">
                                                        @foreach($variationValues as $value)
                                                            {{ $value->attribute->name }}: {{ $value->attribute_value_name }}@if(!$loop->last) - @endif
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <form method="post" action="{{url('cart/delete/'.$item['id'])}}">
                                                    @csrf
                                                    <input type="hidden" name="item_id" value="{{$item['id']}}">
                                                    <button type="submit" class="remove-cart"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="tf-cart-item_price" cart-data-title="السعر ">
                                            <div class="cart-price"
                                                 data-id="{{ $item['id'] }}">  {{$item['price']}} {{ $storeCurrency }} </div>
                                        </td>
                                        <td class="tf-cart-item_quantity" cart-data-title="الكمية ">
                                            <div class="cart-quantity">
                                                <div class="wg-quantity">
                                                    <span class="btn-quantity minus-btn" data-id="{{$item['id']}}">
                                                        <svg class="d-inline-block" width="9" height="1"
                                                             viewBox="0 0 9 1" fill="currentColor">
                                                            <path
                                                                d="M9 1H5.14286H3.85714H0V1.50201e-05H3.85714L5.14286 0L9 1.50201e-05V1Z"></path>
                                                        </svg>
                                                    </span>

                                                    <input type="number" name="number" data-id="{{ $item['id'] }}"
                                                           value="{{ $item['qty'] }}" min="1">
                                                    <span class="btn-quantity plus-btn" data-id="{{$item['id']}}">
                                                        <svg class="d-inline-block" width="9" height="9"
                                                             viewBox="0 0 9 9" fill="currentColor">
                                                            <path
                                                                d="M9 5.14286H5.14286V9H3.85714V5.14286H0V3.85714H3.85714V0H5.14286V3.85714H9V5.14286Z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="tf-cart-item_total" cart-data-title="المجموع ">
                                            <div class="cart-total" data-id="{{ $item['id'] }}">
                                                {{ number_format($item['qty'] * $item['price'],2)}} {{ $storeCurrency }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tf-page-cart-footer">
                            <div class="tf-cart-footer-inner">
                                <div class="tf-page-cart-checkout">
                                    <div class="tf-cart-totals-discounts">
                                        <h3> المجموع الفرعي </h3>
                                        <span class="total-value">
                                            {{$subtotal}} {{ $storeCurrency }}  </span>
                                    </div>
                                    @if(Session::has('coupon_amount'))
                                    <div class="order-total tf-cart-totals-discounts">
                                                    <h3 class="title">
                                                         المجموع بعد الخصم :
                                                    </h3>
                                        <span class="total-price grand_total total-value">
                                      @if(Session::has('coupon_amount'))
                                                {{$subtotal - Session::get('coupon_amount')}} <span> {{ $storeCurrency }} </span>
                                                    </span>
                                        @else
                                            <span class="total-price grand_total">

                                        {{$subtotal}} <span> {{ $storeCurrency }} </span>
                                                    </span>
                                        @endif
                                    </div>
                                    @endif
                                    <form id="applycoupon" method="post" action="javascript:void(0);">
                                        @csrf
                                        <div class="coupon-box pb-20 pt-5 d-flex justify-content-between">
                                            <input id="code" name="code" type="text" placeholder=" كود خصم ">
                                            <button type="submit"
                                                    class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn">
                                                تطبيق
                                            </button>
                                        </div>
                                    </form>

                                    <div class="order-total">
                                        <br>
                                        @if(Session::has('coupon_amount'))
                                          <div class="d-flex justify-content-between">
                                              <h6 class="title total-value">
                                                  قيمه الخصم :
                                              </h6>
                                              <span class="total-price discountAmount" style="color: red">
                                                      {{Session::get("coupon_amount")}} <span> {{ $storeCurrency }}  </span>
                                                    </span>
                                          </div>
                                        @endif
                                    </div>

                                    <div class="cart-checkout-btn">
                                        <a href="{{url('checkout')}}"
                                           class="tf-btn w-100 btn-fill animate-hover-btn radius-3 justify-content-center">
                                            <span> اتمام الطلب  </span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="tf-page-cart text-center mt_140 mb_200">
                        <h5 class="mb_24"> سلة المشتريات فارغة </h5>
                        <p class="mb_24"> يمكنك الاطلاع على جميع المنتجات المتوفرة وشراء بعضها في المتجر </p>
                        <a href="{{url('shop')}}" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn">
                            الرجوع الي المتجر <i class="icon icon-arrow1-top-left"></i></a>
                    </div>
                @endif

            </div>
        </section>
    </div>
    <!-- page-cart -->
@endsection


@section('js')
    <script>
        $(document).ready(function () {
            // زيادة الكمية عند الضغط على الزر +
            $('.plus-btn').off('click').on('click', function (e) {
                e.preventDefault(); // منع السلوك الافتراضي
                let itemId = $(this).data('id');
                let inputField = $('input[name="number"][data-id="' + itemId + '"]');
                let newQuantity = parseInt(inputField.val()) + 1;
                updateCart(itemId, newQuantity);
            });

            // نقصان الكمية عند الضغط على الزر -
            $('.minus-btn').off('click').on('click', function (e) {
                e.preventDefault(); // منع السلوك الافتراضي
                let itemId = $(this).data('id');
                let inputField = $('input[name="number"][data-id="' + itemId + '"]');
                let newQuantity = parseInt(inputField.val()) - 1;
                if (newQuantity > 0) {
                    updateCart(itemId, newQuantity);
                }
            });

            // تحديث الكمية عند كتابة المستخدم كمية مباشرة في حقل الإدخال
            $('input[name="number"]').off('input').on('input', function (e) {
                let itemId = $(this).data('id');
                let newQuantity = parseInt($(this).val());

                // التأكد من أن القيمة المدخلة صحيحة وأن الكمية أكبر من 0
                if (!isNaN(newQuantity) && newQuantity > 0) {
                    updateCart(itemId, newQuantity);
                }
            });

            // تحديث الكمية في السلة
            function updateCart(itemId, newQuantity) {
                $.ajax({
                    url: '/cart/update',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}", // تأكيد الحماية ضد CSRF
                        "item_id": itemId,
                        "quantity": newQuantity
                    },
                    success: function (response) {
                        // تحديث الكميات والأسعار بناءً على الاستجابة
                        $('input[name="number"][data-id="' + itemId + '"]').val(newQuantity);

                        // تحديث المجموع لكل منتج
                        $('.tf-cart-item_total .cart-total[data-id="' + itemId + '"]').text(response.itemTotal.toFixed(2) + ' {{ $storeCurrency }}');

                        // تحديث المجموع الفرعي (Subtotal)
                        $('.total-value').text(response.subtotal.toFixed(2) + ' {{ $storeCurrency }}');
                        
                        updateProgressBar(response.subtotal);
                    },
                    error: function (xhr) {
                        console.log('Error updating cart');
                    }
                });
            }

            $('#cart-shipping-city').on('change', function() {
                let cityId = $(this).val();
                let cityThreshold = parseFloat($(this).find(':selected').data('threshold')) || 0;
                let globalThreshold = parseFloat($(this).data('global-threshold')) || 0;
                
                let thresholds = [cityThreshold, globalThreshold].filter(v => v > 0);
                let threshold = thresholds.length > 0 ? Math.min(...thresholds) : 0;

                $.ajax({
                    url: '/save-city-session',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "city_id": cityId
                    },
                    success: function(response) {
                        if (threshold > 0) {
                            $('#free-shipping-threshold-wrapper').show();
                            let subtotal = parseFloat($('.total-value').first().text().replace(/[^\d.]/g, ''));
                            updateProgressBar(subtotal);
                        } else {
                            $('#free-shipping-threshold-wrapper').hide();
                        }
                    }
                });
            });

            function updateProgressBar(subtotal) {
                let cityThreshold = parseFloat($('#cart-shipping-city option:selected').data('threshold')) || 0;
                let globalThreshold = parseFloat($('#cart-shipping-city').data('global-threshold')) || 0;
                
                let thresholds = [cityThreshold, globalThreshold].filter(v => v > 0);
                let threshold = thresholds.length > 0 ? Math.min(...thresholds) : 0;

                if (!threshold || threshold <= 0) {
                    $('#free-shipping-threshold-wrapper').hide();
                    return;
                }
                
                $('#free-shipping-threshold-wrapper').show();
                let remaining = threshold - subtotal;
                let percent = Math.min(100, (subtotal / threshold) * 100);
                
                $('.progress-bar-fill').css('width', percent + '%');
                $('.progress-star').css('left', 'calc(' + percent + '% - 15px)');
                
                if (remaining > 0) {
                    $('.free-shipping-text').html('<i class="bi bi-box-seam me-2" style="color: #666;"></i> أضف مشتريات بقيمة <span class="remaining-amount" style="color: #28a745;">' + remaining.toFixed(2) + '</span> {{ $storeCurrency }} للحصول على شحن مجاني');
                } else {
                    $('.free-shipping-text').html('<i class="bi bi-box-seam me-2" style="color: #666;"></i> <span style="color: #28a745;">لقد حصلت على شحن مجاني!</span>');
                }
            }
        });
    </script>

    <script>
        // Apply Coupon Code
        $("#applycoupon").submit(function () {
            var code = $("#code").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/apply_coupon',
                data: {code: code},
                success: function (resp) {
                    if (resp.message != '') {
                        alert(resp.message);
                        if (resp.coupon_amount > 0) {
                            $(".discountAmount").text(resp.coupon_amount + "ر.س");
                        } else {
                            $(".discountAmount").text(" 0 ر.س");
                        }
                        if (resp.grand_total > 0) {
                            $(".grand_total").text(resp.grand_total + "ر.س");
                        }
                        // إعادة تحميل الصفحة بعد تطبيق الكوبون
                        location.reload();
                    }

                }, error: function () {
                    alert('error');
                }
            });
        })
    </script>

@endsection
