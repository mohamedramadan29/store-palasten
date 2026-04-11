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
                    <div class="col-xl-12 col-lg-12">
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
                                <h4 class="card-title">معلومات العميل</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">اسم العميل</label>
                                            <input disabled required type="text" id="name" class="form-control" name="name"
                                                   value="{{$order['name']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">العنوان</label>
                                            <input disabled required type="text" id="address" class="form-control" name="address"
                                                   value="{{$order['address']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">المدينة</label>
                                            <input disabled required type="text" id="city" class="form-control" name="city"
                                                   value="{{$order['city']['city'] ?? 'غير محدد'}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">رقم الهاتف</label>
                                            <input disabled required type="text" id="phone" class="form-control" name="phone"
                                                   value="{{$order['phone']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phone2" class="form-label">رقم هاتف آخر</label>
                                            <input disabled type="text" id="phone2" class="form-control" name="phone2"
                                                   value="{{$order['phone2']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="note" class="form-label">ملاحظات العميل</label>
                                            <textarea disabled class="form-control" rows="3">{{$order['note']}}</textarea>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($order['is_marketer_order'] && $order['marketer'])
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">معلومات المسوق</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم المسوق</label>
                                            <input type="text" class="form-control" value="{{ $order['marketer']->name }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">البريد الإلكتروني</label>
                                            <input type="email" class="form-control" value="{{ $order['marketer']->email }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">رقم الهاتف</label>
                                            <input type="text" class="form-control" value="{{ $order['marketer']->phone }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">ربح المسوق من هذا الطلب</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ number_format($order['total_profit'] ?? 0, 2) }}" disabled>
                                                <span class="input-group-text">{{ $storeCurrency ?? 'ر.س' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.marketer.show', $order['marketer']->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-user me-2"></i>صفحة المسوق
                                    </a>
                                    <a href="{{ route('admin.marketer.reports') }}" class="btn btn-info btn-sm ms-2">
                                        <i class="fas fa-chart-line me-2"></i>تقارير المسوقين
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">تفاصيل الطلب</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>المنتج</th>
                                        <th>الكمية</th>
                                        <th>السعر</th>
                                        <th>الإجمالي</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order['details'] as $detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(isset($detail['productdata']['image']))
                                                        <img src="{{asset('assets/uploads/product_images/'.$detail['productdata']['image'])}}" 
                                                             alt="{{ $detail['productdata']['name'] ?? 'منتج غير محدد' }}" 
                                                             style="width: 50px; height: 50px; object-fit: cover; margin-left: 10px;">
                                                    @else
                                                        <div class="text-white bg-secondary d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px; margin-left: 10px;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $detail['productdata']['name'] ?? 'منتج غير محدد' }}</strong>
                                                        @if(isset($detail['productdata']['product_variation_id']))
                                                            <br><small class="text-muted">{{ $detail['variation_name'] ?? '' }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $detail['qty'] ?? 0 }}</td>
                                            <td>{{ number_format($detail['price'] ?? 0, 2) }}</td>
                                            <td>{{ number_format(($detail['price'] ?? 0) * ($detail['qty'] ?? 0), 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>الإجمالي الكلي:</strong></td>
                                            <td class="text-end"><strong>{{number_format($order['grand_total'], 2)}}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">حالة الطلب</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="order_status" class="form-label">تغيير حالة الطلب</label>
                                            <select class="form-select" name="order_status">
                                                <option value="" selected disabled>-- اختر حالة --</option>
                                                <option {{$order['order_status'] == 'جديد' ? 'selected' : ''}} value="جديد">جديد</option>
                                                <option {{$order['order_status'] == 'قيد المعالجة' ? 'selected' : ''}} value="قيد المعالجة">قيد المعالجة</option>
                                                <option {{$order['order_status'] == 'قيد التوصيل' ? 'selected' : ''}} value="قيد التوصيل">قيد التوصيل</option>
                                                <option {{$order['order_status'] == 'مكتمل' ? 'selected' : ''}} value="مكتمل">مكتمل</option>
                                                <option {{$order['order_status'] == 'ملغي' ? 'selected' : ''}} value="ملغي">ملغي</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="p-3 mb-3 rounded bg-light">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <a href="{{url('admin/orders')}}" class="btn btn-primary w-100">العودة للطلبات</a>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">حفظ التغييرات <i class='bx bxs-save'></i></button>
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
