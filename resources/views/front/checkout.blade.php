@extends('front.layouts.master')
@section('title')
    اتمام الطلب
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

        <div class="tf-page-title">
            <div class="container-full">
                <div class="text-center heading"> اتمام الطلب</div>
            </div>
        </div>

        <section class="flat-spacing-11">
            <div class="container">
                @if(count($cartitems) > 0)
                <form method="post" action="{{url('order/store')}}"
                      class="form-checkout tf-page-cart-checkout widget-wrap-checkout">
                    @csrf
                    <div class="tf-page-cart-wrap layout-2">
                        <div class="tf-page-cart-item">
                            <h5 class="fw-5 mb_20"> تفاصيل الشحن </h5>

                            <div class="box grid-2">
                                <fieldset class="fieldset">
                                    <label for="name"> الاسم الأول <span class="text-danger">*</span></label>
                                    <input type="text" id="name" placeholder="مثال: محمد" name="name" required
                                           value="{{old('name')}}">
                                </fieldset>
                                <fieldset class="fieldset">
                                    <label for="name2"> اسم العائلة <span class="text-danger">*</span></label>
                                    <input type="text" id="name2" placeholder="مثال: أحمد" name="name2" required
                                           value="{{old('name2')}}">
                                </fieldset>
                            </div>

                            <fieldset class="box fieldset">
                                <label for="phone"> رقم الجوال <span class="text-danger">*</span></label>
                                <input type="tel" id="phone" placeholder="مثال: 05xxxxxxxx" name="phone" required
                                       value="{{old('phone')}}">
                            </fieldset>

                            <fieldset class="box fieldset">
                                <label for="phone2"> رقم جوال بديل (اختياري)</label>
                                <input type="tel" id="phone2" placeholder="مثال: 05xxxxxxxx" name="phone2"
                                       value="{{old('phone2')}}">
                            </fieldset>

                            <fieldset class="box fieldset">
                                <label for="shippingcity"> المدينة / المنطقة <span class="text-danger">*</span></label>
                                <div class="select-custom">
                                    <select class="form-select w-100" id="shippingcity" name="shippingcity" required data-global-threshold="{{ $publicSetting->global_free_shipping_threshold }}">
                                        <option value="" disabled {{ !isset($selectedCity) ? 'selected' : '' }}> -- اختر المدينة --</option>
                                        @foreach($shippingCity as $city)
                                            <option value="{{$city['id']}}" {{ (isset($selectedCity) && $selectedCity->id == $city->id) ? 'selected' : '' }} data-threshold="{{ $city->free_shipping_threshold }}">
                                                {{$city['city']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>

                            <fieldset class="box fieldset">
                                <label for="address"> العنوان بالتفصيل <span class="text-danger">*</span></label>
                                <input type="text" id="address" placeholder="مثال: حي الروضة، شارع الملك فهد، مبنى 123" name="address" required
                                       value="{{old('address')}}">
                            </fieldset>

                            <fieldset class="box fieldset">
                                <label for="note"> ملاحظات إضافية للسائق (اختياري)</label>
                                <textarea name="note" id="note" rows="3"
                                          placeholder="مثال: اطرق الباب بقوة، أو اتصل عند الوصول، أو اترك الطلب عند البوابة">{{old('note')}}</textarea>
                            </fieldset>
                        </div>

                        <div class="tf-page-cart-footer">
                            <div class="tf-cart-footer-inner">
                                <h5 class="fw-5 mb_20"> طلبك </h5>
                                <ul class="wrap-checkout-product">
                                    @php $subtotal = 0 ; @endphp
                                    @foreach($cartitems as $item)
                                        @php  $subtotal = $subtotal + ($item->price * $item->qty) @endphp
                                        <li class="checkout-product-item">
                                            <figure class="img-product">
                                                <img
                                                    src="{{asset('assets/uploads/product_images/'.($item->variation->image ?? $item->productdata->image))}}"
                                                    alt=" {{$item->productdata->name}}">
                                                <span class="quantity">{{$item->qty}}</span>
                                            </figure>
                                            <div class="content">
                                                <div class="info">
                                                    <p class="name">{{$item->productdata->name}}</p>
                                                    @if($item->product_variation_id != null)
                                                        @php
                                                            $variationValues = \App\Models\admin\VartionsValues::with('attribute')->where('product_variation_id', $item->product_variation_id)->get();
                                                        @endphp
                                                        <div class="meta-variant" style="font-size: 12px; color: #777;">
                                                            @foreach($variationValues as $value)
                                                                {{ $value->attribute->name }}: {{ $value->attribute_value_name }}@if(!$loop->last) - @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                <span
                                                    class="price">  {{$item->qty * $item->price}} {{ $storeCurrency }} </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                @php
                                    $isMarketer = Auth::guard('marketer')->check();
                                @endphp
                                @if($isMarketer)
                                <div class="p-3 mt-3 rounded border marketer-pricing-section" style="background:#f8f9ff">
                                    <h6 class="mb-3 fw-6" style="color:#5c4ac7">🏷️ تسعير المسوق - حدد سعر البيع لكل منتج</h6>
                                    @foreach($cartitems as $idx => $item)
                                    @php
                                        // Standard store price (base for profit)
                                        $storeP = $item->price; 
                                        // Admin defined marketer price - fallback to regular price if null or 0
                                        $variationMktPrice = $item->variation->marketer_price ?? 0;
                                        $productMktPrice = $item->productdata->marketer_price ?? 0;
                                        
                                        if ($item->product_variation_id) {
                                            // If variation has marketer_price > 0 use it, else check product marketer_price
                                            $mktP = $variationMktPrice > 0 ? $variationMktPrice : ($productMktPrice > 0 ? $productMktPrice : $item->price);
                                        } else {
                                            // No variation, use product marketer_price if > 0, else use regular price
                                            $mktP = $productMktPrice > 0 ? $productMktPrice : $item->price;
                                        }
                                    @endphp
                                    <div class="p-2 mb-3" style="border-bottom:1px dashed #ddd">
                                        <div class="mb-1 fw-5" style="font-size:13px">{{ $item->productdata->name }}</div>
                                        <div class="flex-wrap gap-5 d-flex align-items-center">
                                            <div style="font-size:12px;color:#666">
                                                سعر المنتج: <strong>{{ $storeP }} {{ $storeCurrency }}</strong>
                                            </div>
                                            <div style="font-size:12px;color:#666">
                                                سعر المسوق المقترح: <strong>{{ $mktP }} {{ $storeCurrency }}</strong>
                                            </div>
                                            <div class="gap-2 d-flex align-items-center">
                                                <label style="font-size:12px;white-space:nowrap">سعر البيع للعميل:</label>
                                                <input type="number" step="0.01" min="{{ $mktP }}"
                                                    class="form-control form-control-sm marketer-sell-price"
                                                    style="max-width:110px"
                                                    name="marketer_sell_price[{{ $idx }}]"
                                                    value="{{ $storeP }}"
                                                    data-store-price="{{ $storeP }}"
                                                    data-mkt-price="{{ $mktP }}"
                                                    data-qty="{{ $item->qty }}">
                                            </div>
                                            <div style="font-size:12px;color:green">
                                                ربحك: <strong class="item-profit-{{ $idx }}">{{ number_format(($storeP - $mktP) * $item->qty, 2) }}</strong> {{ $storeCurrency }}
                                                <small class="text-muted d-block">(شراء: {{ $mktP }} | بيع: <span class="sell-price-display">{{ $storeP }}</span>)</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="mt-2" style="font-size:14px;color:#5c4ac7">
                                        @php
                                            $totalInitialProfit = 0;
                                            foreach($cartitems as $idx => $item) {
                                                $storeP = $item->price;
                                                $mktP = $item->productdata->marketer_price ?? 0;
                                                if ($item->product_variation_id) {
                                                    $variationMktPrice = $item->variation->marketer_price ?? 0;
                                                    $productMktPrice = $item->productdata->marketer_price ?? 0;
                                                    $mktP = $variationMktPrice > 0 ? $variationMktPrice : ($productMktPrice > 0 ? $productMktPrice : $item->price);
                                                    $storeP = $item->variation->price ?? $item->price;
                                                }
                                                $totalInitialProfit += ($storeP - $mktP) * $item->qty;
                                            }
                                        @endphp
                                        إجمالي ربحك المتوقع: <strong id="total-marketer-profit">{{ number_format($totalInitialProfit, 2) }}</strong> {{ $storeCurrency }}
                                    </div>
                                </div>
                                @endif

                                <div class="pt-4 d-flex justify-content-between line pb_20">
                                    <h6 class="fw-5"> مجموع المنتجات </h6>
                                    <h6 class="total-products fw-5"> {{number_format($subtotal,2)}}  {{ $storeCurrency }} </h6>
                                </div>

                                <div class="pt-4 d-flex justify-content-between line pb_20">
                                    <h6 class="fw-5"> قيمة الشحن </h6>
                                    <h6 class="shipping-price fw-5">
                                        @if($freeShipping)
                                            0.00 {{ $storeCurrency }} (شحن مجاني)
                                        @else
                                            $0.00
                                        @endif
                                    </h6>
                                </div>

                                <form id="applycoupon" method="post" action="javascript:void(0);">
                                    @csrf
                                    <div class="pt-5 pb-20 coupon-box d-flex justify-content-between">
                                        <input id="code" name="code" type="text" placeholder=" كود خصم ">
                                        <button id="coupon_button" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn">تطبيق</button>
                                    </div>
                                </form>

                                @if (Session::has('coupon_amount'))
                                    <div class="pt-4 d-flex justify-content-between line pb_20">
                                        <h6 class="fw-5"> قيمة الخصم </h6>
                                        <h6 class="fw-5">
                                            - {{ Session::get('coupon_amount') }} {{ $storeCurrency }}
                                        </h6>
                                    </div>
                                @endif

                                <div class="pt-4 border-2 d-flex justify-content-between line pb_30 border-top">
                                    <h5 class="fw-6"> المجموع الكلي </h5>
                                    @if (Session::has('coupon_amount'))
                                        <h5 class="grand-total fw-6"> {{number_format($subtotal - Session::get('coupon_amount'), 2)}} {{ $storeCurrency }} </h5>
                                    @else
                                        <h5 class="grand-total fw-6"> {{number_format($subtotal, 2)}} {{ $storeCurrency }} </h5>
                                    @endif
                                </div>

                                <input type="hidden" id="shipping-price" name="shipping_price" value="{{ $freeShipping ? 0 : '' }}">
                                <input type="hidden" id="coupon_amount" name="coupon_amount"
                                       value="{{ Session::has('coupon_amount') ? Session::get('coupon_amount') : 0 }}">
                                <input type="hidden" id="grand_total" name="grand_total" value="{{ $freeShipping ? number_format($subtotal - (Session::get('coupon_amount') ?? 0), 2, '.', '') : '' }}">
                                @if(Auth::guard('marketer')->check())
                                <input type="hidden" name="is_marketer_order" value="1">
                                @endif

                                <div class="d-flex justify-content-center mt_30">
                                    <button type="submit"
                                            class="py-3 tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center w-100 text-uppercase fw-6 fs-16">
                                        اتمام الطلب
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @else
                @endif
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#shippingcity').on('change', function () {
                var cityId = $(this).val();
                if (cityId) {
                    $.ajax({
                        url: '/get-shipping-price',
                        type: 'GET',
                        data: {city_id: cityId},
                        success: function (response) {
                                var subtotal = parseFloat($('.total-products').text().replace(/[^\d.]/g, ''));
                                var shippingPrice = parseFloat(response.price);
                                var cityThreshold = parseFloat($('#shippingcity option:selected').data('threshold')) || 0;
                                var globalThreshold = parseFloat($('#shippingcity').data('global-threshold')) || 0;
                                
                                let thresholds = [cityThreshold, globalThreshold].filter(v => v > 0);
                                let threshold = thresholds.length > 0 ? Math.min(...thresholds) : 0;

                                if (threshold > 0 && subtotal >= threshold) {
                                    shippingPrice = 0;
                                    $('.shipping-price').text('0.00 {{ $storeCurrency }} (شحن مجاني)');
                                } else {
                                    $('.shipping-price').text(shippingPrice + ' {{ $storeCurrency }}');
                                }

                                var couponAmount = parseFloat($('#coupon_amount').val()) || 0;
                                var shipping_price_input = document.getElementById('shipping-price');
                                shipping_price_input.value = shippingPrice;

                                var grandTotal = (subtotal + shippingPrice) - couponAmount;
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

                // Trigger change if city is already selected
                if ($('#shippingcity').val()) {
                    $('#shippingcity').trigger('change');
                }

                // Marketer Profit Calculation
                function updateMarketerProfit() {
                    let totalProfit = 0;
                    let subtotal = 0;

                    $('.marketer-sell-price').each(function(index) {
                        let storePrice = parseFloat($(this).data('store-price'));
                        let mktPrice = parseFloat($(this).data('mkt-price'));
                        let sellPrice = parseFloat($(this).val()) || storePrice;
                        let qty = parseInt($(this).data('qty'));
                        
                        // Minimum sell price is mktPrice
                        if (sellPrice < mktPrice) {
                            sellPrice = mktPrice;
                        }

                        // Profit is: (sell price to customer - marketer purchase price) * quantity
                        let itemProfit = (sellPrice - mktPrice) * qty;
                        totalProfit += itemProfit;
                        subtotal += (sellPrice * qty);

                        // Update profit display
                        $('.item-profit-' + index).text(itemProfit.toFixed(2));
                        
                        // Update sell price display
                        $('.sell-price-display').eq(index).text(sellPrice.toFixed(2));
                        
                        // Show profit explanation
                        let profitExplanation = '';
                        if (sellPrice > mktPrice) {
                            profitExplanation = `(ربح: ${(sellPrice - mktPrice).toFixed(2)} × ${qty} = ${itemProfit.toFixed(2)})`;
                        } else if (sellPrice === mktPrice) {
                            profitExplanation = `(لا يوجد ربح - بيع بسعر الشراء)`;
                        }
                        
                        // Update explanation if element exists
                        if ($('.profit-explanation-' + index).length === 0) {
                            $('.item-profit-' + index).parent().append('<small class="profit-explanation-' + index + ' text-muted d-block">' + profitExplanation + '</small>');
                        } else {
                            $('.profit-explanation-' + index).text(profitExplanation);
                        }
                    });

                    $('#total-marketer-profit').text(totalProfit.toFixed(2));
                    
                    // Update main subtotal and grand total
                    $('.total-products').text(subtotal.toFixed(2) + ' {{ $storeCurrency }}');
                    
                    // Trigger shipping calculation to update grand total
                    $('#shippingcity').trigger('change');
                }

                $('.marketer-sell-price').on('input change', updateMarketerProfit);
                
                // Initial update for marketer profit
                if ($('.marketer-sell-price').length > 0) {
                    updateMarketerProfit();
                }
            });

        $('.form-checkout').on('submit', function () {
            var $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true);
            $btn.html('<i class="fas fa-spinner fa-spin"></i> جاري الارسال...');
        });
    </script>

    <script>
        $("#coupon_button").click(function ($e) {
            $e.preventDefault();
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
                        location.reload();
                    }
                },
                error: function () {
                    alert('error');
                }
            });
        })
    </script>
@endsection