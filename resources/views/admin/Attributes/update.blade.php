<div class="modal fade" id="edit_attribute_{{$attribute['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">  تعديل سمة المنتج   </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('admin/attribute/update/'.$attribute['id'])}}" method="post">
                @csrf
                <div class="modal-body">
                    <label for=""> اسم السمة   </label>
                    <input type="text" name="name" class="form-control" value="{{$attribute['name']}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> رجوع</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
