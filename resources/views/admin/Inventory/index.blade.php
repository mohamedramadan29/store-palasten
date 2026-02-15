@extends('admin.layouts.master')

@section('title')
    إدارة المخزون
@endsection
@section('css')
    {{--    <!-- DataTables CSS --> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        .hover-lift:hover .rounded-circle {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        /* .badge {
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
        } */

        .card {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .card-body {
            padding: 1.5rem;
        }

        .fs-2 {
            font-size: 2rem !important;
        }

        .text-teal {
            color: #14b8a6 !important;
        }

        .bg-teal {
            background-color: #14b8a6 !important;
        }

        .bg-opacity-10 {
            opacity: 0.1;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .fs-2 {
                font-size: 1.5rem !important;
            }
        }
    </style>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-content">
        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="ti ti-package"></i>
                                إدارة المخزون
                            </h4>
                            {{-- <div class="card-tools">
                                <a href="{{ route('orders') }}" class="btn btn-tool btn-sm">
                                    <i class="ti ti-arrow-left"></i> رجوع
                                </a>
                            </div> --}}
                        </div>

                        <!-- Filters Section -->
                        <div class="card-body border-bottom">
                            <form method="GET" action="{{ route('admin.inventory.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="search">البحث عن منتج:</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                value="{{ request('search') }}" placeholder="اسم المنتج">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="stock_status">حالة المخزون:</label>
                                            <select class="form-control" id="stock_status" name="stock_status">
                                                <option value="">الكل</option>
                                                <option value="available"
                                                    {{ request('stock_status') == 'available' ? 'selected' : '' }}>
                                                    متوفر
                                                </option>
                                                <option value="low"
                                                    {{ request('stock_status') == 'low' ? 'selected' : '' }}>
                                                    منخفض
                                                </option>
                                                <option value="out_of_stock"
                                                    {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                                    نفد
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="category_id">الفئة:</label>
                                            <select class="form-control" id="category_id" name="category_id">
                                                <option value="">الكل</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="gap-2 d-flex">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-search"></i> بحث
                                                </button>
                                                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                                                    <i class="ti ti-refresh"></i> إعادة تعيين
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="card-body">
                            <div class="mb-4 row g-3">
                                <!-- Total Products -->
                                <div class="col-lg-2 col-md-6">
                                    <div class="border-0 shadow-sm transition-all duration-300 card hover-lift">
                                        <div class="text-center card-body">

                                            <h3 class="mb-1 fw-bold text-info">{{ $stats['total_products'] }}</h3>
                                            <p class="mb-0 text-muted small">إجمالي المنتجات</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Variants -->
                                <div class="col-lg-2 col-md-6">
                                    <div class="border-0 shadow-sm transition-all duration-300 card hover-lift">
                                        <div class="text-center card-body">

                                            <h3 class="mb-1 fw-bold text-primary">{{ $stats['total_variants'] }}</h3>
                                            <p class="mb-0 text-muted small">إجمالي المتغيرات</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Products -->
                                <div class="col-lg-2 col-md-6">
                                    <div class="border-0 shadow-sm transition-all duration-300 card hover-lift">
                                        <div class="text-center card-body">

                                            <h3 class="mb-1 fw-bold text-success">{{ $stats['available_products'] }}</h3>
                                            <p class="mb-0 text-muted small">منتجات متوفرة</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Variants -->
                                <div class="col-lg-2 col-md-6">
                                    <div class="border-0 shadow-sm transition-all duration-300 card hover-lift">
                                        <div class="text-center card-body">

                                            <h3 class="mb-1 fw-bold text-teal">{{ $stats['available_variants'] }}</h3>
                                            <p class="mb-0 text-muted small">متغيرات متوفرة</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Low Stock -->
                                <div class="col-lg-2 col-md-6">
                                    <div class="border-0 shadow-sm transition-all duration-300 card hover-lift">
                                        <div class="text-center card-body">
                                            <div class="align-items-center"> 
                                                    <h3 class="mb-1 fw-bold text-warning">
                                                        {{ $stats['low_stock_products'] + $stats['low_stock_variants'] }}
                                                    </h3>
                                                    <p class="mb-0 text-muted small">مخزون منخفض</p>
                                                    {{-- <div class="gap-3 mt-2 d-flex">
                                                        <span class="badge bg-warning">
                                                            <i
                                                                class="ti ti-package me-1"></i>{{ $stats['low_stock_products'] }}
                                                            منتج
                                                        </span>
                                                        <span class="badge bg-warning">
                                                            <i
                                                                class="ti ti-layers me-1"></i>{{ $stats['low_stock_variants'] }}
                                                            متغير
                                                        </span>
                                                    </div> --}}
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Out of Stock -->
                                <div class="col-lg-2 col-md-6">
                                    <div class="border-0 shadow-sm transition-all duration-300 card hover-lift">
                                        <div class="text-center card-body">
                                            <div class="align-items-center">
                                                {{-- <div class="flex-shrink-0">
                                                    <div
                                                        class="p-3 bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle bg-danger">
                                                        <i class="ti ti-x-circle text-danger fs-2"></i>
                                                    </div>
                                                </div> --}}
                                                <div class="flex-grow-1 ms-3">
                                                    <h3 class="mb-1 fw-bold text-danger">
                                                        {{ $stats['out_of_stock_products'] + $stats['out_of_stock_variants'] }}
                                                    </h3>
                                                    <p class="mb-0 text-muted small">نفد المخزون</p>
                                                    {{-- <div class="gap-3 mt-2 d-flex">
                                                        <span class="bg-opacity-10 badge bg-danger text-danger">
                                                            <i
                                                                class="ti ti-package me-1"></i>{{ $stats['out_of_stock_products'] }}
                                                            منتج
                                                        </span>
                                                        <span class="bg-opacity-10 badge bg-danger text-danger">
                                                            <i
                                                                class="ti ti-layers me-1"></i>{{ $stats['out_of_stock_variants'] }}
                                                            متغير
                                                        </span>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Products Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th width="50">
                                                <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                            </th> --}}
                                            <th>المنتج</th>
                                            <th>الفئة</th>
                                            <th>السعر</th>
                                            <th>الكمية</th>
                                            <th>حالة المخزون</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)
                                            <!-- Product Row -->
                                            <tr class="product-row" data-product-id="{{ $product->id }}">
                                                {{-- <td>
                                                    <input type="checkbox" class="form-check-input product-checkbox"
                                                        data-type="product" data-id="{{ $product->id }}">
                                                </td> --}}
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($product->image)
                                                            <img src="{{ asset('assets/uploads/product_images/' . $product->image) }}"
                                                                alt="{{ $product->name }}" class="mr-2 img-thumbnail"
                                                                width="50" height="50">
                                                        @else
                                                            <div class="mr-2 bg-gray-200 d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="text-gray-400 ti ti-package"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $product->name }}</strong>
                                                            @if ($product->sku)
                                                                <br><small class="text-muted">SKU:
                                                                    {{ $product->sku }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $product->Main_Category->name ?? 'غير محدد' }}</td>
                                                <td>{{ number_format($product->price, 2) }} </td>
                                                <td>
                                                    <span class="current-stock" data-current="{{ $product->quantity }}">
                                                        {{ $product->quantity }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $product->stock_status['color'] }} text-white">
                                                        <i class="{{ $product->stock_status['icon'] }}"></i>
                                                        {{ $product->stock_status['text'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info stock-adjust-btn"
                                                        data-type="product" data-id="{{ $product->id }}"
                                                        data-current="{{ $product->quantity }}">
                                                        <i class="ti ti-edit"></i> تعديل
                                                    </button>
                                                    @if ($product->variations->count() > 0)
                                                        <button type="button"
                                                            class="btn btn-sm btn-secondary toggle-variants"
                                                            data-product-id="{{ $product->id }}">
                                                            <i class="ti ti-chevron-down"></i>
                                                            {{ $product->variations->count() }} متغيرات
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Variants as Separate Rows -->
                                            @foreach ($product->variations as $variation)
                                                <tr class="variant-row" style="background-color: #f8f9fa;"
                                                    data-parent-product="{{ $product->id }}">
                                                    {{-- <td>
                                                        <input type="checkbox" class="form-check-input product-checkbox"
                                                            data-type="variant" data-id="{{ $variation->id }}">
                                                    </td> --}}
                                                    <td>
                                                        <div class="pr-4 d-flex align-items-center">
                                                            @if ($variation->image)
                                                                <img src="{{ asset('assets/uploads/product_images/' . $variation->image) }}"
                                                                    alt="{{ $variation->attributes_text }}"
                                                                    class="mr-2 img-thumbnail" width="40"
                                                                    height="40">
                                                            @endif
                                                            <div>
                                                                <small class="text-muted">{{ $product->name }}</small>
                                                                <br><strong>{{ $variation->attributes_text }}</strong>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <small
                                                            class="text-muted">{{ $product->Main_Category->name ?? 'غير محدد' }}</small>
                                                    </td>
                                                    <td>{{ number_format($variation->price, 2) }}
                                                        {{ config('app.currency', 'ريال') }}</td>
                                                    <td>
                                                        <span class="current-stock"
                                                            data-current="{{ $variation->stock }}">
                                                            {{ $variation->stock }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $variation->stock_status['color'] }} text-white">
                                                            <i class="{{ $variation->stock_status['icon'] }}"></i>
                                                            {{ $variation->stock_status['text'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-sm btn-info stock-adjust-btn"
                                                            data-type="variant" data-id="{{ $variation->id }}"
                                                            data-current="{{ $variation->stock }}">
                                                            <i class="ti ti-edit"></i> تعديل
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد منتجات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="stockAdjustmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المخزون</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="stockAdjustmentForm">
                        <input type="hidden" id="adjustType" name="type">
                        <input type="hidden" id="adjustId" name="id">

                        <div class="form-group">
                            <label>الكمية الحالية:</label>
                            <input type="text" class="form-control" id="currentQuantity" readonly>
                        </div>

                        <div class="form-group">
                            <label>نوع العملية:</label>
                            <div class="btn-group d-block">
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="operation" value="add" checked> إضافة كمية
                                </label>
                                <label class="btn btn-outline-warning">
                                    <input type="radio" name="operation" value="subtract"> خصم كمية
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="operation" value="set"> تعيين كمية
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">الكمية:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="0"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="reason">السبب (اختياري):</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="saveStockAdjustment">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Quantity Modal -->
    <div class="modal fade" id="bulkQuantityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">كمية مخصصة</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bulkQuantity">الكمية:</label>
                        <input type="number" class="form-control" id="bulkQuantity" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="saveBulkQuantity">حفظ</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--    <!-- DataTables JS --> --}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
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
    <script>
        $(document).ready(function() {
            // Toggle variants - Now variants are shown as separate rows, so this button is not needed
            $('.toggle-variants').click(function() {
                var productId = $(this).data('product-id');
                var variantRows = $('.variant-row[data-parent-product="' + productId + '"]');
                var icon = $(this).find('i');

                variantRows.toggle();
                icon.toggleClass('ti-chevron-down ti-chevron-up');
            });

            // Stock adjustment modal
            $('.stock-adjust-btn').click(function() {
                console.log('Stock adjustment button clicked');

                var type = $(this).data('type');
                var id = $(this).data('id');
                var current = $(this).data('current');

                console.log('Type:', type, 'ID:', id, 'Current:', current);

                $('#adjustType').val(type);
                $('#adjustId').val(id);
                $('#currentQuantity').val(current);
                $('#quantity').val('');
                $('#reason').val('');

                console.log('Opening modal...');
                $('#stockAdjustmentModal').modal('show');
            });

            // Save stock adjustment
            $('#saveStockAdjustment').click(function() {
                // Prevent multiple clicks
                if ($(this).prop('disabled')) {
                    return false;
                }

                $(this).prop('disabled', true);

                var form = $('#stockAdjustmentForm');
                var data = form.serialize();

                // Debug: Log what we're sending
                console.log('Form data being sent:', data);
                console.log('Operation:', $('input[name="operation"]:checked').val());
                console.log('Quantity:', $('#quantity').val());

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('admin.inventory.update-stock') }}',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        console.log('Response:', response);
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                            $('#saveStockAdjustment').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.log('Error response:', xhr);
                        var message = 'حدث خطأ ما';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        alert(message);
                        $('#saveStockAdjustment').prop('disabled', false);
                    }
                });
            });

            // Select all checkbox
            $('#selectAllCheckbox').change(function() {
                $('.product-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Select all button
            $('#selectAll').click(function() {
                $('.product-checkbox').prop('checked', true);
                $('#selectAllCheckbox').prop('checked', true);
            });

            // Deselect all button
            $('#deselectAll').click(function() {
                $('.product-checkbox').prop('checked', false);
                $('#selectAllCheckbox').prop('checked', false);
            });

            // Bulk actions
            $('[data-bulk-action]').click(function(e) {
                e.preventDefault();

                var action = $(this).data('bulk-action');
                var quantity = $(this).data('quantity');

                if (quantity === 'custom') {
                    $('#bulkQuantityModal').modal('show');
                    return;
                }

                performBulkAction(action, quantity);
            });

            // Save bulk quantity
            $('#saveBulkQuantity').click(function() {
                var quantity = $('#bulkQuantity').val();
                var action = $('#bulkQuantityModal').data('action');

                if (quantity) {
                    performBulkAction(action, quantity);
                    $('#bulkQuantityModal').modal('hide');
                }
            });

            function performBulkAction(operation, quantity) {
                var items = [];
                $('.product-checkbox:checked').each(function() {
                    items.push({
                        type: $(this).data('type'),
                        id: $(this).data('id'),
                        operation: operation,
                        quantity: quantity
                    });
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('admin.inventory.bulk-update-stock') }}',
                    method: 'POST',
                    data: {
                        items: items,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = 'حدث خطأ ما';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        alert(message);
                    }
                });
            }
        });
    </script>
@endsection
