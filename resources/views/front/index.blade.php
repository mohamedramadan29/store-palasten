@extends('front.layouts.master')
@section('title')
    الرئيسية
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
        <!-- Slider -->
        @if (count($banners) > 0)
            <div class="tf-slideshow slider-effect-fade position-relative hero_section">
                <div class="swiper tf-sw-slideshow" data-preview="1" data-tablet="1" data-mobile="1" data-centered="false"
                    data-space="0" data-loop="true" data-auto-play="false" data-delay="0" data-speed="1000">
                    <div class="swiper-wrapper">

                        @foreach ($banners as $banner)
                            <div class="swiper-slide">
                                <div class="wrap-slider">
                                    <img src="{{ asset('assets/uploads/banners/' . $banner['image']) }}"
                                        alt="fashion-slideshow">
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="wrap-pagination">
                    <div class="container">
                        <div class="sw-dots sw-pagination-slider justify-content-center"></div>
                    </div>
                </div>
            </div>
            @else
            <div style="margin-top: 40px"></div>
        @endif
        <!-- /Slider -->
          @if (count($mainCategories) > 0)
    <section class="flat-spacing-10 flat-categorie">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="text-3 fw-7 text-uppercase title wow fadeInUp d-inline-block cursor-pointer" 
                    onclick="window.location.href='{{ url('collection') }}'">
                    أقسام المتجر
                </h2>
            </div>

            <div class="swiper tf-sw-collection"
                 data-preview="4"
                 data-tablet="3"
                 data-mobile="2"
                 data-space-lg="30"
                 data-space-md="20"
                 data-space="15"
                 data-loop="true"
                 data-auto-play="true"
                 data-delay="4000">

                <div class="swiper-wrapper">

                    @foreach ($mainCategories as $category)
                        <div class="swiper-slide">
                            <div class="collection-item style-left hover-img position-relative overflow-hidden rounded-4">
                                <a href="{{ url('collection/' . $category['slug']) }}" class="d-block">
                                    <img class="lazyload w-100" style="height:220px; object-fit:cover;"
                                         data-src="{{ asset('assets/uploads/category_images/' . $category['image']) }}"
                                         src="{{ asset('assets/uploads/category_images/' . $category['image']) }}"
                                         alt="{{ $category['name'] }}">

                                    <div class="position-absolute bottom-0 start-0 end-0 text-center pb-4">
                                        <span class="d-inline-block bg-white bg-opacity-85 px-4 py-2 rounded-3 shadow-sm fw-600 text-dark fs-15">
                                            {{ $category['name'] }}
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
@endif



        @if (count($bestproducts) > 0)
            <!-- Best seller -->
            <div id="wishlistMessage"></div>
            <section class="flat-spacing-15 new_products">
                <div class="container">
                    <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                        <div>
                            <span class="title wow fadeInUp" data-wow-delay="0s"> الاكثر مبيعا </span>
                            <p class="sub-title wow fadeInUp" data-wow-delay="0s"> اكثر المنتجات مبيعا في المتجر </p>
                        </div>
                        <div>
                            {{-- <a href="{{ url('shop') }}" class="head_read_more"> عرض الكل <i
                                    class="bi bi-arrow-left"></i></a> --}}
                        </div>
                    </div>
                    <div class="hover-sw-nav hover-sw-3">
                        <div class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3" data-mobile="2"
                            data-space-lg="30" data-space-md="15" data-pagination="2" data-pagination-md="3"
                            data-pagination-lg="3">
                            <div class="swiper-wrapper">

                                @foreach ($bestproducts as $product)
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
                                                    <form id="wishlistForm1_{{ $product['id'] }}" method="post"
                                                        action="{{ url('wishlist/store') }}">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product['id'] }}">
                                                        <button type="button" id="addToWishlist1_{{ $product['id'] }}"
                                                            class="box-icon bg_white wishlist btn-icon-action {{ in_array($product['id'], $wishlistProducts) ? 'in-wishlist' : '' }}">
                                                            <span class="icon icon-heart"></span>
                                                            <span class="tooltip"> اضف الي المفضلة </span>
                                                            <span class="icon icon-heart"></span>
                                                        </button>
                                                    </form>

                                                    <style>
                                                        .wishlist .icon-heart {
                                                            color: gray;
                                                        }

                                                        .wishlist.in-wishlist .icon-heart {
                                                            color: red;
                                                        }
                                                    </style>

                                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $('#addToWishlist1_{{ $product['id'] }}').on('click', function(e) {
                                                                e.preventDefault();
                                                                $.ajax({
                                                                    method: 'POST',
                                                                    url: '{{ url('wishlist/store') }}',
                                                                    data: $('#wishlistForm1_{{ $product['id'] }}').serialize() +
                                                                        '&cookie_id={{ Cookie::get('cookie_id') }}',
                                                                    success: function(response) {
                                                                        Toastify({
                                                                            text: response.message,
                                                                            duration: 3000,
                                                                            gravity: "top",
                                                                            position: "right",
                                                                            backgroundColor: "#4CAF50",
                                                                        }).showToast();

                                                                        if (response.wishlistCount) {
                                                                            $('.nav-wishlist .count-box').text(response.wishlistCount);
                                                                        }

                                                                        $('#addToWishlist1_{{ $product['id'] }}').toggleClass('in-wishlist');
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
                                                        <input type="hidden" id="hidden-variation"
                                                            placeholder="دشقفهخر " name="hidden-variation"
                                                            value="">

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
                    </div>
                </div>
            </section>
        @endif



        @if (count($lastproducts) > 0)
            <!-- Start Best Products -->
            <section class="flat-spacing-5 flat-seller new_products">
                <div class="container">
                    <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                        <div>
                            <span class="title wow fadeInUp" data-wow-delay="0s"> احدث المنتجات </span>
                            <p class="sub-title wow fadeInUp" data-wow-delay="0s"> احدث المنتجات في المتجر </p>
                        </div>
                        {{-- <div>
                            <a href="{{ url('shop') }}" class="head_read_more"> عرض الكل <i
                                    class="bi bi-arrow-left"></i></a>
                        </div> --}}

                    </div>
                    <div class="grid-layout loadmore-item wow fadeInUp" data-wow-delay="0s" data-grid="grid-4">

                        @foreach ($lastproducts as $product)
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
                                        <form id="wishlistForm2_{{ $product['id'] }}" method="post"
                                            action="{{ url('wishlist/store') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                            <button type="button" id="addToWishlist2_{{ $product['id'] }}"
                                                class="box-icon bg_white wishlist btn-icon-action {{ in_array($product['id'], $wishlistProducts) ? 'in-wishlist' : '' }}">
                                                <span class="icon icon-heart"></span>
                                                <span class="tooltip"> اضف الي المفضلة </span>
                                                <span class="icon icon-heart"></span>
                                            </button>
                                        </form>

                                        <style>
                                            .wishlist .icon-heart {
                                                color: gray;
                                            }

                                            .wishlist.in-wishlist .icon-heart {
                                                color: red;
                                            }
                                        </style>
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            $(document).ready(function() {
                                                $('#addToWishlist2_{{ $product['id'] }}').on('click', function(e) {
                                                    e.preventDefault();
                                                    $.ajax({
                                                        method: 'POST',
                                                        url: '{{ url('wishlist/store') }}',
                                                        data: $('#wishlistForm2_{{ $product['id'] }}').serialize() +
                                                            '&cookie_id={{ Cookie::get('cookie_id') }}',
                                                        success: function(response) {
                                                            Toastify({
                                                                text: response.message,
                                                                duration: 3000,
                                                                gravity: "top",
                                                                position: "right",
                                                                backgroundColor: "#4CAF50",
                                                            }).showToast();

                                                            if (response.wishlistCount) {
                                                                $('.nav-wishlist .count-box').text(response.wishlistCount);
                                                            }

                                                            $('#addToWishlist2_{{ $product['id'] }}').toggleClass('in-wishlist');
                                                        },
                                                        error: function(xhr, status, error) {
                                                            $('#wishlistMessage').html('<p>حدث خطأ أثناء إضافة المنتج للمفضلة</p>');
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                        <button data-id="{{ $product->id }}" href="" data-bs-toggle="modal"
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
                                            <span class="price old_price"> {{ $product['price'] }} {{ $storeCurrency }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="price main_price"> {{ $product['price'] }} {{ $storeCurrency }}
                                        </span>
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
                                        <form id="addToCart2_{{ $product['id'] }}" class="" method="post"
                                            action="{{ url('cart/add') }}">
                                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                            <input type="hidden" name="number" value="1">
                                            @if (isset($product['discount']) && $product['discount'] != null)
                                                <input type="hidden" name="price" value="{{ $product['discount'] }}">
                                            @else
                                                <input type="hidden" name="price" value="{{ $product['price'] }}">
                                            @endif
                                            <input type="hidden" id="hidden-variation" placeholder="دشقفهخر "
                                                name="hidden-variation" value="">

                                            <button id="addtocartbutton2_{{ $product['id'] }}" class="add-to-cart">
                                                اضف الي السلة
                                            </button>
                                        </form>
                                        <script>
                                            $(document).ready(function() {
                                                $("#addtocartbutton2_{{ $product['id'] }}").on('click', function(e) {
                                                    e.preventDefault();
                                                    $.ajax({
                                                        url: '/cart/add',
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        },
                                                        data: $("#addToCart2_{{ $product['id'] }}").serialize(),
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
                                                            updateCartModal2();

                                                            // إظهار الـ modal بعد الإضافة
                                                            $('#shoppingCart').modal('show');
                                                        },
                                                        error: function(xhr, status, error) {
                                                            $('#wishlistMessage').html('<p>حدث خطأ أثناء إضافة المنتج للسلة </p>');
                                                        }
                                                    });
                                                });

                                                function updateCartModal2() {
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
                        @endforeach


                    </div>
                    <div class="tf-pagination-wrap view-more-button text-center">
                        <button class="tf-btn-loading tf-loading-default style-2 btn-loadmore "><span class="text">
                                مشاهدة
                                المزيد </span></button>
                    </div>
                </div>
            </section>

            <!-- End Best Product  -->
        @endif


        <!-- /Categories -->
        <!---------------- Selected Index Categories ------------------>
        @if (count($selectedCategories) > 0)
            @foreach ($selectedCategories as $category)
                @if (count($category['products']) > 0)
                    <section class="flat-spacing-15 new_products">
                        <div class="container">
                            <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                                <div>
                                    <span class="title wow fadeInUp" data-wow-delay="0s"> {{ $category['name'] }} </span>
                                    {{-- <p class="sub-title wow fadeInUp" data-wow-delay="0s"> اكثر المنتجات مبيعا في
                                    المتجر </p> --}}
                                </div>
                                <div>
                                    <a href="{{ url('collection/' . $category['slug']) }}" class="head_read_more"> عرض
                                        الكل
                                        <i class="bi bi-arrow-left"></i></a>
                                </div>
                            </div>
                            <div class="hover-sw-nav hover-sw-3">
                                <div class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3"
                                    data-mobile="2" data-space-lg="30" data-space-md="15" data-pagination="2"
                                    data-pagination-md="3" data-pagination-lg="3">
                                    <div class="swiper-wrapper">

                                        @foreach ($category['products'] as $product)
                                            <div class="swiper-slide" lazy="true">
                                                <div class="card-product">
                                                    <div class="card-product-wrapper">
                                                        <a href="{{ url('product/' . $product['slug']) }}"
                                                            class="product-img">
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
                                                            <form
                                                                id="wishlistForm3_{{ $category['id'] }}_{{ $product['id'] }}"
                                                                method="post" action="{{ url('wishlist/store') }}">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product['id'] }}">
                                                                <button type="button"
                                                                    id="addToWishlist3_{{ $category['id'] }}_{{ $product['id'] }}"
                                                                    class="box-icon bg_white wishlist btn-icon-action {{ in_array($product['id'], $wishlistProducts) ? 'in-wishlist' : '' }}">
                                                                    <span class="icon icon-heart"></span>
                                                                    <span class="tooltip"> اضف الي المفضلة </span>
                                                                    <span class="icon icon-heart"></span>
                                                                </button>
                                                            </form>

                                                            <style>
                                                                .wishlist .icon-heart {
                                                                    color: gray;
                                                                }

                                                                .wishlist.in-wishlist .icon-heart {
                                                                    color: red;
                                                                }
                                                            </style>
                                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                            <script>
                                                                $(document).ready(function() {
                                                                    $('#addToWishlist3_{{ $category['id'] }}_{{ $product['id'] }}').on('click', function(e) {
                                                                        e.preventDefault();
                                                                        $.ajax({
                                                                            method: 'POST',
                                                                            url: '{{ url('wishlist/store') }}',
                                                                            data: $('#wishlistForm3_{{ $category['id'] }}_{{ $product['id'] }}')
                                                                                .serialize() + '&cookie_id={{ Cookie::get('cookie_id') }}',
                                                                            success: function(response) {
                                                                                Toastify({
                                                                                    text: response.message,
                                                                                    duration: 3000,
                                                                                    gravity: "top",
                                                                                    position: "right",
                                                                                    backgroundColor: "#4CAF50",
                                                                                }).showToast();

                                                                                if (response.wishlistCount) {
                                                                                    $('.nav-wishlist .count-box').text(response.wishlistCount);
                                                                                }

                                                                                $('#addToWishlist3_{{ $category['id'] }}_{{ $product['id'] }}')
                                                                                    .toggleClass('in-wishlist');
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
                                                        <a href="{{ url('product/' . $product['slug']) }}"
                                                            class="title link">
                                                            {{ $product['name'] }} </a>
                                                        @if (isset($product['discount']) && $product['discount'] != null)
                                                            <div class="">
                                                                <span class="price main_price">
                                                                    {{ $product['discount'] }}
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
                                                            <a href="{{ url('product/' . $product['slug']) }}"
                                                                class="add-to-cart">
                                                                مشاهدة الاختيارات
                                                            </a>
                                                        @else
                                                            <form
                                                                id="addToCart3_{{ $category['id'] }}_{{ $product['id'] }}"
                                                                class="" method="post"
                                                                action="{{ url('cart/add') }}">
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
                                                                <input type="hidden" id="hidden-variation"
                                                                    placeholder="دشقفهخر " name="hidden-variation"
                                                                    value="">

                                                                <button
                                                                    id="addtocartbutton_{{ $category['id'] }}_{{ $product['id'] }}"
                                                                    class="add-to-cart">
                                                                    اضف الي السلة
                                                                </button>
                                                            </form>
                                                            <script>
                                                                $(document).ready(function() {
                                                                    $("#addtocartbutton_{{ $category['id'] }}_{{ $product['id'] }}").on('click', function(e) {
                                                                        e.preventDefault();
                                                                        $.ajax({
                                                                            url: '/cart/add',
                                                                            method: 'POST',
                                                                            headers: {
                                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                            },
                                                                            data: $("#addToCart3_{{ $category['id'] }}_{{ $product['id'] }}")
                                                                                .serialize(),
                                                                            success: function(response) {
                                                                                // عرض الرسالة باستخدام Toastify
                                                                                Toastify({
                                                                                    text: response.message, // عرض الرسالة من response
                                                                                    duration: 3000, // المدة الزمنية لعرض الرسالة
                                                                                    gravity: "top", // اتجاه العرض
                                                                                    position: "right", // موقع الرسالة
                                                                                    backgroundColor: "#4CAF50", // لون الخلفية للرسالة
                                                                                }).showToast();
                                                                                if (response.cartCount) {
                                                                                    $('.nav-cart .count-box').text(response.cartCount);
                                                                                }
                                                                                // تحميل محتوى عربة التسوق المحدثة
                                                                                updateCartModal3();
                                                                                // إظهار الـ modal بعد الإضافة
                                                                                $('#shoppingCart').modal('show');
                                                                            },
                                                                            error: function(xhr, status, error) {
                                                                                $('#wishlistMessage').html('<p>حدث خطأ أثناء إضافة المنتج للسلة </p>');
                                                                            }
                                                                        });
                                                                    });

                                                                    function updateCartModal3() {
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
                            </div>
                        </div>
                    </section>
                @endif
            @endforeach
        @endif

        @if (count($reviews) > 0)
            <!-- Testimonial -->
            <br>
            <br>
            <section class="flat-spacing-5 pt_0 flat-testimonial">
                <div class="container">
                    <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                        <div>
                            <span class="title"> آراء العملاء </span>
                            <p class="sub-title"> ماذا يقول العملاء عنا </p>
                        </div>

                    </div>
                    <div class="wrap-carousel">
                        <div class="swiper tf-sw-testimonial" data-preview="3" data-tablet="2" data-mobile="1"
                            data-space-lg="30" data-space-md="15">
                            <div class="swiper-wrapper">

                                @foreach ($reviews as $review)
                                    <div class="swiper-slide">
                                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay="0s">
                                            <div class="rating">
                                                @for ($i = 0; $i < $review['star']; $i++)
                                                    <i class="icon-start filled"></i>
                                                    <!-- Filled star icon for the rating -->
                                                @endfor
                                                @for ($i = $review['star']; $i < 5; $i++)
                                                    <i class="icon-start empty"></i>
                                                    <!-- Empty star icon for the remaining stars -->
                                                @endfor
                                            </div>
                                            <div class="heading"> {{ $review['name'] }} </div>
                                            <div class="text">
                                                {!! $review['description'] !!}
                                            </div>

                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <style>
                                .icon-start.empty {
                                    font-size: 20px;
                                    color: #ddd;
                                    !important;
                                    /* Default color for empty stars */
                                }

                                .icon-start.filled {
                                    color: #ffbf00;
                                    /* Color for filled stars */
                                }
                            </style>
                        </div>
                        <div class="nav-sw nav-next-slider nav-next-testimonial lg"><span
                                class="icon icon-arrow-left"></span>
                        </div>
                        <div class="nav-sw nav-prev-slider nav-prev-testimonial lg"><span
                                class="icon icon-arrow-right"></span></div>
                        <div class="sw-dots style-2 sw-pagination-testimonial justify-content-center"></div>
                    </div>
                </div>
            </section>
            <!-- /Testimonial -->
        @endif
        <!-- Brand -->
        @if (count($brands) > 0)
            <section class="flat-spacing-12">
                <div class="">
                    <div class="wrap-carousel wrap-brand wrap-brand-v2 autoplay-linear">
                        <div class="swiper tf-sw-brand border-0" data-play="true" data-loop="true" data-preview="6"
                            data-tablet="4" data-mobile="2" data-space-lg="30" data-space-md="15">
                            <div class="swiper-wrapper">
                                @foreach ($brands as $brand)
                                    <div class="swiper-slide">
                                        <div class="brand-item-v2">
                                            <img style="max-width: 150px;" class="lazyload"
                                                data-src="{{ asset('assets/uploads/brands/' . $brand['image']) }}"
                                                src="{{ asset('assets/uploads/brands/' . $brand['image']) }}"
                                                alt="{{ $brand['name'] }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- /Brand -->
        <!-- Icon box -->
        @if (count($advantages) > 0)
            <section class="flat-spacing-7 flat-iconbox wow fadeInUp" data-wow-delay="0s">
                <div class="container">
                    <div class="wrap-carousel wrap-mobile">
                        <div class="swiper tf-sw-mobile" data-preview="1" data-space="15">
                            <div class="swiper-wrapper wrap-iconbox">
                                @foreach ($advantages as $advantage)
                                    <div class="swiper-slide">
                                        <div class="tf-icon-box style-border-line text-center">
                                            <div class="icon">
                                                <i class="fas {{ $advantage['icon'] }}"></i>
                                            </div>
                                            <div class="content">
                                                <div class="title"> {{ $advantage['name'] }} </div>
                                                <p> {{ $advantage['description'] }} </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <div class="sw-dots style-2 sw-pagination-mb justify-content-center"></div>
                    </div>
                </div>
            </section>
        @endif
        <!-- /Icon box -->
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

    @if (isset($product))
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
                            '{{ $storeCurrency }}' : 'غير متوفر';

                        if (data.discount && data.discount > 0) {
                            // عرض السعر بعد التخفيض إذا كان موجودًا
                            document.getElementById('discounted-price').innerText = data.discount +
                                '{{ $storeCurrency }}';
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
    @endif
@endsection
