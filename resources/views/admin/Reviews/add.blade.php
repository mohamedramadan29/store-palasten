@extends('admin.layouts.master')
@section('title')
    اضافة تقييم جديد
@endsection
@section('css')
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form method="post" action="{{ url('admin/review/store') }}" enctype="multipart/form-data">
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
                                <h4 class="card-title"> اضافة تقييم جديد </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> العميل <span class="star"
                                                    style="color: red"> * </span>
                                            </label>
                                            <input required type="text" id="title" class="form-control"
                                                name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">التقييم <span class="star" style="color: red"> *
                                                </span></label>
                                            <div class="star-rating">
                                                <input type="radio" name="rating" id="rating-5" value="5"><label
                                                    for="rating-5">★</label>
                                                <input type="radio" name="rating" id="rating-4" value="4"><label
                                                    for="rating-4">★</label>
                                                <input type="radio" name="rating" id="rating-3" value="3"><label
                                                    for="rating-3">★</label>
                                                <input type="radio" name="rating" id="rating-2" value="2"><label
                                                    for="rating-2">★</label>
                                                <input type="radio" name="rating" id="rating-1" value="1"><label
                                                    for="rating-1">★</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="hidden" name="content" id="content">
                                        <!-- Quill Editors -->
                                        <div id="snow-editor" style="height: 300px;">
                                        </div>
                                    </div>

                                    <style>
                                        .star-rating {
                                            direction: rtl;
                                            display: inline-flex;
                                            gap: 5px;
                                        }

                                        .star-rating input {
                                            display: none;
                                        }

                                        .star-rating label {
                                            font-size: 24px;
                                            color: #ddd;
                                            cursor: pointer;
                                        }

                                        .star-rating input:checked~label,
                                        .star-rating label:hover,
                                        .star-rating label:hover~label {
                                            color: #ffbf00;
                                        }
                                    </style>
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
        <script src="{{ asset('assets/admin/js/components/form-quilljs.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // الحصول على كائن المحرر Quill الموجود بالفعل
                var quill = Quill.find(document.getElementById('snow-editor'));

                // تعبئة محتوى Quill editor بالمحتوى السابق (إن وجد)
                var oldContent = `{!! old('content') !!}`;
                quill.root.innerHTML = oldContent;

                // تحديث الحقل المخفي بالمحتوى قبل إرسال النموذج
                var form = document.querySelector('form');
                form.onsubmit = function() {
                    document.querySelector('input[name=content]').value = quill.root.innerHTML;
                };
            });
        </script>
    @endsection
