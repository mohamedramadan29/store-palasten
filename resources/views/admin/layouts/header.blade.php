<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title> @yield('title') </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully responsive premium admin dashboard template"/>
    <meta name="author" content="Techzaa"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/admin/images/favicon.ico')}}">

    <!-- Vendor css (Require in all Page) -->
    <link href="{{asset('assets/admin/css/vendor.min.css')}}" rel="stylesheet" type="text/css"/>

    <!-- Icons css (Require in all Page) -->
    <link href="{{asset('assets/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>

    <!-- App css (Require in all Page) -->
    <link href="{{asset('assets/admin/css/app-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Theme Config js (Require in all Page) -->
    <script src="{{asset('assets/admin/js/config.js')}}"></script>
    
    <style>
        .stock-alert-item:hover {
            background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
        }
        .stock-alert-item.danger-hover:hover {
            background-color: rgba(var(--bs-danger-rgb), 0.1) !important;
        }
        .stock-alert-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .dropdown-item {
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            transform: translateX(3px);
        }
    </style>
    
    @toastifyCss
    @yield('css')
</head>

<body>

@php

    $setting = \App\Models\admin\PublicSetting::first();


@endphp


<!-- START Wrapper -->
<div class="wrapper">

    <!-- ========== Topbar Start ========== -->
    <header class="topbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <div class="d-flex align-items-center">
                    <!-- Menu Toggle Button -->
                    <div class="topbar-item">
                        <button type="button" class="button-toggle-menu me-2">
                            <iconify-icon icon="solar:hamburger-menu-broken" class="align-middle fs-24"></iconify-icon>
                        </button>
                    </div>

                    <!-- Menu Toggle Button -->
                    <div class="topbar-item">
                        <h4 class="mb-0 fw-bold topbar-button pe-none text-uppercase"> @yield('title') </h4>
                    </div>
                </div>

                <div class="gap-1 d-flex align-items-center">

                    <!-- Theme Color (Light/Dark) -->
                    <div class="topbar-item">
                        <button type="button" class="topbar-button" id="light-dark-mode">
                            <iconify-icon icon="solar:moon-bold-duotone" class="align-middle fs-24"></iconify-icon>
                        </button>
                    </div>

                    @php

                        $unreadNotificationsUsers = \Illuminate\Support\Facades\Auth::guard('admin')->user()->unreadNotifications;
                    @endphp

                            <!-- Stock Alerts Notification -->
                    <div class="dropdown topbar-item">
                        <button type="button" class="topbar-button position-relative"
                                id="page-header-stock-alerts-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            {{-- <iconify-icon icon="solar:package-broken" class="align-middle fs-24"></iconify-icon> --}}
                             <i class="ti ti-package text-warning me-2" style="color: #707693 !important;font-size:24px"></i>
                            @if(isset($stockAlerts) && count($stockAlerts) > 0)
                                <span class="position-absolute topbar-badge fs-10 translate-middle badge bg-warning rounded-pill stock-alert-badge">
                                    {{ count($stockAlerts) }}
                                </span>
                            @endif
                        </button>
                        <div class="py-0 dropdown-menu dropdown-lg dropdown-menu-end"
                             aria-labelledby="page-header-stock-alerts-dropdown">
                            <div class="p-3 border border-dashed border-top-0 border-start-0 border-end-0">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold"> 
                                            <i class="ti ti-package text-warning me-2"></i>
                                            تنبيهات المخزون
                                        </h6>
                                    </div>
                                    @if(isset($stockAlerts) && count($stockAlerts) > 0)
                                        <div class="col-auto">
                                            <a href="{{ url('admin/inventory') }}" class="btn btn-sm btn-outline-warning">
                                                عرض الكل
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if(isset($stockAlerts) && count($stockAlerts) > 0)
                                <div data-simplebar style="max-height: 300px;">
                                    @foreach($stockAlerts as $alert)
                                        <a href="{{ url('admin/inventory') }}?search={{ $alert['product_name'] }}" 
                                           class="dropdown-item py-3 border-bottom stock-alert-item {{ $alert['type'] == 'danger' ? 'bg-danger bg-opacity-10 danger-hover' : 'bg-warning bg-opacity-10' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 me-2">
                                                    @if($alert['type'] == 'danger')
                                                        <iconify-icon icon="solar:close-circle-bold" class="fs-20 text-danger"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="solar:danger-triangle-bold" class="fs-20 text-warning"></iconify-icon>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-semibold {{ $alert['type'] == 'danger' ? 'text-danger' : 'text-warning' }}">
                                                        {{ $alert['title'] }}
                                                    </h6>
                                                    <p class="mb-1 text-muted small">
                                                        {{ $alert['message'] }}
                                                    </p>
                                                    <small class="text-muted">
                                                        الكمية: <span class="fw-bold">{{ $alert['quantity'] }}</span>
                                                        @if($alert['is_variant'])
                                                            <span class="bg-opacity-10 badge bg-secondary text-secondary me-1">متغير</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center">
                                    <iconify-icon icon="solar:check-circle-bold" class="mb-2 fs-48 text-success"></iconify-icon>
                                    <p class="mb-0 text-muted">لا توجد تنبيهات مخزون حالياً</p>
                                    <small class="text-muted">جميع المنتجات في حالة جيدة</small>
                                </div>
                            @endif
                        </div>
                    </div>

                            <!-- Notification -->
                    <div class="dropdown topbar-item">
                        <button type="button" class="topbar-button position-relative"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <iconify-icon icon="solar:bell-bing-bold-duotone" class="align-middle fs-24"></iconify-icon>
                            <span class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">
                                @if ($unreadNotificationsUsers->count() > 0)
                                    {{ $unreadNotificationsUsers->count() }}
                                @else
                                    0
                                @endif  </span>
                        </button>
                        <div class="py-0 dropdown-menu dropdown-lg dropdown-menu-end"
                             aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3 border border-dashed border-top-0 border-start-0 border-end-0">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold"> الاشعارات </h6>
                                    </div>

                                </div>
                            </div>
                            @if($unreadNotificationsUsers->count() > 0)
                                @foreach($unreadNotificationsUsers as $notification)

                                    <div data-simplebar style="max-height: 280px;">
                                        <!-- Item -->
                                        @if($notification['type'] == 'App\Notifications\NewOrder')
                                            <a href="{{url('admin/order/update/'.$notification['data']['order_id'])}}" class="py-3 dropdown-item border-bottom">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="mb-0 fw-semibold"> رقم الطلب  {{ $notification['data']['order_id'] }} </p>
                                                        <p class="mb-0 text-wrap">
                                                            لديك طلب جديد علي الموقع
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{url('admin/offer_order/update/'.$notification['data']['order_id'])}}" class="py-3 dropdown-item border-bottom">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="mb-0 fw-semibold"> رقم الطلب  {{ $notification['data']['order_id'] }} </p>
                                                        <p class="mb-0 text-wrap">
                                                            لديك طلب جديد علي الموقع من صفحة الهبوط
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endif

                                    </div>
                                @endforeach
                            @else
                                <div data-simplebar style="max-height: 280px;">
                                    <a class="py-3 dropdown-item border-bottom">
                                        لا يوجد اشعاارات جديدة
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- User -->
                    <div class="dropdown topbar-item">
                        <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                                        <span class="d-flex align-items-center">
                                             <img class="rounded-circle" width="32"
                                                  src="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}"
                                                  alt="avatar-3">
                                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <h6 class="dropdown-header">
                                مرحبا {{\Illuminate\Support\Facades\Auth::guard('admin')->user()->name}} ! </h6>
                            <a class="dropdown-item" href="{{url('admin/update_admin_details')}}">
                                <i class="align-middle bx bx-user-circle text-muted fs-18 me-1"></i><span
                                        class="align-middle"> حسابي  </span>
                            </a>
                            <a class="dropdown-item" href="{{url('admin/update_admin_password')}}">
                                <i class="align-middle bx bx-message-dots text-muted fs-18 me-1"></i><span
                                        class="align-middle"> تغير كلمة المرور  </span>
                            </a>
                            <div class="my-1 dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{route('logout')}}">
                                <i class="align-middle bx bx-log-out fs-18 me-1"></i><span class="align-middle"> تسجيل خروج  </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
