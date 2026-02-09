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

                    <div class="col-xl-12 col-lg-8">
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
                                            <select class="form-control" id="category_id" data-choices
                                                data-choices-groups data-placeholder="Select Categories" name="category_id">
                                                <option value=""> -- حدد القسم --</option>
                                                @foreach ($MainCategories as $maincat)
                                                    <option value="{{ $maincat['id'] }}" {{ old('category_id') == $maincat['id'] ? 'selected' : '' }}>{{ $maincat['name'] }}</option>
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
                                                    <option value="{{ $brand['id'] }}" {{ old('brand_id') == $brand['id'] ? 'selected' : '' }}>{{ $brand['name'] }}</option>
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
                                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}> مفعل</option>
                                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}> ارشيف</option>
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

                                <div class="row" id="quantity-field-container">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label"> الكمية المتاحة </label>
                                            <input min="0" type="number" id="quantity" name="quantity"
                                                class="form-control" placeholder="" value="{{ old('quantity') }}">
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
                                            <select class="form-select" name="type" id="product-type" required>
                                                <option value="بسيط" {{ old('type') == 'بسيط' ? 'selected' : '' }}>بسيط</option>
                                                <option value="متغير" {{ old('type') == 'متغير' ? 'selected' : '' }}>متغير</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="variable-product-fields" style="display: {{ old('type') == 'متغير' ? 'block' : 'none' }}">
                                    <div id="attribute-container">
                                        <!-- Template for hidden attribute row -->
                                        <div class="row" id="attribute-row-template" style="display: none">
                                            <div class="col-lg-4 col-12">
                                                <div class="mb-3">
                                                    <label for="attribute">اختر السمة:</label>
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
                                                    <label for="variations">حدد المتغيرات:</label>
                                                    <input type="text" class="form-control" name="variations[]" placeholder="ادخل المتغيرات وافصل بين كل متغير بـ(-)">
                                                </div>
                                            </div>
                                            <div class="mb-3 col-2">
                                                <button type="button" style="margin-top: 20px" class="btn btn-sm btn-danger delete-attribute"><i class="ti ti-x"></i></button>
                                            </div>
                                        </div>

                                        @if(old('attributes'))
                                            @foreach(old('attributes') as $index => $oldAttrId)
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-lg-4 col-12">
                                                        <div class="mb-3">
                                                            <label for="attribute">اختر السمة:</label>
                                                            <select class="form-control" name="attributes[]">
                                                                <option value=""> -- حدد السمة --</option>
                                                                @foreach ($attributes as $attribute)
                                                                    <option value="{{ $attribute->id }}" {{ $oldAttrId == $attribute->id ? 'selected' : '' }}>{{ $attribute->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <div class="mb-3">
                                                            <label for="variations">حدد المتغيرات:</label>
                                                            <input type="text" class="form-control" name="variations[]" value="{{ old('variations.'.$index) }}" placeholder="ادخل المتغيرات وافصل بين كل متغير بـ(-)">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-2">
                                                        <button type="button" style="margin-top: 20px" class="btn btn-sm btn-danger delete-attribute"><i class="ti ti-x"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Default first row -->
                                            <div class="row d-flex align-items-center">
                                                <div class="col-lg-4 col-12">
                                                    <div class="mb-3">
                                                        <label for="attribute">اختر السمة:</label>
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
                                                        <label for="variations">حدد المتغيرات:</label>
                                                        <input type="text" class="form-control" name="variations[]" placeholder="ادخل المتغيرات وافصل بين كل متغير بـ(-)">
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-2">
                                                    <button type="button" style="margin-top: 20px" class="btn btn-sm btn-danger delete-attribute"><i class="ti ti-x"></i></button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <button type="button" id="add_new_vartion" class="btn btn-primary btn-sm"><i
                                            class="ti ti-plus"></i> اضافة متغير جديد
                                    </button>

                                    <button type="button" id="confirm-variations" class="btn btn-success btn-sm">تأكيد المتغيرات
                                    </button>

                                    <br>

                                    <div id="product-variants">
                                        @if(old('variant_name'))
                                            @foreach(old('variant_name') as $index => $vName)
                                                <div class="mb-3 variant-inputs d-flex align-items-center justify-content-between">
                                                    <div class="form-group">
                                                        <label>اسم المتغير</label>
                                                        <input name='variant_name[]' class="form-control" type="text" value="{{ $vName }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>سعر المنتج</label>
                                                        <input placeholder="السعر" step="0.01" class="form-control" type="number" name='variant_price[]' value="{{ old('variant_price.'.$index) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>السعر بعد التخفيض</label>
                                                        <input placeholder="السعر" step="0.01" class="form-control" type="number" name='variant_discount[]' value="{{ old('variant_discount.'.$index) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>الكمية المتاحة</label>
                                                        <input placeholder="الكمية" step="1" class="form-control" type="number" name='variant_stock[]' value="{{ old('variant_stock.'.$index) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>صورة المتغير</label>
                                                        <input type='file' class='form-control' name='variant_image[]'>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="button" style="margin-top: 20px" class="btn btn-sm btn-danger delete-variant"><i class="ti ti-x"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
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
                                            <input type="number" id="purches_price" name="purches_price" step="0.01"
                                                class="form-control" placeholder="" value="{{ old('purches_price') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="price" class="form-label"> سعر البيع </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bx-dollar'></i></span>
                                            <input type="number" id="price" name="price" step="0.01" class="form-control"
                                                placeholder="" value="{{ old('price') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="discount" class="form-label"> السعر بعدالخصم </label>
                                        <div class="mb-3 input-group">
                                            <span class="input-group-text fs-20"><i class='bx bxs-discount'></i></span>
                                            <input type="number" id="discount" name="discount" step="0.01" class="form-control"
                                                placeholder="" value="{{ old('discount') }}">
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
        // Prevent multiple initializations
        (function() {
            let initialized = false;
            
            function initializeProductForm() {
                if (initialized) return;
                initialized = true;

                function toggleStockField() {
                    const productType = document.getElementById('product-type').value;
                    const quantityContainer = document.getElementById('quantity-field-container');
                    const simpleFields = document.getElementById('simple-product-fields');
                    const variableFields = document.getElementById('variable-product-fields');
                    const quantityInput = document.getElementById('quantity');

                    if (productType === 'بسيط') {
                        if(simpleFields) simpleFields.style.display = 'block';
                        if(variableFields) variableFields.style.display = 'none';
                        if(quantityContainer) quantityContainer.style.display = 'block';
                        if(quantityInput) quantityInput.setAttribute('required', 'required');
                    } else {
                        if(simpleFields) simpleFields.style.display = 'none';
                        if(variableFields) variableFields.style.display = 'block';
                        if(quantityContainer) quantityContainer.style.display = 'none';
                        if(quantityInput) quantityInput.removeAttribute('required');
                    }
                }

                document.getElementById('product-type').addEventListener('change', toggleStockField);
                toggleStockField(); // Initial call

                document.getElementById('add_new_vartion').addEventListener('click', function(e) {
                    e.preventDefault();

                    let template = document.getElementById('attribute-row-template');
                    let clone = template.cloneNode(true);

                    clone.removeAttribute('id');
                    clone.style.display = 'flex';

                    let variationInput = clone.querySelector('input[name="variations[]"]');
                    if(variationInput) variationInput.value = '';

                    document.getElementById('attribute-container').appendChild(clone);
                });

                document.getElementById('attribute-container').addEventListener('click', function(e) {
                    if (e.target.closest('.delete-attribute')) {
                        let allRows = document.querySelectorAll('#attribute-container .row');
                        // Don't count the hidden template row
                        let visibleRows = Array.from(allRows).filter(row => row.style.display !== 'none');
                        
                        if (visibleRows.length > 1) {
                            e.target.closest('.row').remove();
                        } else {
                            alert('لا يمكنك حذف العنصر الأخير.');
                        }
                    }
                });

                document.getElementById('confirm-variations').addEventListener('click', function(e) {
                    e.preventDefault();
                    const attributes = document.querySelectorAll('#attribute-container .row:not(#attribute-row-template) select[name="attributes[]"]');
                    const variations = document.querySelectorAll('#attribute-container .row:not(#attribute-row-template) input[name="variations[]"]');

                    let selectedValues = [];
                    let selectedAttributeIds = []; // لحفظ IDs السمات المحددة
                    let isValid = true;

                    attributes.forEach((attribute, index) => {
                        const selectedAttribute = attribute.value;
                        console.log(`السمة ${index}:`, selectedAttribute);
                        if (selectedAttribute) {
                            const variationValuesString = variations[index].value.trim();
                            console.log(`القيم ${index}:`, variationValuesString);
                            if (!variationValuesString) {
                                isValid = false;
                                return;
                            }
                            const variationValues = variationValuesString.split('-').map(v => v.trim()).filter(v => v !== '');
                            console.log(`القيم بعد التقسيم ${index}:`, variationValues);
                            if (variationValues.length === 0) {
                                isValid = false;
                                return;
                            }
                            selectedValues.push(variationValues);
                            selectedAttributeIds.push(selectedAttribute); // حفظ ID السمة
                        }
                    });

                    console.log('جميع القيم المحددة:', selectedValues);
                    console.log('جميع IDs السمات:', selectedAttributeIds);

                    if (!isValid || selectedValues.length === 0) {
                        alert('من فضلك حدد السمة وادخل المتغيرات مفصولة بـ (-) لكل سطر.');
                        return;
                    }

                    // تعطيل حقول السمات الأصلية لمنع إرسالها مع النموذج
                    attributes.forEach(attr => {
                        attr.disabled = true;
                    });

                    const productVariants = cartesianProduct(selectedValues);
                    let productVariantsHTML = '';
                    
                    // إضافة حقول السمات المحددة (للتأكد - سنراها مؤقتاً)
                    productVariantsHTML += '<div class="mb-3 alert alert-info">السمات المحددة: ';
                    selectedAttributeIds.forEach((attrId, idx) => {
                        productVariantsHTML += `<input type="hidden" name="attributes[]" value="${attrId}">`;
                        productVariantsHTML += `<span class="badge bg-primary me-2">السمة ${idx + 1}: ID = ${attrId}</span>`;
                    });
                    productVariantsHTML += '</div>';

                    productVariants.forEach(variant => {
                        const variantText = variant.join(' - ');
                        const variationInputsHTML = `
            <div class="mb-3 variant-inputs d-flex align-items-center justify-content-between">
                <div class="form-group">
                    <label>اسم المتغير</label>
                    <input name='variant_name[]' class="form-control" type="text" value="${variantText}" readonly>
                </div>
                <div class="form-group">
                    <label>سعر الشراء</label>
                    <input placeholder="سعر الشراء" class="form-control" type="number" name='variant_purchase_price[]' min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label>سعر البيع</label>
                    <input placeholder="سعر البيع" required class="form-control" type="number" name='variant_price[]' min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label>السعر بعد التخفيض</label>
                    <input placeholder="السعر" class="form-control" type="number" name='variant_discount[]' min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label>الكمية المتاحة</label>
                    <input placeholder="الكمية" required class="form-control" type="number" name='variant_stock[]' min="0">
                </div>
                <div class="form-group">
                    <label>صورة المتغير</label>
                    <input type='file' class='form-control' name='variant_image[]' accept="image/*">
                </div>
                <div class="form-group">
                    <button type="button" style="margin-top: 20px" class="btn btn-sm btn-danger delete-variant"><i class="ti ti-x"></i></button>
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
                    const container = document.getElementById('product-variants');
                    container.onclick = function(e) {
                        if (e.target.closest('.delete-variant')) {
                            const variantRow = e.target.closest('.variant-inputs');
                            variantRow.remove();
                        }
                    };
                }
                
                // Call once for old values
                attachDeleteEventListeners();
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeProductForm);
            } else {
                initializeProductForm();
            }
        })();
    </script>

    <script src="{{ asset('assets/admin/js/components/form-quilljs.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var quill = Quill.find(document.getElementById('snow-editor'));
            var oldContent = `{!! old('description') !!}`;
            if (quill) quill.root.innerHTML = oldContent;

            var form = document.querySelector('form');
            if (form) {
                form.onsubmit = function(e) {
                    // Validation for Main Category
                    var categorySelect = document.getElementById('category_id');
                    if (categorySelect && !categorySelect.value) {
                         e.preventDefault();
                         Toastify({
                            text: "من فضلك حدد القسم الرئيسي للمنتج",
                            duration: 3000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#FF5722",
                        }).showToast();
                        // Scroll to the error
                        categorySelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }

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