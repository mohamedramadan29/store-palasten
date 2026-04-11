@extends('front.layouts.master')
@section('title')
    تغيير كلمة المرور
@endsection
@section('content')
    <div class="page-content" style="margin-top:120px">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Customer Sidebar -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 card-title" style="font-size: 20px">قائمة الحساب</h5>
                        </div>
                        <div class="p-0 card-body">
                            <nav class="nav flex-column nav-pills">
                                <a href="{{ route('customer.dashboard') }}" class="nav-link">
                                    <i class="ti ti-home"></i>
                                    الرئيسية
                                </a>
                                <a href="{{ route('customer.profile') }}" class="nav-link">
                                    <i class="ti ti-user"></i>
                                    الملف الشخصي
                                </a>
                                <a href="{{ route('customer.orders') }}" class="nav-link">
                                    <i class="ti ti-shopping-bag"></i>
                                    طلباتي
                                </a>
                                <a href="{{ route('customer.password') }}" class="nav-link active">
                                    <i class="ti ti-lock"></i>
                                    تغيير كلمة المرور
                                </a>
                                {{-- <a href="#" class="nav-link">
                                    <i class="ti ti-location"></i>
                                    
                                </a> --}}
                                <hr class="my-2">
                                <a href="{{ route('customer.logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti ti-logout"></i>
                                    تسجيل الخروج
                                </a>
                                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Password Change Content -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="font-size: 22px">تغيير كلمة المرور</h4>
                            <p class="mb-0 text-muted">قم بتغيير كلمة المرور الخاصة بحسابك</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="passwordForm">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                            <small class="form-text text-muted">أدخل كلمة المرور الحالية لحسابك</small>
                                        </div>
                                        <div class="mb-4">
                                            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                                            <small class="form-text text-muted">يجب أن تكون 8 أحرف على الأقل</small>
                                        </div>
                                        <div class="mb-4">
                                            <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                            <small class="form-text text-muted">أعد إدخال كلمة المرور الجديدة</small>
                                        </div>
                                        <div class="gap-2 d-flex">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ti ti-device-floppy"></i>
                                                حفظ التغييرات
                                            </button>
                                            <a href="{{ route('customer.profile') }}" class="btn btn-outline-secondary">
                                                <i class="ti ti-arrow-left"></i>
                                                العودة للملف الشخصي
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="alert alert-info">
                                        <h5 class="alert-heading">
                                            <i class="ti ti-info-circle"></i>
                                            نصائح هامة
                                        </h5>
                                        <hr>
                                        <ul class="mb-0">
                                            <li>استخدم كلمة مرور قوية تحتوي على أحرف كبيرة وصغيرة وأرقام</li>
                                            <li>لا تستخدم معلومات شخصية سهلة التخمين</li>
                                            <li>غير كلمة المرور بشكل دوري للحماية</li>
                                            <li>لا تشارك كلمة المرور مع أي شخص</li>
                                        </ul>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
 
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .nav-pills .nav-link {
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        .nav-pills .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        @media (max-width: 768px) {
            .col-lg-3 {
                margin-bottom: 2rem;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                
                $.ajax({
                    url: '{{ route("customer.password.update") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showAlert('success', response.message);
                            // Reset form
                            document.getElementById('passwordForm').reset();
                        } else {
                            // Show error message
                            showAlert('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        
                        for (var field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                        
                        showAlert('error', errorMessage);
                    }
                });
            });
            
            // Password strength checker
            $('#new_password').on('input', function() {
                var password = $(this).val();
                var strength = 0;
                
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]+/)) strength++;
                if (password.match(/[A-Z]+/)) strength++;
                if (password.match(/[0-9]+/)) strength++;
                if (password.match(/[$@#&!]+/)) strength++;
                
                var strengthText = '';
                var strengthClass = '';
                
                if (strength <= 2) {
                    strengthText = 'ضعيفة';
                    strengthClass = 'text-danger';
                } else if (strength <= 3) {
                    strengthText = 'متوسطة';
                    strengthClass = 'text-warning';
                } else {
                    strengthText = 'قوية';
                    strengthClass = 'text-success';
                }
                
                $('#password-strength').remove();
                $(this).after('<small id="password-strength" class="form-text' + strengthClass + '">' + strengthText + '</small>');
            });
        });
        
        function showAlert(type, message) {
            var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            var alertHtml = '<div class="alert' + alertClass + ' alert-dismissible fade show" role="alert">' +
                           message +
                           '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                           '</div>';
            
            $('.card-body').prepend(alertHtml);
            
            // Auto dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        }
    </script>
@endsection
