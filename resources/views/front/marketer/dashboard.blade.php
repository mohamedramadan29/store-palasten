@extends('front.layouts.master')

@section('title')
    لوحة تحكم المسوق
@endsection

@section('content')
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">لوحة تحكم المسوق: {{ $marketer->name }}</div>
    </div>
</div>

<section class="flat-spacing-10">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-3 col-6 mb-3">
                <div class="card text-center p-4 shadow-sm border-0 bg-light">
                    <h3 class="fw-7 mb-1">{{ $totalOrders }}</h3>
                    <p class="text-muted">إجمالي الطلبات</p>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="card text-center p-4 shadow-sm border-0 bg-success text-white">
                    <h3 class="fw-7 mb-1 text-white">{{ number_format($totalProfit, 2) }}</h3>
                    <p>أرباح مؤكدة</p>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="card text-center p-4 shadow-sm border-0 bg-warning text-dark">
                    <h3 class="fw-7 mb-1">{{ number_format($pendingProfit, 2) }}</h3>
                    <p>أرباح معلقة</p>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="card text-center p-4 shadow-sm border-0 bg-info text-white">
                    <h3 class="fw-7 mb-1 text-white">{{ $completedOrders }}</h3>
                    <p>طلبات مكتملة</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="tf-page-cart-wrap shadow-sm p-4 rounded bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-6 mb-0">روابط سريعة</h5>
                        <form action="{{ route('marketer.logout') }}" method="get">
                            <button type="submit" class="btn btn-sm btn-outline-danger">تسجيل الخروج</button>
                        </form>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-lg-3 col-6 mb-3">
                            <a href="{{ route('marketer.orders') }}" class="tf-btn w-100 radius-3 btn-outline animate-hover-btn">📦 طلباتي</a>
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
                            <a href="{{ url('shop') }}" class="tf-btn w-100 radius-3 btn-outline animate-hover-btn">🛍️ المنتجات</a>
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
                            <a href="{{ route('marketer.reports') }}" class="tf-btn w-100 radius-3 btn-outline animate-hover-btn">📊 التقارير</a>
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
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
