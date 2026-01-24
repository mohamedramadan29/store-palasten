@extends('admin.layouts.master')
@section('title')
    اضافة ميزة جديدة للمتجر
@endsection
@section('css')
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form method="post" action="{{url('admin/advantage/store')}}" enctype="multipart/form-data">
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
                                <h4 class="card-title"> اضافة ميزة جديدة للمتجر </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> الاسم </label>
                                            <input required type="text" id="name" class="form-control" name="name"
                                                   value="{{old('name')}}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="icon" class="form-label"> اختر ايقونة الميزة </label>
                                            <div class="icon-selection">
                                                <!-- وضع هنا 100 أيقونة من الأكثر استخدامًا -->
                                                <i class="fas fa-home icon-option"></i>
                                                <i class="fas fa-user icon-option"></i>
                                                <i class="fas fa-search icon-option"></i>
                                                <i class="fas fa-envelope icon-option"></i>
                                                <i class="fas fa-phone icon-option"></i>
                                                <i class="fas fa-shopping-cart icon-option"></i>
                                                <i class="fas fa-lock icon-option"></i>
                                                <i class="fas fa-cog icon-option"></i>
                                                <i class="fas fa-heart icon-option"></i>
                                                <i class="fas fa-star icon-option"></i>
                                                <i class="fas fa-trash icon-option"></i>
                                                <i class="fas fa-pencil-alt icon-option"></i>
                                                <i class="fas fa-camera icon-option"></i>
                                                <i class="fas fa-map-marker-alt icon-option"></i>
                                                <i class="fas fa-calendar-alt icon-option"></i>
                                                <i class="fas fa-check icon-option"></i>
                                                <i class="fas fa-exclamation-triangle icon-option"></i>
                                                <i class="fas fa-comment icon-option"></i>
                                                <i class="fas fa-share icon-option"></i>
                                                <i class="fas fa-book icon-option"></i>
                                                <i class="fas fa-bell icon-option"></i>
                                                <i class="fas fa-print icon-option"></i>
                                                <i class="fas fa-link icon-option"></i>
                                                <i class="fas fa-power-off icon-option"></i>
                                                <i class="fas fa-edit icon-option"></i>
                                                <i class="fas fa-upload icon-option"></i>
                                                <i class="fas fa-download icon-option"></i>
                                                <i class="fas fa-sync-alt icon-option"></i>
                                                <i class="fas fa-sign-in-alt icon-option"></i>
                                                <i class="fas fa-sign-out-alt icon-option"></i>
                                                <i class="fas fa-clock icon-option"></i>
                                                <i class="fas fa-folder icon-option"></i>
                                                <i class="fas fa-chart-line icon-option"></i>
                                                <i class="fas fa-eye icon-option"></i>
                                                <i class="fas fa-user-plus icon-option"></i>
                                                <i class="fas fa-user-circle icon-option"></i>
                                                <i class="fas fa-users icon-option"></i>
                                                <i class="fas fa-thumbs-up icon-option"></i>
                                                <i class="fas fa-download icon-option"></i>
                                                <i class="fas fa-trophy icon-option"></i>
                                                <i class="fas fa-clipboard icon-option"></i>
                                                <i class="fas fa-box-open icon-option"></i>
                                                <i class="fas fa-car icon-option"></i>
                                                <i class="fas fa-credit-card icon-option"></i>
                                                <i class="fas fa-handshake icon-option"></i>
                                                <i class="fas fa-bolt icon-option"></i>
                                                <i class="fas fa-dollar-sign icon-option"></i>
                                                <i class="fas fa-chart-bar icon-option"></i>
                                                <i class="fas fa-headphones icon-option"></i>
                                                <i class="fas fa-play icon-option"></i>
                                                <i class="fas fa-pause icon-option"></i>
                                                <i class="fas fa-stop icon-option"></i>
                                                <i class="fas fa-forward icon-option"></i>
                                                <i class="fas fa-backward icon-option"></i>
                                                <i class="fas fa-microphone icon-option"></i>
                                                <i class="fas fa-podcast icon-option"></i>
                                                <i class="fas fa-volume-up icon-option"></i>
                                                <i class="fas fa-volume-down icon-option"></i>
                                                <i class="fas fa-volume-mute icon-option"></i>
                                                <i class="fas fa-gamepad icon-option"></i>
                                                <i class="fas fa-paint-brush icon-option"></i>
                                                <i class="fas fa-file-alt icon-option"></i>
                                                <i class="fas fa-file-pdf icon-option"></i>
                                                <i class="fas fa-folder-open icon-option"></i>
                                                <i class="fas fa-tags icon-option"></i>
                                                <i class="fas fa-key icon-option"></i>
                                                <i class="fas fa-hashtag icon-option"></i>
                                                <i class="fas fa-clipboard-check icon-option"></i>
                                                <i class="fas fa-chalkboard-teacher icon-option"></i>
                                                <i class="fas fa-newspaper icon-option"></i>
                                                <i class="fas fa-bicycle icon-option"></i>
                                                <i class="fas fa-rocket icon-option"></i>
                                                <i class="fas fa-tree icon-option"></i>
                                                <i class="fas fa-moon icon-option"></i>
                                                <i class="fas fa-sun icon-option"></i>
                                                <i class="fas fa-graduation-cap icon-option"></i>
                                                <i class="fas fa-medal icon-option"></i>
                                                <i class="fas fa-anchor icon-option"></i>
                                                <i class="fas fa-taxi icon-option"></i>
                                                <i class="fas fa-truck icon-option"></i>
                                                <i class="fas fa-utensils icon-option"></i>
                                                <i class="fas fa-glass-martini icon-option"></i>
                                                <i class="fas fa-bed icon-option"></i>
                                                <i class="fas fa-tv icon-option"></i>
                                                <i class="fas fa-shower icon-option"></i>
                                                <i class="fas fa-toilet icon-option"></i>
                                                <i class="fas fa-wifi icon-option"></i>
                                                <i class="fas fa-lightbulb icon-option"></i>
                                                <i class="fas fa-battery-full icon-option"></i>
                                                <i class="fas fa-music icon-option"></i>
                                                <i class="fas fa-video icon-option"></i>
                                                <i class="fas fa-bookmark icon-option"></i>
                                            </div>
                                            <input required type="hidden" id="icon" class="form-control" name="icon" value="{{old('icon')}}">
                                        </div>
                                    </div>

                                    <style>
                                        .icon-selection {
                                            display: flex;
                                            flex-wrap: wrap;
                                            gap: 10px;
                                        }

                                        .icon-option {
                                            font-size: 24px;
                                            cursor: pointer;
                                        }

                                        .icon-option.selected {
                                            color: #007bff;
                                        }
                                    </style>

                                    <script>
                                        const icons = document.querySelectorAll('.icon-option');
                                        const iconInput = document.getElementById('icon');

                                        icons.forEach(icon => {
                                            icon.addEventListener('click', function () {
                                                icons.forEach(i => i.classList.remove('selected'));
                                                this.classList.add('selected');
                                                iconInput.value = this.classList[1];  // Assigns the icon class (e.g., 'fa-home') to input
                                            });
                                        });
                                    </script>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="description" class="form-label"> الوصف </label>
                                            <textarea name="description" id="" cols="30" rows="10"
                                                      class="form-control">{{old('description')}}</textarea>
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
                                <div class="col-lg-2">
                                    <a href="{{url('admin/advantages')}}" class="btn btn-primary w-100"> رجوع </a>
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
