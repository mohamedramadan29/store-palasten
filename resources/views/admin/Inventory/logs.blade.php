@extends('admin.layouts.master')

@section('title')
    سجل حركة المخزون
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/light.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .stats-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .filter-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .filter-section .form-control,
        .filter-section .form-select {
            border-radius: 8px;
            border: none;
            padding: 10px 15px;
        }
        .select2-container--default .select2-selection--single {
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            height: auto;
        }
        .log-row {
            transition: background-color 0.2s ease;
        }
        .log-row:hover {
            background-color: #f8f9fa;
        }
        .movement-addition {
            color: #10b981;
            font-weight: 600;
        }
        .movement-subtraction {
            color: #ef4444;
            font-weight: 600;
        }
        .badge-movement {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        .protected-badge {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 15px;
            }
            .filter-section {
                padding: 15px;
            }
        }
    </style>
@endsection

@section('content')
  <div class="page-content">
    <div class="container-fluid">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">سجل حركة المخزون</h4>
                <p class="mb-0 text-muted">
                    سجل كامل وغير قابل للتعديل لجميع حركات المخزون 
                    <span class="protected-badge me-2">
                        <i class="ti ti-lock me-1"></i>محمي
                    </span>
                </p>
            </div>
            <div>
                <a href="{{ route('admin.inventory.logs.export') }}?{{ request()->getQueryString() }}"
                    class="btn btn-success">
                    <i class="ti ti-download me-1"></i>
                    تصدير CSV
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="mb-4 row">
            <div class="col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-10 stats-icon bg-primary text-primary me-3">
                                <i class="ti ti-list"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">إجمالي الحركات</h6>
                                <h3 class="mb-0">{{ $statistics['total_movements'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-10 stats-icon bg-success text-success me-3">
                                <i class="ti ti-arrow-up"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">إجمالي الإضافات</h6>
                                <h3 class="mb-0 movement-addition">{{ $statistics['total_additions'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-10 stats-icon bg-danger text-danger me-3">
                                <i class="ti ti-arrow-down"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">إجمالي الخصومات</h6>
                                <h3 class="mb-0 movement-subtraction">{{ $statistics['total_subtractions'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-10 stats-icon bg-warning text-warning me-3">
                                <i class="ti ti-currency-dollar"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">قيمة التكلفة</h6>
                                <h3 class="mb-0">
                                    {{ number_format($statistics['total_cost_additions'] + abs($statistics['total_cost_subtractions']), 2) }} {{ $currency }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.inventory.logs') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="text-white form-label">من تاريخ</label>
                        <input type="text" class="form-control datepicker" name="start_date"
                            value="{{ request('start_date') ?? now()->subDays(30)->format('Y-m-d') }}"
                            placeholder="اختر تاريخ البداية">
                    </div>
                    <div class="col-md-3">
                        <label class="text-white form-label">إلى تاريخ</label>
                        <input type="text" class="form-control datepicker" name="end_date"
                            value="{{ request('end_date') ?? now()->format('Y-m-d') }}"
                            placeholder="اختر تاريخ النهاية">
                    </div>
                    <div class="col-md-3">
                        <label class="text-white form-label">المنتج</label>
                        <select class="form-select" name="product_id" id="productSelect">
                            <option value="">جميع المنتجات</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-white form-label">نوع الحركة</label>
                        <select class="form-select" name="movement_type">
                            <option value="">جميع الحركات</option>
                            @foreach ($movementTypes as $type => $label)
                                <option value="{{ $type }}"
                                    {{ request('movement_type') == $type ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="gap-2 d-flex">
                            <button type="submit" class="btn btn-light">
                                <i class="ti ti-search me-1"></i>
                                تطبيق الفلاتر
                            </button>
                            <a href="{{ route('admin.inventory.logs') }}" class="btn btn-outline-light">
                                <i class="ti ti-refresh me-1"></i>
                                إعادة تعيين
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">سجل الحركات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>التاريخ والوقت</th>
                                <th>المرجع</th>
                                <th>المنتج</th>
                                <th>نوع الحركة</th>
                                <th>الكمية قبل</th>
                                <th>التغير</th>
                                <th>الكمية بعد</th>
                                <th>التكلفة</th>
                                <th>المستخدم</th>
                                <th>السبب</th>
                                <th>تفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="log-row">
                                    <td>
                                        <small>{{ $log->created_at->format('Y-m-d H:i:s') }}</small>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.inventory.product-history', $log->product_id) }}" 
                                           class="text-decoration-none">
                                            {{ $log->product_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-movement bg-{{ 
                                            $log->movement_type == 'sale' ? 'danger' : 
                                            ($log->movement_type == 'cancel' ? 'success' : 
                                            ($log->movement_type == 'manual_add' ? 'primary' : 
                                            ($log->movement_type == 'manual_subtract' ? 'warning' : 'info'))) 
                                        }}">
                                            {{ $log->movement_type_label }}
                                        </span>
                                    </td>
                                    <td>{{ $log->quantity_before }}</td>
                                    <td class="{{ $log->isAddition() ? 'movement-addition' : 'movement-subtraction' }}">
                                        {{ $log->formatted_quantity_change }}
                                    </td>
                                    <td>{{ $log->quantity_after }}</td>
                                    <td>{{ number_format($log->total_cost, 2) }} {{ $currency }}</td>
                                    <td>
                                        <small>{{ $log->user_name }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $log->reason ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.inventory.logs.show', $log->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="py-4 text-center text-muted">
                                        <i class="mb-2 ti ti-inbox text-muted fs-1 d-block"></i>
                                        لا توجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($logs->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
  </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize date pickers
        flatpickr(".datepicker", {
            locale: "ar",
            dateFormat: "Y-m-d",
            maxDate: "today"
        });

        // Initialize Select2
        $('#productSelect').select2({
            placeholder: "ابحث عن منتج...",
            allowClear: true,
            language: {
                noResults: function() {
                    return "لا توجد نتائج";
                },
                searching: function() {
                    return "جاري البحث...";
                }
            }
        });

        // Auto-refresh logs every 30 seconds
        setInterval(function() {
            // Optional: Add auto-refresh functionality
        }, 30000);
    </script>
@endsection
