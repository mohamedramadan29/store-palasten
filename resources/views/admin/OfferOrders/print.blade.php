@extends('admin.layouts.master')

@section('title')
    فاتورة الطلب
@endsection

@section('css')
<style media="screen">
    @page { size: A4; margin: 10mm; }

    body {
        font-size: 11px;
        color: #000;
        line-height: 1.4;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    h2 { font-size: 15px; margin-bottom: 3px; font-weight: bold; }
    h4 { font-size: 13px; margin-bottom: 6px; font-weight: bold; }
    p  { margin: 0; font-size: 11px; }

    table { width: 100%; border-collapse: collapse; }

    .table th, .table td {
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

    .invoice-logo { max-width: 180px; height: auto; }
</style>

<style media="print">
    @page { size: A4; margin: 10mm; }

    body {
        font-size: 13pt;
        color: #000;
        line-height: 1.5;
    }

    h2 { font-size: 18pt; margin-bottom: 8px; font-weight: bold; }
    h4 { font-size: 15pt; margin-bottom: 10px; font-weight: bold; }
    p  { font-size: 13pt; margin: 0; }

    .table th, .table td {
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

    .invoice-logo { max-width: 220px; height: auto; }

    .d-print-none { display: none !important; }

    .bg-info-subtle, .position-absolute img { display: none !important; }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-4 align-items-center">
                            <div class="col-6 text-start">
                                <img src="{{asset('assets/uploads/PublicSetting/'.$publicsetting['website_logo'])}}" class="invoice-logo" alt="logo">
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
                                <tr>
                                    <td>المدينة</td>
                                    <td>{{$order['shipping']['city']}}</td>
                                </tr>
                                <tr>
                                    <td>العنوان الكامل</td>
                                    <td>{{$order['address']}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>تفاصيل المنتج</h4>
                        <table class="table mb-4">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th width="20%">قيمة الشحن</th>
                                    <th width="20%">السعر الكلي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$order['product_name']}}</td>
                                    <td class="text-center">{{number_format($order['ship_price'], 2)}} {{$publicsetting['website_currency']}}</td>
                                    <td class="text-center">{{number_format($order['total_price'], 2)}} {{$publicsetting['website_currency']}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <p class="mb-4"><strong>توصيل الطلب يكون في حد أقصى 7 أيام</strong></p>

                        <div class="mt-4 text-center d-print-none">
                            <a href="javascript:window.print()" class="btn btn-info btn-lg px-5">
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