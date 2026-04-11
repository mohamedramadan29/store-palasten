@extends('admin.layouts.master')
@section('title')
    الصفحات
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                @if (Session::has('success'))
                    @php
                        toastify()->success(Session::get('success'));
                    @endphp
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        @php
                            toastify()->error($error);
                        @endphp
                    @endforeach
                @endif
                <div class="col-xl-12">
                    <div class="card">
                        <div class="gap-1 card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title flex-grow-1">الصفحات</h4>
                            <a href="{{ route('admin.pages.create') }}" class="btn btn-sm btn-primary">
                                إضافة صفحة جديدة <i class="ti ti-plus"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pagesTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان الصفحة</th>
                                            <th>الرابط</th>
                                            <th>في الفوتر</th>
                                            <th>الحالة</th>
                                            <th>تاريخ الإنشاء</th>
                                            <th>عمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pages as $page)
                                            <tr>
                                                <td>{{ $page->id }}</td>
                                                <td>{{ $page->title }}</td>
                                                <td>
                                                    @if($page->slug)
                                                        <code>/page/{{ $page->slug }}</code>
                                                    @else
                                                        <span class="text-muted">لا يوجد رابط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($page->show_in_footer)
                                                        <span class="badge bg-success">نعم</span>
                                                    @else
                                                        <span class="badge bg-secondary">لا</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($page->is_active)
                                                        <span class="badge bg-success">مفعل</span>
                                                    @else
                                                        <span class="badge bg-danger">معطل</span>
                                                    @endif
                                                </td>
                                                <td>{{ $page->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.pages.show', $page) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        @if($page->trashed())
                                                            <form action="{{ route('admin.pages.restore', $page->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success" title="استعادة" onclick="return confirm('هل أنت متأكد من استعادة هذه الصفحة؟')">
                                                                    <i class="ti ti-refresh"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.pages.force-delete', $page->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف نهائي" onclick="return confirm('هل أنت متأكد من حذف هذه الصفحة نهائياً؟')">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذه الصفحة؟')">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
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
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pagesTable').DataTable({
                language: {
                    search: "بحث:",
                    lengthMenu: "عرض _MENU_ سجلات",
                    info: "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                    paginate: {
                        first: "الأول",
                        last: "الأخير",
                        next: "التالي",
                        previous: "السابق"
                    }
                }
            });
        });
    </script>
@endsection
