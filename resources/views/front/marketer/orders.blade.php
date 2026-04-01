@extends('front.layouts.master')

@section('title')
    طلباتي كمسوق
@endsection

@section('content')
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">قائمة طلباتي</div>
    </div>
</div>

<section class="flat-spacing-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="tf-page-cart-wrap shadow-sm p-4 rounded bg-white overflow-auto">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <table class="table tf-table-cart">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>العميل</th>
                                <th>إجمالي الطلب</th>
                                <th>ربحي</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div>{{ $order->name }}</div>
                                    <small class="text-muted">{{ $order->phone }}</small>
                                </td>
                                <td>{{ number_format($order->grand_total, 2) }}</td>
                                <td class="text-success fw-6">{{ number_format($order->total_profit, 2) }}</td>
                                <td>
                                    @if($order->order_status == 'لم يبدا')
                                        <span class="badge bg-warning text-dark">{{ $order->order_status }}</span>
                                    @elseif($order->order_status == 'بداية التنفيذ')
                                        <span class="badge bg-info text-white">{{ $order->order_status }}</span>
                                    @elseif($order->order_status == 'مكتمل')
                                        <span class="badge bg-success text-white">{{ $order->order_status }}</span>
                                    @elseif($order->order_status == 'ملغي')
                                        <span class="badge bg-danger text-white">{{ $order->order_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!in_array($order->order_status, ['مكتمل', 'ملغي']))
                                    <form action="{{ route('marketer.order.cancel', $order->id) }}" method="post" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">إلغاء</button>
                                    </form>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-5">لا يوجد طلبات حالياً</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('marketer.dashboard') }}" class="text-primary fw-6">العودة للوحة التحكم</a>
        </div>
    </div>
</section>

<style>
    .badge { padding: 8px 12px; border-radius: 6px; font-weight: 500; }
    .table th { border-top: 0; background: #f8f9ff; }
    .fw-6 { font-weight: 600; }
</style>
@endsection
