@extends('front.layouts.master')
@section('title')
    {{ $category['name'] }}
@endsection

@section('css')
    <style>
        .list-group-item {
            border: none;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .list-group-item a {
            text-decoration: none;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .list-group-item a:hover {
            color: var(--bs-primary) !important;
        }

        .list-group-item .bi {
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .list-group-item .collapse.show + a .bi {
            transform: rotate(180deg);
        }

        .subcategories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            padding: 1rem;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease-out;
            justify-content: space-between;
            direction: rtl;
        }

        @media (max-width: 576px) {
            .subcategories-grid {
                grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
                gap: 0.8rem;
                padding: 0.75rem;
            }
            
            .subcategory-image {
                height: 100px;
            }
            
            .subcategory-title {
                font-size: 0.85rem;
                padding: 0.6rem;
            }
        }

        .collapse.show .subcategories-grid {
            opacity: 1;
            transform: translateY(0);
        }

        .subcategory-card {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }

        .subcategory-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .subcategory-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .subcategory-title {
            padding: 0.75rem;
            text-align: center;
            color: #333;
            font-size: 0.9rem;
            background: #f8f9fa;
            margin: 0;
        }

        .subcategory-card.active .subcategory-title {
            background: var(--bs-primary);
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="page_content">

        <!-- page-title -->
        <div class="tf-page-title" style="margin-bottom:0; padding-bottom:0 !important">
            <div class="container-full">
                <div class="heading text-center">منتجات القسم</div>
                <p class="text-center text-2 text_black-2 mt_5">
                    {{ $category['name'] }}
                    @isset($sub_category['name'])
                        / {{ $sub_category['name'] }}
                    @endisset
                </p>
            </div>
        </div>
        <!-- /page-title -->

        @if (!isset($sub_category))
            @if ($category->SubCategories->count() > 0)
                <div class="collapse {{ isset($category) && $category->id == $category->id ? 'show' : '' }}"
                    id="subCat{{ $category->id }}">
                    <div class="subcategories-grid">
                        @foreach ($category->SubCategories as $subCat)
                            <a href="{{ url('collection/' . $category->slug . '/' . $subCat->slug) }}"
                                class="subcategory-card {{ isset($sub_category) && $sub_category->id == $subCat->id ? 'active' : '' }}">
                                <img src="{{ asset('assets/uploads/Subcategory_images/' . $subCat->image) }}"
                                    class="subcategory-image" alt="{{ $subCat->name }}">
                                <h6 class="subcategory-title">{{ $subCat->name }}</h6>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        @if ($products->count() > 0)
            <!-- Section Product -->
            <section class="flat-spacing-2">
                <div class="container">
                    <div class="row">

                        <!-- Main Content -->
                        <div class="col-12">
                            <div class="tf-shop-control py-4">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-center">
                                                <div class="tf-control-sorting">
                                                    <div class="tf-dropdown-sort" style="border: none">
                                                        <form class="filter-choice select-form" name="sortProducts" id="sortProducts">
                                                            <select name="sort" title="sort-by"
                                                                class="form-select rounded-pill px-5 py-2 shadow-sm fw-500 text-dark"
                                                                id="sort" onchange="this.form.submit()">
                                                                <option value="" selected disabled hidden>رتب حسب</option>
                                                                <option value="price_from_low_heigh" {{ request('sort') == 'price_from_low_heigh' ? 'selected' : '' }}>
                                                                    السعر : من الأقل إلى الأعلى
                                                                </option>
                                                                <option value="price_from_hieght_low" {{ request('sort') == 'price_from_hieght_low' ? 'selected' : '' }}>
                                                                    السعر : من الأعلى إلى الأقل
                                                                </option>
                                                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                                                    الأقدم أولاً
                                                                </option>
                                                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                                                    الأحدث أولاً
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                </div>
            </section>
        @else
            <div class="text-center" style="margin-bottom: 40px">
                <p style="font-size: 20px; margin-bottom: 15px">لا يوجد منتجات في هذا القسم</p>
                <a style="background: #000; border-color: #000; width: 120px;" href="{{ url('/') }}"
                    class="btn btn-primary head_read_more">
                    الرئيسية <i style="position: relative; top: 2px;" class="bi bi-arrow-left"></i>
                </a>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.list-group-item a[data-bs-toggle="collapse"]').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('data-bs-target');
                    const icon = this.querySelector('.bi');

                    if (icon) {
                        icon.classList.toggle('bi-chevron-down');
                        icon.classList.toggle('bi-chevron-up');
                    }

                    const collapseElement = document.querySelector(target);
                    if (collapseElement) {
                        const bsCollapse = new bootstrap.Collapse(collapseElement, { toggle: false });

                        if (collapseElement.classList.contains('show')) {
                            bsCollapse.hide();
                        } else {
                            document.querySelectorAll('.collapse.show').forEach(el => {
                                if (el !== collapseElement) {
                                    bootstrap.Collapse.getInstance(el)?.hide();
                                }
                            });
                            bsCollapse.show();
                        }
                    }
                });
            });
        });
    </script>

        });
    </script>

        });
    </script>

    @if (isset($product))
        <script>
            function fetchPrice() {
                let form = document.getElementById('addToCart');
                let formData = new FormData(form);

                fetch('{{ route('product.getPrice', $product->id) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('price-value').innerText = data.price ? data.price + '{{ $storeCurrency }}' : 'غير متوفر';

                    if (data.discount && data.discount > 0) {
                        document.getElementById('discounted-price').innerText = data.discount + '{{ $storeCurrency }}';
                        document.getElementById('discount-section').style.display = 'block';
                        document.getElementById('price-value').style.textDecoration = "line-through";
                    } else {
                        document.getElementById('discount-section').style.display = 'none';
                        document.getElementById('price-value').style.textDecoration = "none";
                    }

                    document.getElementById('hidden-variation').value = data.variation_id;
                    document.getElementById('hidden-price').value = data.price;
                    document.getElementById('hidden-discount').value = data.discount ? data.discount : '';
                });
            }
        </script>
    @endif
@endsection