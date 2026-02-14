@extends('admin.layouts.master')

@section('title')
    فاتورة الطلب
@endsection

@section('css')
<style media="screen">
    @page {
        size: A4;
        margin: 10mm;
    }

    body {
        font-size: 11px;
        color: #000;
        line-height: 1.4;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    h2 {
        font-size: 15px;
        margin-bottom: 3px;
        font-weight: bold;
    }

    h4 {
        font-size: 13px;
        margin-bottom: 6px;
        font-weight: bold;
    }

    p {
        margin: 0;
        font-size: 11px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        border: 1px solid #000 !important;
        padding: 6px !important;
        font-size: 11px;
        vertical-align: middle;
    }

    .table thead th {
        background: #fff !important;
        font-weight: bold;
        text-align: center;
    }

    .invoice-logo {
        width: 50%;
        height: auto;
    }
</style>

<style media="print">
    @page {
        size: A4;
        margin: 10mm;
    }

    body {
        font-size: 13pt;
        color: #000;
        line-height: 1.5;
    }

    h2 {
        font-size: 18pt;
        margin-bottom: 8px;
        font-weight: bold;
    }

    h4 {
        font-size: 15pt;
        margin-bottom: 10px;
        font-weight: bold;
    }

    p {
        margin: 0;
        font-size: 13pt;
    }

    .table th,
    .table td {
        border: 1px solid #000 !important;
        padding: 10px !important;
        font-size: 13pt;
        vertical-align: middle;
    }

    .table thead th {
        background: #f8f9fa !important;
        font-weight: bold;
        text-align: center;
    }

    .invoice-logo {
        width: 60%;
        height: auto;
    }

    .d-print-none {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="mb-3 row align-items-center">
                            <div class="col-6 text-start">
                                <img src="{{asset('assets/uploads/PublicSetting/'.$publicsetting['website_logo'])}}" class="invoice-logo">
                            </div>
                            <div class="col-6 text-end">
                                <h2>فاتورة رقم #{{$order['id']}}</h2>
                                <p>تاريخ الطلب: {{$order['created_at']->format('Y/m/d')}}</p>
                            </div>
                        </div>

                        <h4>بيانات العميل والشحن</h4>
                        <table class="table mb-4">
                            <tbody>
                                <tr>
                                    <td width="30%">الاسم</td>
                                    <td>{{$order['name']}}</td>
                                </tr>
                                <tr>
                                    <td>رقم الهاتف</td>
                                    <td>{{$order['phone']}}</td>
                                </tr>
                                @if($order['phone2'])
                                <tr>
                                    <td>رقم هاتف إضافي</td>
                                    <td>{{$order['phone2']}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>المدينة</td>
                                    <td>{{$order['city']['city'] ?? 'غير محدد'}}</td>
                                </tr>
                                <tr>
                                    <td>العنوان الكامل</td>
                                    <td>{{$order['address']}}</td>
                                </tr>
                                @if($order['note'])
                                <tr>
                                    <td>ملاحظات العميل</td>
                                    <td>{{$order['note']}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <h4>تفاصيل المنتجات</h4>
                        <table class="table mb-4">
                            <thead>
                                <tr>
                                    <th width="10%">الصورة</th>
                                    <th>اسم المنتج</th>
                                    <th width="10%">الكمية</th>
                                    <th width="20%">سعر الوحدة</th>
                                    <th width="20%">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal = 0; @endphp
                                @foreach($order['details'] as $detail)
                                    @php
                                        $lineTotal = $detail['product_price'] * $detail['product_qty'];
                                        $subtotal += $lineTotal;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{asset('assets/uploads/product_images/'.($detail->variation->image ?? $detail->product->image))}}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        </td>
                                        <td>
                                            <div>{{$detail['product_name']}}</div>
                                            @if($detail->product_variation_id != null)
                                                @php
                                                    $variationValues = \App\Models\admin\VartionsValues::with('attribute')->where('product_variation_id', $detail->product_variation_id)->get();
                                                @endphp
                                                <div style="font-size: 11px; color: #777;">
                                                    @foreach($variationValues as $value)
                                                        {{ $value->attribute->name }}: {{ $value->attribute_value_name }}@if(!$loop->last) - @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{$detail['product_qty']}}</td>
                                        <td class="text-center">{{$detail['product_price']}} {{$publicsetting['website_currency']}}</td>
                                        <td class="text-center">{{number_format($lineTotal,2)}} {{$publicsetting['website_currency']}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <table class="table mb-4">
                            <tbody>
                                <tr>
                                    <td>مجموع المنتجات</td>
                                    <td class="text-end">{{$subtotal}} {{$publicsetting['website_currency']}}</td>
                                </tr>
                                <tr>
                                    <td>قيمة الشحن</td>
                                    <td class="text-end">{{$order['shipping_price']}} {{$publicsetting['website_currency']}}</td>
                                </tr>
                                <tr>
                                    <td>قيمة الخصم</td>
                                    <td class="text-end">0 {{$publicsetting['website_currency']}}</td>
                                </tr>
                                <tr>
                                    <td><strong>المجموع الكلي</strong></td>
                                    <td class="text-end"><strong>{{$order['grand_total']}} {{$publicsetting['website_currency']}}</strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <p class="mb-4"><strong>توصيل الطلب يكون في حد أقصى 7 أيام</strong></p>

                        <div class="mt-4 text-center d-print-none">  
                            <a href="javascript:window.print()" class="px-5 btn btn-primary btn-lg">  
                                <i class="bx bx-printer me-2"></i> طباعة الفاتورة  
                            </a>  
                        </div>  

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection