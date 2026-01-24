@extends('front.layouts.master')
@section('title')
    الرئيسية
@endsection

@section('content')
    <div class="page_content">
        <!-- page-title -->
        <div class="tf-page-title style-2">
            <div class="container-full">
                <div class="heading text-center"> الاسئلة الشائعة  </div>
            </div>
        </div>
        <!-- /page-title -->
        <!-- FAQ -->
        <section class="flat-spacing-11">
            <div class="container">
                <div class="tf-accordion-wrap d-flex justify-content-between">
                    <div class="content">
                        <h5 id="shopping-information" class="mb_24"> الاسئلة الشائعة  </h5>
                        <div class="flat-accordion style-default has-btns mb_60">
                            @foreach($faqs as $faq)
                                <div class="flat-toggle">
                                    <div class="toggle-title"> {{$faq['title']}} </div>
                                    <div class="toggle-content">
                                        <p> {!! $faq['content'] !!} </p>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- /FAQ -->
    </div>

@endsection
