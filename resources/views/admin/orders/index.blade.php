@extends('admin.layouts.master')

@section('title')
    الطلبات
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                @if (Session::has('Success_message'))
                    @php toastify()->success(Session::get('Success_message')); @endphp
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        @php toastify()->error($error); @endphp
                    @endforeach
                @endif

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-1">
                            <h4 class="card-title flex-grow-1">الطلبات</h4>
                        </div>

                        <div class="table-responsive">
                            <table id="table-search" class="table table-bordered align-middle mb-0 table-hover">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الطلب</th>
                                        <th>اسم العميل</th>
                                        <th>رقم الهاتف</th>
                                        <th>المدينة</th>
                                        <th>قيمة الشحن</th>
                                        <th>الإجمالي</th>
                                        <th>حالة الطلب</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order['id'] }}</td>
                                            <td>{{ $order['name'] }}</td>
                                            <td>{{ $order['phone'] }}</td>
                                            <td>{{ $order['city']['city'] }}</td>
                                            <td>{{ $order['shipping_price'] }}</td>
                                            <td>{{ $order['grand_total'] }}</td>
                                            <td>
                                                @if($order['order_status'] == 'لم يبدا')
                                                    <span class="badge badge-info bg-warning">{{ $order['order_status'] }}</span>
                                                @elseif($order['order_status'] == 'بداية التنفيذ')
                                                    <span class="badge badge-info bg-info">{{ $order['order_status'] }}</span>
                                                @elseif($order['order_status'] == 'مكتمل')
                                                    <span class="badge badge-info bg-success">{{ $order['order_status'] }}</span>
                                                @elseif($order['order_status'] == 'ملغي')
                                                    <span class="badge badge-info bg-danger">{{ $order['order_status'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{url('admin/order/update/'.$order['id'])}}" class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="{{url('admin/order/print/'.$order['id'])}}" class="btn btn-soft-primary btn-sm" target="_blank">
                                                        <i class='bx bxs-printer'></i>
                                                    </a>
                                                    <button type="button" class="btn btn-soft-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete_category_{{$order['id']}}">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('admin.orders.delete')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <script>
        $.fn.dataTable.ext.errMode = 'none';

        $(document).ready(function() {
            $('#table-search').DataTable({
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                if (col.hidden && ![3, 4, 5].includes(col.columnIndex)) {
                                    return '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                               '<td>'+col.title+':'+'</td> '+
                                               '<td>'+col.data+'</td>'+
                                           '</tr>';
                                }
                                return '';
                            }).join('');

                            return data ? $('<table/>').append(data) : false;
                        }
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: [0, 1, 2] },
                    { responsivePriority: 2, targets: 8 },
                    { responsivePriority: 3, targets: [6, 7] },
                    { responsivePriority: 10000, targets: [3, 4, 5] }
                ],
                language: {
                    search: "بحث:",
                    lengthMenu: "عرض _MENU_ عناصر لكل صفحة",
                    zeroRecords: "لم يتم العثور على سجلات",
                    info: "عرض _PAGE_ من _PAGES_",
                    infoEmpty: "لا توجد سجلات متاحة",
                    infoFiltered: "(تمت التصفية من إجمالي _MAX_ سجلات)",
                    paginate: {
                        previous: "السابق",
                        next: "التالي"
                    }
                }
            });
        });
    </script>
@endsection