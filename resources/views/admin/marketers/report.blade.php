@extends('admin.layouts.master')

@section('title')
    تقارير المسوقين
@endsection

@section('content')
    <!-- ==================================================== -->
    <!-- Start Content -->
    <div class="page-content">
        <!-- Start Container -->
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12">
                    <div class="">
                        <div class="card-header">
                            <h4 class="mb-0 card-title">
                                <i class="fas fa-chart-line me-2"></i>
                                تقارير المسوقين
                            </h4>
                            <div class="no-print">
                                <small class="text-muted">
                                    @if (request('start_date') && request('end_date'))
                                        : {{ request('start_date') }} - {{ request('end_date') }}
                                    @endif
                                    @if (request('order_status'))
                                        | : {{ request('order_status') }}
                                    @endif
                                </small>
                            </div>
                            <br>

                        </div>
                        <div class="">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.marketer.reports') }}"
                                class="mb-4 row g-3 no-print">
                                <div class="col-md-4 col-6">
                                    <label for="start_date" class="form-label">من تاريخ</label>
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ request('start_date') }}" class="form-control" />
                                </div>
                                <div class="col-md-4 col-6">
                                    <label for="end_date" class="form-label">إلى تاريخ</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                        class="form-control" />
                                </div>
                                <div class="col-md-3 col-5">
                                    <label for="end_date" class="form-label"> اخترالحالة </label>
                                    <select class="form-select" name="order_status">
                                        <option value="" {{ !request('order_status') ? 'selected' : '' }}>-- اختر حالة
                                            --</option>
                                        <option {{ request('order_status') == ' ' ? 'selected' : '' }} value=" "> كل
                                            الحالات </option>
                                        <option value="لم يبدا"
                                            {{ request('order_status') == 'لم يبدا' ? 'selected' : '' }}>لم يبدا</option>
                                        <option value="بداية التنفيذ"
                                            {{ request('order_status') == 'بداية التنفيذ' ? 'selected' : '' }}>بداية
                                            التنفيذ</option>
                                        <option value="مكتمل" {{ request('order_status') == 'مكتمل' ? 'selected' : '' }}>
                                            مكتمل</option>
                                        <option value="ملغي" {{ request('order_status') == 'ملغي' ? 'selected' : '' }}>
                                            ملغي</option>
                                    </select>
                                </div>
                                <div class="col-md-5 col-7" style="display: flex;align-items:center">
                                    <button type="submit" class="btn btn-primary">
                                        {{-- <i class="fas fa-filter me-2"></i> --}}
                                        تصفية
                                    </button>
                                    <a href="{{ route('admin.marketer.reports') }}" class="btn btn-secondary ms-2">
                                        {{-- <i class="fas fa-redo me-2"></i> --}}
                                        إعادة
                                    </a>

                                </div>
                                <div class="col-md-4 col-6">
                                    <button type="button" onclick="window.print()" class="btn btn-success ms-2">
                                        <i class="fas fa-print me-2"></i>
                                        طباعة
                                    </button>
                                </div>
                            </form>

                            <!-- Summary Cards -->
                            <div class="mb-4 row">
                                <div class="col-md-3 col-6">
                                    <div class="card border-primary">
                                        <div class="text-center card-body">
                                            <h5 class="card-title text-primary">{{ $overall['totalOrders'] }}</h5>
                                            <p class="card-text">إجمالي الطلبات</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="card border-success">
                                        <div class="text-center card-body">
                                            <h5 class="card-title text-success">
                                                {{ number_format($overall['totalProfit'], 2) }}
                                                {{ $storeCurrency ?? 'ر.س' }}</h5>
                                            <p class="card-text">إجمالي الأرباح</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="card border-info">
                                        <div class="text-center card-body">
                                            <h5 class="card-title text-info">{{ number_format($overall['totalSales'], 2) }}
                                                {{ $storeCurrency ?? 'ر.س' }}</h5>
                                            <p class="card-text">إجمالي المبيعات</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="card border-warning">
                                        <div class="text-center card-body">
                                            <h5 class="card-title text-warning">{{ $report->count() }}</h5>
                                            <p class="card-text">عدد المسوقين النشطين</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>المسوق</th>
                                            <th>إجمالي الطلبات</th>
                                            <th class="text-center">مكتمل</th>
                                            <th class="text-center">قيد التنفيذ</th>
                                            <th class="text-center">ملغي</th>
                                            <th class="text-center">إجمالي الأرباح</th>
                                            <th class="text-center">إجمالي المبيعات</th>
                                            <th class="text-center">التفاصيل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($report as $row)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="text-white avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                            {{ substr($row['marketer']->name ?? $row['marketer']->email, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">
                                                                {{ $row['marketer']->name ?? $row['marketer']->email }}
                                                            </div>
                                                            <small class="text-muted">{{ $row['marketer']->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $row['totalOrders'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $row['completedOrders'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning">{{ $row['pendingOrders'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">{{ $row['cancelledOrders'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <strong
                                                        class="text-success">{{ number_format($row['totalProfit'], 2) }}
                                                        {{ $storeCurrency ?? ' <span> </span> ' }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong class="text-info">{{ number_format($row['totalSales'], 2) }}
                                                        {{ $storeCurrency ?? ' <span> </span> ' }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.marketer.show', $row['marketer']->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-secondary">
                                        <tr class="fw-bold">
                                            <td> <span> </span> </td>
                                            <td>{{ $overall['totalOrders'] }}</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center text-success">
                                                {{ number_format($overall['totalProfit'], 2) }}
                                                {{ $storeCurrency ?? ' <span> </span> ' }}</td>
                                            <td class="text-center text-info">
                                                {{ number_format($overall['totalSales'], 2) }}
                                                {{ $storeCurrency ?? ' <span> </span> ' }}</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if ($report->isEmpty())
                                <div class="py-5 text-center">
                                    <i class="mb-3 fas fa-chart-line fa-3x text-muted"></i>
                                    <h5 class="text-muted">لا توجد تقارير حالياً</h5>
                                    <p class="text-muted">لم يتم العثور على أي بيانات للفترة المحددة. جرب تغيير نطاق
                                        التاريخ.</p>
                                </div>
                            @endif
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

        /* Print-specific styles */
        @media print {
            body {
                font-size: 12px;
                line-height: 1.4;
            }

            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .table {
                font-size: 11px;
            }

            .table th,
            .table td {
                padding: 8px;
                vertical-align: middle;
            }

            .badge {
                border: 1px solid #000;
                background: white !important;
                color: black !important;
            }

            .text-primary,
            .text-success,
            .text-info,
            .text-warning {
                color: black !important;
            }

            .page-break {
                page-break-before: always;
            }

            .table-responsive {
                overflow: visible !important;
            }
        }
    </style>
@endsection
