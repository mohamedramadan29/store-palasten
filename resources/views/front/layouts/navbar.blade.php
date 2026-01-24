<!--------------------------- Get Website Colors ------------------->

@php

    $colors = \App\Models\admin\Color::first();

    $website_background = $colors['website_background'];
    $top_navbar_background = $colors['top_navbar_background'];
    $second_navbar_background = $colors['second_navbar_background'];
    $third_navbar_background = $colors['third_navbar_background'];
    $main_title_color = $colors['main_title_color'];
    $all_button_background = $colors['all_button_background'];
    $main_price_color = $colors['main_price_color'];
    $public_add_to_cart_background = $colors['public_add_to_cart_background'];
    $public_add_to_cart_color = $colors['public_add_to_cart_color'];
    $footer_background = $colors['footer_background'];
    $footer_color = $colors['footer_color'];

@endphp



<style>
    * {
        --website_background: <?php echo $website_background; ?>;
        --top_navbar_background: <?php echo $top_navbar_background; ?>;
        --second_navbar_background: <?php echo $second_navbar_background; ?>;
        --third_navbar_background: <?php echo $third_navbar_background; ?>;
        --main_title_color: <?php echo $main_title_color; ?>;
        --all_button_background: <?php echo $all_button_background; ?>;
        --main_price_color: <?php echo $main_price_color; ?>;
        --public_add_to_cart_background: <?php echo $public_add_to_cart_background; ?>;
        --public_add_to_cart_color: <?php echo $public_add_to_cart_color; ?>;
        --footer_background: <?php echo $footer_background; ?>;
        --footer_color: <?php echo $footer_color; ?>;
    }
</style>


<!-- /preload -->
<div id="wrapper">
    @php
        $topnavs = \App\Models\admin\TopNavBar::where('status', 1)->get();
    @endphp
    @if (count($topnavs) > 0)
        <!-- Top Bar -->
        <div class="tf-top-bar line" style="background-color:var(--top_navbar_background);">
            <div class="px_15 lg-px_40">
                <div class="tf-top-bar_wrap gap-30 align-items-center">
                    <div class="text-center overflow-hidden">
                        <div class="swiper tf-sw-top_bar" data-preview="1" data-space="0" data-loop="true" data-speed="1000"
                            data-delay="2000">
                            <div class="swiper-wrapper">

                                @foreach ($topnavs as $nav)
                                    <div class="swiper-slide">
                                        <p class="top-bar-text fw-5"> {{ $nav['content'] }}
                                            @if ($nav['button'] != '')
                                                <a href="{{ $nav['link'] }}" title="all collection"
                                                    class="tf-btn btn-line"> {{ $nav['button'] }} <i
                                                        class="icon icon-arrow1-top-left"></i></a>
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Top Bar -->
    @endif
    @php
        $settings = \App\Models\admin\PublicSetting::select('website_logo')->first();
        $socialmedia = \App\Models\admin\SocialMedia::first();
    @endphp
    <!-- Search Top Bar -->
    <div class="top_navbar">
        <div class="sections">
            <div class="logo_section">
                <a href="{{url('/')}}">
                <img src=" {{ asset('assets/uploads/PublicSetting/' . $settings->website_logo) }}" alt="logo">
                </a>


            </div>
            <div class="search_section">
                <form method="get" action="{{ url('main-search') }}" id="search_form">
                    @csrf
                    <div class="search">
                        @php
                            $categories = \App\Models\admin\MainCategory::with('SubCategories')
                                ->where('status', 1)
                                ->get();
                        @endphp
                        <select class="" id="category-select" name="category">
                            <option value=""> الكل</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}"> {{ $category['name'] }} </option>
                            @endforeach
                        </select><input id="search-input" name="product_name" class="search_input" type="text"
                            placeholder="اكتب كلمة البحث">
                        <button type="submit"><i class="icon icon-search"></i></button>
                    </div>
                </form>
                <!-- Dropdown results container -->
                <div id="search-results" class="dropdown-menu" style="display: none;"></div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
                    $(document).ready(function() {
                        // دالة البحث
                        function performSearch(query, category) {
                            // تحقق من طول المدخل
                            if (query.length > 2 || category) {
                                // عرض مؤشر التحميل أثناء البحث
                                $('#search-results').html('<p>جاري البحث...</p>').show();

                                $.ajax({
                                    url: '{{ route('search.products') }}', // تأكد من أن الرابط يولد عبر HTTPS
                                    method: 'GET',
                                    data: {
                                        query: query,
                                        category: category,
                                    },
                                    success: function(response) {
                                        // إذا كانت هناك نتائج
                                        if (response.length > 0) {
                                            $('#search-results').html(response).show();
                                        } else {
                                            $('#search-results').html('<p>لا توجد نتائج</p>').show();
                                        }
                                    },
                                    error: function(xhr) {
                                        console.log("Error In Search Result", xhr);
                                        $('#search-results').html('<p>حدث خطأ أثناء البحث</p>').show();
                                    }
                                });
                            } else {
                                $('#search-results').hide();
                            }
                        }

                        // عند تغيير الفئة
                        $('#category-select').on('change', function() {
                            // إفراغ حقل البحث
                            $('#search-input').val('');

                            // إخفاء نتائج البحث
                            $('#search-results').hide();

                            // استدعاء البحث مع الفئة الجديدة
                            let category = $(this).val();
                            performSearch('', category); // البحث فقط حسب الفئة الجديدة
                        });

                        // عند الكتابة في حقل البحث
                        $('#search-input').on('input', function() {
                            let query = $(this).val();
                            let category = $('#category-select').val();
                            performSearch(query, category); // البحث حسب المدخلات والفئة
                        });

                        // إخفاء القائمة المنسدلة عند النقر خارجها
                        $(document).click(function(e) {
                            if (!$(e.target).closest('.search').length) {
                                $('#search-results').hide();
                            }
                        });
                    });
                </script>


                <style>
                    /* تصميم منطقة النتائج المنسدلة */
                    #search-results {
                        position: absolute;
                        background: white;
                        border: 1px solid #ddd;
                        width: 49%;
                        max-height: 300px;
                        overflow-y: auto;
                        z-index: 1000;
                        display: none;
                    }

                    /* تصميم العناصر الفردية في القائمة المنسدلة */
                    #search-results .dropdown-item {
                        padding: 10px;
                        border-bottom: 1px solid #ddd;
                        cursor: pointer;
                        color: black;
                        text-decoration: none;
                        display: block;
                    }

                    #search-results .dropdown-item:hover {
                        background-color: #f8f9fa;
                    }
                </style>
            </div>
            <div class="cart_section">
                <ul class="tf-top-bar_item tf-social-icon d-flex gap-10 flex-wrap">
                    @if ($socialmedia['facebook'] != '')
                        <li><a href="{{ $socialmedia['facebook'] }}"
                                class="box-icon w_28 round social-facebook bg_line"><i
                                    class="icon fs-12 icon-fb"></i></a></li>
                    @endif
                    @if ($socialmedia['instagram'] != '')
                        <li><a href="{{ $socialmedia['instagram'] }}"
                                class="box-icon w_28 round social-instagram bg_line"><i
                                    class="icon fs-12 icon-instagram"></i></a></li>
                    @endif
                    @if ($socialmedia && $socialmedia->linkedin)
                        <li><a href="{{ $socialmedia->linkedin }}" class="box-icon w_28 round social-linkedin bg_line">
                                <i class="bi fs-12  bi-linkedin"></i></a>
                        </li>
                    @endif
                    @if ($socialmedia['x-twitter'] != '')
                        <li><a href="{{ $socialmedia['x-twitter'] }}"
                                class="box-icon w_28 round social-twiter bg_line"><i
                                    class="icon fs-10 icon-Icon-x"></i></a></li>
                    @endif

                    @if ($socialmedia['youtube'] != '')
                        <li><a href="{{ $socialmedia['youtube'] }}"
                                class="box-icon w_28 round social-twiter bg_line"><i
                                    class="icon fs-10 icon-youtube"></i></a></li>
                    @endif

                    @if ($socialmedia['whatsapp'] != '')
                        <li><a href="{{ $socialmedia['whatsapp'] }}"
                                class="box-icon w_28 round social-twiter bg_line"><i
                                    class="icon fs-10 icon-whatsapp"></i></a></li>
                    @endif

                    @if ($socialmedia['tiktok'] != '')
                        <li><a href="{{ $socialmedia['tiktok'] }}" class="box-icon w_28 round social-tiktok bg_line"><i
                                    class="icon fs-12 icon-tiktok"></i></a></li>
                    @endif

                    @if ($socialmedia['pinterest'] != '')
                        <li><a href="{{ $socialmedia['pinterest'] }}"
                                class="box-icon w_28 round social-pinterest bg_line"><i
                                    class="icon fs-12 icon-pinterest-1"></i></a></li>
                    @endif
                    @if ($socialmedia['snapchat'] != '')
                        <li><a href="{{ $socialmedia['snapchat'] }}"
                                class="box-icon w_28 round social-pinterest bg_line"><i
                                    class="fs-12 bi bi-snapchat"></i></a></li>
                    @endif
                    @if ($socialmedia['telegram'] != '')
                        <li><a href="{{ $socialmedia['telegram'] }}"
                                class="box-icon w_28 round social-facebook bg_line"><i
                                    class="fs-12 bi bi-telegram"></i></a></li>
                    @endif

                </ul>
            </div>
        </div>
    </div>

    <!--/ Search Top Bar -->
    <!-- Header -->
    <header id="header" class="header-default header-absolute">
        <div class="px_15 lg-px_40">
            <div class="row wrapper-header align-items-center">
                <div class="col-md-4 col-3 tf-lg-hidden">
                    <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16"
                            fill="none">
                            <path
                                d="M2.00056 2.28571H16.8577C17.1608 2.28571 17.4515 2.16531 17.6658 1.95098C17.8802 1.73665 18.0006 1.44596 18.0006 1.14286C18.0006 0.839753 17.8802 0.549063 17.6658 0.334735C17.4515 0.120408 17.1608 0 16.8577 0H2.00056C1.69745 0 1.40676 0.120408 1.19244 0.334735C0.978109 0.549063 0.857702 0.839753 0.857702 1.14286C0.857702 1.44596 0.978109 1.73665 1.19244 1.95098C1.40676 2.16531 1.69745 2.28571 2.00056 2.28571ZM0.857702 8C0.857702 7.6969 0.978109 7.40621 1.19244 7.19188C1.40676 6.97755 1.69745 6.85714 2.00056 6.85714H22.572C22.8751 6.85714 23.1658 6.97755 23.3801 7.19188C23.5944 7.40621 23.7148 7.6969 23.7148 8C23.7148 8.30311 23.5944 8.59379 23.3801 8.80812C23.1658 9.02245 22.8751 9.14286 22.572 9.14286H2.00056C1.69745 9.14286 1.40676 9.02245 1.19244 8.80812C0.978109 8.59379 0.857702 8.30311 0.857702 8ZM0.857702 14.8571C0.857702 14.554 0.978109 14.2633 1.19244 14.049C1.40676 13.8347 1.69745 13.7143 2.00056 13.7143H12.2863C12.5894 13.7143 12.8801 13.8347 13.0944 14.049C13.3087 14.2633 13.4291 14.554 13.4291 14.8571C13.4291 15.1602 13.3087 15.4509 13.0944 15.6653C12.8801 15.8796 12.5894 16 12.2863 16H2.00056C1.69745 16 1.40676 15.8796 1.19244 15.6653C0.978109 15.4509 0.857702 15.1602 0.857702 14.8571Z"
                                fill="currentColor"></path>
                        </svg>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-6 mobile_logo">
                    <a href="{{ url('/') }}" class="logo-header">
                        <img src="{{ asset('assets/uploads/PublicSetting/' . $settings['website_logo']) }}"
                            alt="logo" class="logo">
                    </a>
                </div>
                <div class="col-xl-9 tf-md-hidden">
                    <nav class="box-navigation text-center">
                        <ul class="box-nav-ul d-flex align-items-center justify-content-center gap-30">
                            <li class="menu-item"><a href="{{ url('/') }}" class="item-link"> الرئيسية </a></li>
                            <li class="menu-item"><a href="{{ url('shop') }}" class="item-link"> المتجر </a></li>
                            <li class="menu-item position-relative">
                                <a href="{{ url('collection') }}" class="item-link"> الاقسام <i class="icon icon-arrow-down"></i></a>
                                <div class="sub-menu submenu-default">
                                    <ul class="menu-list">
                                        @foreach ($categories as $category)
                                            <li class="menu-item-2 {{ $category->SubCategories->isNotEmpty() ? 'has-subcategories' : '' }}">
                                                <a href="{{ url('collection/' . $category['slug']) }}"
                                                    class="menu-link-text link text_black-2">
                                                    {{ $category['name'] }}
                                                </a>
                                                @if ($category->SubCategories->isNotEmpty())
                                                    <div class="sub-menu submenu-default">
                                                        <ul class="menu-list">
                                                            @foreach ($category->SubCategories as $subcategory)
                                                                <li>
                                                                    <a href="{{ url('collection/' . $category['slug'] . '/' . $subcategory['slug']) }}"
                                                                        class="menu-link-text link text_black-2 position-relative">
                                                                        {{ $subcategory['name'] }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>

                            <li class="menu-item"><a href="{{ url('collection') }}" class="item-link"> جميع الاقسام
                                </a></li>

                            <li class="menu-item"><a href="{{ url('cart') }}" class="item-link"> السلة </a></li>
                            <li class="menu-item"><a href="{{ url('faq') }}" class="item-link"> الاسئلة الشائعة
                                </a></li>
                        </ul>
                    </nav>
                
                
                
                
                </div>
                <div class="col-xl-3 col-md-4 col-3">
                    <ul class="nav-icon d-flex justify-content-end align-items-center gap-20">
                        <li class="nav-account"><a href="#login" data-bs-toggle="modal" class="nav-icon-item"><i
                                    class="fas fa-user"></i></a></li>
                        @php
                            $wishlistCount = \App\Models\front\wishlist::wishlistitems()->count();
                            $cartCount = \App\Models\front\Cart::getcartitems()->count();
                        @endphp
                        <li class="nav-wishlist"><a href="{{ url('wishlist') }}" class="nav-icon-item"><i
                                    class="fas fa-heart"></i><span
                                    class="count-box">{{ $wishlistCount }}</span></a>
                        </li>
                        <li class="nav-cart"><a id="shoppingCartmodel" href="#shoppingCart" data-bs-toggle="modal"
                                class="nav-icon-item"><i class="fas fa-shopping-bag"></i><span
                                    class="count-box">{{ $cartCount }}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
                
                
    </header>
    <!-- /Header -->

    <script>
        $(document).ready(function() {
            $("#shoppingCartmodel").on('click', function(e) {
                e.preventDefault();
                $('#shoppingCart').modal('show');

            });
        });
    </script>
