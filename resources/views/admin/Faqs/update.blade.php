@extends('admin.layouts.master')
@section('title')
    تعديل السؤال
@endsection
@section('css')
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form method="post" action="{{url('admin/faq/update/'.$faq['id'])}}" enctype="multipart/form-data">
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
                                <h4 class="card-title"> تعديل السؤال  </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> السؤال <span class="star"
                                                                                               style="color: red"> * </span>
                                            </label>
                                            <input required type="text" id="title" class="form-control" name="title"
                                                   value="{{$faq['title']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="hidden" name="content" id="content">
                                        <!-- Quill Editors -->
                                        <div id="snow-editor" style="height: 300px;">
                                            {{$faq['content']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 bg-light mb-3 rounded">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100"> حفظ <i
                                            class='bx bxs-save'></i></button>
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
            <!-- Quill Editor js -->
            <script src="{{asset('assets/admin/js/components/form-quilljs.js')}}"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    // الحصول على كائن المحرر Quill الموجود بالفعل
                    var quill = Quill.find(document.getElementById('snow-editor'));

                    // تعبئة محتوى Quill editor بالمحتوى السابق أو المحتوى الحالي من قاعدة البيانات
                    var oldContent = `{!! old('content', $faq['content']) !!}`;
                    quill.root.innerHTML = oldContent;

                    // تحديث الحقل المخفي بالمحتوى قبل إرسال النموذج
                    var form = document.querySelector('form');
                    form.onsubmit = function () {
                        document.querySelector('input[name=content]').value = quill.root.innerHTML;
                    };
                });
            </script>
@endsection
