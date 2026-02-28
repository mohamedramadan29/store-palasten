@extends('front.layouts.master')
@section('title')
تتبع طلبيتك
@endsection

@section('content')
<div class="page_content">
    <!-- page-title -->
    <div class="tf-page-title style-2">
        <div class="container-full">
            <div class="text-center heading"> تتبع طلبيتك </div>
        </div>
    </div>
    <!-- /page-title -->

    <section class="flat-spacing-11">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="mb-5 text-center box-form">
                        <h5 class="mb_24"> أدخل رقم الجوال لتتبع الطلب </h5>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('track.order.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3 form-group text-start">
                                <label class="form-label" for="phone"> رقم الجوال </label>
                                <input type="text" name="phone" id="phone" class="form-control"
                                    placeholder="أدخل رقم الجوال الذي قمت بالطلب به" value="{{ old('phone') }}"
                                    required>
                            </div>
                            <button type="submit" class="tf-btn btn-fill w-100"> تتبع الطلب </button>
                        </form>
                    </div>
                </div>
            </div>

            @if(isset($orders))
            <div class="mt-4 row">
                <div class="col-12">
                    <h4 class="mb-4"> نتائج التتبع </h4>
                    @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table text-center table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th> رقم الطلب </th>
                                    <th> تاريخ الطلب </th>
                                    <th> حالة الطلب </th>
                                    <th> الإجمالي </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td> #{{ $order->id }} </td>
                                    <td> {{ $order->created_at->format('Y-m-d') }} </td>
                                    <td>
                                        @if($order->order_status == 'لم يبدا')
                                        <span class="badge bg-warning text-dark"> لم يبدا </span>
                                        @elseif($order->order_status == 'بداية التنفيذ')
                                        <span class="text-white badge bg-info"> بداية التنفيذ </span>
                                        @elseif($order->order_status == 'مكتمل')
                                        <span class="text-white badge bg-success">مكتمل</span>
                                        @elseif($order->order_status == 'ملغي')
                                        <span class="text-white badge bg-danger">{{ $order->order_status }}</span>
                                        @else
                                        <span class="text-white badge bg-secondary">{{ $order->order_status }}</span>
                                        @endif
                                    </td>
                                    <td> {{ number_format($order->grand_total, 2) }} {{ $storeCurrency }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center alert alert-info">
                        لم يتم العثور على أي طلبات مرتبطة برقم الجوال هذا.
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection