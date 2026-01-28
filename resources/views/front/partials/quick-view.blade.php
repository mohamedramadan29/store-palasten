<div class="header">
    <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
</div>
<div class="wrap">
    <div class="tf-product-media-wrap">
        <div class="swiper tf-single-slide">
            <div class="swiper-wrapper" style="align-items: center">
                <div class="swiper-slide position-relative">
                    <div id="variant-overlay-modal" 
                         style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.7); padding: 3px 12px; border-radius: 5px; font-weight: 500; font-size: 14px; color: #000; z-index: 100; display: none; backdrop-filter: blur(2px); border: 1px solid rgba(255,255,255,0.3);">
                    </div>
                    <div class="item">
                        <img id="main-product-image-modal" src="{{asset('assets/uploads/product_images/'.$product['image'])}}" alt="">
                    </div>
                </div>
                @if($product->gallary && $product->gallary->count() > 0)
                    @foreach($product->gallary as $gallary)
                        <div class="swiper-slide">
                            <div class="item">
                                <img src="{{asset('assets/uploads/product_gallery/'.$gallary->image)}}" alt="">
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
            <form id="addToCart-modal" class="" method="post" action="{{url('cart/add')}}">
                @csrf
                <div class="tf-product-info-variant-picker">
                    @if($productVariations->count() > 0)
                        @foreach($variationAttributes as $attributeId => $attribute)
                            <div class="variant-group mb-3">
                                <div class="variant-name fw-6 mb-2">{{ $attribute['name'] }}:</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($attribute['values'] as $index => $value)
                                        <div class="variant-item">
                                            <input type="radio" 
                                                   id="modal_attr_{{ $attributeId }}_{{ $index }}" 
                                                   name="attribute_values[{{ $attributeId }}]" 
                                                   value="{{ $value }}" 
                                                   @if($index == 0) checked @endif
                                                   onchange="fetchPriceModal()"
                                                   class="btn-check">
                                            <label class="btn btn-outline-dark btn-sm px-3" 
                                                   for="modal_attr_{{ $attributeId }}_{{ $index }}">
                                                {{ $value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        <!-- عرض السعر هنا -->
                        <div id="product-price-modal" class="tf-product-info-price">
                            <p class="quantity-title fw-6">السعر: <span id="price-value-modal"
                                                                         class="price-on-sale"> </span>
                            </p>
                            <p id="discount-section-modal" style="display: none;">
                                <span id="discounted-price-modal" class="price-on-sale"> </span></p>
                        </div>
                        <div id="stock-status-modal" class="tf-product-info-stock mt-2">
                            <!-- سيتم تحديث حالة المخزون هنا -->
                        </div>
                        <br>
                        <!-- حقول مخفية للسعر والمتغيرات -->
                        <input type="hidden" placeholder="سعر المتغير " id="hidden-price-modal"
                               name="price" value="">
                        <input type="hidden" id="hidden-discount-modal" placeholder=" سعر خصم المتغير "
                               name="discount" value="">
                        <input type="hidden" id="hidden-variation-modal" placeholder=" " name="hidden-variation"
                               value="">

                    @else
                        <input type="hidden" id="hidden-variation-modal" placeholder=" " name="hidden-variation"
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
                        <div id="stock-status-modal" class="tf-product-info-stock mt-2">
                            @if($product->quantity > 0)
                                <span class="badge bg-success">متوفر: {{ $product->quantity }}</span>
                            @else
                                <span class="badge bg-danger">غير متوفر حالياً</span>
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


                    <button id="addtocartbutton-modal" href="javascript:void(0);"
                            class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"
                            @if($productVariations->count() == 0 && $product->quantity <= 0) disabled style="background-color: #ccc; cursor: not-allowed;" @endif>
                        <span>  
                            @if($productVariations->count() == 0 && $product->quantity <= 0)
                                غير متوفر
                            @else
                                اضف الي السلة
                            @endif
                        </span></button>
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



