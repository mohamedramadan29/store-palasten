@extends('admin.layouts.master')
@section('title') طلبات المسوقين @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="page-content">
    <div class="container-xxl">
        @if (Session::has('Success_message'))
            @php toastify()->success(Session::get('Success_message')); @endphp
        @endif
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title flex-grow-1">طلبات المسوقين</h4>
                    </div>
                    <div class="table-responsive">
                        <table id="table-search" class="table mb-0 align-middle table-bordered table-hover">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>حالة الطلب</th>
                                    <th>المسوق</th>
                                    <th>اسم العميل</th>
                                    <th>رقم الهاتف</th>
                                    <th>الإجمالي</th>
                                    <th>إجمالي الربح</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($order->order_status == 'لم يبدا')
                                            <span class="badge bg-warning">{{ $order->order_status }}</span>
                                        @elseif($order->order_status == 'بداية التنفيذ')
                                            <span class="badge bg-info">{{ $order->order_status }}</span>
                                        @elseif($order->order_status == 'مكتمل')
                                            <span class="badge bg-success">{{ $order->order_status }}</span>
                                        @elseif($order->order_status == 'ملغي')
                                            <span class="badge bg-danger">{{ $order->order_status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->marketer->name ?? 'غير محدد' }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ number_format($order->grand_total, 2) }}</td>
                                    <td class="text-success fw-bold">{{ number_format($order->total_profit, 2) }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ url('admin/order/update/'.$order->id) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{ url('admin/order/print/'.$order->id) }}" class="btn btn-soft-primary btn-sm" target="_blank">
                                                <i class='bx bxs-printer'></i>
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
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table-search').DataTable({
            language: {
                search: "بحث:", lengthMenu: "عرض _MENU_ عناصر",
                zeroRecords: "لم يتم العثور على سجلات",
                info: "عرض _PAGE_ من _PAGES_", infoEmpty: "لا توجد سجلات",
                paginate: { previous: "السابق", next: "التالي" }
            }
        });
    });
</script>
@endsection
