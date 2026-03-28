    <div class="tf-mini-cart-threshold px-3 pt-3">
        @php
            $publicSetting = \App\Models\admin\PublicSetting::first();
            $cityId = session('shipping_city_id');
            $selectedCity = $cityId ? \App\Models\admin\ShippingCity::find($cityId) : null;
            $cityThreshold = $selectedCity->free_shipping_threshold ?? 0;
            $globalThreshold = $publicSetting->global_free_shipping_threshold ?? 0;
            $thresholds = array_filter([$cityThreshold, $globalThreshold], function($v) { return $v > 0; });
            $threshold = count($thresholds) > 0 ? min($thresholds) : 0;
            
            $subtotal = 0;
            foreach($cartItems as $item) {
                $subtotal += ($item->price * $item->qty);
            }
            $remaining = $threshold - $subtotal;
            $percent = $threshold > 0 ? min(100, ($subtotal / $threshold) * 100) : 0;
        @endphp

        <div id="mini-cart-threshold-wrapper" style="{{ $threshold > 0 ? '' : 'display: none;' }}">
            <div class="free-shipping-text-wrapper mb-2">
                <p class="free-shipping-text fw-5 mb-0" style="font-size: 13px; color: #333;">
                    @if($threshold > 0)
                        @if($remaining > 0)
                            <i class="bi bi-box-seam me-2" style="color: #666;"></i>
                            أضف مشتريات بقيمة <span class="remaining-amount" style="color: #28a745;">{{ number_format($remaining, 2) }}</span> {{ $storeCurrency }} للشحن المجاني
                        @else
                            <i class="bi bi-box-seam me-2" style="color: #666;"></i>
                            <span style="color: #28a745;">لقد حصلت على شحن مجاني!</span>
                        @endif
                    @endif
                </p>
            </div>
            <div class="progress-bar-container position-relative mb-3" style="height: 6px; background-color: #e9ecef; border-radius: 3px; overflow: visible;">
                <div class="progress-bar-fill" style="height: 100%; background-color: #28a745; border-radius: 3px; transition: width 0.3s ease; width: {{ $percent }}%;"></div>
                <div class="progress-star position-absolute" style="top: -8px; left: calc({{ $percent }}% - 12px); transition: left 0.3s ease;">
                    <i class="fas fa-star" style="color: #ffc107; font-size: 16px; text-shadow: 0 0 5px rgba(0,0,0,0.2);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="tf-mini-cart-wrap">
        <div class="tf-mini-cart-main">
            <div class="tf-mini-cart-scroll">
                <div class="tf-mini-cart-items">
                    @php $subtotal = 0 ; @endphp
                    @foreach($cartItems as $item)
                        @php  $subtotal = $subtotal + ($item->price * $item->qty) @endphp
                        <div class="tf-mini-cart-item">
                            <div class="tf-mini-cart-image">
                                <a href="{{url('product/'.$item->productdata->slug)}}">
                                    <img src="{{asset('assets/uploads/product_images/'.($item->variation->image ?? $item->productdata->image))}}"
                                         alt="">
                                </a>
                            </div>
                            <div class="tf-mini-cart-info">
                                <a class="title link"
                                   href="{{url('product/'.$item->productdata->slug)}}">{{$item->productdata->name}}</a>
                                @if($item->product_variation_id != null)
                                    @php
                                        $variationValues = \App\Models\admin\VartionsValues::with('attribute')->where('product_variation_id', $item->product_variation_id)->get();
                                    @endphp
                                    <div class="meta-variant">
                                        @foreach($variationValues as $value)
                                            {{ $value->attribute->name }}: {{ $value->attribute_value_name }}@if(!$loop->last) - @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="price fw-6"> {{$item->qty}}
                                    * {{$item->price}} {{ $storeCurrency }}</div>
                                <div class="tf-mini-cart-btns">
                                    <div class="wg-quantity small">
                                        <span class="btn-quantity minus-btn" data-id="{{$item->id}}">-</span>

                                        <input type="number" name="number" data-id="{{ $item->id }}"
                                               value="{{ $item->qty }}" min="1">
                                        <span class="btn-quantity plus-btn" data-id="{{$item->id}}">+</span>
                                    </div>
                                    <form method="post" action="{{url('cart/delete/'.$item->id)}}">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{$item->id}}">
                                        <button type="submit" class="tf-mini-cart-remove"><i
                                                class="bi bi-trash-fill"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if($cartItems->count() > 0)
            <div class="tf-mini-cart-bottom">
                <div class="tf-mini-cart-bottom-wrap">
                    <div class="tf-cart-totals-discounts">
                        <div class="tf-cart-total"> المبلغ الاجمالي</div>
                        <div
                            class="tf-totals-total-value fw-6 total-value">  {{ number_format($subtotal,2)}} {{ $storeCurrency }} </div>
                    </div>

                    <div class="tf-mini-cart-view-checkout">
                        <a href="{{url('cart')}}" class="tf-btn btn-outline radius-3 link w-100 justify-content-center">
                            مشاهدة سلة الشراء </a>
                        <a href="{{url('checkout')}}"
                           class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"><span> انتقل الي اتمام الطلب  </span></a>
                    </div>
                </div>
            </div>
        @else
            <div class="tf-mini-cart-bottom">
                <div class="tf-mini-cart-bottom-wrap">
                    <div class="tf-cart-totals-discounts">
                        <div class="tf-cart-total"> سلة الشراء فارغة</div>
                    </div>

                    <div class="tf-mini-cart-view-checkout">
                        <a href="{{url('shop')}}"
                           class="tf-btn btn-outline radius-3 link w-100 justify-content-center">
                            تسوق الان </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
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
                },
                error: function (xhr) {
                    console.log('Error updating cart');
                }
            });
        }
    });
</script>
