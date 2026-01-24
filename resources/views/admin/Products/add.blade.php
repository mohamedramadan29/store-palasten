@extends('admin.layouts.master')

@section('title')
    اضافة منتج جديد
@endsection

@section('css')
    <style>
        #product-variants {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .variant-inputs {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem 1.5rem;
            align-items: flex-start;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: #f8fafc;
        }

        .variant-inputs .form-group {
            flex: 1;
            min-width: 220px;
            max-width: 320px;
        }

        .variant-inputs .form-group input[type="number"],
        .variant-inputs .form-group input[type="text"],
        .variant-inputs .form-group input[type="file"] {
            width: 100%;
        }

        .variant-inputs .form-group:has(.delete-variant) {
            flex: 0 0 auto;
            min-width: auto;
        }

        @media (max-width: 768px) {
            .variant-inputs {
                flex-direction: column;
                gap: 1rem;
                padding: 1.25rem 1rem;
            }

            .variant-inputs .form-group {
                min-width: 100%;
                max-width: 100%;
            }

            .variant-inputs .form-group:has(.delete-variant) {
                align-self: flex-end;
            }
        }

        @media (min-width: 576px) and (max-width: 991.98px) {
            .variant-inputs .form-group {
                min-width: 45%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <!-- Start Container Fluid -->
        <div class="container-xxl">
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
            <form method="post" action="{{ url('admin/product/add') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-xl-12 col-lg-8 ">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> معلومات المنتج </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> اسم المنتج </label>
                                            <input required type="text" id="name" name="name"
                                                class="form-control" placeholder="" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label"> اضف اسم خاص للرابط ( اختياري )</label>
                                            <input type="text" id="slug" name="slug" class="form-control"
                                                placeholder="" value="{{ old('slug') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label"> حدد القسم الرئيسي </label>
                                            <select required class="form-control" id="category_id" data-choices
                                                data-choices-groups data-placeholder="Select Categories" name="category_id">
                                                <option value=""> -- حدد القسم --</option>
                                                @foreach ($MainCategories as $maincat)
                                                    <option value="{{ $maincat['id'] }}">{{ $maincat['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="sub_category_id" class="form-label"> حدد القسم الفرعي </label>
                                            <select class="form-control" id="sub_category_id"
                                                data-placeholder="Select Categories" name="sub_category_id">
                                                <option value=""> -- حدد القسم الفرعي --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="brand_id" class="form-label"> العلامة التجارية </label>
                                            <select class="form-control" id="brand_id" data-choices data-choices-groups
                                                data-placeholder="Select Categories" name="brand_id">
                                                <option value=""> -- حدد العلامة التجارية --</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label"> حالة المنتج </label>
                                            <select required class="form-control" id="status" data-choices
                                                data-choices-groups data-placeholder="Select Categories" name="status">
                                                <option value=""> -- حدد حالة المنتج --</option>
                                                <option value="1" selected> مفعل</option>
                                                <option value="0"> ارشيف</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="short_description" class="form-label"> وصف مختصر عن
                                                المنتج </label>
                                            <textarea class="form-control bg-light-subtle" id="short_description" rows="5" placeholder=""
                                                name="short_description">{{ old('short_description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">وصف المنتج</label>
                                            <input type="hidden" name="description" id="content">
                                            <div id="snow-editor" style="height: 300px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label"> الكمية المتاحة </label>
                                            <input required min="1" type="number" id="quantity" name="quantity"
                                                class="form-control" placeholder="">
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
                                            <input required type="file" id="image" name="image"
                                                class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="gallery" class="form-label"> اضافة صور للمعرض </label>
                                            <input type="file" multiple id="gallery" name="gallery[]"
                                                class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="video" class="form-label"> اضافة فيديو للمنتج </label>
                                            <input type="file" id="video" name="video" class="form-control"
                                                accept="video/*">
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
                                                <option selected value="بسيط">بسيط</option>
                                                <option value="متغير">متغير</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="variable-product-fields" style="display: none">
                                    <div id="attribute-container">
                                        <div class="row d-flex align-items-center" id="attribute-row-template"
                                            style="display: none">
                                            <div class="col-lg-4 col-12">
                                                <div class="mb-3">
                                                    <label for="attribute">اختر السمة:</label>
                                                    <select class="form-control" name="attributes[]">
                                                        <option value=""> -- حدد السمة --</option>
                                                        @foreach ($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="mb-3">
                                                    <label for="variations">حدد المتغيرات:</label>
                                                    <input type="text" class="form-control" name="variations[]"
                                                        placeholder="ادخل المتغيرات وافصل بين كل متغير بـ(-)">
                                                </div>
                                            </div>
                                            <div class="mb-3 col-2">
                                                <button style="margin-top: 20px"
                                                    class="btn btn-sm btn-danger delete-attribute"><i
                                                        class="ti ti-x"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <button id="add_new_vartion" class="btn btn-primary btn-sm"><i
                                            class="ti ti-plus"></i> اضافة متغير جديد
                                    </button>

                                    <button id="confirm-variations" class="btn btn-success btn-sm">تأكيد المتغيرات
                                    </button>

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
                                        <label for="purches_price" class="form-label"> سعر الشراء </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bx-dollar'></i></span>
                                            <input type="number" id="purches_price" name="purches_price"
                                                class="form-control" placeholder="000">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="price" class="form-label"> سعر البيع </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bx-dollar'></i></span>
                                            <input type="number" id="price" name="price" class="form-control"
                                                placeholder="000">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="discount" class="form-label"> السعر بعدالخصم </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bxs-discount'></i></span>
                                            <input type="number" id="discount" name="discount" class="form-control"
                                                placeholder="000">
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
                                            <label for="meta_title" class="form-label"> العنوان </label>
                                            <input type="text" id="meta_title" name="meta_title" class="form-control"
                                                placeholder="" value="{{ old('meta_title') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label"> الكلمات المفتاحية </label>
                                            <input type="text" id="meta_keywords" name="meta_keywords"
                                                class="form-control" placeholder="" value="{{ old('meta_keywords') }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label"> الوصف </label>
                                            <textarea class="form-control bg-light-subtle" id="meta_description" rows="7" placeholder=""
                                                name="meta_description">{{ old('meta_description') }}</textarea>
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
                                    <button type="submit" class="btn btn-outline-secondary w-100"> حفظ <i
                                            class='bx bxs-save'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById('product-type').addEventListener('change', function() {
            if (this.value === 'بسيط') {
                document.getElementById('simple-product-fields').style.display = 'block';
                document.getElementById('variable-product-fields').style.display = 'none';
            } else {
                document.getElementById('simple-product-fields').style.display = 'none';
                document.getElementById('variable-product-fields').style.display = 'block';
            }
        });

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
                const selectedAttribute = attribute.value;
                if (selectedAttribute) {
                    const variationValues = variations[index].value.split('-').map(v => v.trim());
                    selectedValues.push(variationValues);
                }
            });

            const productVariants = cartesianProduct(selectedValues);
            let productVariantsHTML = '';

            productVariants.forEach(variant => {
                const variantText = variant.join(' - ');
                const variationInputsHTML = `
            <div class="variant-inputs d-flex align-items-center justify-content-between">
                <div class="form-group">
                    <label>اسم المتغير</label>
                    <input name='variant_name[]' class="form-control" type="text" value="${variantText}">
                </div>
                <div class="form-group">
                    <label>سعر المنتج</label>
                    <input placeholder="السعر" class="form-control" type="number" name='variant_price[]'>
                </div>
                <div class="form-group">
                    <label>السعر بعد التخفيض</label>
                    <input placeholder="السعر" class="form-control" type="number" name='variant_discount[]'>
                </div>
                <div class="form-group">
                    <label>الكمية المتاحة</label>
                    <input placeholder="الكمية" class="form-control" type="number" name='variant_stock[]'>
                </div>
                <div class="form-group">
                    <label>صورة المتغير</label>
                    <input type='file' class='form-control' name='variant_image[]'>
                </div>
                <div class="form-group">
                    <button style="margin-top: 20px" class="btn btn-sm btn-danger delete-variant"><i class="ti ti-x"></i></button>
                </div>
            </div>
        `;
                productVariantsHTML += variationInputsHTML;
            });

            document.getElementById('product-variants').innerHTML = productVariantsHTML;

            attachDeleteEventListeners();
        });

        function cartesianProduct(arrays) {
            return arrays.reduce(function(a, b) {
                var result = [];
                a.forEach(function(a) {
                    b.forEach(function(b) {
                        result.push(a.concat([b]));
                    });
                });
                return result;
            }, [[]]);
        }

        function attachDeleteEventListeners() {
            const deleteButtons = document.querySelectorAll('.delete-variant');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const variantRow = this.closest('.variant-inputs');
                    variantRow.remove();
                });
            });
        }
    </script>

    <script src="{{ asset('assets/admin/js/components/form-quilljs.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var quill = Quill.find(document.getElementById('snow-editor'));
            var oldContent = `{!! old('description') !!}`;
            if (quill) quill.root.innerHTML = oldContent;

            var form = document.querySelector('form');
            if (form) {
                form.onsubmit = function() {
                    document.querySelector('input[name=description]').value = quill.root.innerHTML;
                };
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#category_id').on('change', function() {
                var categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: '{{ route('get.subcategories') }}',
                        type: "GET",
                        data: { category_id: categoryId },
                        success: function(data) {
                            $('#sub_category_id').empty();
                            if (data.message) {
                                $('#sub_category_id').append('<option value="">' + data.message + '</option>');
                            } else {
                                $('#sub_category_id').append('<option value=""> -- حدد القسم الفرعي --</option>');
                                $.each(data, function(key, value) {
                                    $('#sub_category_id').append('<option value="' + key + '">' + value + '</option>');
                                });
                            }
                        }
                    });
                } else {
                    $('#sub_category_id').empty();
                    $('#sub_category_id').append('<option value=""> -- حدد القسم الفرعي --</option>');
                }
            });
        });
    </script>
@endsection