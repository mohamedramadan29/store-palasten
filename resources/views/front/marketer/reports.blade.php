@extends('front.layouts.master')

@section('title')
    تقارير المسوق
@endsection

@section('content')
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">تقارير الأداء والأرباح</div>
    </div>
</div>

<section class="flat-spacing-10">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-4 mb-3">
                <div class="card p-4 shadow-sm border-0 bg-success text-white">
                    <h5 class="fw-6 text-white mb-2">الأرباح المؤكدة</h5>
                    <h2 class="fw-7 mb-0">{{ number_format($totalProfit, 2) }}</h2>
                    <small>من الطلبات المكتملة</small>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card p-4 shadow-sm border-0 bg-warning text-dark">
                    <h5 class="fw-6 mb-2">الأرباح المعلقة</h5>
                    <h2 class="fw-7 mb-0">{{ number_format($pendingProfit, 2) }}</h2>
                    <small>بانتظار اكتمال الطلبات</small>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card p-4 shadow-sm border-0 bg-light">
                    <h5 class="fw-6 mb-2">نسبة التحويل</h5>
                    @php
                        $conversionRate = $totalOrders > 0 ? ($completedOrders->count() / $totalOrders) * 100 : 0;
                    @endphp
                    <h2 class="fw-7 mb-0">{{ number_format($conversionRate, 1) }}%</h2>
                    <small>{{ $completedOrders->count() }} طلب مكتمل من {{ $totalOrders }}</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="tf-page-cart-wrap shadow-sm p-4 rounded bg-white">
                    <h5 class="fw-6 mb-4">آخر 10 طلبات رابحة</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>التاريخ</th>
                                    <th>إجمالي الطلب</th>
                                    <th>الربح المحقق</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders->take(10) as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td>{{ number_format($order->grand_total, 2) }}</td>
                                    <td class="text-success fw-6">{{ number_format($order->total_profit, 2) }}</td>
                                    <td>
                                        @if($order->order_status == 'لم يبدا')
                                            <span class="badge bg-warning text-dark small">{{ $order->order_status }}</span>
                                        @elseif($order->order_status == 'بداية التنفيذ')
                                            <span class="badge bg-info text-white small">{{ $order->order_status }}</span>
                                        @elseif($order->order_status == 'مكتمل')
                                            <span class="badge bg-success text-white small">{{ $order->order_status }}</span>
                                        @elseif($order->order_status == 'ملغي')
                                            <span class="badge bg-danger text-white small">{{ $order->order_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد بيانات متاحة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('marketer.dashboard') }}" class="text-primary fw-6">العودة للوحة التحكم</a>
        </div>
    </div>
</section>

<style>
    .card { border-radius: 15px; }
    .badge { padding: 5px 10px; border-radius: 4px; }
    .table th { background-color: #fbfbfc; border-top: 0; }
</style>
@endsection
