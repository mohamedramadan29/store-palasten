@extends('admin.layouts.master')
@section('title')
     تعديل العلامة التجارية - {{$brand['name']}}
@endsection
@section('css')
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form method="post" action="{{url('admin/brand/update/'.$brand['id'])}}" enctype="multipart/form-data">
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
                                <h4 class="card-title"> ادخل المعلومات  </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">

                                        <div class="mb-3">
                                            <label for="name" class="form-label"> الاسم  </label>
                                            <input required type="text" id="name" class="form-control" name="name"
                                                   value="{{$brand['name']}}">
                                        </div>

                                    </div>

                                    <div class="col-lg-6">

                                        <label for="crater" class="form-label"> حالة التفعيل </label>
                                        <select required name="status" class="form-control" id="crater" data-choices
                                                data-choices-groups data-placeholder="Select Crater">
                                            <option value=""> -- حدد الحالة --</option>
                                            <option @if($brand['status'] == 1) selected @endif value="1">مفعل</option>
                                            <option @if($brand['status'] == 0) selected @endif value="0">غير مفعل</option>

                                        </select>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">صورة العلامة التجارية </h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <img width="80px" height="80px" class="img-thumbnail" src="{{asset('assets/uploads/brands/'.$brand['image'])}}" alt="">
                                </div>
                                <!-- File Upload -->
                                {{--                            <form action="https://techzaa.getappui.com/" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">--}}
                                {{--                                <div class="fallback">--}}
                                {{--                                    <input name="file" type="file" multiple />--}}
                                {{--                                </div>--}}
                                {{--                                <div class="dz-message needsclick">--}}
                                {{--                                    <i class="bx bx-cloud-upload fs-48 text-primary"></i>--}}
                                {{--                                    <h3 class="mt-4">Drop your images here, or <span class="text-primary">click to browse</span></h3>--}}
                                {{--                                    <span class="text-muted fs-13">--}}
                                {{--                                                       1600 x 1200 (4:3) recommended. PNG, JPG and GIF files are allowed--}}
                                {{--                                                  </span>--}}
                                {{--                                </div>--}}
                                {{--                            </form>--}}
                            </div>
                        </div>

                        <div class="p-3 bg-light mb-3 rounded">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <a href="{{url('admin/brands')}}" class="btn btn-primary w-100"> رجوع </a>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">  حفظ <i class='bx bxs-save'></i> </button>
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
