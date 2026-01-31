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
            <div class="mb-5 text-center">
                <h2 class="cursor-pointer text-3 fw-7 text-uppercase title wow fadeInUp d-inline-block" 
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
                            <div class="overflow-hidden collection-item style-left hover-img position-relative rounded-4">
                                <a href="{{ url('collection/' . $category['slug']) }}" class="d-block">
                                    <img class="lazyload w-100" style="height:220px; object-fit:cover;"
                                         data-src="{{ asset('assets/uploads/category_images/' . $category['image']) }}"
                                         src="{{ asset('assets/uploads/category_images/' . $category['image']) }}"
                                         alt="{{ $category['name'] }}">

                                    <div class="bottom-0 pb-4 text-center position-absolute start-0 end-0">
                                        <span class="px-4 py-2 bg-white shadow-sm d-inline-block bg-opacity-85 rounded-3 fw-600 text-dark fs-15">
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
                                        @include('front.partials.product-card', ['product' => $product])
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
                            @include('front.partials.product-card', ['product' => $product])
                        @endforeach


                    </div>
                    <div class="text-center tf-pagination-wrap view-more-button">
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
                                                @include('front.partials.product-card', ['product' => $product])
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
    <script>
        });
    </script>

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
