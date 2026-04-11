@extends('front.layouts.master')
@section('title')
    الصفحات
@endsection
@section('content')
    <!-- Page Header -->
    <section class="page-header bg-light py-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-5 fw-bold mb-3">الصفحات</h1>
                    <p class="lead text-muted">اكتشف جميع صفحات موقعنا</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">الرئيسية</a>
                            </li>
                            <li class="breadcrumb-item active">الصفحات</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Pages Grid -->
    <section class="pages-section py-5">
        <div class="container">
            @if($pages->count() > 0)
                <div class="row g-4">
                    @foreach($pages as $page)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4">
                                        <div class="page-icon">
                                            <i class="ti ti-file-text display-1 text-primary"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-3">{{ $page->title }}</h5>
                                    <p class="card-text text-muted mb-4">
                                        {{ Str::limit(strip_tags($page->content), 120) }}
                                    </p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('page.show', $page->slug) }}" class="btn btn-primary">
                                            <i class="ti ti-eye"></i>
                                            قراءة المزيد
                                        </a>
                                        @if($page->show_in_footer)
                                            <span class="badge bg-success text-white">
                                                <i class="ti ti-link"></i>
                                                في الفوتر
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="ti ti-file-text display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted">لا توجد صفحات حالياً</h4>
                    <p class="text-muted">لم يتم إضافة أي صفحات بعد</p>
                    <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                        <i class="ti ti-home"></i>
                        العودة للرئيسية
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('css')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 0.5rem;
            backdrop-filter: blur(10px);
        }
        
        .breadcrumb-item {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .breadcrumb-item a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-item a:hover {
            color: #f8f9fa;
        }
        
        .breadcrumb-item.active {
            color: white;
            font-weight: 600;
        }
        
        .page-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea15, #764ba215);
            border-radius: 50%;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
            transition: transform 0.3s ease;
        }
        
        .card:hover .page-icon {
            transform: scale(1.1);
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .hover-lift {
            position: relative;
            overflow: hidden;
        }
        
        .hover-lift::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05));
            transition: left 0.5s ease;
        }
        
        .card:hover .hover-lift::before {
            left: 100%;
        }
        
        .d-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .card {
                margin-bottom: 1.5rem;
            }
            
            .d-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
    </style>
@endsection
