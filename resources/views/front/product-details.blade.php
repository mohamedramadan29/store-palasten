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
            <div class="flex-wrap tf-breadcrumb-wrap d-flex justify-content-between align-items-center">
                <div class="tf-breadcrumb-list">
                    <a href="{{ url('/') }}" class="text"> الرئيسية </a>
                    <i class="icon icon-arrow-right"></i>
                    <a href="{{ url('collection/' . $product['Main_Category']['slug']) }}" class="text">{{
                        $product['Main_Category']['name'] }}</a>
                    <i class="icon icon-arrow-right"></i>
                    <span class="text"> {{ $product['name'] }} </span>
                </div>
            </div>
            {{-- <div class="text-center heading">{{ $product['name'] }}</div> --}}
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
                            <div class="thumbs-bottom">
                                <div class="thumbs-slider thumbs-default"
                                    style="display: flex !important; flex-direction: column !important; gap: 20px !important;">
                                    <div class="swiper tf-product-media-main tf-product-media-main-default"
                                        style="width: 100% !important; min-height: 400px !important; position: relative;">
                                        <!-- Overlay for variant name -->
                                        <div id="variant-overlay"
                                            style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.6); padding: 3px 12px; border-radius: 5px; font-weight: 500; font-size: 14px; color: #000; z-index: 100; display: none; backdrop-filter: blur(2px); border: 1px solid rgba(255,255,255,0.3);">
                                        </div>
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <a href="javascript:void(0);" class="item">
                                                    <img id="main-product-image"
                                                        src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                        alt="{{ $product['name'] }}"
                                                        style="width: 100%; height: 400px; display: block; border: 1px solid #ccc;">
                                                </a>
                                            </div>
                                            @if (isset($gallary) && count($gallary) > 0)
                                            @foreach ($gallary as $gallary_item)
                                            <div class="swiper-slide">
                                                <a href="javascript:void(0);" class="item">
                                                    <img src="{{ asset('assets/uploads/product_gallery/' . $gallary_item['image']) }}"
                                                        alt="{{ $product['name'] }}"
                                                        style="width: 100%; height: 400px; display: block; border: 1px solid #ccc;">
                                                </a>
                                            </div>
                                            @endforeach
                                            @endif
                                            @php
                                            $uniqueVariationImages = [];
                                            @endphp
                                            @foreach ($productVariations as $variation)
                                            @if ($variation['image'] && !in_array($variation['image'],
                                            $uniqueVariationImages))
                                            @php
                                            $uniqueVariationImages[] = $variation['image'];
                                            @endphp
                                            <div class="swiper-slide" data-variation-id="{{ $variation['id'] }}">
                                                <a href="javascript:void(0);" class="item">
                                                    <img src="{{ asset('assets/uploads/product_images/' . $variation['image']) }}"
                                                        alt="{{ $product['name'] }}"
                                                        style="width: 100%; height: 400px; display: block; border: 1px solid #ccc;">
                                                </a>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="swiper-button-next button-style-arrow thumbs-next"></div>
                                        <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>
                                    </div>
                                    <div class="swiper tf-product-media-thumbs tf-product-media-thumbs-default"
                                        data-direction="horizontal"
                                        style="width: 100% !important; min-height: 80px !important;">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <div class="item">
                                                    <img src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                                                        alt="{{ $product['name'] }}"
                                                        style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #eee; border-radius: 5px;">
                                                </div>
                                            </div>
                                            @if (isset($gallary) && count($gallary) > 0)
                                            @foreach ($gallary as $gallary_item)
                                            <div class="swiper-slide">
                                                <div class="item">
                                                    <img src="{{ asset('assets/uploads/product_gallery/' . $gallary_item['image']) }}"
                                                        alt="{{ $product['name'] }}"
                                                        style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #eee; border-radius: 5px;">
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                            @php
                                            $uniqueThumbImages = [];
                                            @endphp
                                            @foreach ($productVariations as $variation)
                                            @if ($variation['image'] && !in_array($variation['image'],
                                            $uniqueThumbImages))
                                            @php
                                            $uniqueThumbImages[] = $variation['image'];
                                            @endphp
                                            <div class="swiper-slide" data-variation-id="{{ $variation['id'] }}">
                                                <div class="item">
                                                    <img src="{{ asset('assets/uploads/product_images/' . $variation['image']) }}"
                                                        alt="{{ $product['name'] }}"
                                                        style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #eee; border-radius: 5px;">
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
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
                                {{-- <div class="tf-product-info-badges"> --}}
                                    {{-- <div class="product-status-content"> --}}
                                        {{-- <p class="fw-6">{{$product['short_description']}}</p> --}}
                                        {{-- </div> --}}
                                    {{-- </div> --}}
                                <!-- عرض خيارات السمات -->
                                <form id="addToCart_{{ $product['id'] }}" class="" method="post"
                                    action="{{ url('cart/add') }}">
                                    @csrf
                                    <div class="tf-product-info-variant-picker">
                                        @if ($productVariations->count() > 0)
                                        @foreach ($variationAttributes as $attributeId => $attribute)
                                        <div class="variant-picker-item">
                                            <div class="mb-2 variant-picker-label">
                                                {{ $attribute['name'] }}: <span class="fw-6 selected-value">{{
                                                    $attribute['values'][0] }}</span>
                                            </div>
                                            <div class="variant-picker-values">
                                                @foreach ($attribute['values'] as $index => $value)
                                                <input type="radio" id="attribute_{{ $attributeId }}_{{ $index }}"
                                                    name="attribute_values[{{ $attributeId }}]" value="{{ $value }}"
                                                    @if($index==0) checked @endif
                                                    onchange="fetchPrice2(); updateOverlayText(); this.closest('.variant-picker-item').querySelector('.selected-value').innerText = this.value;"
                                                    class="d-none">
                                                <label class="style-text"
                                                    for="attribute_{{ $attributeId }}_{{ $index }}">
                                                    <p>{{ $value }}</p>
                                                </label>
                                                @endforeach
                                            </div>
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
                                        <div id="stock-status" class="mt-2 tf-product-info-stock">
                                            <!-- سيتم تحديث حالة المخزون هنا -->
                                        </div>
                                        <br>
                                        <!-- حقول مخفية للسعر والمتغيرات -->
                                        <input type="hidden" placeholder="سعر المتغير " id="hidden-price" name="price"
                                            value="">
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

                                        <div id="stock-status" class="mt-2 tf-product-info-stock">
                                            @if($product->quantity > 0)
                                            <span class="badge bg-success">متوفر: {{ $product->quantity }}</span>
                                            @else
                                            <span class="badge bg-danger">غير متوفر حالياً</span>
                                            @endif
                                        </div>
                                        @if (isset($product['discount']) && $product['discount'] != null)
                                        <input type="hidden" name="price" value="{{ $product['discount'] }}">
                                        @else
                                        <input type="hidden" name="price" value="{{ $product['price'] }}">
                                        @endif
                                        @endif

                                    </div>
                                    <div class="quantity_and_addtocart">
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
                                                class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"
                                                @if($productVariations->count() == 0 && $product->quantity <= 0)
                                                    disabled style="background-color: #ccc; cursor: not-allowed;"
                                                    @endif>
                                                    <span>
                                                        @if($productVariations->count() == 0 && $product->quantity <= 0)
                                                            غير متوفر @else اضف الي السلة @endif </span></button>
                                        </div>
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

                                            // تهيئة Swiper للمصغرات كـ carousel
                                            if (document.querySelector('.tf-product-media-thumbs-default')) {
                                                var thumbSwiper = new Swiper('.tf-product-media-thumbs-default', {
                                                    direction: 'horizontal',
                                                    slidesPerView: 'auto',
                                                    spaceBetween: 10,
                                                    freeMode: true,
                                                    watchSlidesProgress: true,
                                                    breakpoints: {
                                                        0: {
                                                            slidesPerView: 4,
                                                        },
                                                        768: {
                                                            slidesPerView: 5,
                                                        },
                                                        1024: {
                                                            slidesPerView: 6,
                                                        }
                                                    }
                                                });

                                                var mainSwiper = new Swiper('.tf-product-media-main-default', {
                                                    spaceBetween: 10,
                                                    navigation: {
                                                        nextEl: '.thumbs-next',
                                                        prevEl: '.thumbs-prev',
                                                    },
                                                    thumbs: {
                                                        swiper: thumbSwiper,
                                                    },
                                                });
                                            }

                                            // تحديث النص على الصورة
                                            window.updateOverlayText = function() {
                                                let selectedValues = [];
                                                document.querySelectorAll('#addToCart_{{ $product['id'] }} .variant-picker-item input[type="radio"]:checked').forEach(input => {
                                                    selectedValues.push(input.value);
                                                });
                                                let overlay = document.getElementById('variant-overlay');
                                                if (selectedValues.length > 0) {
                                                    overlay.innerText = selectedValues.join(' / ');
                                                    overlay.style.display = 'block';
                                                } else {
                                                    overlay.style.display = 'none';
                                                }
                                            }

                                            // جلب السعر عند التحميل إذا كان هناك متغيرات
                                            if (document.querySelector('.variant-picker-item')) {
                                                fetchPrice2();
                                                updateOverlayText();
                                            }
                                        });
                                </script>

                                <script>
                                    function fetchPrice2() {
                                            let form = document.getElementById('addToCart_{{ $product['id'] }}');
                                            let formData = new FormData(form);
                                            let productId = formData.get('product_id');

                                            fetch(`/product/${productId}/get-price`, {
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

                                                    // تحديث حالة المخزون
                                                    let stockStatus = document.getElementById('stock-status');
                                                    let addToCartBtn = document.getElementById('addtocartbutton_{{ $product['id'] }}');
                                                    
                                                    if (data.stock !== undefined) {
                                                        if (data.stock > 0) {
                                                            stockStatus.innerHTML = `<span class="badge bg-success">متوفر: ${data.stock}</span>`;
                                                            addToCartBtn.disabled = false;
                                                            addToCartBtn.style.backgroundColor = "";
                                                            addToCartBtn.style.cursor = "";
                                                            addToCartBtn.querySelector('span').innerText = "اضف الي السلة";
                                                        } else {
                                                            stockStatus.innerHTML = `<span class="badge bg-danger">غير متوفر حالياً</span>`;
                                                            addToCartBtn.disabled = true;
                                                            addToCartBtn.style.backgroundColor = "#ccc";
                                                            addToCartBtn.style.cursor = "not-allowed";
                                                            addToCartBtn.querySelector('span').innerText = "غير متوفر";
                                                        }
                                                    }

                                                    // التوجه للصورة المناسبة في السليدر
                                                    if (data.variation_id) {
                                                        const mainSwiperEl = document.querySelector('.tf-product-media-main-default');
                                                        if (mainSwiperEl && mainSwiperEl.swiper) {
                                                            const swiper = mainSwiperEl.swiper;
                                                            const slides = swiper.slides;
                                                            let targetIndex = -1;

                                                            for (let i = 0; i < slides.length; i++) {
                                                                if (slides[i].getAttribute('data-variation-id') == data.variation_id) {
                                                                    targetIndex = i;
                                                                    break;
                                                                }
                                                            }

                                                            if (targetIndex !== -1) {
                                                                swiper.slideTo(targetIndex);
                                                            } else if (data.image) {
                                                                // إذا لم نجد سلايد خاص، نغير الصورة في السلايد الأول
                                                                const mainImg = document.getElementById('main-product-image');
                                                                if (mainImg) {
                                                                    mainImg.src = data.image;
                                                                    mainImg.setAttribute('data-src', data.image);
                                                                    swiper.slideTo(0);
                                                                }
                                                            }
                                                        }
                                                    }
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
                                        <source src="{{ asset('assets/uploads/product_videos/' . $product->video) }}"
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
                                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
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
                                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                        <input type="hidden" name="number" value="1">
                                        @if (isset($product['discount']) && $product['discount'] != null)
                                        <input type="hidden" name="price" value="{{ $product['discount'] }}">
                                        @else
                                        <input type="hidden" name="price" value="{{ $product['price'] }}">
                                        @endif
                                        <input type="hidden" id="hidden-variation" placeholder="دشقفهخر "
                                            name="hidden-variation" value="">

                                        <button id="addtocartbutton_{{ $product['id'] }}" class="add-to-cart">
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
    function fetchPrice() {
            let form = document.getElementById('addToCart');
            let formData = new FormData(form);
            let productId = formData.get('product_id');

            fetch(`/product/${productId}/get-price`, {
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

                    // تحديث الصورة في المودال (إذا وجد)
                    if (data.image) {
                        const modalImg = document.getElementById('main-product-image-modal');
                        if (modalImg) {
                            modalImg.src = data.image;
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
</script>
@endsection