@extends('front.layouts.master')
@section('title')
    لوحة تحكمي
@endsection
@section('content')
    <div class="page-content" style="margin-top:120px ">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Customer Sidebar -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 card-title" style="font-size: 20px">قائمة الحساب</h5>
                        </div>
                        <div class="p-0 card-body">
                            <nav class="nav flex-column nav-pills">
                                <a href="{{ route('customer.dashboard') }}" class="nav-link active">
                                    <i class="ti ti-home"></i>
                                    الرئيسية
                                </a>
                                <a href="{{ route('customer.profile') }}" class="nav-link">
                                    <i class="ti ti-user"></i>
                                    الملف الشخصي
                                </a>
                                <a href="{{ route('customer.orders') }}" class="nav-link">
                                    <i class="ti ti-shopping-bag"></i>
                                    طلباتي
                                </a>
                                    <a href="{{ route('customer.password') }}" class="nav-link">
                                    <i class="ti ti-lock"></i>
                                    تغيير كلمة المرور
                                </a>
                                {{-- <a href="#" class="nav-link">
                                    <i class="ti ti-location"></i>
                                    العناوين
                                </a> --}}
                                <hr class="my-2">
                                <a href="{{ route('customer.logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti ti-logout"></i>
                                
                                    تسجيل الخروج 
                                </a>
                                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Main Content -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="font-size: 22px">مرحباً {{ $user->name }}</h4>
                            {{-- <p class="mb-0 text-muted">آخر تسجيل دخول: {{ $user->created_at->format('Y-m-d H:i') }}</p> --}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center card">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="ti ti-shopping-bag display-4 text-primary"></i>
                                            </div>
                                            <h5 class="card-title">الطلبات</h5>
                                            <h3 class="text-primary">{{ isset($orders) ? $orders->count() : 0 }}</h3>
                                            <p class="text-muted">إجمالي الطلبات</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="text-center card">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="ti ti-user display-4 text-success"></i>
                                            </div>
                                            <h5 class="card-title">العناوين</h5>
                                            <h3 class="text-success">{{ isset($addresses) ? $addresses->count() : 0 }}</h3>
                                            <p class="text-muted">العناوين المحفوظة</p>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="mt-4">
                                <h5>أحدث الطلبات</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-responsive">
                                        <thead>
                                            <tr>
                                                <th>رقم الطلب</th>
                                                <th>التاريخ</th>
                                                <th>الحالة</th>
                                                <th>الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (isset($orders) ? $orders->take(5) : collect() as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if ($order->order_status == 'لم يبدا')
                                                            <span class="badge bg-warning">قيد المعالجة</span>
                                                        @elseif($order->order_status == 'بداية التنفيذ')
                                                            <span class="badge bg-info">قيد التجهيز</span>
                                                        @elseif($order->order_status == 'مكتمل')
                                                            <span class="badge bg-primary">تم الشحن</span>
                                                        @elseif($order->order_status == 'مكتمل')
                                                            <span class="badge bg-success"> مكتمل </span>
                                                        @else
                                                            <span
                                                                class="badge bg-secondary">{{ $order->order_status }}</span>
                                                        @endif
                                                    </td>
                                                    <td> {{ $storeCurrency }} {{ $order->grand_total }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3 text-center">

                                    <a href="{{ route('customer.orders') }}" class="btn btn-primary">
                                        عرض جميع الطلبات
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .nav-pills .nav-link {
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        .nav-pills .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .col-lg-3 {
                margin-bottom: 2rem;
            }
        }
    </style>
@endsection
