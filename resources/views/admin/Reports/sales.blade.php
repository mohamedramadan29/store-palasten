@extends('admin.layouts.master')

@section('title')
    تقرير أرباح المبيعات
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
        <div class="container-xxl">
            <!-- Page Header -->
            <div class="row">
                <div class="col-12">
                    <div class="border-0 shadow-sm card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 card-title">
                                        <i class="ti ti-chart-line text-primary me-2"></i>
                                        تقرير أرباح المبيعات
                                    </h4>
                                    <p class="mb-0 text-muted">تحليل شامل للمبيعات والأرباح خلال الفترة المحددة</p>
                                </div>
                                <div>
                                    <a href="{{ route('admin.reports.sales.export') }}?{{ request()->getQueryString() }}"
                                        class="btn btn-success">
                                        <i class="ti ti-download me-1"></i>
                                        تصدير CSV
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="row">
                <div class="col-12">
                    <div class="filter-section">
                        <form method="GET" action="{{ route('admin.reports.sales') }}">
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
                                            {{ request('order_status') == 'لم يبدا' ? 'selected' : '' }}>لم يبدا</option>
                                        <option value="بداية التنفيذ"
                                            {{ request('order_status') == 'بداية التنفيذ' ? 'selected' : '' }}>بداية
                                            التنفيذ</option>
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
                                        <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-light">
                                            <i class="ti ti-refresh me-1"></i>
                                            إعادة تعيين
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="mb-4 row">
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-opacity-10 stats-icon bg-primary text-primary me-3">
                                    <i class="ti ti-currency-dollar"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">إجمالي المبيعات</h6>
                                    <h3 class="mb-0">{{ number_format($totalSales, 2) }} {{ $currency }}</h3>
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
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">عدد الطلبات</h6>
                                    <h3 class="mb-0">{{ $totalOrders }}</h3>
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
                                    <h6 class="mb-1 text-muted">صافي الأرباح</h6>
                                    <h3 class="mb-0 {{ $netProfit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                        {{ number_format($netProfit, 2) }} {{ $currency }}
                                    </h3>
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
                                    <i class="ti ti-package"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">إجمالي الكمية</h6>
                                    <h3 class="mb-0">{{ $totalQuantity }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="mb-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 card-title">
                                <i class="ti ti-chart-bar text-primary me-2"></i>
                                رسم بياني للمبيعات والأرباح
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="salesChart"></canvas>
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
                            <h5 class="mb-0 card-title">
                                <i class="ti ti-table text-primary me-2"></i>
                                جدول تفصيلي للمبيعات
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>رقم الطلب</th>
                                            <th>التاريخ</th>
                                            <th>المنتج</th>
                                            <th>الكمية</th>
                                            <th>سعر الوحدة</th>
                                            <th>الإجمالي</th>
                                            <th>التكلفة</th>
                                            <th>الربح</th>
                                            <th>الحالة</th>
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
                                                <td>
                                                    <a href="{{ url('admin/order/update/' . $item->order_id) }}"
                                                        class="text-primary text-decoration-none">
                                                        #{{ $item->order_id }}
                                                    </a>
                                                </td>
                                                <td>{{ $item->order->created_at->format('Y-m-d H:i') }}</td>
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
                                                                : 'warning') }}">
                                                        {{ $item->order->order_status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="py-4 text-center text-muted">
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
        const salesData = chartData.map(item => item.sales);
        const profitData = chartData.map(item => item.profit);
        const ordersData = chartData.map(item => item.orders);

        // Create chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'المبيعات',
                        data: salesData,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 'flex'
                    },
                    {
                        label: 'الأرباح',
                        data: profitData,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 'flex'
                    },
                    {
                        label: 'عدد الطلبات',
                        data: ordersData,
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderColor: 'rgb(245, 158, 11)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 'flex',
                        yAxisID: 'y1'
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
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (context.datasetIndex === 2) { // Orders dataset
                                        label += context.parsed.y + ' طلب';
                                    } else {
                                        label += context.parsed.y.toFixed(2) + ' {{ $currency }}';
                                    }
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
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'القيمة ({{ $currency }})',
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
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'عدد الطلبات',
                            font: {
                                family: 'system-ui'
                            }
                        },
                        grid: {
                            drawOnChartArea: false
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
