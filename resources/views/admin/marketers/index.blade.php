@extends('admin.layouts.master')
@section('title') إدارة المسوقين @endsection
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
                        <h4 class="card-title flex-grow-1">إدارة المسوقين</h4>
                    </div>
                    <div class="table-responsive">
                        <table id="marketers-table" class="table mb-0 align-middle table-bordered table-hover">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>رقم الهاتف</th>
                                    <th>الحالة</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marketers as $marketer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $marketer->name }}</td>
                                    <td>{{ $marketer->email }}</td>
                                    <td>{{ $marketer->phone }}</td>
                                    <td>
                                        @if($marketer->status == 'active')
                                            <span class="badge bg-success">مفعل</span>
                                        @else
                                            <span class="badge bg-warning text-dark">غير مفعل</span>
                                        @endif
                                    </td>
                                    <td>{{ $marketer->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ url('admin/marketer/'.$marketer->id) }}" class="btn btn-soft-info btn-sm">
                                                <i class="ri-eye-line"></i> التفاصيل
                                            </a>
                                            @if($marketer->status == 'inactive')
                                            <form action="{{ url('admin/marketer/status/'.$marketer->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="btn btn-soft-success btn-sm">تفعيل</button>
                                            </form>
                                            @else
                                            <form action="{{ url('admin/marketer/status/'.$marketer->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                <input type="hidden" name="status" value="inactive">
                                                <button type="submit" class="btn btn-soft-warning btn-sm">إيقاف</button>
                                            </form>
                                            @endif
                                            <button type="button" class="btn btn-soft-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delete_marketer_{{$marketer->id}}">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </div>

                                        {{-- Delete Modal --}}
                                        <div class="modal fade" id="delete_marketer_{{$marketer->id}}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header"><h5 class="modal-title">تأكيد الحذف</h5></div>
                                                    <div class="modal-body"><p>هل أنت متأكد من حذف هذا المسوق؟</p></div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">إلغاء</button>
                                                        <form action="{{ url('admin/marketer/delete/'.$marketer->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
        // Check if DataTable is already initialized
        if (!$.fn.DataTable.isDataTable('#marketers-table')) {
            $('#marketers-table').DataTable({
                language: {
                    search: "بحث:", lengthMenu: "عرض _MENU_ عناصر",
                    zeroRecords: "لم يتم العثور على سجلات",
                    info: "عرض _PAGE_ من _PAGES_", infoEmpty: "لا توجد سجلات",
                    paginate: { previous: "السابق", next: "التالي" }
                }
            });
        }
    });
</script>
@endsection
