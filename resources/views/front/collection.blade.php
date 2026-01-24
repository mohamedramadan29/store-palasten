@extends('front.layouts.master')
@section('title')
     جميع الاقسام
@endsection

@section('content')
    <div class="page_content">

        <!-- page-title -->
        <div class="tf-page-title">
            <div class="container-full">
                <div class="heading text-center"> جميع الاقسام  </div>
            </div>
        </div>
        <!-- /page-title -->
        <section class="flat-spacing-1">
            <div class="container">
                <div class="tf-grid-layout lg-col-3 tf-col-2">
                    @foreach($categories as $category)
                        <div class="collection-item hover-img">
                            <div class="collection-inner">
                                <a href="{{url('collection/'.$category['slug'])}}" class="collection-image img-style">
                                    <img class="lazyload" data-src="{{asset('assets/uploads/category_images/'.$category['image'])}}" src="{{asset('assets/uploads/category_images/'.$category['image'])}}"  alt="{{$category['name']}}">
                                </a>
                                <div class="collection-content">
                                    <a href="{{url('collection/'.$category['slug'])}}" class="tf-btn collection-title hover-icon"><span>{{$category['name']}}</span><i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                {!! $categories->links('vendor.pagination.pagination') !!}
            </div>
        </section>

    </div>
@endsection
