@extends('front.layouts.master')
@section('title')
    profile
@endsection
@section('content')
    <div class="page-content" style="margin-top:120px">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Customer Sidebar -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 card-title"> قائمة الحساب </h5>
                        </div>
                        <div class="p-0 card-body">
                            <nav class="nav flex-column nav-pills">
                                <a href="{{ route('customer.dashboard') }}" class="nav-link">
                                    <i class="ti ti-home"></i>
                                     الرئيسية
                                </a>
                                <a href="{{ route('customer.profile') }}" class="nav-link active">
                                    <i class="ti ti-user"></i>
                                    الملف الشخصي
                                </a>
                                <a href="{{ route('customer.orders') }}" class="nav-link">
                                    <i class="ti ti-shopping-bag"></i>
                                    طلباتي
                                </a>
                                     <a href="{{ route('customer.password') }}" class="nav-link">
                                    <i class="ti ti-lock"></i>
                                    تغيير كلمة المرور
                                </a>
                                <hr class="my-2">
                                <a href="{{ route('customer.logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti ti-logout"></i>
                                
                                    تسجيل الخروج 
                                </a>
                                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                {{-- <a href="{{ route('customer.addresses') }}" class="nav-link">
                                    <i class="ti ti-location"></i>
                                    Addresses
                                </a> --}}
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Profile Content -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="font-size: 22px"> معلومات الحساب  </h4>
                            <p class="mb-0 text-muted"> التحكم في بيانات الحساب  </p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                 
                                <div class="col-md-12">
                                    <form id="profileForm">
                                        @csrf
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="name" class="form-label"> الاســم  </label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="{{ $user->name }}" required>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="email" class="form-label"> البريد الالكتــروني  </label>
                                                <input type="email" class="form-control" id="email" name="email" 
                                                       value="{{ $user->email }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="phone" class="form-label"> رقم الهاتف  </label>
                                                <input type="tel" class="form-control" id="phone" name="phone" 
                                                       value="{{ $user->phone }}">
                                            </div>
                                            
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label"> عضو منذ  </label>
                                            <input type="text" class="form-control" value="{{ $user->created_at->format('Y-m-d') }}" readonly>
                                        </div>
                                        <div class="gap-2 d-flex">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ti ti-device-floppy"></i>
                                                حفظ التعديلات 
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                <i class="ti ti-lock"></i>
                                                تغير كلمة المرور 
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="changePassword()">Update Password</button>
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
        
        .avatar-lg {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: white;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
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
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("customer.profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating profile');
            });
        });

        function changePassword() {
            const form = document.getElementById('changePasswordForm');
            const formData = new FormData(form);
            
            fetch('{{ route("customer.password.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password changed successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                    form.reset();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error changing password');
            });
        }
    </script>
@endsection
