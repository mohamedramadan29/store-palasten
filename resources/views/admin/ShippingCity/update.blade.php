<div class="modal fade" id="edit_attribute_{{$city['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> تعديل مدينة الشحن  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('admin/city/update/'.$city['id'])}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <label for=""> اسم المدينة </label>
                    <input type="text" name="city" required class="form-control" value="{{$city['city']}}">
                    </div>
                    <div class="mb-3">
                        <label for=""> سعر الشحن  </label>
                        <input type="number" required name="price" class="form-control" value="{{$city['price']}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> رجوع</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
