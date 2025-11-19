<div class="col-md-{{$col??3}} {{$margin??'mb-2'}}">
    <div class="form-group">
        <label for="input_{{$columnName}}" class="form-label">
            {{ trans('translation.' . (str_replace('_','-',$columnName)) ) }}
            @if (!isset($is_required))
            <span class="text-danger">*</span>
            @endif 
        </label>
        <input class=" d-none" type="checkbox" role="switch" name="{{$columnName}}" checked value="0">
        <div class="form-group">
            <div class="form-check form-switch form-switch-md">
                <input class="form-check-input {{ !isset($is_required) ? 'check-empty-input':'' }}" type="checkbox" role="switch" id="input_{{$columnName}}" name="{{$columnName}}"  {{1 == ($modelItem[$columnName]??0) ? 'checked':''}} value="1">
            </div>
        </div>
    </div>
</div>