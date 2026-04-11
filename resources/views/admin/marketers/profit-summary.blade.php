@extends('admin.layouts.master')

@section('title')
    <span> </span>
@endsection

@section('content')
    <!-- ==================================================== -->
    <!-- Start Content -->
    <div class="page-content">
        <!-- Start Container -->
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-coins me-2"></i>
                                <span> </span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.marketer.profit-summary') }}" class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label">من تاريخ</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date" class="form-label">إلى تاريخ</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>
                                        تصفية
                                    </button>
                                    <a href="{{ route('admin.marketer.profit-summary') }}" class="btn btn-secondary ms-2">
                                        <i class="fas fa-redo me-2"></i>
                                        إعادة تعيين
                                    </a>
                                </div>
                            </form>

                            <!-- Summary Cards -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">{{ $summary['totalMarketers'] }}</h5>
                                            <p class="card-text">إجمالي المسوقين</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-success">{{ number_format($summary['totalProfit'], 2) }} {{ $storeCurrency ?? 'ر.س' }}</h5>
                                            <p class="card-text">إجمالي الأرباح</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-info">{{ number_format($summary['totalSales'], 2) }} {{ $storeCurrency ?? 'ر.س' }}</h5>
                                            <p class="card-text">إجمالي المبيعات</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-warning">{{ number_format($summary['averageProfit'], 2) }} {{ $storeCurrency ?? 'ر.س' }}</h5>
                                            <p class="card-text">متوسط الربح لكل مسوق</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Profit Distribution Chart -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">توزيع الأرباح حسب المسوقين</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="profitChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">إحصائيات النشاط</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span>المسوقون الأعلى أداءً</span>
                                                    <span class="badge bg-success">{{ $summary['topMarketers']['count'] }}</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: {{ ($summary['topMarketers']['count'] / $summary['totalMarketers']) * 100 }}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span>المسوقون النشطون</span>
                                                    <span class="badge bg-warning">{{ $summary['activeMarketers']['count'] }}</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" style="width: {{ ($summary['activeMarketers']['count'] / $summary['totalMarketers']) * 100 }}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span>المسوقون غير النشطين</span>
                                                    <span class="badge bg-danger">{{ $summary['inactiveMarketers']['count'] }}</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-danger" style="width: {{ ($summary['inactiveMarketers']['count'] / $summary['totalMarketers']) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Top Marketers Table -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">أفضل 10 مسوقين</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>المسوق</th>
                                                    <th>إجمالي الطلبات</th>
                                                    <th class="text-center">مكتمل</th>
                                                    <th class="text-center">قيد التنفيذ</th>
                                                    <th class="text-center">إجمالي الأرباح</th>
                                                    <th class="text-center">إجمالي المبيعات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topMarketers as $index => $marketer)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                {{ $index + 1 }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ $marketer['marketer']->name ?? $marketer['marketer']->email }}</div>
                                                                <small class="text-muted">{{ $marketer['marketer']->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $marketer['totalOrders'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success">{{ $marketer['completedOrders'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning">{{ $marketer['pendingOrders'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong class="text-success">{{ number_format($marketer['totalProfit'], 2) }} {{ $storeCurrency ?? ' <span> </span> ' }}</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong class="text-info">{{ number_format($marketer['totalSales'], 2) }} {{ $storeCurrency ?? ' <span> </span> ' }}</strong>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($topMarketers->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد بيانات حالياً</h5>
                                        <p class="text-muted">لم يتم العثور على أي مسوقين لديهم أرباح في الفترة المحددة. جرب تغيير نطاق التاريخ.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@section('css')
    <style>
        .avatar-sm {
            width: 35px;
            height: 35px;
            font-size: 14px;
            font-weight: bold;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.75em;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('profitChart').getContext('2d');
            
            const profitData = @json($topMarketers->pluck('totalProfit'));
            const marketerNames = @json($topMarketers->map(function($marketer) {
                return $marketer['marketer']->name ?? $marketer['marketer']->email;
            }));
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: marketerNames,
                    datasets: [{
                        label: ' <span> </span> ',
                        data: profitData,
                        backgroundColor: 'rgba(40, 167, 69, 0.6)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' {{ $storeCurrency ?? " <span> </span> " }}';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' <span> </span> : ' + context.parsed.y.toLocaleString() + ' {{ $storeCurrency ?? " <span> </span> " }}';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
