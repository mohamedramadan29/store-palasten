<div class="modal fade" id="add_attribute" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">  اضافة بانر جديد  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('admin/banner/add')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for=""> صورة البانر  </label>
                        <input required type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for=""> حالة التفعيل  </label>
                        <select name="status" id="status" class="form-control">
                            <option value=""> -- حدد حالة التفعيل   -- </option>
                            <option selected value="1"> مفعل  </option>
                            <option value="0"> غير مفعل  </option>
                        </select>
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
