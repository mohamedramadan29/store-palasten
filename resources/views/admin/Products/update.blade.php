@extends('admin.layouts.master')

@section('title')
    تعديل المنتج
@endsection

@section('css')
    <style>
        #product-variants {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .variant-inputs,
        .variation.variant-inputs {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem 1.5rem;
            align-items: flex-start;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: #f8fafc;
        }

        .variant-inputs .form-group,
        .variation.variant-inputs .form-group {
            flex: 1;
            min-width: 220px;
            max-width: 320px;
        }

        .variant-inputs .form-group input[type="number"],
        .variant-inputs .form-group input[type="text"],
        .variant-inputs .form-group input[type="file"],
        .variation.variant-inputs .form-group input[type="number"],
        .variation.variant-inputs .form-group input[type="text"],
        .variation.variant-inputs .form-group input[type="file"] {
            width: 100%;
        }

        .variant-inputs .form-group:has(.delete-variant),
        .variation.variant-inputs .form-group:has(.delete-variant) {
            flex: 0 0 auto;
            min-width: auto;
        }

        @media (max-width: 768px) {
            .variant-inputs,
            .variation.variant-inputs {
                flex-direction: column;
                gap: 1rem;
                padding: 1.25rem 1rem;
            }

            .variant-inputs .form-group,
            .variation.variant-inputs .form-group {
                min-width: 100%;
                max-width: 100%;
            }

            .variant-inputs .form-group:has(.delete-variant),
            .variation.variant-inputs .form-group:has(.delete-variant) {
                align-self: flex-end;
            }
        }

        @media (min-width: 576px) and (max-width: 991.98px) {
            .variant-inputs .form-group,
            .variation.variant-inputs .form-group {
                min-width: 45%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            @if (Session::has('Success_message'))
                @php toastify()->success(\Illuminate\Support\Facades\Session::get('Success_message')); @endphp
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    @php toastify()->error($error); @endphp
                @endforeach
            @endif

            <form method="post" action="{{ url('admin/product/update/' . $product['slug']) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-12 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> تعديل المنتج </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> اسم المنتج </label>
                                            <input required type="text" id="name" name="name" class="form-control" value="{{ $product['name'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label"> اضف اسم خاص للرابط ( اختياري ) </label>
                                            <input required type="text" id="slug" name="slug" class="form-control" value="{{ $product['slug'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label"> حدد القسم الرئيسي </label>
                                            <select required class="form-control" id="category_id" data-choices data-choices-groups name="category_id">
                                                <option value=""> -- حدد القسم --</option>
                                                @foreach ($MainCategories as $maincat)
                                                    <option value="{{ $maincat['id'] }}" {{ $product['category_id'] == $maincat['id'] ? 'selected' : '' }}>
                                                        {{ $maincat['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="sub_category_id" class="form-label"> حدد القسم الفرعي </label>
                                            <select class="form-control" id="sub_category_id" name="sub_category_id">
                                                <option value=""> -- حدد القسم الفرعي --</option>
                                                @if ($product['sub_category_id'])
                                                    <option selected value="{{ $product['sub_category_id'] }}">
                                                        {{ $product['Sub_Category']['name'] ?? '' }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="brand_id" class="form-label"> العلامة التجارية </label>
                                            <select class="form-control" id="brand_id" data-choices data-choices-groups name="brand_id">
                                                <option value=""> -- حدد العلامة التجارية --</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand['id'] }}" {{ $product['brand_id'] == $brand['id'] ? 'selected' : '' }}>
                                                        {{ $brand['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label"> حالة المنتج </label>
                                            <select class="form-control" id="status" name="status">
                                                <option value=""> -- حدد حالة المنتج --</option>
                                                <option value="1" {{ $product['status'] == 1 ? 'selected' : '' }}>مفعل</option>
                                                <option value="0" {{ $product['status'] == 0 ? 'selected' : '' }}>ارشيف</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="short_description" class="form-label"> وصف مختصر عن المنتج </label>
                                            <textarea class="form-control bg-light-subtle" id="short_description" rows="5" name="short_description">{{ $product['short_description'] }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label"> وصف المنتج </label>
                                            <input type="hidden" name="description" id="content">
                                            <div id="snow-editor" style="height: 300px;">
                                                {{ $product['description'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label"> الكمية المتاحة </label>
                                            <input min="1" type="number" id="quantity" name="quantity" value="{{ $product['quantity'] }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> مرفقات المنتج </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="image" class="form-label"> صورة المنتج </label>
                                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                            <br>
                                            <img width="80px" class="img-thumbnail" src="{{ asset('assets/uploads/product_images/' . $product['image']) }}" alt="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="gallery" class="form-label"> اضافة صور للمعرض </label>
                                            <input type="file" multiple id="gallery" name="gallery[]" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="video" class="form-label"> اضافة فيديو للمنتج </label>
                                            <input type="file" id="video" name="video" class="form-control" accept="video/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> تحديد نوع المنتج </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="type">نوع المنتج:</label>
                                            <select class="form-control" name="type" id="product-type" required>
                                                <option value="بسيط" {{ $product['type'] == 'بسيط' ? 'selected' : '' }}>بسيط</option>
                                                <option value="متغير" {{ $product['type'] == 'متغير' ? 'selected' : '' }}>متغير</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="variable-product-fields" style="display: {{ $product->type == 'متغير' ? 'block' : 'none' }};">
                                    @if ($product->type == 'متغير')
                                        <div>
                                            @foreach ($variations as $variation)
                                                <input type="hidden" name="variant_id[]" value="{{ $variation->id }}">
                                                <div class="mb-3 variation variant-inputs d-flex align-items-center justify-content-between">
                                                    @foreach ($variation->variationValues as $value)
                                                        <div class="form-group">
                                                            <label>{{ $value->attribute->name }}</label>
                                                            <input type="text" name="variant_attributes[{{ $loop->parent->index }}][{{ $value->attribute_id }}]" value="{{ $value->attribute_value_name }}" class="form-control" required>
                                                        </div>
                                                    @endforeach

                                                    <div class="form-group">
                                                        <label>سعر المتغير</label>
                                                        <input type="number" name="variant_price[]" value="{{ $variation->price }}" class="form-control" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>تخفيض المتغير</label>
                                                        <input type="number" name="variant_discount[]" value="{{ $variation->discount }}" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>المخزون</label>
                                                        <input type="number" name="variant_stock[]" value="{{ $variation->stock }}" class="form-control" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>صورة المتغير</label>
                                                        <input type="file" name="variant_image[]" class="form-control">
                                                        @if ($variation->image)
                                                            <img src="{{ asset($variation->image) }}" alt="" style="max-width: 100px; margin-top: 8px; border-radius: 4px;">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div id="attribute-container">
                                        <br>
                                        <h4 class="card-title"> تحديث وتعديل متغيرات المنتج </h4>
                                        <br>
                                        <div class="row d-flex align-items-center" id="attribute-row-template" style="display: none">
                                            <div class="col-lg-4 col-12">
                                                <div class="mb-3">
                                                    <label>اختر السمة:</label>
                                                    <select class="form-control" name="attributes[]">
                                                        <option value=""> -- حدد السمة --</option>
                                                        @foreach ($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="mb-3">
                                                    <label>حدد المتغيرات:</label>
                                                    <input type="text" class="form-control" name="variations[]" placeholder="ادخل المتغيرات وافصل بين كل متغير بـ(-)">
                                                </div>
                                            </div>
                                            <div class="mb-3 col-2">
                                                <button style="margin-top: 20px" class="btn btn-sm btn-danger delete-attribute"><i class="ti ti-x"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <button id="add_new_vartion" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i> اضافة متغير جديد</button>
                                    <button id="confirm-variations" class="btn btn-success btn-sm">تأكيد المتغيرات</button>
                                    <br>
                                    <div id="product-variants"></div>
                                </div>
                            </div>
                        </div>

                        <div class="card" id="simple-product-fields">
                            <div class="card-header">
                                <h4 class="card-title"> تفاصيل السعر </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="form-label"> سعر الشراء </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bx-dollar'></i></span>
                                            <input type="number" name="purches_price" class="form-control" value="{{ $product['purches_price'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label"> سعر البيع </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bx-dollar'></i></span>
                                            <input type="number" name="price" class="form-control" value="{{ $product['price'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label"> السعر بعد الخصم </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bxs-discount'></i></span>
                                            <input type="number" name="discount" class="form-control" value="{{ $product['discount'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> معلومات السيو ومحركات البحث </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label"> العنوان </label>
                                            <input type="text" name="meta_title" class="form-control" value="{{ $product['meta_title'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label"> الكلمات المفتاحية </label>
                                            <input type="text" name="meta_keywords" class="form-control" value="{{ $product['meta_keywords'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label"> الوصف </label>
                                            <textarea class="form-control bg-light-subtle" name="meta_description" rows="7">{{ $product['meta_description'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 mb-3 rounded bg-light">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <a href="{{ url('admin/products') }}" class="btn btn-primary w-100"> رجوع </a>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100"> حفظ <i class='bx bxs-save'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @if (count($gallaries) > 0)
                <table class="table mt-4 table-bordered">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gallaries as $gallary)
                            <tr>
                                <td><img width="60" height="60" class="img-thumbnail" src="{{ asset('assets/uploads/product_gallery/' . $gallary['image']) }}" alt=""></td>
                                <td>
                                    <button type="button" class="btn btn-soft-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete_gallary_{{ $gallary['id'] }}">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                            @include('admin.Products.delete_gallary')
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById('product-type').addEventListener('change', function() {
            document.getElementById('simple-product-fields').style.display = this.value === 'بسيط' ? 'block' : 'none';
            document.getElementById('variable-product-fields').style.display = this.value === 'متغير' ? 'block' : 'none';
        });
    </script>

    <script>
        document.getElementById('add_new_vartion').addEventListener('click', function(e) {
            e.preventDefault();
            let template = document.getElementById('attribute-row-template');
            let clone = template.cloneNode(true);
            clone.removeAttribute('id');
            clone.style.display = 'flex';
            clone.querySelector('input[name="variations[]"]').value = '';
            document.getElementById('attribute-container').appendChild(clone);
        });

        document.getElementById('attribute-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-attribute')) {
                let allRows = document.querySelectorAll('#attribute-container .row');
                if (allRows.length > 1) {
                    e.target.closest('.row').remove();
                } else {
                    alert('لا يمكنك حذف العنصر الأخير.');
                }
            }
        });

        document.getElementById('confirm-variations').addEventListener('click', function(e) {
            e.preventDefault();
            const attributes = document.querySelectorAll('select[name="attributes[]"]');
            const variations = document.querySelectorAll('input[name="variations[]"]');

            let selectedValues = [];
            attributes.forEach((attribute, index) => {
                if (attribute.value) {
                    const variationValues = variations[index].value.split('-').map(v => v.trim());
                    selectedValues.push(variationValues);
                }
            });

            const productVariants = cartesianProduct(selectedValues);
            let html = '';

            productVariants.forEach(variant => {
                const variantText = variant.join(' - ');
                html += `
                    <div class="variant-inputs d-flex align-items-center justify-content-between">
                        <div class="form-group">
                            <label>اسم المتغير</label>
                            <input name='variant_new_name[]' class="form-control" type="text" value="${variantText}">
                        </div>
                        <div class="form-group">
                            <label>سعر المنتج</label>
                            <input placeholder="السعر" class="form-control" type="number" name='variant_new_price[]'>
                        </div>
                        <div class="form-group">
                            <label>السعر بعد التخفيض</label>
                            <input placeholder="السعر" class="form-control" type="number" name='variant_new_discount[]'>
                        </div>
                        <div class="form-group">
                            <label>الكمية المتاحة</label>
                            <input placeholder="الكمية" class="form-control" type="number" name='variant_new_stock[]'>
                        </div>
                        <div class="form-group">
                            <label>صورة المنتج</label>
                            <input type='file' class='form-control' name='variant_new_image[]'>
                        </div>
                        <div class="form-group">
                            <button style="margin-top: 20px" class="btn btn-sm btn-danger delete-variant"><i class="ti ti-x"></i></button>
                        </div>
                    </div>
                `;
            });

            document.getElementById('product-variants').innerHTML = html;
            attachDeleteEventListeners();
        });

        function cartesianProduct(arrays) {
            return arrays.reduce((a, b) => a.flatMap(x => b.map(y => x.concat([y]))), [[]]);
        }

        function attachDeleteEventListeners() {
            document.querySelectorAll('.delete-variant').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.variant-inputs').remove();
                });
            });
        }
    </script>

    <script src="{{ asset('assets/admin/js/components/form-quilljs.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var quill = Quill.find(document.getElementById('snow-editor'));
            if (quill) {
                quill.root.innerHTML = `{!! old('description', $product['description']) !!}`;

                document.querySelector('form').addEventListener('submit', function() {
                    document.querySelector('input[name=description]').value = quill.root.innerHTML;
                });
            }
        });
    </script>
@endsection