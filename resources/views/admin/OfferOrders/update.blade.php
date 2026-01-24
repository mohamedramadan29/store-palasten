@extends('admin.layouts.master')
@section('title')
    تفاصيل الطلب
@endsection
@section('css')

@endsection
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <form method="post" action="{{url('admin/offer_order/update/'.$order['id'])}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-12 col-lg-12 ">
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

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> معلومات العميل  </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">  الاسم  </label>
                                            <input disabled required type="text" id="name" class="form-control" name="name"
                                                   value="{{$order['name']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">  العنوان  </label>
                                            <input disabled required type="text" id="name" class="form-control" name="name"
                                                   value="{{$order['address']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">  مدينة الشحن  </label>
                                            <input disabled required type="text" id="name" class="form-control" name="name"
                                                   value="{{$order['shipping']['city']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> رقم الهاتف  </label>
                                            <input disabled required type="text" id="name" class="form-control" name="name"
                                                   value="{{$order['phone']}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> تفاصيل الطلب   </h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">  المنتج   </th>
                                            <th class="text-center">  قيمة الشحن   </th>
                                            <th class="text-center"> السعر الكلي   </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center"> {{$order['product_name']}} </td>
                                            <td class="text-center"> {{$order['ship_price']}} </td>
                                            <td class="text-center"> {{$order['total_price']}} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> حالة الطلب  </h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">  تحديث حالة الطلب   </label>
                                        <select class="form-select" name="order_status">
                                            <option value="" selected disabled> -- حدد حالة الطلب  --</option>
                                            <option {{$order['order_status'] == 'لم يبدا' ? 'selected' : ''}} value="لم يبدا">لم يبدأ</option>
                                            <option {{$order['order_status'] == 'بداية التنفيذ' ? 'selected' : ''}} value="بداية التنفيذ"> بداية التنفيذ </option>
                                            <option {{$order['order_status'] == 'مكتمل' ? 'selected' : ''}} value="مكتمل">مكتمل </option>
                                            <option {{$order['order_status'] == 'ملغي' ? 'selected' : ''}} value="ملغي">ملغي</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light mb-3 rounded">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">  تحديث حالة الطلب  <i class='bx bxs-save'></i></button>
                                </div>
                                <div class="col-lg-2">
                                    <a href="{{url('admin/offer_orders')}}" class="btn btn-primary w-100"> رجوع </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection