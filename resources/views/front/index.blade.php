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
    @if (Session::has('error'))
    @php
    toastify()->error(\Illuminate\Support\Facades\Session::get('error'));
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
                        <img src="{{ asset('assets/uploads/banners/' . $banner['image']) }}" alt="fashion-slideshow">
                    </div>
                </div>
                @endforeach

            </div>
        </div>
        <div class="wrap-pagination">
            <div class="container position-relative">
                <!-- Dots positioned on the right using Native Swiper classes -->
                <div class="sw-dots sw-pagination-slider justify-content-start"></div>
                
                <!-- Social media positioned absolutely on the left -->
                @php
                    $socialmedia = \App\Models\admin\SocialMedia::first();
                @endphp
                @if($socialmedia)
                <div class="slider-social-icons position-absolute" style="left: 15px; bottom: 0; z-index: 22; transform: translateY(-50%);">
                    <ul class="flex-wrap gap-2 m-0 tf-social-icon d-flex" style="list-style: none; padding: 0;">
                        @if ($socialmedia['facebook'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['facebook'] }}" class="box-icon w_28 round social-facebook bg_white"><i class="icon fs-12 icon-fb"></i></a></li>
                        @endif
                        @if ($socialmedia['instagram'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['instagram'] }}" class="box-icon w_28 round social-instagram bg_white"><i class="icon fs-12 icon-instagram"></i></a></li>
                        @endif
                        @if ($socialmedia->linkedin)
                            <li><a target="_blank" href="{{ $socialmedia->linkedin }}" class="box-icon w_28 round social-linkedin bg_white"><i class="bi fs-12 bi-linkedin"></i></a></li>
                        @endif
                        @if ($socialmedia['x-twitter'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['x-twitter'] }}" class="box-icon w_28 round social-twiter bg_white"><i class="icon fs-10 icon-Icon-x"></i></a></li>
                        @endif
                        @if ($socialmedia['youtube'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['youtube'] }}" class="box-icon w_28 round social-twiter bg_white"><i class="icon fs-10 icon-youtube"></i></a></li>
                        @endif
                        @if ($socialmedia['whatsapp'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['whatsapp'] }}" class="box-icon w_28 round social-twiter bg_white"><i class="icon fs-10 icon-whatsapp"></i></a></li>
                        @endif
                        @if ($socialmedia['tiktok'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['tiktok'] }}" class="box-icon w_28 round social-tiktok bg_white"><i class="icon fs-12 icon-tiktok"></i></a></li>
                        @endif
                        @if ($socialmedia['pinterest'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['pinterest'] }}" class="box-icon w_28 round social-pinterest bg_white"><i class="icon fs-12 icon-pinterest-1"></i></a></li>
                        @endif
                        @if ($socialmedia['snapchat'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['snapchat'] }}" class="box-icon w_28 round social-pinterest bg_white"><i class="fs-12 bi bi-snapchat"></i></a></li>
                        @endif
                        @if ($socialmedia['telegram'] != '')
                            <li><a target="_blank" href="{{ $socialmedia['telegram'] }}" class="box-icon w_28 round social-facebook bg_white"><i class="fs-12 bi bi-telegram"></i></a></li>
                        @endif
                    </ul>
                </div>
                @endif
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
            <div class="mb-5 text-center">
                <h2 class="cursor-pointer text-3 fw-7 text-uppercase title wow fadeInUp d-inline-block"
                    onclick="window.location.href='{{ url('collection') }}'">
                    أقسام المتجر
                </h2>
            </div>

            <div class="swiper tf-sw-collection" data-preview="4" data-tablet="3" data-mobile="2" data-space-lg="30"
                data-space-md="20" data-space="15" data-loop="true" data-auto-play="true" data-delay="4000">

                <div class="swiper-wrapper">

                    @foreach ($mainCategories as $category)
                    <div class="swiper-slide">
                        <div class="overflow-hidden collection-item style-left hover-img position-relative rounded-4">
                            <a href="{{ url('collection/' . $category['slug']) }}" class="d-block">
                                <img class="lazyload w-100" style="height:220px; object-fit:cover;"
                                    data-src="{{ asset('assets/uploads/category_images/' . $category['image']) }}"
                                    src="{{ asset('assets/uploads/category_images/' . $category['image']) }}"
                                    alt="{{ $category['name'] }}">

                                <div class="bottom-0 pb-4 text-center position-absolute start-0 end-0">
                                    <span
                                        class="px-4 py-2 bg-white shadow-sm d-inline-block bg-opacity-85 rounded-3 fw-600 text-dark fs-15">
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



    @if (count($bestproducts) > 0 && $public_setting->show_best_selling == 1)
    <!-- Best seller -->
    <div id="wishlistMessage"></div>
    <section class="flat-spacing-15 new_products">
        <div class="container">
            <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                <div>
                    <div class="gap-3 mb-3 d-flex align-items-center">
                        <span class="title wow fadeInUp" data-wow-delay="0s"> الاكثر مبيعا </span>
                        <span class="px-3 py-1 text-white d-inline-block rounded-2 fw-600 fs-13">
                         
                        </span>
                    </div>
                    {{-- <p class="sub-title wow fadeInUp" data-wow-delay="0s"> اكثر المنتجات مبيعا في المتجر </p> --}}
                </div>
                <div>
                    <a href="{{ url('shop') }}" class="head_read_more">    {{ count($bestproducts) }} منتج </a>
                </div>
            </div>
            <div class="grid-layout wow" id="bestProductsGrid" data-wow-delay="0s" data-grid="grid-4" style="gap: 15px;">
                @foreach ($bestproducts as $product)
                <div class="best-product-item" style="display: {{ $loop->iteration <= 6 ? 'block' : 'none' }};">
                    @include('front.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>

            <div class="gap-2 pt-2 mt-4 d-flex justify-content-center gap-md-3">
                @if(count($bestproducts) > 6)
                <button id="loadMoreBestProducts" class="tf-btn-loading tf-loading-default style-2 w-50" style="max-width: 200px; min-height: 48px; border-radius: 4px;">
                    <span class="text fs-15"><i class="icon icon-plus fs-5 pe-1"></i> عرض المزيد </span>
                </button>
                @endif
                <a href="{{ url('shop') }}" class="text-center tf-btn-loading tf-loading-default style-2 w-50" style="max-width: 200px; min-height: 48px; border-radius: 4px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <span class="text fs-15">عرض الكل</span>
                </a>
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                let currentBest = 6;
                const totalBest = {{ count($bestproducts) }};
                const bestItems = document.querySelectorAll('.best-product-item');
                const bestBtn = document.getElementById('loadMoreBestProducts');

                if (bestBtn) {
                    bestBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentBest += 6;
                        bestItems.forEach((item, index) => {
                            if(index < currentBest) {
                                item.style.display = 'block';
                            }
                        });
                        if (currentBest >= totalBest) {
                            bestBtn.style.display = 'none';
                        }
                    });
                }
            });
            </script>
        </div>
    </section>
    @endif



    @if (count($lastproducts) > 0 && $public_setting->show_latest_products == 1)
    <!-- Start Best Products -->
    <section class="flat-spacing-5 flat-seller new_products">
        <div class="container">
            <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                <div>
                    <div class="gap-3 mb-3 d-flex align-items-center">
                        <span class="title wow fadeInUp" data-wow-delay="0s"> احدث المنتجات </span> 
                    </div>
                    {{-- <p class="sub-title wow fadeInUp" data-wow-delay="0s"> احدث المنتجات في المتجر </p> --}}
                </div>
                <div>
                    <a href="{{ url('shop') }}" class="head_read_more">   {{ count($lastproducts) }} منتج </a>
                </div>

            </div>
            <div class="grid-layout wow" id="lastProductsGrid" data-wow-delay="0s" data-grid="grid-4" style="gap: 15px;">
                @foreach ($lastproducts as $product)
                <div class="last-product-item" style="display: {{ $loop->iteration <= 6 ? 'block' : 'none' }};">
                    @include('front.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>

            <div class="gap-2 pt-2 mt-4 d-flex justify-content-center gap-md-3">
                @if(count($lastproducts) > 6)
                <button id="loadMoreLastProducts" class="tf-btn-loading tf-loading-default style-2 w-50" style="max-width: 200px; min-height: 48px; border-radius: 4px;">
                    <span class="text fs-15"><i class="icon icon-plus fs-5 pe-1"></i> عرض المزيد </span>
                </button>
                @endif
                <a href="{{ url('shop') }}" class="text-center tf-btn-loading tf-loading-default style-2 w-50" style="max-width: 200px; min-height: 48px; border-radius: 4px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <span class="text fs-15">عرض الكل</span>
                </a>
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                let currentLast = 6;
                const totalLast = {{ count($lastproducts) }};
                const lastItems = document.querySelectorAll('.last-product-item');
                const lastBtn = document.getElementById('loadMoreLastProducts');

                if (lastBtn) {
                    lastBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentLast += 6;
                        lastItems.forEach((item, index) => {
                            if(index < currentLast) {
                                item.style.display = 'block';
                            }
                        });
                        if (currentLast >= totalLast) {
                            lastBtn.style.display = 'none';
                        }
                    });
                }
            });
            </script>
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
                    <div class="gap-3 mb-3 d-flex">
                        <span class="title wow fadeInUp" data-wow-delay="0s"> {{ $category['name'] }} </span>
                         
                    </div>
                    {{-- <p class="sub-title wow fadeInUp" data-wow-delay="0s"> اكثر المنتجات مبيعا في
                        المتجر </p> --}}
                </div>
                  <div>
                    <a href="{{ url('shop') }}" class="head_read_more">    {{ count($category['products']) }} منتج </a>
                </div>

            </div>
            
            <div class="grid-layout wow" id="catProductsGrid_{{ $category['id'] }}" data-wow-delay="0s" data-grid="grid-4" style="gap: 15px;">
                @foreach ($category['products'] as $product)
                <div class="cat-product-item-{{ $category['id'] }}" style="display: {{ $loop->iteration <= 6 ? 'block' : 'none' }};">
                    @include('front.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>

            <div class="gap-2 pt-2 mt-4 d-flex justify-content-center gap-md-3">
                @if(count($category['products']) > 6)
                <button id="loadMoreCatProducts_{{ $category['id'] }}" class="tf-btn-loading tf-loading-default style-2 w-50" style="max-width: 200px; min-height: 48px; border-radius: 4px;">
                    <span class="text fs-15"><i class="icon icon-plus fs-5 pe-1"></i> عرض المزيد </span>
                </button>
                @endif
                <a href="{{ url('collection/' . $category['slug']) }}" class="text-center tf-btn-loading tf-loading-default style-2 w-50" style="max-width: 200px; min-height: 48px; border-radius: 4px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <span class="text fs-15">عرض الكل</span>
                </a>
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                let currentCat_{{ $category['id'] }} = 6;
                const totalCat_{{ $category['id'] }} = {{ count($category['products']) }};
                const catItems_{{ $category['id'] }} = document.querySelectorAll('.cat-product-item-{{ $category['id'] }}');
                const catBtn_{{ $category['id'] }} = document.getElementById('loadMoreCatProducts_{{ $category['id'] }}');

                if (catBtn_{{ $category['id'] }}) {
                    catBtn_{{ $category['id'] }}.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentCat_{{ $category['id'] }} += 6;
                        catItems_{{ $category['id'] }}.forEach((item, index) => {
                            if(index < currentCat_{{ $category['id'] }}) {
                                item.style.display = 'block';
                            }
                        });
                        if (currentCat_{{ $category['id'] }} >= totalCat_{{ $category['id'] }}) {
                            catBtn_{{ $category['id'] }}.style.display = 'none';
                        }
                    });
                }
            });
            </script>
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
                    <div class="gap-3 mb-3 d-flex align-items-center">
                        <span class="title"> آراء العملاء </span>
                        {{-- <span class="px-3 py-1 text-white bg-primary d-inline-block rounded-2 fw-600 fs-13">
                            {{ count($reviews) }} تقييم
                        </span> --}}
                    </div>
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
                                    @for ($i = 0; $i < $review['star']; $i++) <i class="icon-start filled"></i>
                                        <!-- Filled star icon for the rating -->
                                        @endfor
                                        @for ($i = $review['star']; $i < 5; $i++) <i class="icon-start empty"></i>
                                            <!-- Empty star icon for the remaining stars -->
                                            @endfor
                                </div>
                                <div class="heading"> {{ $review['name'] }} </div>
                                <div class="text" style="height: 120px;overflow-y: scroll;">
                                    {!! $review['description'] !!}
                                </div>
                                @if(!empty($review->image))
                                <div class="author">
                                    <div class="image">
                                        <img src="{{ asset('assets/uploads/reviews/' . $review->image) }}"
                                            alt="{{ $review['name'] }}"
                                            style="width: 100%;height: auto; margin-top: 15px;border-radius:10px; max-height: 120px; object-fit: cover; object-position: center;">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <style>
                        .icon-start.empty {
                            font-size: 20px;
                            color: #ddd !important;
                            /* Default color for empty stars */
                        }

                        .icon-start.filled {
                            color: #ffbf00;
                            /* Color for filled stars */
                        }
                    </style>
                </div>
                <div class="nav-sw nav-next-slider nav-next-testimonial lg"><span class="icon icon-arrow-left"></span>
                </div>
                <div class="nav-sw nav-prev-slider nav-prev-testimonial lg"><span class="icon icon-arrow-right"></span>
                </div>
                <div class="sw-dots style-2 sw-pagination-testimonial justify-content-center"></div>
            </div>
            <div class="mt-4 text-center view-more-button">
                <a href="{{ url('all-reviews') }}"
                    class="tf-btn-loading btn tf-loading-default style-2 btn-loadmore"><span class="text">
                        مشاهدة جميع الاراء </span></a>
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
                <div class="border-0 swiper tf-sw-brand" data-play="true" data-loop="true" data-preview="6"
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
                            <div class="text-center tf-icon-box style-border-line">
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