@extends('front.layouts.master')
@section('title')
    تقيم المتجر
@endsection
@section('content')
    <div class="page_content">
        @if (Session::has('Success_message'))
            @php
                toastify()->success(\Illuminate\Support\Facades\Session::get('Success_message'));
            @endphp
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                @php
                    toastify()->error($error);
                @endphp
            @endforeach
        @endif

        <!-- page-cart -->
        <section class="flat-spacing-11">
            <div class="container">
                <div class="card">
                    <form method="post" action="{{url('review')}}"
                          class="form-checkout tf-page-cart-checkout ">
                        @csrf
                        <div class="tf-page-cart-wrap layout-2">
                            <div class="tf-page-cart-item">
                                <h5 class="fw-5 mb_20"> تقيم المتجر </h5>
                                <div class="box grid">
                                    <fieldset class="fieldset">
                                        <label for="name"> اسم العميل </label>
                                        <input type="text" id="name" placeholder="" name="name" required
                                               value="{{old('name')}}">
                                    </fieldset>
                                </div>
                                <fieldset class="box fieldset">
                                    <div class="mb-3">
                                        <label class="form-label">التقييم  </label>
                                        <div class="star-rating">
                                            <input type="radio" name="rating" id="rating-5" value="5"><label for="rating-5">★</label>
                                            <input type="radio" name="rating" id="rating-4" value="4"><label for="rating-4">★</label>
                                            <input type="radio" name="rating" id="rating-3" value="3"><label for="rating-3">★</label>
                                            <input type="radio" name="rating" id="rating-2" value="2"><label for="rating-2">★</label>
                                            <input type="radio" name="rating" id="rating-1" value="1"><label for="rating-1">★</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="box fieldset">
                                    <label for="note"> كتابة التقيم   </label>
                                    <textarea name="content" id="content">{{old('content')}}</textarea>
                                </fieldset>
                                <button
                                    class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center">
                                    تقيم
                                </button>
                            </div>
                            <div class="tf-cart-footer-inner">

                            </div>
                        </div>
                    </form>
                </div>

                <style>
                    .star-rating {
                        direction: rtl;
                        display: inline-flex;
                        gap: 5px;
                    }
                    .star-rating input {
                        display: none;
                    }
                    .star-rating label {
                        font-size: 24px;
                        color: #ddd;
                        cursor: pointer;
                    }
                    .star-rating input:checked ~ label,
                    .star-rating label:hover,
                    .star-rating label:hover ~ label {
                        color: #ffbf00;
                    }
                    .form-checkout label::after{
                        display: none;
                    }
                </style>

            </div>

        </section>
    </div>
@endsection
