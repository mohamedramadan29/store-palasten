@extends('front.layouts.master')
@section('title')
    {{ $page->title }}
@endsection
@section('content')
    <!-- Page Header -->
    {{-- <section class="py-5 mb-5 page-header bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="text-center col-lg-8">
                    <h1 class="mb-3 display-5 fw-bold">{{ $page->title }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">الرئيسية</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $page->title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Page Content -->
    <br>
    <section class="py-5 page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="border-0 shadow-sm card">
                        <div class="p-4 card-body p-lg-5">
                            @if($page->content)
                                <div class="page-content">
                                    <div class="content-wrapper">
                                        {!! $page->content !!}
                                    </div>
                                </div>
                            @else
                                <div class="py-5 text-center">
                                    <div class="mb-4">
                                        <i class="ti ti-file-text display-1 text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">محتوى الصفحة فارغ</h5>
                                    <p class="text-muted">هذه الصفحة لا تحتوي على محتوى حالياً</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Pages -->
    @php
        $relatedPages = \App\Models\Page::where('id', '!=', $page->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();
    @endphp
    @if($relatedPages->count() > 0)
        <section class="py-5 related-pages bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="mb-4 text-center">صفحات أخرى قد تهمك</h3>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($relatedPages as $relatedPage)
                        <div class="col-md-6 col-lg-4">
                            <div class="border-0 shadow-sm card h-100">
                                <div class="text-center card-body">
                                    <div class="mb-3">
                                        <i class="ti ti-file-text display-4 text-primary"></i>
                                    </div>
                                    <h5 class="card-title">{{ $relatedPage->title }}</h5>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit(strip_tags($relatedPage->content), 100) }}
                                    </p>
                                    <a href="{{ route('page.show', $relatedPage->slug) }}" class="btn btn-outline-primary btn-sm">
                                        قراءة المزيد
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
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
        
        .page-content .content-wrapper {
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .page-content .content-wrapper h1,
        .page-content .content-wrapper h2,
        .page-content .content-wrapper h3,
        .page-content .content-wrapper h4,
        .page-content .content-wrapper h5,
        .page-content .content-wrapper h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        
        .page-content .content-wrapper p {
            margin-bottom: 1.5rem;
        }
        
        .page-content .content-wrapper img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .page-content .content-wrapper ul,
        .page-content .content-wrapper ol {
            margin-bottom: 1.5rem;
            padding-right: 2rem;
        }
        
        .page-content .content-wrapper li {
            margin-bottom: 0.5rem;
        }
        
        .page-content .content-wrapper blockquote {
            border-right: 4px solid #667eea;
            padding-right: 1rem;
            margin: 1.5rem 0;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        
        .related-pages .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .related-pages .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .page-content .content-wrapper {
                font-size: 1rem;
            }
        }
    </style>
@endsection
