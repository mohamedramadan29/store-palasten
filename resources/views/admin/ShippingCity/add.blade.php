<div class="modal fade" id="add_attribute" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> اضف مدينة شحن جديدة   </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('admin/city/add')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="">اسم المدينة  </label>
                    <input required type="text" name="city" class="form-control"  value="">
                    </div>
                    <div class="mb-3">
                        <label for=""> سعر الشحن  </label>
                        <input required type="number" name="price" class="form-control"  value="">
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
