@extends('admin.layouts.master')
@section('title')
    تقارير
@endsection
@section('content')
    <!-- ==================================================== -->
    <!-- Start right Content here -->
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">

            <!-- Start here.... -->

            <div class="row">
                <div class="col-md-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-md bg-soft-primary rounded">
                                        <i class="bx bx-cart avatar-title fs-32 text-primary"></i>
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-6 text-end">
                                    <p class="text-muted mb-0"> الطلبات </p>
                                    <h3 class="text-dark mt-1 mb-0"> @php echo  count(\App\Models\front\Order::all()) @endphp  </h3>
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </div> <!-- end card body -->
                        <div class="card-footer py-2 bg-light bg-opacity-50">
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="{{url('admin/orders')}}" class="text-reset fw-semibold fs-12"> مشاهدة
                                    التفاصيل </a>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
                <div class="col-md-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-md bg-soft-primary rounded">
                                        <iconify-icon icon="solar:t-shirt-bold-duotone"
                                                      class="avatar-title fs-32 text-primary"></iconify-icon>
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-6 text-end">
                                    <p class="text-muted mb-0"> المنتجات </p>
                                    <h3 class="text-dark mt-1 mb-0">  @php echo  count(\App\Models\admin\Product::all()) @endphp  </h3>
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </div> <!-- end card body -->
                        <div class="card-footer py-2 bg-light bg-opacity-50">
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="{{url('admin/products')}}" class="text-reset fw-semibold fs-12"> مشاهدة
                                    التفاصيل </a>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-3anchor" id="datalabels"> متابعة الطلبات والمبيعات </h4>
                        </div>
                        <div class="card-body">
                            @php
                                /////////////////////// All Orders
                                $count_orders = count(\App\Models\front\Order::all());
                                ////////////// not started
                                $count_orders_not_started = count(\App\Models\front\Order::where('order_status','لم يبدا')->get());
                                 ////////////// compeleted
                                 $count_orders_completed = count(\App\Models\front\Order::where('order_status','مكتمل')->get());
                                 ///////// بداية التنفيذ
                                 $count_orders_started = count(\App\Models\front\Order::where('order_status','بداية التنفيذ')->get());
                                 ///////// cancelled
                                 $count_orders_cancelled = count(\App\Models\front\Order::where('order_status','ملغي')->get());

                            @endphp
                            <canvas id="orderschart2" style="width:100%;max-width:700px"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                // Get order counts from PHP variables
                                const totalOrders = <?php echo json_encode($count_orders); ?>;
                                const completedOrders = <?php echo json_encode($count_orders_completed); ?>;
                                const notStartedOrders = <?php echo json_encode($count_orders_not_started); ?>;
                                const StaredOrders = <?php echo json_encode($count_orders_started); ?>;
                                const canceledOrders = <?php echo json_encode($count_orders_cancelled); ?>;

                                // Create the chart
                                const ctx2 = document.getElementById('orderschart2').getContext('2d');
                                const ordersChart2 = new Chart(ctx2, {
                                    type: 'bar', // You can change this to 'pie', 'line', etc. based on your preference
                                    data: {
                                        labels: ['عدد الطلبات الكلي', 'طلبات مكتملة', 'طلبات لم تبدأ', 'طلبات بداية التنفيذ', 'طلبات ملغاة'],
                                        datasets: [{
                                            label: 'عدد الطلبات',
                                            data: [totalOrders, completedOrders, notStartedOrders, StaredOrders, canceledOrders],
                                            backgroundColor: [
                                                '#3498db',
                                                '#2ecc71',
                                                '#8e44ad',
                                                '#f1c40f',
                                                '#c0392b'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-3anchor" id="datalabels"> رسم بياني شهري للمبيعات  </h4>
                        </div>
                        <div class="card-body">
                            @php
                                use Illuminate\Support\Facades\DB;

                                $salesData = DB::table('orders')
                                    ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw("COUNT(*) as total_sales"))
                                    ->where('order_status', '!=', 'ملغي')
                                    ->groupBy('month')
                                    ->orderBy('month')
                                    ->get();
                                $ordermonths = $salesData->pluck('month')->toArray();
                                $sales = $salesData->pluck('total_sales')->toArray();
                            @endphp

                            <canvas id="salesChart" style="width:100%; max-width:700px"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                // تمرير بيانات الرسم البياني من PHP إلى JavaScript
                                const ordermonths = @json($ordermonths);
                                const sales = @json($sales);

                                // إعداد الرسم البياني باستخدام Chart.js
                                const ctx = document.getElementById('salesChart').getContext('2d');
                                const salesChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ordermonths,
                                        datasets: [{
                                            label: 'عدد المبيعات',
                                            data: sales,
                                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            borderWidth: 3
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-3anchor" id="datalabels"> احدث الطلبات </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-search"
                                       class="table table-bordered gridjs-table align-middle mb-0 table-hover table-centered">
                                    <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                        </th>
                                        <th> الاسم</th>
                                        <th> رقم الهاتف</th>
                                        <th> السعر الكلي</th>
                                        <th> العمليات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php

                                        $i = 1;
                                    @endphp
                                    @foreach($lastorders as $order)
                                        <tr>
                                            <td>
                                                {{$order['id']}}
                                            </td>
                                            <td> {{$order['name']}}  </td>
                                            <td>
                                                {{$order['phone']}}
                                            </td>
                                            <td> {{$order['grand_total']}} </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{url('/admin/order/update/'.$order['id'])}}"
                                                       class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken"
                                                                      class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
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
    <!-- ==================================================== -->
    <!-- End Page Content -->
    <!-- ==================================================== -->
@endsection

@section('js')
    <script src="{{asset('assets/admin/js/components/apexchart-column.js')}}"></script>
@endsection
