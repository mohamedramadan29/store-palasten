@extends('admin.layouts.master')
@section('title')
    المنتجات
@endsection
@section('css')

    {{--    <!-- DataTables CSS -->--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <div class="row">
                @if (Session::has('Success_message'))
                    @php
                        toastify()->success(\Illuminate\Support\Facades\Session::get('Success_message'));
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
                            <h4 class="card-title flex-grow-1"> المنتجات   </h4>

                            <a href="{{url('admin/product/add')}}" class="btn btn-sm btn-primary">
                                اضف منتج جديد <i class="ti ti-plus"></i>
                            </a>
                        </div>

                        <!-- Category Filters -->
                        <div class="card-body border-bottom bg-light">
                            <form method="GET" action="{{ url('admin/products') }}" class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">القسم الرئيسي</label>
                                    <select name="main_category_id" id="mainCategoryFilter" class="form-select">
                                        <option value="">جميع الأقسام الرئيسية</option>
                                        @foreach($MainCategories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ request('main_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">القسم الفرعي</label>
                                    <select name="sub_category_id" id="subCategoryFilter" class="form-select">
                                        <option value="">جميع الأقسام الفرعية</option>
                                        @if(request('main_category_id'))
                                            @foreach($SubCategories->where('parent_id', request('main_category_id')) as $subCategory)
                                                <option value="{{ $subCategory->id }}" 
                                                    {{ request('sub_category_id') == $subCategory->id ? 'selected' : '' }}>
                                                    {{ $subCategory->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="gap-2 d-flex">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-filter me-1"></i>
                                            فلترة
                                        </button>
                                        <a href="{{ url('admin/products') }}" class="btn btn-outline-secondary">
                                            <i class="ti ti-refresh me-1"></i>
                                            إعادة تعيين
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <div>
                            <div class="table-responsive">
                                <table id="table-search" class="table mb-0 align-middle table-bordered gridjs-table table-hover table-centered">
                                    <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th> اسم المنتج  </th>
                                        <th> القسم الرئيسي   </th>
                                        <th> سعر الشراء  </th>
                                        <th> سعر البيع  </th>
                                        <th> سعر التخفيض   </th>
                                        <th>  الصورة </th>
                                        <th>  العمليات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                {{$i++}}
                                            </td>
                                            <td><a href="{{url('admin/product/update/'.$product['slug'])}}"></a>  {{$product['name']}} </td>
                                            <td> {{$product['Main_Category']['name']}} </td>
                                            <td> {{$product['price']}} </td>
                                            <td> {{$product['price']}} </td>
                                            <td> {{$product['price']}} </td>
                                            <td>
                                                <img class="img-thumbnail" src="{{asset('assets/uploads/product_images/'.$product['image'])}}" width="80" height="80px" alt="">
                                            </td>
                                            <td>
                                                <div class="gap-2 d-flex">
                                                    <a href="{{url('admin/product/update/'.$product['slug'])}}" class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken"
                                                                      class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <button type="button" class="btn btn-soft-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete_category_{{$product['id']}}">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                      class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        @include('admin.Products.delete')
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Container Fluid -->

    </div>
    <!-- ==================================================== -->
    <!-- End Page Content -->
    <!-- ==================================================== -->

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--    <!-- DataTables JS -->--}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var selectedSubCategory = '{{ request("sub_category_id") }}';
            
            // Dynamic subcategory filtering
            $('#mainCategoryFilter').on('change', function() {
                var mainCategoryId = $(this).val();
                var subCategorySelect = $('#subCategoryFilter');
                
                // Clear subcategory options
                subCategorySelect.html('<option value="">جميع الأقسام الفرعية</option>');
                
                if (mainCategoryId) {
                    // Get subcategories via AJAX
                    $.ajax({
                        url: '{{ route("get.subcategories") }}',
                        type: "GET",
                        data: { category_id: mainCategoryId },
                        success: function(data) {
                            if (data.message) {
                                subCategorySelect.append('<option value="">' + data.message + '</option>');
                            } else {
                                $.each(data, function(key, value) {
                                    var selected = (selectedSubCategory == key) ? 'selected' : '';
                                    subCategorySelect.append('<option value="' + key + '" ' + selected + '>' + value + '</option>');
                                });
                            }
                            selectedSubCategory = ''; // Reset after the first initialization
                        },
                        error: function() {
                            // Fallback to static data if AJAX fails
                            var subcategories = @json($SubCategories ? $SubCategories->where('parent_id', request('main_category_id'))->pluck('name', 'id') : []);
                            $.each(subcategories, function(id, name) {
                                var selected = (selectedSubCategory == id) ? 'selected' : '';
                                subCategorySelect.append('<option value="' + id + '" ' + selected + '>' + name + '</option>');
                            });
                            selectedSubCategory = '';
                        }
                    });
                }
            });
            
            // Initialize existing subcategories if main category is selected
            var selectedMainCategory = '{{ request("main_category_id") }}';
            if (selectedMainCategory) {
                $('#mainCategoryFilter').trigger('change');
            }
            
            // تحقق ما إذا كان الجدول قد تم تهيئته من قبل
            if ($.fn.DataTable.isDataTable('#table-search')) {
                $('#table-search').DataTable().destroy(); // تدمير التهيئة السابقة
            }

            // تهيئة DataTables من جديد
            $('#table-search').DataTable({
                "language": {
                    "search": "بحث:",
                    "lengthMenu": "عرض _MENU_ عناصر لكل صفحة",
                    "zeroRecords": "لم يتم العثور على سجلات",
                    "info": "عرض _PAGE_ من _PAGES_",
                    "infoEmpty": "لا توجد سجلات متاحة",
                    "infoFiltered": "(تمت التصفية من إجمالي _MAX_ سجلات)",
                    "paginate": {
                        "previous": "السابق",
                        "next": "التالي"
                    }
                }
            });
        });
    </script>
@endsection
