@extends('front.layouts.master')

@section('title')
    لوحة تحكم المسوق
@endsection

@section('css')
<style>
    .seller-data p{
        font-size: 16px
    }
    .seller-data h3{
        font-size: 23px
    }
</style>
@endsection
@section('content')
<div class="tf-page-title">
    <div class="container-full">
        <div class="text-center heading">لوحة تحكم المسوق: {{ $marketer->name }}</div>
    </div>
</div>

<section class="flat-spacing-10">
    <div class="container">
        <div class="mb-4 row">
            <div class="mb-3 col-lg-3 col-6">
                <div class="p-4 text-center text-white border-0 shadow-sm card bg-primary seller-data">
                    <h3 class="mb-1 text-white fw-7">{{ $totalOrders }}</h3>
                    <p class="text-white">إجمالي الطلبات</p>
                </div>
            </div>
            <div class="mb-3 col-lg-3 col-6">
                <div class="p-4 text-center text-white border-0 shadow-sm card bg-success seller-data">
                    <h3 class="mb-1 text-white fw-7">{{ $completedOrders }}</h3>
                    <p class="text-white">طلبات مكتملة</p>
                </div>
            </div>
            <div class="mb-3 col-lg-3 col-6">
                <div class="p-4 text-center border-0 shadow-sm card bg-warning text-dark seller-data">
                    <h3 class="mb-1 fw-7">{{ $pendingOrders }}</h3>
                    <p class="text-dark">قيد الشحن</p>
                </div>
            </div>
            <div class="mb-3 col-lg-3 col-6">
                <div class="p-4 text-center text-white border-0 shadow-sm card bg-danger seller-data">
                    <h3 class="mb-1 text-white fw-7">{{ $cancelledOrders }}</h3>
                    <p class="text-white">طلبات ملغية</p>
                </div>
            </div>
        </div>

        <div class="mb-4 row">
            <div class="mb-3 col-lg-4 col-6">
                <div class="p-4 text-center text-white border-0 shadow-sm card bg-info seller-data">
                    <h3 class="mb-1 text-white fw-7">{{ number_format($totalProfit, 2) }} {{ $storeCurrency ?? '₪' }}</h3>
                    <p class="text-white">أرباح مؤكدة</p>
                </div>
            </div>
            <div class="mb-3 col-lg-4 col-6">
                <div class="p-4 text-center text-white border-0 shadow-sm card bg-secondary seller-data">
                    <h3 class="mb-1 text-white fw-7">{{ number_format($pendingProfit, 2) }} {{ $storeCurrency ?? '₪' }}</h3>
                    <p class="text-white">أرباح معلقة</p>
                </div>
            </div>
            <div class="mb-3 col-lg-4 col-6">
                <div class="p-4 text-center border-0 shadow-sm card bg-gradient seller-data" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3 class="mb-1 fw-7">{{ number_format($totalProfit + $pendingProfit, 2) }} {{ $storeCurrency ?? '₪' }}</h3>
                    <p class="">إجمالي الأرباح</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="p-4 bg-white rounded shadow-sm tf-page-cart-wrap">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-6">روابط سريعة</h5>
                        <form action="{{ route('marketer.logout') }}" method="get">
                            <button type="submit" class="btn btn-sm btn-outline-danger">تسجيل الخروج</button>
                        </form>
                    </div>
                    
                    
                    
                    <div class="text-center row">
                        <div class="mb-3 col-lg-3 col-6">
                            <a href="{{ route('marketer.orders') }}" class="tf-btn w-100 radius-3 btn-outline animate-hover-btn">📦 طلباتي</a>
                        </div>
                        <div class="mb-3 col-lg-3 col-6">
                            <a href="{{ url('marketer-shop') }}" class="tf-btn w-100 radius-3 btn-outline animate-hover-btn">🛍️ متجر المسوقين </a>
                        </div>
                        <div class="mb-3 col-lg-3 col-6">
                            <a href="{{ route('marketer.reports') }}" class="tf-btn w-100 radius-3 btn-outline animate-hover-btn">📊 التقارير</a>
                        </div>
                        <div class="mb-3 col-lg-3 col-6">
                            <a href="{{ route('marketer.profile') }}" class="tf-btn w-100 radius-3 btn-outline animate-hover-btn">👤 الملف الشخصي</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card { transition: all 0.3s ease; border-radius: 12px; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .tf-btn.btn-outline { border: 1px solid #e5e5e5; color: #333; background: #fff; }
    .tf-btn.btn-outline:hover { background: #333; color: #fff; border-color: #333; }
</style>
@endsection
