@extends('admin.layouts.master')

@section('title')
    تفاصيل سجل المخزون
@endsection

@section('css')
    <style>
        .detail-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .movement-positive {
            color: #10b981;
            font-weight: 600;
        }

        .movement-negative {
            color: #ef4444;
            font-weight: 600;
        }

        .protected-badge {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        .metadata-item {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 8px;
            margin: 4px 0;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">تفاصيل سجل المخزون #{{ $log->id }}</h4>
                    <p class="mb-0 text-muted">
                        سجل محمي - غير قابل للتعديل أو الحذف
                        <span class="protected-badge me-2">
                            <i class="ti ti-lock me-1"></i>محمي
                        </span>
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.inventory.logs') }}" class="btn btn-outline-primary">
                        <i class="ti ti-arrow-right me-1"></i>
                        العودة للسجلات
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Main Information -->
                <div class="col-lg-8">
                    <div class="mb-4 card detail-card">
                        <div class="card-header">
                            <h5 class="mb-0">معلومات الحركة</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">التاريخ والوقت</label>
                                        <h6>{{ $log->created_at->format('Y-m-d H:i:s') }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">المرجع</label>
                                        <h6>
                                            @switch($log->reference_type)
                                                @case('order')
                                                    <a href="{{ url('admin/order/update/' . $log->reference_id) }}"
                                                        class="text-primary text-decoration-none">
                                                        طلب #{{ $log->reference_id }}
                                                    </a>
                                                @break

                                                @case('manual')
                                                    <span class="badge bg-info">تعديل يدوي</span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary">{{ $log->reference_type }}</span>
                                            @endswitch
                                        </h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">المنتج</label>
                                        <h6>
                                            <a href="{{ route('admin.inventory.product-history', $log->product_id) }}"
                                                class="text-decoration-none">
                                                {{ $log->product_name }}
                                            </a>
                                        </h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">نوع الحركة</label>
                                        <h6>
                                            <span
                                                class="badge bg-{{ $log->movement_type == 'sale'
                                                    ? 'danger'
                                                    : ($log->movement_type == 'cancel'
                                                        ? 'success'
                                                        : ($log->movement_type == 'manual_add'
                                                            ? 'primary'
                                                            : ($log->movement_type == 'manual_subtract'
                                                                ? 'warning'
                                                                : 'info'))) }}">
                                                {{ $log->movement_type_label }}
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">الكمية قبل</label>
                                        <h6>{{ $log->quantity_before }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">تغير الكمية</label>
                                        <h6 class="{{ $log->isAddition() ? 'movement-positive' : 'movement-negative' }}">
                                            {{ $log->formatted_quantity_change }}
                                        </h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">الكمية بعد</label>
                                        <h6>{{ $log->quantity_after }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">إجمالي التكلفة</label>
                                        <h6>{{ number_format($log->total_cost, 2) }} {{ $currency }}</h6>
                                    </div>
                                </div>
                            </div>

                            @if ($log->reason)
                                <div class="mt-3">
                                    <label class="text-muted small">السبب</label>
                                    <p class="mb-0">{{ $log->reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    @if ($log->metadata && count($log->metadata) > 0)
                        <div class="card detail-card">
                            <div class="card-header">
                                <h5 class="mb-0">بيانات إضافية</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($log->metadata as $key => $value)
                                    <div class="metadata-item">
                                        <strong>{{ $key }}:</strong>
                                        @if (is_array($value))
                                            {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Information -->
                <div class="col-lg-4">
                    <!-- User Information -->
                    <div class="mb-4 card detail-card">
                        <div class="card-header">
                            <h5 class="mb-0">معلومات المستخدم</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">اسم المستخدم</label>
                                <h6>{{ $log->user_name }}</h6>
                            </div>
                            @if ($log->user)
                                <div class="mb-2">
                                    <label class="text-muted small">البريد الإلكتروني</label>
                                    <h6>{{ $log->user->email }}</h6>
                                </div>
                            @endif
                            <div class="mb-2">
                                <label class="text-muted small">عنوان IP</label>
                                <h6>{{ $log->ip_address ?? 'غير متوفر' }}</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="mb-4 card detail-card">
                        <div class="card-header">
                            <h5 class="mb-0">معلومات المنتج الحالية</h5>
                        </div>
                        <div class="card-body">
                            @if ($log->product)
                                <div class="mb-2">
                                    <label class="text-muted small">الاسم الحالي</label>
                                    <h6>{{ $log->product->name }}</h6>
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small">المخزون الحالي</label>
                                    <h6>
                                        <span class="badge bg-{{ $log->product->quantity > 10 ? 'success' : 'danger' }}">
                                            {{ $log->product->quantity }}
                                        </span>
                                    </h6>
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small">سعر الشراء الحالي</label>
                                    <h6>{{ number_format($log->product->purches_price ?? 0, 2) }} {{ $currency }}</h6>
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small">سعر البيع الحالي</label>
                                    <h6>{{ number_format($log->product->price ?? 0, 2) }} {{ $currency }}</h6>
                                </div>
                            @else
                                <p class="text-muted">المنتج لم يعد متوفراً</p>
                            @endif
                        </div>
                    </div>

                    <!-- Audit Information -->
                    <div class="card detail-card">
                        <div class="card-header">
                            <h5 class="mb-0">معلومات التدقيق</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">رقم السجل</label>
                                <h6>#{{ $log->id }}</h6>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted small">تاريخ الإنشاء</label>
                                <h6>{{ $log->created_at->format('Y-m-d H:i:s') }}</h6>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted small">آخر تحديث</label>
                                <h6>{{ $log->updated_at->format('Y-m-d H:i:s') }}</h6>
                            </div>
                            <div class="alert alert-warning small">
                                <i class="ti ti-lock me-1"></i>
                                هذا السجل محمي ولا يمكن تعديله أو حذفه
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
