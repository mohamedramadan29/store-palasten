@extends('admin.layouts.master')
@section('title')
    حسابي - تعديل كلمة المرور
@endsection
@section('css')
@endsection
@section('content')
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <form name="updateAdminPasswordForm" method="post" action="{{url('admin/update_admin_password')}}"
                  enctype="multipart/form-data" autocomplete="off">
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
                                <h4 class="card-title"> حسابي - تعديل كلمة المرور </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">

                                        <div class="mb-3">
                                            <label for="name" class="form-label"> الاسم </label>
                                            <input disabled readonly type="text" id="name" class="form-control"
                                                   name="name"
                                                   value="{{$admin_data['name']}}">
                                        </div>

                                    </div>
                                    <div class="col-lg-6">

                                        <div class="mb-3">
                                            <label for="old_password" class="form-label"> كلمة المرور القديمة </label>
                                            <input type="password" id="old_password" class="form-control"
                                                   name="old_password">
                                            <span id="check_password"></span>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label"> كملة المرور الجديدة </label>
                                            <input type="password" id="new_password" class="form-control"
                                                   name="new_password">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">

                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label"> تأكيد كلمة المرور </label>
                                            <input type="password" id="confirm_password" class="form-control"
                                                   name="confirm_password">
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script>
                // Check Old Admin Password
                $("#old_password").keyup(function () {
                    var current_password = $("#old_password").val();
                    var csrf_token = '{{ csrf_token() }}'; // Include the CSRF token here
                    $.ajax({
                        type: 'post',
                        url: 'check_admin_password',
                        data: {
                            current_password: current_password,
                            _token: csrf_token // Add the CSRF token to the data
                        },
                        success: function (response) {
                            if (response == "true") {
                                $("#check_password").html('<p style="color:green"> كلمة المرور صحيحة </p>');
                            } else if (response == 'false') {
                                $("#check_password").html('<p style="color:red"> كلمة المرور خطا </p>');
                            }
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                });
            </script>

@endsection
