<div class="wrap">
    <div class="tf-mini-cart-threshold">
{{--        <div class="tf-progress-bar">--}}
{{--                            <span style="width: 50%;">--}}
{{--                                <div class="progress-car">--}}
{{--                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="14" viewBox="0 0 21 14"--}}
{{--                                         fill="currentColor">--}}
{{--                                        <path fill-rule="evenodd" clip-rule="evenodd"--}}
{{--                                              d="M0 0.875C0 0.391751 0.391751 0 0.875 0H13.5625C14.0457 0 14.4375 0.391751 14.4375 0.875V3.0625H17.3125C17.5867 3.0625 17.845 3.19101 18.0104 3.40969L20.8229 7.12844C20.9378 7.2804 21 7.46572 21 7.65625V11.375C21 11.8582 20.6082 12.25 20.125 12.25H17.7881C17.4278 13.2695 16.4554 14 15.3125 14C14.1696 14 13.1972 13.2695 12.8369 12.25H7.72563C7.36527 13.2695 6.39293 14 5.25 14C4.10706 14 3.13473 13.2695 2.77437 12.25H0.875C0.391751 12.25 0 11.8582 0 11.375V0.875ZM2.77437 10.5C3.13473 9.48047 4.10706 8.75 5.25 8.75C6.39293 8.75 7.36527 9.48046 7.72563 10.5H12.6875V1.75H1.75V10.5H2.77437ZM14.4375 8.89937V4.8125H16.8772L19.25 7.94987V10.5H17.7881C17.4278 9.48046 16.4554 8.75 15.3125 8.75C15.0057 8.75 14.7112 8.80264 14.4375 8.89937ZM5.25 10.5C4.76676 10.5 4.375 10.8918 4.375 11.375C4.375 11.8582 4.76676 12.25 5.25 12.25C5.73323 12.25 6.125 11.8582 6.125 11.375C6.125 10.8918 5.73323 10.5 5.25 10.5ZM15.3125 10.5C14.8293 10.5 14.4375 10.8918 14.4375 11.375C14.4375 11.8582 14.8293 12.25 15.3125 12.25C15.7957 12.25 16.1875 11.8582 16.1875 11.375C16.1875 10.8918 15.7957 10.5 15.3125 10.5Z"></path>--}}
{{--                                    </svg>--}}
{{--                                </div>--}}
{{--                            </span>--}}
{{--        </div>--}}
{{--        <div class="tf-progress-msg">--}}
{{--            اضف مشتريات بقيمة <span class="price fw-6">100 ريال </span> <span class=""> للحصول علي شحن مجاني  </span>--}}
{{--        </div>--}}
    </div>
    <div class="tf-mini-cart-wrap">
        <div class="tf-mini-cart-main">
            <div class="tf-mini-cart-scroll">
                <div class="tf-mini-cart-items">
                    @php $subtotal = 0 ; @endphp
                    @foreach($cartItems as $item)
                        @php  $subtotal = $subtotal + ($item['price'] * $item['qty']) @endphp
                        <div class="tf-mini-cart-item">
                            <div class="tf-mini-cart-image">
                                <a href="{{url('product/'.$item['productdata']['slug'])}}">
                                    <img src="{{asset('assets/uploads/product_images/'.$item['productdata']['image'])}}"
                                         alt="">
                                </a>
                            </div>
                            <div class="tf-mini-cart-info">
                                <a class="title link"
                                   href="{{url('product/'.$item['productdata']['slug'])}}">{{$item['productdata']['name']}}</a>
                                <div class="meta-variant">Light gray</div>
                                <div class="price fw-6"> {{$item['qty']}}
                                    * {{$item['price']}} {{ $storeCurrency }}</div>
                                <div class="tf-mini-cart-btns">
                                    <div class="wg-quantity small">
                                        <span class="btn-quantity minus-btn" data-id="{{$item['id']}}">-</span>

                                        <input type="number" name="number" data-id="{{ $item['id'] }}"
                                               value="{{ $item['qty'] }}" min="1">
                                        <span class="btn-quantity plus-btn" data-id="{{$item['id']}}">+</span>
                                    </div>
                                    <form method="post" action="{{url('cart/delete/'.$item['id'])}}">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{$item['id']}}">
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
