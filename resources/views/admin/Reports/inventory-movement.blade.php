@extends('admin.layouts.master')

@section('title')
    تقرير حركة المخزون
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
        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
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
        .profit-positive {
            color: #10b981;
            font-weight: 600;
        }
        .profit-negative {
            color: #ef4444;
            font-weight: 600;
        }
        .select2-container--default .select2-selection--single {
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            height: auto;
        }
        .movement-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        .stock-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 8px;
            margin: 4px 0;
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
                <h4 class="mb-1">تقرير حركة المخزون</h4>
                <p class="mb-0 text-muted">تحليل تفصيلي لحركة المخزون والقيم المالية</p>
            </div>
            <div>
                <a href="{{ route('admin.reports.inventory-movement.export') }}?{{ request()->getQueryString() }}"
                    class="btn btn-success">
                    <i class="ti ti-download me-1"></i>
                    تصدير CSV
                </a>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.reports.inventory-movement') }}">
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
                        <label class="text-white form-label">حالة الطلب</label>
                        <select class="form-select" name="order_status">
                            <option value="all" {{ request('order_status') == 'all' ? 'selected' : '' }}>جميع
                                الحالات</option>
                            <option value="لم يبدا"
                                {{ request('order_status') == 'لم يبدا' ? 'selected' : '' }}>طلبات جديدة</option>
                            <option value="بداية التنفيذ"
                                {{ request('order_status') == 'بداية التنفيذ' ? 'selected' : '' }}>جاري التنفيذ</option>
                            <option value="مكتمل"
                                {{ request('order_status') == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                            <option value="ملغي"
                                {{ request('order_status') == 'ملغي' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="gap-2 d-flex">
                            <button type="submit" class="btn btn-light">
                                <i class="ti ti-search me-1"></i>
                                تطبيق الفلاتر
                            </button>
                            <a href="{{ route('admin.reports.inventory-movement') }}" class="btn btn-outline-light">
                                <i class="ti ti-refresh me-1"></i>
                                إعادة تعيين
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="mb-4 row">
            <div class="col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-10 stats-icon bg-primary text-primary me-3">
                                <i class="ti ti-package"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">إجمالي الحركات</h6>
                                <h3 class="mb-0">{{ $inventorySummary['total_movements'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-10 stats-icon bg-info text-info me-3">
                                <i class="ti ti-box"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">الكمية المنقولة</h6>
                                <h3 class="mb-0">{{ $inventorySummary['total_quantity_moved'] }}</h3>
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
                                <h6 class="mb-1 text-muted">قيمة المبيعات</h6>
                                <h3 class="mb-0">{{ number_format($inventorySummary['total_sales_value'], 2) }} {{ $currency }}</h3>
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
                                <i class="ti ti-trending-up"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">صافي الربح</h6>
                                <h3 class="mb-0 {{ $inventorySummary['total_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                    {{ number_format($inventorySummary['total_profit'], 2) }} {{ $currency }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movements by Status -->
        <div class="mb-4 row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">حركات المخزون حسب الحالة</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($movementsByStatus as $status => $data)
                                <div class="col-md-3">
                                    <div class="stock-info">
                                        <h6 class="mb-2">
                                            <span class="badge bg-{{ 
                                                $status == 'مكتمل' ? 'success' : 
                                                ($status == 'ملغي' ? 'danger' : 
                                                ($status == 'بداية التنفيذ' ? 'warning' : 'info')) 
                                            }}">
                                                {{ $status }}
                                            </span>
                                        </h6>
                                        <div class="small">
                                            <div>الكمية: <strong>{{ $data['total_quantity'] }}</strong></div>
                                            <div>القيمة: <strong>{{ number_format($data['total_value'], 2) }} {{ $currency }}</strong></div>
                                            <div>الربح: <strong class="{{ $data['total_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">{{ number_format($data['total_profit'], 2) }} {{ $currency }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="mb-4 row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">رسم بياني لحركة المخزون</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">تفاصيل حركة المخزون</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>رقم الطلب</th>
                                        <th>المنتج</th>
                                        <th>الكمية</th>
                                        <th>سعر البيع</th>
                                        <th>الإجمالي</th>
                                        <th>التكلفة</th>
                                        <th>الربح</th>
                                        <th>الحالة</th>
                                        <th>المخزون</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orderDetails as $item)
                                        <?php
                                            $total = $item->product_price * $item->product_qty;
                                            $cost = ($item->product->purches_price ?? 0) * $item->product_qty;
                                            $profit = $total - $cost;
                                        ?>
                                        <tr>
                                            <td>{{ $item->order->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ url('admin/order/update/' . $item->order_id) }}"
                                                    class="text-primary text-decoration-none">
                                                    #{{ $item->order_id }}
                                                </a>
                                            </td>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ $item->product_qty }}</td>
                                            <td>{{ number_format($item->product_price, 2) }} {{ $currency }}</td>
                                            <td>{{ number_format($total, 2) }} {{ $currency }}</td>
                                            <td>{{ number_format($cost, 2) }} {{ $currency }}</td>
                                            <td class="{{ $profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                                {{ number_format($profit, 2) }} {{ $currency }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $item->order->order_status == 'مكتمل'
                                                        ? 'success'
                                                        : ($item->order->order_status == 'ملغي'
                                                            ? 'danger'
                                                            : ($item->order->order_status == 'بداية التنفيذ'
                                                                ? 'warning'
                                                                : 'info')) }}">
                                                    {{ $item->order->order_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $item->product->quantity }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="py-4 text-center text-muted">
                                                <i class="mb-2 ti ti-inbox text-muted fs-1 d-block"></i>
                                                لا توجد بيانات للفترة المحددة
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

        <!-- Product Movements Summary -->
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">ملخص حركة المخزون حسب المنتج</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>المنتج</th>
                                        <th>المخزون الحالي</th>
                                        <th>المبيعات</th>
                                        <th>الملغاة</th>
                                        <th>جاري التنفيذ</th>
                                        <th>طلبات جديدة</th>
                                        <th>إجمالي الحركات</th>
                                        <th>القيمة الإجمالية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productMovements as $movement)
                                        <tr>
                                            <td>{{ $movement['product']->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $movement['current_stock'] > 10 ? 'success' : 'danger' }}">
                                                    {{ $movement['current_stock'] }}
                                                </span>
                                            </td>
                                            <td>{{ $movement['total_sold'] }}</td>
                                            <td>{{ $movement['total_cancelled'] }}</td>
                                            <td>{{ $movement['total_processing'] }}</td>
                                            <td>{{ $movement['total_pending'] }}</td>
                                            <td>{{ $movement['total_moved'] }}</td>
                                            <td>{{ number_format($movement['total_value'], 2) }} {{ $currency }}</td>
                                        </tr>
                                    @endforeach
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

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // Chart data
        const chartData = @json($chartData);

        // Prepare chart datasets
        const labels = chartData.map(item => item.date);
        const soldData = chartData.map(item => item.sold);
        const cancelledData = chartData.map(item => item.cancelled);
        const processingData = chartData.map(item => item.processing);
        const pendingData = chartData.map(item => item.pending);

        // Create chart
        const ctx = document.getElementById('inventoryChart').getContext('2d');
        const inventoryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'مكتمل',
                        data: soldData,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 'flex'
                    },
                    {
                        label: 'ملغي',
                        data: cancelledData,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 'flex'
                    },
                    {
                        label: 'جاري التنفيذ',
                        data: processingData,
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderColor: 'rgb(245, 158, 11)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 'flex'
                    },
                    {
                        label: 'طلبات جديدة',
                        data: pendingData,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 'flex'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'system-ui',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + ' وحدة';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'system-ui'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'الكمية',
                            font: {
                                family: 'system-ui'
                            }
                        },
                        grid: {
                            borderDash: [2, 2]
                        },
                        ticks: {
                            font: {
                                family: 'system-ui'
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
