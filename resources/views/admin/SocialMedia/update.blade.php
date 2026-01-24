@extends('admin.layouts.master')
@section('title')
    مواقع التواصل الاجتماعي
@endsection
@section('css')
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form method="post" action="{{url('admin/social-media/update')}}" enctype="multipart/form-data">
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
                                <h4 class="card-title"> ادخل روابط منصات التواصل الاجتماعي الخاصة بك </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="facebook" class="form-label"> الفيسبوك </label>
                                            <input  type="text" id="facebook" class="form-control" name="facebook"
                                                   value="{{$socials['facebook']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="instagram" class="form-label"> انستجرام  </label>
                                            <input   type="text" id="instagram" class="form-control" name="instagram"
                                                   value="{{$socials['instagram']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="linkedin" class="form-label"> لينكدان </label>
                                            <input  type="text" id="linkedin" class="form-control" name="linkedin"
                                                   value="{{$socials['linkedin']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="x-twitter" class="form-label"> X - تويتر  </label>
                                            <input   type="text" id="x-twitter" class="form-control" name="x-twitter"
                                                   value="{{$socials['x-twitter']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="youtube" class="form-label"> يوتيوب </label>
                                            <input type="text" id="youtube" class="form-control" name="youtube"
                                                   value="{{$socials['youtube']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="whatsapp" class="form-label"> واتساب  </label>
                                            <input   type="text" id="whatsapp" class="form-control" name="whatsapp"
                                                   value="{{$socials['whatsapp']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="pinterest" class="form-label"> بينترست </label>
                                            <input  type="text" id="pinterest" class="form-control" name="pinterest"
                                                   value="{{$socials['pinterest']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="tiktok" class="form-label"> تيك توك </label>
                                            <input  type="text" id="tiktok" class="form-control" name="tiktok"
                                                   value="{{$socials['tiktok']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="snapchat" class="form-label">  سناب شات </label>
                                            <input  type="text" id="snapchat" class="form-control" name="snapchat"
                                                   value="{{$socials['snapchat']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="telegram" class="form-label"> تيليجرام </label>
                                            <input  type="text" id="telegram" class="form-control" name="telegram"
                                                   value="{{$socials['telegram']}}">
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

@endsection
