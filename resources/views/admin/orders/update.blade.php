@extends('admin.layouts.master')
@section('title')
     تفاصيل الطلب
@endsection
@section('css')

@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form method="post" action="{{url('admin/order/update/'.$order['id'])}}" enctype="multipart/form-data">
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
                                                   value="{{$order['city']['city'] ?? ' غير محدد '}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label"> رقم الهاتف </label>
                                            <input disabled required type="text" id="phone" class="form-control" name="phone"
                                                   value="{{$order['phone']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phone2" class="form-label"> رقم هاتف إضافي </label>
                                            <input disabled type="text" id="phone2" class="form-control" name="phone2"
                                                   value="{{$order['phone2']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="note" class="form-label"> ملاحظات العميل </label>
                                            <textarea disabled class="form-control" rows="3">{{$order['note']}}</textarea>
                                        </div>
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
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>  المنتج   </th>
                                        <th> العدد  </th>
                                        <th> السعر   </th>
                                        <th> السعر الكلي   </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order['details'] as $detail)
                                        <tr>
                                            <td>
                                                <div class="gap-2 d-flex align-items-center">
                                                    <img src="{{asset('assets/uploads/product_images/'.($detail->variation->image ?? $detail->product->image))}}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                    <div>
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
                                                    </div>
                                                </div>
                                            </td>
                                            <td> {{$detail['product_qty']}} </td>
                                            <td> {{$detail['product_price']}} </td>
                                            <td> {{ number_format($detail['product_qty'] * $detail['product_price'] , 2)  }} </td>
                                        </tr>
                                    @endforeach

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
                        <div class="p-3 mb-3 rounded bg-light">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <a href="{{url('admin/orders')}}" class="btn btn-primary w-100"> رجوع </a>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">  تحديث حالة الطلب  <i class='bx bxs-save'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Container Fluid -->


        <!-- ==================================================== -->
        <!-- End Page Content -->
        <!-- ==================================================== -->
@endsection

@section('js')
@endsection
