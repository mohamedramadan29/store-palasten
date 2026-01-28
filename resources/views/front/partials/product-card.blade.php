@php
    $productVariations = \App\Models\admin\ProductVartions::where('product_id', $product['id'])->get();
    $variationAttributes = [];
    if ($productVariations->count() > 0) {
        foreach ($productVariations as $variation) {
            $variationValues = \App\Models\admin\VartionsValues::where('product_variation_id', $variation->id)->get();
            foreach ($variationValues as $value) {
                if (isset($value->attribute)) {
                    $variationAttributes[$value->attribute_id]['name'] = $value->attribute->name;
                    $variationAttributes[$value->attribute_id]['values'][] = $value->attribute_value_name;
                }
            }
        }
        foreach ($variationAttributes as $key => $attribute) {
            $variationAttributes[$key]['values'] = array_unique($attribute['values']);
        }
    }
@endphp

<div class="card-product {{ $productVariations->count() > 0 ? 'has-variants' : '' }}" id="product-card-{{ $product['id'] }}">
    <div class="card-product-wrapper">
        <a href="{{ url('product/' . $product['slug']) }}" class="product-img aspect-ratio-box">
            <div class="card-variant-overlay"></div>
            <img class="lazyload img-product main-card-img"
                data-src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                alt="{{ $product['name'] }}">
            @if ($product->gallary && $product->gallary->first())
                <img class="lazyload img-hover"
                    data-src="{{ asset('assets/uploads/product_gallery/' . $product->gallary->first()->image) }}"
                    src="{{ asset('assets/uploads/product_gallery/' . $product->gallary->first()->image) }}"
                    alt="{{ $product['name'] }}">
            @else
                <img class="lazyload img-hover"
                    data-src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                    src="{{ asset('assets/uploads/product_images/' . $product['image']) }}"
                    alt="{{ $product['name'] }}">
            @endif
        </a>
        <div class="list-product-btn">
            <form id="wishlistForm_{{ $product['id'] }}" method="post" action="{{ url('wishlist/store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <button type="button" 
                    onclick="toggleWishlist(this, {{ $product['id'] }})"
                    class="box-icon bg_white wishlist btn-icon-action {{ in_array($product['id'], $wishlistProducts) ? 'in-wishlist' : '' }}">
                    <span class="icon icon-heart"></span>
                    <span class="tooltip"> اضف الي المفضلة </span>
                    <span class="icon icon-heart"></span>
                </button>
            </form>
            <button data-id="{{ $product->id }}" href="" data-bs-toggle="modal"
                class="box-icon bg_white quickview tf-btn-loading btn-quick-view">
                <span class="icon icon-view"></span>
                <span class="tooltip"> مشاهدة </span>
            </button>
        </div>
    </div>
    <div class="text-center card-product-info">
        <a href="{{ url('product/' . $product['slug']) }}" class="title link text-truncate d-block">
            {{ $product['name'] }}
        </a>
        
        <div class="mb-2 text-center price-container">
            @if (isset($product['discount']) && $product['discount'] != null)
                <span class="price main_price card-price">{{ $product['discount'] }} {{ $storeCurrency }}</span>
                <span class="price old_price card-old-price">{{ $product['price'] }} {{ $storeCurrency }}</span>
            @else
                <span class="price main_price card-price">{{ $product['price'] }} {{ $storeCurrency }}</span>
            @endif
        </div>

        <form id="addToCartForm_{{ $product['id'] }}" method="post" action="{{ url('cart/add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
            <input type="hidden" name="number" value="1">
            <input type="hidden" name="price" class="hidden-price" value="{{ $product['discount'] ?? $product['price'] }}">
            <input type="hidden" name="hidden-variation" class="hidden-variation-id" value="">

            @if ($productVariations->count() > 0)
                <div class="mb-3 card-variant-picker">
                    @foreach ($variationAttributes as $attributeId => $attribute)
                        <div class="mb-2 variant-group">
                            <div class="mb-1 small fw-6">{{ $attribute['name'] }}:</div>
                            <div class="flex-wrap gap-1 d-flex justify-content-center">
                                @foreach ($attribute['values'] as $index => $value)
                                    <input type="radio" 
                                           id="attr_{{ $product['id'] }}_{{ $attributeId }}_{{ $index }}" 
                                           name="attribute_values[{{ $attributeId }}]" 
                                           value="{{ $value }}" 
                                           @if($index == 0) checked @endif
                                           onchange="fetchCardPrice({{ $product['id'] }})"
                                           class="btn-check card-variant-input">
                                    <label class="px-2 py-0 btn btn-outline-dark btn-sm fs-12" 
                                           for="attr_{{ $product['id'] }}_{{ $attributeId }}_{{ $index }}">
                                        {{ $value }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <button type="button" onclick="addToCart({{ $product['id'] }})" class="add-to-cart w-100">
                اضف الي السلة
            </button>
        </form>
    </div>
</div>

<style>
    .aspect-ratio-box {
        position: relative;
        width: 100%;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        overflow: hidden;
        display: block;
    }
    .aspect-ratio-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-variant-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.7);
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 11px;
        color: #000;
        z-index: 5;
        display: none;
        backdrop-filter: blur(2px);
        border: 1px solid rgba(255,255,255,0.5);
        pointer-events: none;
    }
    .btn-check:checked + .btn-outline-dark {
        background-color: #000;
        color: #fff;
    }
    .fs-12 { font-size: 12px !important; }
    .card-variant-picker label { 
        min-width: 30px; 
        border-radius: 2px;
        border-color: #ddd;
        color: #555;
    }
    .card-product-info .add-to-cart {
        margin-top: 10px;
        padding: 8px;
        font-size: 14px;
    }
</style>
