<!-- ========== App Menu Start ========== -->
@php

    $setting = \App\Models\admin\PublicSetting::first();


@endphp
<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{url('admin/dashboard')}}" class="logo-dark">
            <img src="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}" class="logo-sm" alt="logo sm">
            <img src="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}" class="logo-lg" alt="logo dark">
        </a>

        <a href="{{url('admin/dashboard')}}" class="logo-light">
            <img src="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}" class="logo-sm" alt="logo sm">
            <img src="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}" class="logo-lg" alt="logo light">
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title"> العام</li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('admin/dashboard')}}">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> الرئيسية  </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarProducts">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  المنتجات  </span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/products')}}"> جميع المنتجات </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/product/add')}}"> اضف منتج جديد </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCategory">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  التصنيفات  </span>
                </a>
                <div class="collapse" id="sidebarCategory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/main-categories')}}"> التصنيفات الرئيسية </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarBrands" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarBrands">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  العلامات التجارية   </span>
                </a>
                <div class="collapse" id="sidebarBrands">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/brands')}}"> العلامات التجارية </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/brand/add')}}"> اضف جديد </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarOrders">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  الطلبات  </span>
                </a>
                <div class="collapse" id="sidebarOrders">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/orders')}}"> جميع الطلبات  </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/offer_orders')}}"> طلبات صفحة الهبوط  </a>
                        </li>
{{--                        <li class="sub-nav-item">--}}
{{--                            <a class="sub-nav-link" href="{{url('admin/order/store')}}"> اضافة طلب </a>--}}
{{--                        </li>--}}
{{--                        <li class="sub-nav-item">--}}
{{--                            <a class="sub-nav-link" href="{{url('admin/orders/archive')}}"> ارشيف الطلبات  </a>--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAttributes" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarAttributes">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:confetti-minimalistic-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  سمات المنتج المتغير   </span>
                </a>
                <div class="collapse" id="sidebarAttributes">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/attributes')}}"> مشاهدة السمات المتاحة </a>
                        </li>
                    </ul>
                </div>
            </li>

{{--            <li class="nav-item">--}}
{{--                <a class="nav-link menu-arrow" href="#sidebarInvoice" data-bs-toggle="collapse" role="button"--}}
{{--                   aria-expanded="false" aria-controls="sidebarInvoice">--}}
{{--                                   <span class="nav-icon">--}}
{{--                                        <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>--}}
{{--                                   </span>--}}
{{--                    <span class="nav-text">  الفواتير  </span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarInvoice">--}}
{{--                    <ul class="nav sub-navbar-nav">--}}
{{--                        <li class="sub-nav-item">--}}
{{--                            <a class="sub-nav-link" href="invoice-list.html">List</a>--}}
{{--                        </li>--}}
{{--                        <li class="sub-nav-item">--}}
{{--                            <a class="sub-nav-link" href="invoice-details.html">Details</a>--}}
{{--                        </li>--}}
{{--                        <li class="sub-nav-item">--}}
{{--                            <a class="sub-nav-link" href="invoice-add.html">Create</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}
            <li class="menu-title mt-2"> اعدادات الموقع</li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('admin/public-setting/update')}}">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  الاعدادات العامة   </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('admin/social-media/update')}}">
                                   <span class="nav-icon">
                                         <iconify-icon icon="solar:share-circle-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  مواقع التواصل الاجتماعي    </span>
                </a>
            </li>


            <li class="menu-title mt-2"> المستخدمين</li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebaradminprofile" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCustomers">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> حسابي  </span>
                </a>
                <div class="collapse" id="sidebaradminprofile">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/update_admin_details')}}"> تعديل البيانات </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/update_admin_password')}}"> تعديل كلمة
                                المرور </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCustomers" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCustomers">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> العملاء  </span>
                </a>
                <div class="collapse" id="sidebarCustomers">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="customer-list.html"> جميع العملاء </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="customer-detail.html">Details</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCoupons" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCoupons">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:leaf-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> كوبونات الخصم  </span>
                </a>
                <div class="collapse" id="sidebarCoupons">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/coupons')}}"> جميع الكوبونات </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/coupon/add')}}"> اضافة كوبون </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarshippingCity" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarshippingCity">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:case-round-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> المدن المتاحة للشحن  </span>
                </a>
                <div class="collapse" id="sidebarshippingCity">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/shipping-city')}}"> مدن الشحن </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('admin/reviews')}}">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  آراء العملاء </span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{url('admin/reports')}}">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:chat-round-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  تقارير  </span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarfaqs" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarfaqs">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:question-circle-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">الاسئلة الشائعة للمتجر    </span>
                </a>
                <div class="collapse" id="sidebarfaqs">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/faqs')}}"> الاسئلة الشائعة للمتجر </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/faq/add')}}"> اضافة سوال جديد </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebartopnavbar" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebartopnavbar">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:case-round-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> الشريط الاعلاني اعلي المتجر  </span>
                </a>
                <div class="collapse" id="sidebartopnavbar">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/top-navbar')}}">التفاصيل </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarbanners" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarbanners">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:case-round-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> البانرات الرئيسية   </span>
                </a>
                <div class="collapse" id="sidebarbanners">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/banners')}}">التفاصيل </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarcolors" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarcolors">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:case-round-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> الوان الموقع   </span>
                </a>
                <div class="collapse" id="sidebarcolors">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/colors')}}">التفاصيل </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sideadvanatge" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sideadvanatge">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:case-round-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> مميزات المتجر  </span>
                </a>
                <div class="collapse" id="sideadvanatge">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/advantages')}}">التفاصيل </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sideadoffers" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sideadoffers">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:case-round-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text">  صفحات الهبوط </span>
                </a>
                <div class="collapse" id="sideadoffers">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{url('admin/offers')}}"> مشاهدة الكل   </a>
                        </li>
                    </ul>
                </div>
            </li>


            {{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="apps-email.html">--}}
{{--                                   <span class="nav-icon">--}}
{{--                                        <iconify-icon icon="solar:mailbox-bold-duotone"></iconify-icon>--}}
{{--                                   </span>--}}
{{--                    <span class="nav-text"> Email </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="apps-calendar.html">--}}
{{--                                   <span class="nav-icon">--}}
{{--                                        <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>--}}
{{--                                   </span>--}}
{{--                    <span class="nav-text"> Calendar </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="apps-todo.html">--}}
{{--                                   <span class="nav-icon">--}}
{{--                                        <iconify-icon icon="solar:checklist-bold-duotone"></iconify-icon>--}}
{{--                                   </span>--}}
{{--                    <span class="nav-text"> Todo </span>--}}
{{--                </a>--}}
{{--            </li>--}}


        </ul>
    </div>
</div>
<!-- ========== App Menu End ========== -->
