@extends('admin.layouts.master')
@section('title')
    عرض الصفحة: {{ $page->title }}
@endsection
@section('css')
    <style>
        .page-content-display {
            min-height: 400px;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            background-color: #fff;
        }
        .media-item {
            margin-bottom: 1rem;
        }
        .media-item img {
            max-width: 100%;
            height: auto;
            border-radius: 0.375rem;
        }
        .media-item video {
            max-width: 100%;
            height: auto;
            border-radius: 0.375rem;
        }
    </style>
@endsection
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">عرض الصفحة: {{ $page->title }}</h4>
                            <div>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-warning">
                                    <i class="ti ti-edit"></i> تعديل
                                </a>
                                <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="ti ti-arrow-left"></i> رجوع
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <h5>عنوان الصفحة</h5>
                                        <p class="h4">{{ $page->title }}</p>
                                    </div>
                                    
                                    @if($page->slug)
                                        <div class="mb-4">
                                            <h5>رابط الصفحة</h5>
                                            <p><code>/page/{{ $page->slug }}</code></p>
                                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="ti ti-external-link"></i> عرض في الموقع
                                            </a>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-4">
                                        <h5>محتوى الصفحة</h5>
                                        <div class="page-content-display">
                                            {!! $page->content !!}
                                        </div>
                                    </div>
                                    
                                    @if($page->media && is_array($page->media) && count($page->media) > 0)
                                        <div class="mb-4">
                                            <h5>الوسائط</h5>
                                            <div class="row">
                                                @foreach($page->media as $media)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="media-item">
                                                            @if(str_contains($media, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                <img src="{{ $media }}" alt="صورة" class="img-fluid">
                                                            @elseif(str_contains($media, ['.mp4', '.avi', '.mov', '.wmv']))
                                                                <video controls class="img-fluid">
                                                                    <source src="{{ $media }}" type="video/mp4">
                                                                    متصفحك لا يدعم تشغيل الفيديو.
                                                                </video>
                                                            @else
                                                                <a href="{{ $media }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="ti ti-link"></i> {{ $media }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">معلومات الصفحة</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">معرف الصفحة</label>
                                                <p class="form-control-plaintext">{{ $page->id }}</p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">الحالة</label>
                                                <p>
                                                    @if($page->is_active)
                                                        <span class="badge bg-success">مفعل</span>
                                                    @else
                                                        <span class="badge bg-danger">معطل</span>
                                                    @endif
                                                </p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">الظهور في الفوتر</label>
                                                <p>
                                                    @if($page->show_in_footer)
                                                        <span class="badge bg-success">نعم</span>
                                                    @else
                                                        <span class="badge bg-secondary">لا</span>
                                                    @endif
                                                </p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">تاريخ الإنشاء</label>
                                                <p class="form-control-plaintext">{{ $page->created_at->format('Y-m-d H:i:s') }}</p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">آخر تعديل</label>
                                                <p class="form-control-plaintext">{{ $page->updated_at->format('Y-m-d H:i:s') }}</p>
                                            </div>
                                            
                                            @if($page->trashed())
                                                <div class="mb-3">
                                                    <label class="form-label">حالة الحذف</label>
                                                    <p><span class="badge bg-warning">محذوفة</span></p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
