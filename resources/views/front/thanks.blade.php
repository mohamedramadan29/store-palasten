@extends('front.layouts.master')

@section('title')
فاتورة إتمام الطلب
@endsection

@section('css')
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style media="screen">
@page { size: A4; margin: 10mm; }

body {
    font-size: 12px;
    color: #000;
    line-height: 1.5;
    background: #f8f9fa;
}

.invoice-container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.invoice-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 40px 20px;
    text-align: center;
}

.invoice-header h1 {
    font-size: 36px;
    margin: 10px 0;
}

.invoice-header p {
    font-size: 18px;
}

.invoice-body {
    padding: 40px;
}

.invoice-info {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
    gap: 12px;
    font-size: 16px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
    font-size: 16px;
}

.table th,
.table td {
    border: 1px solid #000;
    padding: 14px;
    text-align: center;
}

.table thead th {
    background: #2c3e50;
    color: #fff;
}

.totals {
    max-width: 450px;
    margin-left: auto;
    font-size: 18px;
}

.totals div {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
}

.totals .grand-total {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 16px 18px;
    border-radius: 12px;
    font-size: 22px;
}

.print-btn {
    text-align: center;
    margin: 30px 0;
}

.print-btn button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 16px 50px;
    border-radius: 50px;
    font-size: 20px;
    border: none;
    cursor: pointer;
}
</style>

<style media="print">
@page { 
    size: A4; 
    margin: 10mm; 
}

header, footer, nav, .site-header, .site-footer, .main-header, .main-footer,
.navbar, .topbar, .header, .footer-section,
.tf-page-title, .breadcrumb, .sidebar, aside,
.search-bar, .search-form, .search-button, .search-icon,
.social-links, .social-icons, .social-media, .share-buttons,
.social a, .bx.bx-search, .bx.bx-facebook, .bx.bx-twitter, .bx.bx-instagram,
.print-btn, .cus-container2 > .print-btn
{
    display: none !important;
}

.invoice-header {
    display: none !important;
}

body {
    font-size: 11pt;
    color: #000;
    line-height: 1.4;
    background: #fff !important;
    direction: rtl;
    text-align: right;
    padding: 0;
    margin: 0;
}

.invoice-container {
    max-width: 100%;
    margin: 0;
    padding: 30px;
    background: none;
    box-shadow: none;
    border-radius: 0;
    border: none;
}

.invoice-body::before {
    content: "فاتورة رقم #" attr(data-order-id);
    font-size: 18pt;
    font-weight: bold;
    display: block;
    text-align: center;
    margin-bottom: 10px;
}

.invoice-body::after {
    content: "تاريخ الطلب: " attr(data-order-date);
    font-size: 12pt;
    display: block;
    text-align: center;
    margin-bottom: 30px;
    clear: both;
}

.invoice-body {
    padding: 20px 30px;
}

.invoice-info {
    background: none !important;
    padding: 0;
    margin-bottom: 30px;
}

.info-grid {
    display: block;
    font-size: 11pt;
}

.info-grid > div {
    padding: 6px 0;
    border-bottom: 1px dashed #ccc;
}

.table th, .table td {
    border: 1px solid #000 !important;
    padding: 10px !important;
    font-size: 11pt;
    text-align: center;
}

.table thead th {
    background: #f8f9fa !important;
    color: #000 !important;
    font-weight: bold;
}

.totals {
    display: none !important;
}

.print-totals-table {
    display: table !important;
    width: 50%;
    margin-left: auto;
    margin-top: 30px;
    font-size: 12pt;
    border-collapse: collapse;
}

.print-totals-table td {
    border: 1px solid #000;
    padding: 10px;
    text-align: center;
}

.print-grand-total td {
    background: #f0f0f0 !important;
    font-weight: bold;
    font-size: 14pt;
}

.footer-note {
    text-align: center;
    margin-top: 40px;
    font-size: 12pt;
}

.footer-note p {
    margin: 8px 0;
}

.print-logo {
    display: block !important;
    text-align: center;
    margin-bottom: 20px;
}
</style>
@endsection

@section('content')
<div class="page_content">
<section class="invoice-section">
<div class="cus-container2">

    <div class="print-btn">
        <button onclick="window.print()"><i class="bx bx-printer"></i> طباعة الفاتورة</button>
    </div>

    @php
        $publicsetting = \App\Models\admin\PublicSetting::select('website_logo')->first();
        $order = \App\Models\front\Order::with('city')->where('id', session('order_id'))->first();
        $items = \App\Models\front\OrderDetails::where('order_id',$order->id)->get();
        $subtotal = 0;
    @endphp

    <div class="invoice-container">

        <div class="invoice-header">
            <img src="{{asset('assets/uploads/PublicSetting/'.$publicsetting->website_logo)}}" height="80" alt="Logo">
            <h1>فاتورة رقم #{{$order->id}}</h1>
            <p>تاريخ الطلب: {{$order->created_at->format('Y/m/d')}}</p>
        </div>

        <div class="invoice-body"
             data-order-id="{{$order->id}}"
             data-order-date="{{$order->created_at->format('Y/m/d')}}">

            <div class="print-logo" style="display: none;">
                <img src="{{asset('assets/uploads/PublicSetting/'.$publicsetting->website_logo)}}" style="max-width: 180px; height: auto;">
            </div>

            <div class="invoice-info">
                <div class="info-grid">
                    <div>الاسم: {{$order->name}}</div>
                    <div>الهاتف: {{$order->phone}}</div>
                    <div>المدينة: {{$order->city->city}}</div>
                    <div>العنوان: {{$order->address}}</div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php $subtotal += $item->product_price * $item->product_qty; @endphp
                        <tr>
                            <td>{{$item->product_name}}</td>
                            <td>{{number_format($item->product_price,2)}} {{$storeCurrency}}</td>
                            <td>{{$item->product_qty}}</td>
                            <td>{{number_format($item->product_price*$item->product_qty,2)}} {{$storeCurrency}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <div><span>مجموع المنتجات</span><span>{{number_format($subtotal,2)}} {{$storeCurrency}}</span></div>
                <div><span>الشحن</span><span>{{number_format($order->shipping_price,2)}} {{$storeCurrency}}</span></div>
                <div class="grand-total"><span>المجموع الكلي</span><span>{{number_format($order->grand_total,2)}} {{$storeCurrency}}</span></div>
            </div>

            <table class="table print-totals-table" style="display:none;">
                <tr>
                    <td>مجموع المنتجات</td>
                    <td>{{number_format($subtotal,2)}} {{$storeCurrency}}</td>
                </tr>
                <tr>
                    <td>الشحن</td>
                    <td>{{number_format($order->shipping_price,2)}} {{$storeCurrency}}</td>
                </tr>
                <tr class="print-grand-total">
                    <td><strong>المجموع الكلي</strong></td>
                    <td><strong>{{number_format($order->grand_total,2)}} {{$storeCurrency}}</strong></td>
                </tr>
            </table>

            <div class="footer-note">
                <p><strong>توصيل الطلب يكون في حد أقصى 3 أيام</strong></p>
                <p>شكراً لتسوقك معنا!</p>
            </div>

        </div>
    </div>

</div>
</section>
</div>
@endsection