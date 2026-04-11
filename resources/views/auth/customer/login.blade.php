@extends('front.layouts.master')
@section('title')
    تسجيل الدخول
@endsection
@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <br>
                <br>
                <div class="col-lg-6 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">تسجيل الدخول</h4>
                            <p class="mb-0 text-muted">أهلا بعودتك! سجل دخولك للمتابعة</p>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('customer.login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني *</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور *</label>
                                    <div class="input-group">
                                         
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>

                                <div class="gap-2 d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-login"></i>
                                        تسجيل الدخول
                                    </button>
                                    <a href="{{ route('customer.register') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-user-plus"></i>
                                        إنشاء حساب جديد
                                    </a>
                                </div>

                                {{-- <div class="mt-4 text-center">
                                    <a href="#" class="text-primary">نسيت كلمة المرور؟</a>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-content {
            padding: 80px 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            color: white;
            border: none;
            padding: 2rem;
        }
        
        .card-title {
            margin: 0;
            font-weight: 600;
        }
        
         input[type='email'],
        input[type='tel']
        {
            direction: rtl;
            text-align: right;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group-text {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.1rem;
            z-index: 10;
        }
        
        .form-control {
            padding-right: 45px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn {
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .d-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        @media (max-width: 768px) {
            .page-content {
                padding: 40px 0;
            }
            
            .d-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
