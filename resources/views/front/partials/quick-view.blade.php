<div class="header">
    <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
</div>
<div class="wrap">
    <div class="tf-product-media-wrap">
        <div class="swiper tf-single-slide">
            <div class="swiper-wrapper" style="align-items: center">
                <div class="swiper-slide">
                    <div class="item">
                        <img src="{{asset('assets/uploads/product_images/'.$product['image'])}}" alt="">
                    </div>
                </div>
                @if($product['gallary'] && $product['gallary'] !='')
                    @foreach($product['gallary'] as $gallary)
                        <div class="swiper-slide">
                            <div class="item">
                                <img src="{{asset('assets/uploads/product_gallery/'.$gallary['image'])}}" alt="">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="swiper-button-next button-style-arrow single-slide-prev"></div>
            <div class="swiper-button-prev button-style-arrow single-slide-next"></div>
        </div>
    </div>
    <div class="tf-product-info-wrap position-relative">
        <div class="tf-product-info-list">
            <div class="tf-product-info-title">
                <h5><a class="link" href="#"> {{$product->name}}  </a></h5>
            </div>
            <div class="tf-product-info-badges">
                <div class="product-status-content">
                    <p class="fw-6">{{$product['short_description']}}</p>
                </div>
            </div>
            <!-- عرض خيارات السمات -->
            <form id="addToCart" class="" method="post" action="{{url('cart/add')}}">
                @csrf
                <div class="tf-product-info-variant-picker">
                    @if($productVariations->count() > 0)
                        @foreach($variationAttributes as $attributeId => $attribute)
                            <div class="form-group">
                                <label
                                    for="attribute_{{ $attributeId }}">{{ $attribute['name'] }}</label>
                                <select name="attribute_values[{{ $attributeId }}]"
                                        class="form-control" onchange="fetchPrice()">
                                    <option value="">اختر {{ $attribute['name'] }}</option>
                                    @foreach($attribute['values'] as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                        <!-- عرض السعر هنا -->
                        <div id="product-price" class="tf-product-info-price">
                            <p class="quantity-title fw-6">السعر: <span id="price-value"
                                                                        class="price-on-sale"> </span>
                            </p>
                            <p id="discount-section" style="display: none;">
                                <span id="discounted-price" class="price-on-sale"> </span></p>
                        </div>
                        <br>
                        <!-- حقول مخفية للسعر والمتغيرات -->
                        <input type="hidden" placeholder="سعر المتغير " id="hidden-price"
                               name="price" value="">
                        <input type="hidden" id="hidden-discount" placeholder=" سعر خصم المتغير "
                               name="discount" value="">
                        <input type="hidden" id="hidden-variation" placeholder="دشقفهخر " name="hidden-variation"
                               value="">

                    @else
                        <input type="hidden" id="hidden-variation" placeholder="دشقفهخر " name="hidden-variation"
                               value="">
                        <div class="tf-product-info-price">
                            @if(isset($product['discount']) && $product['discount'] !=null)
                                <div
                                    class="price-on-sale">{{$product['discount']}} {{ $storeCurrency }} </div>
                                <div
                                    class="compare-at-price">{{$product['price']}} {{ $storeCurrency }}</div>
                            @else
                                <div
                                    class="price-on-sale">{{$product['price']}} {{ $storeCurrency }}</div>
                            @endif
                        </div>
                        @if(isset($product['discount']) && $product['discount'] !=null)
                            <input type="hidden" name="price" value="{{$product['discount']}}">
                        @else
                            <input type="hidden" name="price" value="{{$product['price']}}">
                        @endif
                    @endif

                </div>
                <div class="tf-product-info-quantity">
                    <div class="quantity-title fw-6"> الكمية</div>
                    <div class="wg-quantity">
                        <span class="btn-quantity minus-btn">-</span>
                        <input type="text" name="number" value="1">
                        <span class="btn-quantity plus-btn">+</span>
                    </div>
                </div>
                <div class="tf-product-info-buy-button">
                    <input type="hidden" name="product_id" value="{{$product['id']}}">


                    <button id="addtocartbutton" href="javascript:void(0);"
                            class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart">
                        <span>  اضف الي السلة    </span></button>
                </div>
            </form>

            <div>
                <br>
                <a href="{{url('product/'.$product['slug'])}}" class="tf-btn fw-6 btn-line"> تفاصيل المنتج  <i
                        class="icon icon-arrow1-top-left"></i></a>
            </div>
        </div>
    </div>
</div>


