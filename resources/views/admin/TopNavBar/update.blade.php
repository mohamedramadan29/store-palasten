<div class="modal fade" id="edit_attribute_{{$nav['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> تعديل المحتوي  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('admin/top-navbar/update/'.$nav['id'])}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for=""> المحتوي </label>
                        <input required type="text" name="content" class="form-control" value="{{$nav['content']}}">
                    </div>
                    <div class="mb-3">
                        <label for=""> اضافة رابط </label>
                        <input type="text" name="link" class="form-control" value="{{$nav['link']}}">
                    </div>
                    <div class="mb-3">
                        <label for=""> الزر  </label>
                        <input type="text" name="button" class="form-control" value="{{$nav['button']}}">
                    </div>
                    <div class="mb-3">
                        <label for=""> حالة التفعيل  </label>
                        <select name="status" id="status" class="form-control">
                            <option value=""> -- حدد حالة التفعيل   -- </option>
                            <option @if($nav['status'] == 1) selected @endif value="1"> مفعل  </option>
                            <option @if($nav['status'] == 0) selected @endif value="0"> غير مفعل  </option>
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
