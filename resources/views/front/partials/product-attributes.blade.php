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
<input type="hidden" id="hidden-variation" placeholder="دشقفهخر " name="hidden-variation" value="">
