@extends('admin.layouts.master')

@section('title')
    تفاصيل المسوق: {{ $marketer->name }}
@endsection

@section('content')
<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">تفاصيل المسوق</h4>
                    <div class="page-title-right">
                        <a href="{{ url('admin/marketers') }}" class="btn btn-secondary btn-sm">
                            <i class="ri-arrow-left-line me-1"></i> العودة للمسوقين
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة بيانات المسوق -->
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="mx-auto mb-3 avatar-lg">
                                <span class="text-white avatar-title rounded-circle bg-primary fs-3">
                                    {{ substr($marketer->name, 0, 1) }}
                                </span>
                            </div>
                            <h5 class="mb-1">{{ $marketer->name }}</h5>
                            <p class="mb-3 text-muted">{{ $marketer->email }}</p>
                            
                            <div class="gap-2 mb-3 d-flex justify-content-center">
                                @if($marketer->status == 'active')
                                    <span class="badge bg-success">مفعل</span>
                                @else
                                    <span class="badge bg-warning">غير مفعل</span>
                                @endif
                                <span class="badge bg-info">مسوق</span>
                            </div>

                            <div class="mt-4">
                                <p class="mb-1 text-muted"><i class="ri-phone-line me-1"></i> {{ $marketer->phone ?? 'غير متوفر' }}</p>
                                <p class="mb-1 text-muted"><i class="ri-calendar-line me-1"></i> انضم: {{ $marketer->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الإحصائيات -->
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-md-4 col-6">
                        <div class="text-white card bg-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1">الأرباح المؤكدة</p>
                                        <h4 class="mb-0">{{ number_format($totalProfit, 2) }} {{ $storeCurrency ?? '₪' }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="ri-money-dollar-circle-line fs-3"></i>
                                    </div>
                                </div>
                                <p class="mt-2 mb-0 small">من {{ $completedOrders->count() }} طلب مكتمل</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="card bg-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1">الأرباح المعلقة</p>
                                        <h4 class="mb-0">{{ number_format($pendingProfit, 2) }} {{ $storeCurrency ?? '₪' }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="ri-time-line fs-3"></i>
                                    </div>
                                </div>
                                <p class="mt-2 mb-0 small">من {{ $pendingOrders->count() }} طلب قيد التنفيذ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="text-white card bg-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1">إجمالي المبيعات</p>
                                        <h4 class="mb-0">{{ number_format($totalSales, 2) }} {{ $storeCurrency ?? '₪' }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="ri-shopping-bag-line fs-3"></i>
                                    </div>
                                </div>
                                <p class="mt-2 mb-0 small">{{ $totalOrders }} طلب كلي</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 row">
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="text-center card-body">
                                <h3 class="text-primary">{{ $totalOrders }}</h3>
                                <p class="mb-0 text-muted">إجمالي الطلبات</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="text-center card-body">
                                <h3 class="text-success">{{ $completedOrders->count() }}</h3>
                                <p class="mb-0 text-muted">الطلبات المكتملة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="text-center card-body">
                                <h3 class="text-warning">{{ $pendingOrders->count() }}</h3>
                                <p class="mb-0 text-muted">قيد التنفيذ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="text-center card-body">
                                <h3 class="text-danger">{{ $cancelledOrders->count() }}</h3>
                                <p class="mb-0 text-muted">الملغية</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول الطلبات -->
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">طلبات المسوق</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>التاريخ</th>
                                        <th>العميل</th>
                                        <th>المدينة</th>
                                        <th>إجمالي الطلب</th>
                                        <th>الربح</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->shippingcity ?? 'غير محدد' }}</td>
                                        <td>{{ number_format($order->grand_total, 2) }} {{ $storeCurrency ?? '₪' }}</td>
                                        <td class="{{ $order->total_profit > 0 ? 'text-success' : '' }}">
                                            {{ number_format($order->total_profit, 2) }} {{ $storeCurrency ?? '₪' }}
                                        </td>
                                        <td>
                                            @if($order->order_status == 'مكتمل')
                                                <span class="badge bg-success">مكتمل</span>
                                            @elseif($order->order_status == 'لم يبدا')
                                                <span class="badge bg-warning">جديد</span>
                                            @elseif($order->order_status == 'بداية التنفيذ')
                                                <span class="badge bg-info">قيد التنفيذ</span>
                                            @elseif($order->order_status == 'ملغي')
                                                <span class="badge bg-danger">ملغي</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('admin/order/update/'.$order->id) }}" class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="py-4 text-center text-muted">
                                            لا توجد طلبات لهذا المسوق
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
