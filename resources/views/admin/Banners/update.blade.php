<div class="modal fade" id="edit_attribute_{{$banner['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> تعديل البانر  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('admin/banner/update/'.$banner['id'])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="banner_id" value="{{$banner['id']}}">
                        <label for=""> تعديل صورة البانر  </label>
                        <input type="file" name="image" class="form-control" value="">
                        <img width="80px" height="80px" class="img-product img-thumbnail" src="{{asset('assets/uploads/banners/'.$banner['image'])}}" alt="">
                        <img src="" class="img-thumbnail img-product" alt="">
                    </div>
                    <div class="mb-3">
                        <label for=""> حالة التفعيل  </label>
                        <select name="status" id="status" class="form-control">
                            <option value=""> -- حدد حالة التفعيل   -- </option>
                            <option @if($banner['status'] == 1) selected @endif value="1"> مفعل  </option>
                            <option @if($banner['status'] == 0) selected @endif value="0"> غير مفعل  </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> رجوع</button>
                    <button type="submit" class="btn btn-primary"> تعديل  </button>
                </div>
            </form>
        </div>
    </div>
</div>
