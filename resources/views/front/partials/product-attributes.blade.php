@foreach($variationAttributes as $attributeId => $attribute)
    <div class="variant-picker-item">
        <div class="variant-picker-label mb-2">
            {{ $attribute['name'] }}: <span class="fw-6 selected-value">{{ $attribute['values'][0] }}</span>
        </div>
        <div class="variant-picker-values">
            @foreach($attribute['values'] as $index => $value)
                <input type="radio" 
                       id="attribute_{{ $attributeId }}_{{ $index }}" 
                       name="attribute_values[{{ $attributeId }}]" 
                       value="{{ $value }}" 
                       @if($index == 0) checked @endif
                       onchange="fetchPrice(); this.closest('.variant-picker-item').querySelector('.selected-value').innerText = this.value;"
                       class="d-none">
                <label class="style-text" for="attribute_{{ $attributeId }}_{{ $index }}">
                    <p>{{ $value }}</p>
                </label>
            @endforeach
        </div>
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
<input type="hidden" id="hidden-variation" placeholder="دشقفهخر " name="hidden-variation" value="">
