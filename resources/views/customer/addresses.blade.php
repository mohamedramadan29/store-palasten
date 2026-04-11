@extends('front.layouts.master')
@section('title')
    العناوين المحفوظة
@endsection
@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Customer Sidebar -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">قائمة الحساب</h5>
                        </div>
                        <div class="card-body p-0">
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
                                <a href="{{ route('customer.addresses') }}" class="nav-link active">
                                    <i class="ti ti-location"></i>
                                    العناوين
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Addresses Content -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">العناوين المحفوظة</h4>
                                <p class="text-muted mb-0">إدارة عناوين التوصيل</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="ti ti-plus"></i>
                                إضافة عنوان جديد
                            </button>
                        </div>
                        <div class="card-body">
                            @if($addresses->count() > 0)
                                <div class="row g-4">
                                    @foreach($addresses as $address)
                                        <div class="col-md-6">
                                            <div class="card h-100 border position-relative">
                                                @if($address->is_default)
                                                    <span class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-success">الافتراضي</span>
                                                    </span>
                                                @endif
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $address->type }}</h6>
                                                    <p class="card-text">
                                                        <strong>{{ $address->address_line_1 }}</strong><br>
                                                        @if($address->address_line_2)
                                                            {{ $address->address_line_2 }}<br>
                                                        @endif
                                                        {{ $address->city }}, @if($address->state){{ $address->state }}, @endif {{ $address->country }}<br>
                                                        @if($address->postal_code)
                                                            {{ $address->postal_code }}<br>
                                                        @endif
                                                        @if($address->phone)
                                                            <i class="ti ti-phone"></i> {{ $address->phone }}
                                                        @endif
                                                    </p>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-outline-primary edit-address" data-id="{{ $address->id }}">
                                                            <i class="ti ti-edit"></i>
                                                            تعديل
                                                        </button>
                                                        @if(!$address->is_default)
                                                            <button class="btn btn-sm btn-outline-success set-default" data-id="{{ $address->id }}">
                                                                <i class="ti ti-check"></i>
                                                                افتراضي
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-sm btn-outline-danger delete-address" data-id="{{ $address->id }}">
                                                            <i class="ti ti-trash"></i>
                                                            حذف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="ti ti-location display-1 text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">لا توجد عناوين محفوظة</h5>
                                    <p class="text-muted">لم تقم بإضافة أي عناوين بعد</p>
                                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                        <i class="ti ti-plus"></i>
                                        إضافة عنوان جديد
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة عنوان جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAddressForm">
                        @csrf
                        <div class="mb-3">
                            <label for="type" class="form-label">نوع العنوان</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">اختر النوع</option>
                                <option value="home">المنزل</option>
                                <option value="work">العمل</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address_line_1" class="form-label">العنوان الرئيسي *</label>
                            <input type="text" class="form-control" id="address_line_1" name="address_line_1" required>
                        </div>
                        <div class="mb-3">
                            <label for="address_line_2" class="form-label">العنوان الفرعي</label>
                            <input type="text" class="form-control" id="address_line_2" name="address_line_2">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">المدينة *</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">الولاية</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">الرمز البريدي</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">الدولة *</label>
                                <input type="text" class="form-control" id="country" name="country" value="السعودية" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                <label class="form-check-label" for="is_default">
                                    تعيين كعنوان افتراضي
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="saveAddress()">حفظ العنوان</button>
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
        function saveAddress() {
            const form = document.getElementById('addAddressForm');
            const formData = new FormData(form);
            
            fetch('{{ route("customer.addresses.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('حدث خطأ: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ العنوان');
            });
        }
    </script>
@endsection
