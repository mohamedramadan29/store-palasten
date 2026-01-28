@extends('front.layouts.master')
@section('title')
    المتجر
@endsection

@section('content')
    <div class="page_content">

        <!-- page-title -->
        <div class="tf-page-title" style="margin-bottom: 0;padding-bottom:0px !important">
            <div class="container-full">
                <div class="heading text-center"> المتجر </div>
                <p class="text-center text-2 text_black-2 mt_5"> جميع المنتجات </p>
            </div>
        </div>
        <!-- /page-title -->

        <!-- Section Product -->
        <section class="flat-spacing-2">
            <div class="container">
                <div class="tf-shop-control grid-3 align-items-center">

                    {{-- <ul class="tf-control-layout d-flex justify-content-center">
                        <li class="tf-view-layout-switch sw-layout-2" data-value-grid="grid-2">
                            <div class="item"><span class="icon icon-grid-2"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-3" data-value-grid="grid-3">
                            <div class="item"><span class="icon icon-grid-3"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-4 active" data-value-grid="grid-4">
                            <div class="item"><span class="icon icon-grid-4"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-5" data-value-grid="grid-5">
                            <div class="item"><span class="icon icon-grid-5"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-6" data-value-grid="grid-6">
                            <div class="item"><span class="icon icon-grid-6"></span></div>
                        </li>
                    </ul> --}}
                    <div class="tf-control-sorting d-flex justify-content-start">
                        <div class="tf-dropdown-sort" style="border: none" data-bs-toggle="dropdown">
                            <form class="filter-choice select-form" name="sortProducts" id="sortProducts">
                                <select name="sort" title="sort-by" class="form-select"
                                    data-placeholder="Price: Low to High" id="sort" class="chosen-select"
                                    onchange="this.form.submit()">
                                    <option value="" selected> رتب حسب</option>
                                    <option @if (isset($_GET['sort']) && $_GET['sort'] == 'price_from_low_heigh') selected @endif value="price_from_low_heigh">
                                        السعر : من الاقل الي الاعلي
                                    </option>
                                    <option @if (isset($_GET['sort']) && $_GET['sort'] == 'price_from_hieght_low') selected @endif value="price_from_hieght_low">
                                        السعر : من الاعلي الي الاقل
                                    </option>
                                    <option @if (isset($_GET['sort']) && $_GET['sort'] == 'oldest') selected @endif value="oldest"> رتب حسب الاقدم
                                    </option>
                                    <option @if (isset($_GET['sort']) && $_GET['sort'] == 'latest') selected @endif value="latest">رتب حسب الاحدث
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="wrapper-control-shop">
                    <div class="meta-filter-shop"></div>
                    <div class="grid-layout wrapper-shop" data-grid="grid-4">
                        @foreach ($products as $product)
                            @include('front.partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <!-- pagination -->
                    {!! $products->links('vendor.pagination.pagination') !!}
                </div>

            </div>
        </section>
        <!-- /Section Product -->

    </div>
@endsection


@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        });
    </script>

        });
    </script>





    <script>
        function fetchPrice() {
            let form = document.getElementById('addToCart');
            let formData = new FormData(form);

            fetch('{{ route('product.getPrice', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // تحديث السعر في الواجهة
                    document.getElementById('price-value').innerText = data.price ? data.price +
                        '{{ $storeCurrency }}' : 'غير متوفر';

                    if (data.discount && data.discount > 0) {
                        // عرض السعر بعد التخفيض إذا كان موجودًا
                        document.getElementById('discounted-price').innerText = data.discount + '{{ $storeCurrency }}';
                        document.getElementById('discount-section').style.display = 'block';
                        document.getElementById('price-value').style.textDecoration = "line-through";
                    } else {
                        // إخفاء قسم التخفيض إذا لم يكن هناك تخفيض
                        document.getElementById('discount-section').style.display = 'none';
                        document.getElementById('price-value').style.textDecoration = "none";
                    }
                    // تحديث الحقول المخفية بالقيمة الحقيقية للسعر والخصم
                    document.getElementById('hidden-variation').value = data.variation_id;
                    document.getElementById('hidden-price').value = data.price;
                    document.getElementById('hidden-discount').value = data.discount ? data.discount : '';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
