@extends('front.layouts.master')
@section('title')
    جميع آراء العملاء
@endsection
@section('content')
    <div class="page_content">
        <section class="flat-spacing-11 flat-testimonial" style="padding-top: 130px">
            <div class="container">
                <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                    <div>
                        <span class="title"> جميع آراء العملاء </span>
                        <p class="sub-title" style="margin-top: 15px"> ماذا يقول العملاء عنا </p>
                    </div>
                </div>

                @if (count($reviews) > 0)
                    <div class="mb-5 grid-layout" data-grid="grid-3">
                        @foreach ($reviews as $review)
                            <div class="testimonial-item style-column wow fadeInUp" data-wow-delay="0s" style="border: 1px solid #eee; border-radius: 10px; padding: 20px;">
                                <div class="rating">
                                    @for ($i = 0; $i < $review['star']; $i++) 
                                        <i class="icon-start filled"></i>
                                    @endfor
                                    @for ($i = $review['star']; $i < 5; $i++) 
                                        <i class="icon-start empty"></i>
                                    @endfor
                                </div>
                                <div class="mt-3 heading"> {{ $review['name'] }} </div>
                                <div class="mt-2 text" style="height: 120px;overflow-y: scroll;">
                                    {!! $review['description'] !!}
                                </div>
                                @if(!empty($review->image))
                                <div class="author">
                                    <div class="image">
                                        <img src="{{ asset('assets/uploads/reviews/' . $review->image) }}" alt="{{ $review['name'] }}" style="width: 100%;height: auto; margin-top: 15px; max-height: 120px; object-fit: cover; object-position: center;border-radius:10px">
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-5 text-center">
                        <p> لا توجد آراء للعملاء حتى الآن. </p>
                    </div>
                @endif
                <style>
                    .icon-start.empty {
                        font-size: 20px;
                        color: #ddd !important;
                    }
                    .icon-start.filled {
                        font-size: 20px;
                        color: #ffbf00;
                    }
                </style>
            </div>
        </section>
    </div>
@endsection
