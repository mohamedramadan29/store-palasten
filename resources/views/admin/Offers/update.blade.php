@extends('admin.layouts.master')
@section('title')
    تعديل صفحة الهبوط للمنتج
@endsection
@section('css')
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
            <form method="post" action="{{url('admin/offer/update/'.$offer['id'])}}" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-xl-12 col-lg-8 ">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">  تعديل صفحة الهبوط للمنتج  </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> اسم المنتج </label>
                                            <input required type="text" id="product_name" name="product_name" class="form-control"
                                                   placeholder="" value="{{$offer['product_name']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label"> اضف اسم خاص للرابط  ( اختياري )  </label>
                                            <input type="text" id="slug" name="slug" class="form-control"
                                                   placeholder="" value="{{$offer['slug']}}">
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
                                            <input type="file" id="image" name="image" class="form-control"
                                                   accept="image/*">
                                            <img class="img-thumbnail" src="{{asset('assets/uploads/product_offers/'.$offer['image'])}}" width="80" height="80px" alt="">
                                        </div>
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
                                        <label for="product-price" class="form-label">  السعر  </label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text fs-20"><i class='bx bx-dollar'></i></span>
                                            <input type="number" id="price" name="price" value="{{$offer['price']}}"
                                                   class="form-control"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="p-3 bg-light mb-3 rounded">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100"> حفظ <i
                                            class='bx bxs-save'></i></button>
                                </div>
                                <div class="col-lg-2">
                                    <a href="{{url('admin/products')}}" class="btn btn-primary w-100"> رجوع </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Container Fluid -->

    </div>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('product-type').addEventListener('change', function () {
            if (this.value === 'بسيط') {
                document.getElementById('simple-product-fields').style.display = 'block';
                document.getElementById('variable-product-fields').style.display = 'none';
            } else {
                document.getElementById('simple-product-fields').style.display = 'none';
                document.getElementById('variable-product-fields').style.display = 'block';
            }
        });
    </script>

    <script>
        document.getElementById('confirm-variations').addEventListener('click', function ($e) {
            $e.preventDefault();
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
                    <label>صورة المنتج</label>
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

            // أضف الاستماع لأزرار الحذف الجديدة
            attachDeleteEventListeners();
        });

        function cartesianProduct(arrays) {
            return arrays.reduce(function (a, b) {
                var result = [];
                a.forEach(function (a) {
                    b.forEach(function (b) {
                        result.push(a.concat([b]));
                    });
                });
                return result;
            }, [[]]);
        }

        function attachDeleteEventListeners() {
            const deleteButtons = document.querySelectorAll('.delete-variant');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const variantRow = this.closest('.variant-inputs');
                    variantRow.remove();
                });
            });
        }

    </script>
@endsection
