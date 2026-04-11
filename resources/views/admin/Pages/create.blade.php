@extends('admin.layouts.master')
@section('title')
    إضافة صفحة جديدة
@endsection
@section('css')
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
    <style>
        .ql-container {
            direction: rtl;
            text-align: right;
            min-height: 300px;
            font-size: 16px;
        }
        .ql-editor {
            direction: rtl;
            text-align: right;
            min-height: 300px;
        }
        .page-content-editor {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
        }
    </style>
@endsection
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                @if (Session::has('success'))
                    @php
                        toastify()->success(Session::get('success'));
                    @endphp
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        @php
                            toastify()->error($error);
                        @endphp
                    @endforeach
                @endif
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">إضافة صفحة جديدة</h4>
                        </div>
                        <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">عنوان الصفحة *</label>
                                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">رابط الصفحة (اختياري)</label>
                                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" placeholder="سيتم إنشاؤه تلقائياً إذا ترك فارغاً">
                                            <small class="text-muted">سيتم عرض الرابط كـ: /page/[الرابط]</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="content" class="form-label">محتوى الصفحة</label>
                                            <input type="hidden" name="content" id="content">
                                            <div id="snow-editor" class="page-content-editor" style="height: 400px;">
                                                {{ old('content') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0 card-title">إعدادات الصفحة</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="show_in_footer" name="show_in_footer" value="1" {{ old('show_in_footer') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="show_in_footer">
                                                            إظهار في الفوتر
                                                        </label>
                                                    </div>
                                                    <small class="text-muted">سيتم عرض رابط الصفحة في قسم الروابط بالفوتر</small>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                                        <label class="form-check-label" for="is_active">
                                                            تفعيل الصفحة
                                                        </label>
                                                    </div>
                                                    <small class="text-muted">الصفحة تكون متاحة للزوار عند التفعيل</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i> حفظ الصفحة
                                </button>
                                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left"></i> رجوع
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- Quill Editor JS -->
    {{-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> --}}
    <script src="{{ asset('assets/admin/js/components/form-quilljs.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var quill = Quill.find(document.getElementById('snow-editor'));
            if (quill) {
                quill.root.innerHTML = `{!! old('content') !!}`;

                document.querySelector('form').addEventListener('submit', function() {
                    document.querySelector('input[name=content]').value = quill.root.innerHTML;
                });
            }
        });
        
        // Auto-generate slug from title
        $('#title').on('input', function() {
            if ($('#slug').val() === '') {
                const slug = $(this).val().toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                $('#slug').val(slug);
            }
        });
    </script>
@endsection
