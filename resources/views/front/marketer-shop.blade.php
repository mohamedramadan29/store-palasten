@extends('front.layouts.master')
@section('title')
    متجر المسوقين
@endsection

@section('content')
    <div class="page_content">

        <!-- page-title -->
        <div class="tf-page-title" style="margin-bottom: 0;padding-bottom:0px !important">
            <div class="container-full">
                <div class="text-center heading"> متجر المسوقين </div>
                <p class="text-center text-2 text_black-2 mt_5"> الأسعار الخاصة للمسوقين </p>
            </div>
        </div>
        <!-- /page-title -->

        <!-- Section Product -->
        <section class="flat-spacing-2">
            <div class="container">
                <div class="tf-shop-control grid-3 align-items-center">
                    <div class="tf-control-sorting d-flex justify-content-start">
                        <div class="tf-dropdown-sort" style="border: none" data-bs-toggle="dropdown">
                            <form class="filter-choice select-form" name="sortProducts" id="sortProducts">
                                <select name="sort" title="sort-by" class="form-select"
                                    data-placeholder="Price: Low to High" id="sort" class="chosen-select"
                                    onchange="this.form.submit()">
                                    <option value="" selected> رتب حسب</option>
                                    <option @if (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') selected @endif value="price_asc">
                                        السعر : من الاقل الي الاعلي
                                    </option>
                                    <option @if (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') selected @endif value="price_desc">
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

                    <div class="tf-control-filter d-flex justify-content-center">
                        <form action="{{ url('marketer-shop') }}" method="GET" class="filter-choice select-form">
                            <select name="category_id" class="form-select" onchange="this.form.submit()">
                                <option value="">جميع الفئات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        @if (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="tf-control-filter d-flex justify-content-end">
                        <form action="{{ url('marketer-shop') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="بحث عن منتج..." 
                                value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}">
                            <button type="submit" class="btn btn-primary ms-2">بحث</button>
                        </form>
                    </div>
                </div>

                <div class="wrapper-control-shop">
                    <div class="meta-filter-shop"></div>
                    @if ($products->count() > 0)
                        <div class="grid-layout wrapper-shop" data-grid="grid-4">
                            @foreach ($products as $product)
                                @include('front.partials.marketer-product-card', ['product' => $product])
                            @endforeach
                        </div>
                        <!-- pagination -->
                        {!! $products->links('vendor.pagination.pagination') !!}
                    @else
                        <div class="py-5 text-center">
                            <div class="alert alert-info">
                                <i class="mb-3 fas fa-info-circle fa-2x"></i>
                                <h4 class="alert-heading">لا يوجد منتجات</h4>
                                <p class="mb-0">لا توجد منتجات متاحة في هذا القسم</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </section>
        <!-- /Section Product -->

    </div>
@endsection

@section('css')
<style>
    .price-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .regular-price {
        font-size: 14px;
    }
    
    .marketer-price {
        font-size: 16px;
    }
    
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .variations-info {
        border-top: 1px solid #eee;
        padding-top: 8px;
    }
    
    .product-img img {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
</style>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function fetchPrice(productId) {
            let form = document.getElementById('addToCart');
            let formData = new FormData(form);

            fetch(`/product/get-price/${productId}`, {
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
