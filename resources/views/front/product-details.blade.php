@extends('front.layouts.master')
@section('title')
    {{ $product['name'] }}
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
                <div class="tf-breadcrumb-wrap d-flex justify-content-between flex-wrap align-items-center">
                    <div class="tf-breadcrumb-list">
                        <a href="{{ url('/') }}" class="text"> الرئيسية </a>
                        <i class="icon icon-arrow-right"></i>
                        <a href="{{ url('collection/' . $product['Main_Category']['slug']) }}"
                            class="text">{{ $product['Main_Category']['name'] }}</a>
                        <i class="icon icon-arrow-right"></i>
                        <span class="text"> {{ $product['name'] }} </span>
                    </div>
                </div>
                <div class="heading text-center">{{ $product['name'] }}</div>
            </div>
        </div>
        <!-- /page-title -->
        <!-- /breadcrumb -->
        <!-- default -->
        <section class="flat-spacing-4 pt_0">
            <div class="tf-main-product">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="tf-product-media-wrap sticky-top">
                                <div class="thumbs-slider thumbs-default">
                                    <div class="swiper tf-product-media-thumbs tf-product-media-thumbs-default"
                                        data-direction="vertical">
                                        <div class="swiper-wrapper stagger-wrap">
                                        </div>
                                    </div>
                                    <div class="swiper tf-product-media-main tf-product-media-main-default">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <a href="#" class="item">
                                                    <img class="lazyload"
                                                        data-src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                        src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                        alt="{{ $product['name'] }}">
                                                </a>
                                            </div>
                                            @if ($product['gallary'] && $product['gallary'] != '')
                                                @foreach ($product['gallary'] as $gallary)
                                                    <div class="swiper-slide">
                                                        <a href="#" class="item">
                                                            <img class="lazyload"
                                                                data-src="{{ asset('assets/uploads/product_gallery/' . $gallary['image']) }}"
                                                                src="{{ asset('assets/uploads/product_gallary/' . $gallary['image']) }}"
                                                                alt="{{ $product['name'] }}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="swiper-button-next button-style-arrow thumbs-next"></div>
                                        <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="tf-product-info-wrap position-relative">
                                <div class="tf-zoom-main"></div>
                                <div class="tf-product-info-list">
                                    <div class="tf-product-info-title">
                                        <h5> {{ $product['name'] }} </h5>
                                    </div>
                                    {{--                                    <div class="tf-product-info-badges"> --}}
                                    {{--                                        <div class="product-status-content"> --}}
                                    {{--                                            <p class="fw-6">{{$product['short_description']}}</p> --}}
                                    {{--                                        </div> --}}
                                    {{--                                    </div> --}}
                                    <!-- عرض خيارات السمات -->
                                    <form id="addToCart_{{ $product['id'] }}" class="" method="post"
                                        action="{{ url('cart/add') }}">
                                        @csrf
                                        <div class="tf-product-info-variant-picker">
                                            @if ($productVariations->count() > 0)
                                                @foreach ($variationAttributes as $attributeId => $attribute)
                                                    <div class="form-group">
                                                        <label
                                                            for="attribute_{{ $attributeId }}">{{ $attribute['name'] }}</label>
                                                        <select name="attribute_values[{{ $attributeId }}]"
                                                            class="form-control" onchange="fetchPrice2()">
                                                            <option value="">اختر {{ $attribute['name'] }}</option>
                                                            @foreach ($attribute['values'] as $value)
                                                                <option value="{{ $value }}">{{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                                <!-- عرض السعر هنا -->
                                                <div id="product-price" class="tf-product-info-price">
                                                    <p class="quantity-title fw-6">السعر: <span id="price-value"
                                                            class="price-on-sale"> </span>
                                                    </p>
                                                    <p id="discount-section" style="display: none;">
                                                        <span id="discounted-price" class="price-on-sale"> </span>
                                                    </p>
                                                </div>
                                                <br>
                                                <!-- حقول مخفية للسعر والمتغيرات -->
                                                <input type="hidden" placeholder="سعر المتغير " id="hidden-price"
                                                    name="price" value="">
                                                <input type="hidden" id="hidden-discount" placeholder=" سعر خصم المتغير "
                                                    name="discount" value="">
                                                <input type="hidden" id="hidden-variation" placeholder=" "
                                                    name="hidden-variation" value="">
                                            @else
                                                <input type="hidden" id="hidden-variation" placeholder="دشقفهخر "
                                                    name="hidden-variation" value="">
                                                <div class="tf-product-info-price">
                                                    @if (isset($product['discount']) && $product['discount'] != null)
                                                        <div class="price-on-sale">{{ $product['discount'] }}
                                                            {{ $storeCurrency }} </div>
                                                        <div class="compare-at-price">{{ $product['price'] }}
                                                            {{ $storeCurrency }}</div>
                                                    @else
                                                        <div class="price-on-sale">{{ $product['price'] }}
                                                            {{ $storeCurrency }}</div>
                                                    @endif
                                                </div>
                                                @if (isset($product['discount']) && $product['discount'] != null)
                                                    <input type="hidden" name="price"
                                                        value="{{ $product['discount'] }}">
                                                @else
                                                    <input type="hidden" name="price" value="{{ $product['price'] }}">
                                                @endif
                                            @endif

                                        </div>
                                        <div class="tf-product-info-quantity">
                                            <div class="quantity-title fw-6"> الكمية</div>
                                            <div class="wg-quantity">
                                                <span class="btn-quantity minus-btn">-</span>
                                                <input type="text" name="number" value="1">
                                                <span class="btn-quantity plus-btn">+</span>
                                            </div>
                                        </div>
                                        <div class="tf-product-info-buy-button">
                                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">


                                            <button id="addtocartbutton_{{ $product['id'] }}" href="javascript:void(0);"
                                                class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart">
                                                <span> اضف الي السلة </span></button>
                                        </div>
                                    </form>

                                    <!-- AddToAny BEGIN -->
                                    <div class="share_button">
                                        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                                            <!-- <a class="a2a_dd" href="https://www.addtoany.com/share"></a> -->
                                            <a href="" class="a2a_button_facebook"></a>
                                            <a href="" class="a2a_button_whatsapp"></a>
                                            <a href="" class="a2a_button_linkedin"></a>
                                            <a href="" class="a2a_button_twitter"></a>
                                            <a href="" class="a2a_button_x"></a>
                                            <a href="" class="a2a_button_telegram"></a>
                                        </div>
                                        <script async src="https://static.addtoany.com/menu/page.js"></script>
                                        <!-- AddToAny END -->
                                    </div>
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                                    <script>
                                        $(document).ready(function() {
                                            $("#addtocartbutton_{{ $product['id'] }}").on('click', function(e) {
                                                e.preventDefault();
                                                $.ajax({
                                                    url: '/cart/add',
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    data: $("#addToCart_{{ $product['id'] }}").serialize(),
                                                    success: function(response) {
                                                        // عرض الرسالة باستخدام Toastify
                                                        Toastify({
                                                            text: response.message,
                                                            duration: 3000,
                                                            gravity: "top",
                                                            position: "right",
                                                            backgroundColor: "#4CAF50",
                                                        }).showToast();

                                                        // تحديث عداد المنتجات في السلة
                                                        if (response.cartCount) {
                                                            $('.nav-cart .count-box').text(response.cartCount);
                                                        }

                                                        // تحديث محتويات الـ modal للسلة فورًا
                                                        updateCartModal();

                                                        // إظهار الـ modal بعد الإضافة
                                                        $('#shoppingCart').modal('show');
                                                    },
                                                    error: function(xhr, status, error) {
                                                        $('#wishlistMessage').html('<p>حدث خطأ أثناء إضافة المنتج للسلة </p>');
                                                    }
                                                });
                                            });

                                            function updateCartModal() {
                                                $.ajax({
                                                    url: '/cart/items', // رابط جلب العناصر المحدثة
                                                    method: 'GET',
                                                    success: function(response) {
                                                        console.log('Cart modal response:',
                                                            response); // طباعة استجابة السيرفر للتحقق من البيانات

                                                        // استبدال محتويات الـ modal بالـ HTML المستلم من السيرفر
                                                        $('#shoppingCart .wrap').html(response.html);

                                                        // تحديث عداد السلة
                                                        $('.nav-cart .count-box').text(response.cartCount); // تحديث العداد مباشرة

                                                        // تحديث متغير $cartCount في الواجهة إذا كنت تستخدمه في أماكن أخرى
                                                        window.cartCount = response.cartCount; // تخزين القيمة في متغير عالمي
                                                        console.log(window.cartCount);
                                                    },
                                                    error: function(xhr, status, error) {
                                                        console.log('خطأ في تحديث السلة');
                                                    }
                                                });
                                            }
                                        });
                                    </script>

                                    <script>
                                        function fetchPrice2() {
                                            let form = document.getElementById('addToCart_{{ $product['id'] }}');
                                            let formData = new FormData(form);

                                            fetch('{{ route('product.getPrice', $product->id) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    // تحديث السعر في الواجهة
                                                    document.getElementById('price-value').innerText = data.price ? data.price +
                                                        '{{ $storeCurrency }}' : 'غير متوفر';

                                                    if (data.discount && data.discount > 0) {
                                                        // عرض السعر بعد التخفيض إذا كان موجودًا
                                                        document.getElementById('discounted-price').innerText = data.discount + '{{ $storeCurrency }}';
                                                        document.getElementById('discount-section').style.display = 'block';
                                                        document.getElementById('price-value').style.textDecoration = "line-through";
                                                    } else {
                                                        // إخفاء قسم التخفيض إذا لم يكن هناك تخفيض
                                                        document.getElementById('discount-section').style.display = 'none';
                                                        document.getElementById('price-value').style.textDecoration = "none";
                                                    }
                                                    // تحديث الحقول المخفية بالقيمة الحقيقية للسعر والخصم
                                                    document.getElementById('hidden-variation').value = data.variation_id;
                                                    document.getElementById('hidden-price').value = data.price;
                                                    document.getElementById('hidden-discount').value = data.discount ? data.discount : '';
                                                })
                                                .catch(error => console.error('Error:', error));
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /default -->
        <!-- tabs -->
        <section class="flat-spacing-17 pt_0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="widget-tabs style-has-border">
                            <ul class="widget-menu-tab">
                                <li class="item-title active">
                                    <span class="inner"> وصف المنتج </span>
                                </li>
                                {{-- <li class="item-title">
                                    <span class="inner"> التقيمات </span>
                                </li> --}}
                                @if ($product['video'] != null)
                                    <li class="item-title">
                                        <span class="inner"> فيديو المنتج </span>
                                    </li>
                                @endif

                            </ul>
                            <div class="widget-content-tab">
                                <div class="widget-content-inner active">
                                    <div class="">
                                        <p class="mb_30"> 
                                        {!! $product['description'] !!}
                                        </p>
                                    </div>
                                </div>
                                {{--
                                <div class="widget-content-inner">
                                    <table class="tf-pr-attrs">
                                        <tbody>
                                            <tr class="tf-attr-pa-color">
                                                <th class="tf-attr-label">Color</th>
                                                <td class="tf-attr-value">
                                                    <p>White, Pink, Black</p>
                                                </td>
                                            </tr>
                                            <tr class="tf-attr-pa-size">
                                                <th class="tf-attr-label">Size</th>
                                                <td class="tf-attr-value">
                                                    <p>S, M, L, XL</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> --}}
                                @if ($product->video)
                                    <div class="widget-content-inner">
                                        <div class="">
                                            <video controls style="max-width: 100%; height: auto;">
                                                <source
                                                    src="{{ asset('assets/uploads/product_videos/' . $product->video) }}"
                                                    type="video/mp4">
                                                المتصفح الخاص بك لا يدعم تشغيل الفيديو.
                                            </video>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /tabs -->
        <!-- product -->
        <section class="flat-spacing-1 pt_0" style="overflow-y:hidden">
            <div class="container">
                <div class="flat-title">
                    <span class="title"> ربما يعجبك أيضا </span>
                </div>
                <div class="hover-sw-nav hover-sw-2">
                    <div class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3" data-mobile="2"
                        data-space-lg="30" data-space-md="15" data-pagination="2" data-pagination-md="3"
                        data-pagination-lg="3">
                        <div class="swiper-wrapper">
                            @foreach ($similar_products as $product)
                                <div class="swiper-slide" lazy="true">
                                    <div class="card-product">
                                        <div class="card-product-wrapper">
                                            <a href="{{ url('product/' . $product['slug']) }}" class="product-img">
                                                <img class="lazyload img-product"
                                                    data-src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                    src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                    alt="{{ $product['name'] }}">
                                                @if ($product->gallary && $product->gallary->first())
                                                    <img class="lazyload img-hover"
                                                        data-src="{{ asset('assets/uploads/product_gallery/' . $product->gallary->first()->image) }}"
                                                        src="{{ asset('assets/uploads/product_gallery/' . $product->gallary->first()->image) }}"
                                                        alt="{{ $product['name'] }}">
                                                @else
                                                    <img class="lazyload img-hover"
                                                        data-src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                        src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                        alt="{{ $product['name'] }}">
                                                @endif

                                            </a>
                                            <div class="list-product-btn">
                                                <form id="wishlistForm_{{ $product['id'] }}" method="post"
                                                    action="{{ url('wishlist/store') }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $product['id'] }}">
                                                    <button type="button" id="addToWishlist_{{ $product['id'] }}"
                                                        class="box-icon bg_white wishlist btn-icon-action">
                                                        <span class="icon icon-heart"></span>
                                                        <span class="tooltip"> اضف الي المفضلة </span>
                                                        <span class="icon icon-heart"></span>
                                                    </button>
                                                </form>
                                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                <script>
                                                    $(document).ready(function() {
                                                        $('#addToWishlist_{{ $product['id'] }}').on('click', function(e) {
                                                            e.preventDefault();
                                                            $.ajax({
                                                                method: 'POST',
                                                                url: 'wishlist/store',
                                                                data: $('#wishlistForm_{{ $product['id'] }}').serialize(),
                                                                success: function(response) {
                                                                    // عرض الرسالة باستخدام Toastify
                                                                    Toastify({
                                                                        text: response.message, // عرض الرسالة من response
                                                                        duration: 3000, // المدة الزمنية لعرض الرسالة
                                                                        gravity: "top", // اتجاه العرض
                                                                        position: "right", // موقع الرسالة
                                                                        backgroundColor: "#4CAF50", // لون الخلفية للرسالة
                                                                    }).showToast();
                                                                    if (response.wishlistCount) {
                                                                        $('.nav-wishlist .count-box').text(response.wishlistCount);
                                                                    }
                                                                },
                                                                error: function(xhr, status, error) {
                                                                    $('#wishlistMessage').html('<p>حدث خطأ أثناء إضافة المنتج للمفضلة</p>');
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>
                                                <button data-id="{{ $product->id }}" href=""
                                                    data-bs-toggle="modal"
                                                    class="box-icon bg_white quickview tf-btn-loading btn-quick-view">
                                                    <span class="icon icon-view"></span>
                                                    <span class="tooltip"> مشاهدة </span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-product-info">
                                            <a href="{{ url('product/' . $product['slug']) }}" class="title link">
                                                {{ $product['name'] }} </a>
                                            @if (isset($product['discount']) && $product['discount'] != null)
                                                <div class="">
                                                    <span class="price main_price"> {{ $product['discount'] }}
                                                        {{ $storeCurrency }} </span>
                                                    <span class="price old_price"> {{ $product['price'] }}
                                                        {{ $storeCurrency }} </span>
                                                </div>
                                            @else
                                                <span class="price main_price"> {{ $product['price'] }}
                                                    {{ $storeCurrency }} </span>
                                            @endif

                                            @php
                                                $productVariations = \App\Models\admin\ProductVartions::where(
                                                    'product_id',
                                                    $product['id'],
                                                )->get();
                                            @endphp
                                            @if ($productVariations->count() > 0)
                                                <a href="{{ url('product/' . $product['slug']) }}" class="add-to-cart">
                                                    مشاهدة الاختيارات
                                                </a>
                                            @else
                                                <form id="addToCart_{{ $product['id'] }}" class="" method="post"
                                                    action="{{ url('cart/add') }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $product['id'] }}">
                                                    <input type="hidden" name="number" value="1">
                                                    @if (isset($product['discount']) && $product['discount'] != null)
                                                        <input type="hidden" name="price"
                                                            value="{{ $product['discount'] }}">
                                                    @else
                                                        <input type="hidden" name="price"
                                                            value="{{ $product['price'] }}">
                                                    @endif
                                                    <input type="hidden" id="hidden-variation" placeholder="دشقفهخر "
                                                        name="hidden-variation" value="">

                                                    <button id="addtocartbutton_{{ $product['id'] }}"
                                                        class="add-to-cart">
                                                        اضف الي السلة
                                                    </button>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        $("#addtocartbutton_{{ $product['id'] }}").on('click', function(e) {
                                                            e.preventDefault();
                                                            $.ajax({
                                                                url: '/cart/add',
                                                                method: 'POST',
                                                                headers: {
                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                },
                                                                data: $("#addToCart_{{ $product['id'] }}").serialize(),
                                                                success: function(response) {
                                                                    // عرض الرسالة باستخدام Toastify
                                                                    Toastify({
                                                                        text: response.message,
                                                                        duration: 3000,
                                                                        gravity: "top",
                                                                        position: "right",
                                                                        backgroundColor: "#4CAF50",
                                                                    }).showToast();

                                                                    // تحديث عداد المنتجات في السلة
                                                                    if (response.cartCount) {
                                                                        $('.nav-cart .count-box').text(response.cartCount);
                                                                    }

                                                                    // تحديث محتويات الـ modal للسلة فورًا
                                                                    updateCartModal();

                                                                    // إظهار الـ modal بعد الإضافة
                                                                    $('#shoppingCart').modal('show');
                                                                },
                                                                error: function(xhr, status, error) {
                                                                    $('#wishlistMessage').html('<p>حدث خطأ أثناء إضافة المنتج للسلة </p>');
                                                                }
                                                            });
                                                        });

                                                        function updateCartModal() {
                                                            $.ajax({
                                                                url: '/cart/items', // رابط جلب العناصر المحدثة
                                                                method: 'GET',
                                                                success: function(response) {
                                                                    console.log('Cart modal response:',
                                                                        response); // طباعة استجابة السيرفر للتحقق من البيانات

                                                                    // استبدال محتويات الـ modal بالـ HTML المستلم من السيرفر
                                                                    $('#shoppingCart .wrap').html(response.html);

                                                                    // تحديث عداد السلة
                                                                    $('.nav-cart .count-box').text(response.cartCount); // تحديث العداد مباشرة

                                                                    // تحديث متغير $cartCount في الواجهة إذا كنت تستخدمه في أماكن أخرى
                                                                    window.cartCount = response.cartCount; // تخزين القيمة في متغير عالمي
                                                                    console.log(window.cartCount);
                                                                },
                                                                error: function(xhr, status, error) {
                                                                    console.log('خطأ في تحديث السلة');
                                                                }
                                                            });
                                                        }
                                                    });
                                                </script>
                                            @endif


                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span
                            class="icon icon-arrow-left"></span></div>
                    <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span
                            class="icon icon-arrow-right"></span></div>
                    <div class="sw-dots style-2 sw-pagination-product justify-content-center"></div>
                </div>
            </div>
        </section>
        <!-- /product -->
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '#addtocartbutton', function(e) {
                e.preventDefault(); // منع السلوك الافتراضي للنموذج
                // إرسال الطلب باستخدام AJAX
                $.ajax({
                    url: '/cart/add',
                    method: 'POST',
                    data: $("#addToCart").serialize(), // البيانات المرسلة
                    success: function(response) {
                        // عرض الرسالة باستخدام Toastify
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#4CAF50",
                        }).showToast();

                        if (response.cartCount) {
                            $('.nav-cart .count-box').text(response.cartCount);
                        }
                        // تحديث عربة التسوق
                        // تحديث محتويات الـ modal للسلة فورًا
                        updateCartModal();

                        // إظهار الـ modal بعد الإضافة
                        $('#shoppingCart').modal('show');
                        $('#quick_view').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", xhr.responseText); // عرض أي أخطاء
                        $('#wishlistMessage').html('<p>حدث خطأ أثناء إضافة المنتج للسلة</p>');
                    }
                });
            });

            // تحديث عربة التسوق
            function updateCartModal() {
                $.ajax({
                    url: '/cart/items', // رابط جلب العناصر المحدثة
                    method: 'GET',
                    success: function(response) {
                        console.log('Cart modal response:',
                            response); // طباعة استجابة السيرفر للتحقق من البيانات

                        // استبدال محتويات الـ modal بالـ HTML المستلم من السيرفر
                        $('#shoppingCart .wrap').html(response.html);

                        // تحديث عداد السلة
                        $('.nav-cart .count-box').text(response.cartCount); // تحديث العداد مباشرة

                        // تحديث متغير $cartCount في الواجهة إذا كنت تستخدمه في أماكن أخرى
                        window.cartCount = response.cartCount; // تخزين القيمة في متغير عالمي
                        console.log(window.cartCount);
                    },
                    error: function(xhr, status, error) {
                        console.log('خطأ في تحديث السلة');
                    }
                });
            }
        });
    </script>

    <script>
        document.querySelectorAll('.btn-quick-view').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');

                // طلب AJAX لجلب البيانات
                fetch(`/product/quick-view/${productId}`)
                    .then(response => response.text())
                    .then(html => {
                        // إدخال المحتوى في المودال
                        document.getElementById('modal-content').innerHTML = html;

                        // إعادة تهيئة المودال
                        const modalElement = document.getElementById('quick_view');
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();

                        // تهيئة Swiper بعد تحميل المحتوى
                        var swiper = new Swiper('.tf-single-slide', {
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                        });
                        // تهيئة أزرار التحكم بالكمية
                        initializeQuantityButtons();
                    })
                    .catch(error => console.error('Error fetching product details:', error));
            });
        });

        // تهيئة أزرار التحكم بالكمية
        function initializeQuantityButtons() {
            document.querySelectorAll('.plus-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    input.value = parseInt(input.value) + 1;
                });
            });

            document.querySelectorAll('.minus-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.nextElementSibling;
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                    }
                });
            });
        }

        document.addEventListener('hidden.bs.modal', function() {
            // إزالة أي عناصر overlay بقيت على الصفحة
            document.querySelectorAll('.modal-backdrop').forEach(overlay => {
                overlay.remove();
            });
            // إزالة فئة الـ modal-open من الـ body
            document.body.classList.remove('modal-open');
        });
    </script>

    <script>
        function fetchPrice() {
            let form = document.getElementById('addToCart');
            let formData = new FormData(form);

            fetch('{{ route('product.getPrice', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // تحديث السعر في الواجهة
                    document.getElementById('price-value').innerText = data.price ? data.price +
                        ' {{ $storeCurrency }}' : 'غير متوفر';

                    if (data.discount && data.discount > 0) {
                        document.getElementById('discounted-price').innerText = data.discount +
                            ' {{ $storeCurrency }}';
                        document.getElementById('discount-section').style.display = 'block';
                        document.getElementById('price-value').style.textDecoration = "line-through";
                    } else {
                        document.getElementById('discount-section').style.display = 'none';
                        document.getElementById('price-value').style.textDecoration = "none";
                    }
                    // تحديث الحقول المخفية
                    document.getElementById('hidden-variation').value = data.variation_id;
                    document.getElementById('hidden-price').value = data.price;
                    document.getElementById('hidden-discount').value = data.discount || '';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
