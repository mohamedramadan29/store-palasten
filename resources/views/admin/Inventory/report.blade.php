@extends('admin.layouts.master')

@section('title')
    تقرير المخزون
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ti ti-file-report"></i>
                        تقرير المخزون
                    </h4>
                    <div class="card-tools">
                        <a href="{{ route('inventory.index') }}" class="btn btn-tool btn-sm">
                            <i class="ti ti-arrow-left"></i> رجوع
                        </a>
                        <button type="button" class="btn btn-tool btn-sm" onclick="window.print()">
                            <i class="ti ti-printer"></i> طباعة
                        </button>
                        <button type="button" class="btn btn-tool btn-sm" onclick="exportToExcel()">
                            <i class="ti ti-file-download"></i> Excel
                        </button>
                    </div>
                </div>

                <!-- Statistics Overview -->
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="ti ti-package"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي المنتجات</span>
                                    <span class="info-box-number">{{ $stats['total_products'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="ti ti-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">منتجات متوفرة</span>
                                    <span class="info-box-number">{{ $stats['available_products'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="ti ti-alert-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">مخزون منخفض</span>
                                    <span class="info-box-number">{{ $stats['low_stock_products'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="ti ti-x-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">منتجات نفدت</span>
                                    <span class="info-box-number">{{ $stats['out_of_stock_products'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="ti ti-currency"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">قيمة المخزون الإجمالية</span>
                                    <span class="info-box-number">{{ number_format($stats['total_stock_value'], 2) }} {{ config('app.currency', 'ريال') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="ti ti-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">نسبة المنتجات النافدة</span>
                                    <span class="info-box-number">
                                        @if($stats['total_products'] > 0)
                                            {{ round(($stats['out_of_stock_products'] / $stats['total_products']) * 100, 2) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('inventory.report') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search">البحث:</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="اسم المنتج أو SKU">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_status">حالة المخزون:</label>
                                    <select class="form-control" id="stock_status" name="stock_status">
                                        <option value="">الكل</option>
                                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>
                                            متوفر
                                        </option>
                                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>
                                            منخفض
                                        </option>
                                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                            نفد
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-search"></i> بحث
                                        </button>
                                        <a href="{{ route('inventory.report') }}" class="btn btn-secondary">
                                            <i class="ti ti-refresh"></i> إعادة تعيين
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="stockReportTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المنتج</th>
                                    <th>الفئة</th>
                                    <th>السعر</th>
                                    <th>الكمية</th>
                                    <th>حالة المخزون</th>
                                    <th>قيمة المخزون</th>
                                    <th>المتغيرات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->image)
                                                    <img src="{{ asset('assets/uploads/product_images/' . $product->image) }}" 
                                                         alt="{{ $product->name }}" class="img-thumbnail ml-2" width="40" height="40">
                                                @endif
                                                <div>
                                                    <strong>{{ $product->name }}</strong>
                                                    @if($product->sku)
                                                        <br><small class="text-muted">SKU: {{ $product->sku }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->Main_Category->name ?? 'غير محدد' }}</td>
                                        <td>{{ number_format($product->price, 2) }} {{ config('app.currency', 'ريال') }}</td>
                                        <td>
                                            <span class="badge {{ $product->quantity > 10 ? 'badge-success' : ($product->quantity > 0 ? 'badge-warning' : 'badge-danger') }}">
                                                {{ $product->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($product->quantity <= 0)
                                                <span class="badge badge-danger">نفد</span>
                                            @elseif($product->quantity <= 10)
                                                <span class="badge badge-warning">منخفض</span>
                                            @else
                                                <span class="badge badge-success">متوفر</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($product->quantity * $product->price, 2) }} {{ config('app.currency', 'ريال') }}</td>
                                        <td>
                                            @if($product->variations->count() > 0)
                                                <button type="button" class="btn btn-sm btn-info" onclick="showVariants({{ $product->id }})">
                                                    {{ $product->variations->count() }} متغيرات
                                                </button>
                                            @else
                                                <span class="text-muted">لا يوجد</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    <!-- Variants Details (Hidden by default) -->
                                    <tr id="variants-{{ $product->id }}" style="display: none;">
                                        <td colspan="8">
                                            <div class="p-3 bg-light">
                                                <h6>متغيرات المنتج:</h6>
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>المتغير</th>
                                                            <th>السعر</th>
                                                            <th>الكمية</th>
                                                            <th>حالة المخزون</th>
                                                            <th>قيمة المخزون</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($product->variations as $variation)
                                                            <tr>
                                                                <td>
                                                                    @php
                                                                        $attributes = [];
                                                                        foreach($variation->variationValues as $value) {
                                                                            $attributes[] = $value->attribute->name . ': ' . $value->attribute_value_name;
                                                                        }
                                                                        echo implode(' | ', $attributes);
                                                                    @endphp
                                                                </td>
                                                                <td>{{ number_format($variation->price, 2) }} {{ config('app.currency', 'ريال') }}</td>
                                                                <td>
                                                                    <span class="badge {{ $variation->stock > 10 ? 'badge-success' : ($variation->stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                                                                        {{ $variation->stock }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if($variation->stock <= 0)
                                                                        <span class="badge badge-danger">نفد</span>
                                                                    @elseif($variation->stock <= 10)
                                                                        <span class="badge badge-warning">منخفض</span>
                                                                    @else
                                                                        <span class="badge badge-success">متوفر</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ number_format($variation->stock * $variation->price, 2) }} {{ config('app.currency', 'ريال') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-primary text-white">
                                    <th colspan="6">الإجمالي</th>
                                    <th>{{ number_format($products->sum(function($p) { return $p->quantity * $p->price; }), 2) }} {{ config('app.currency', 'ريال') }}</th>
                                    <th>{{ $products->sum(function($p) { return $p->variations->count(); }) }} متغير</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
function showVariants(productId) {
    $('#variants-' + productId).toggle();
}

function exportToExcel() {
    // Simple CSV export
    var table = document.getElementById('stockReportTable');
    var rows = table.getElementsByTagName('tr');
    var csv = [];
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (var j = 0; j < cols.length; j++) {
            // Remove HTML tags and get text content
            var text = cols[j].innerText || cols[j].textContent;
            text = text.replace(/"/g, '""'); // Escape quotes
            row.push('"' + text + '"');
        }
        
        csv.push(row.join(','));
    }
    
    var csvContent = csv.join('\n');
    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement('a');
    var url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'stock_report_' + new Date().toISOString().slice(0,10) + '.csv');
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Auto-hide variant rows when printing
window.addEventListener('beforeprint', function() {
    $('[id^="variants-"]').hide();
});
</script>
@endsection
