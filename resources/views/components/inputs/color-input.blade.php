<div class="col-md-{{$col??3}} {{$margin??'mb-2'}}">
    <label for="input_{{$columnName}}" class=" form-label">
        {{ trans('translation.'.(str_replace('_','-',$columnName))) }}
        @if (!isset($is_required))
            <span class="text-danger">*</span>
        @endif 
    </label>
    <input type="color" class="form-control form-control-color {{ !isset($is_required) ? 'check-empty-input':'' }}" id="input_{{$columnName}}" name="{{$columnName}}" placeholder="{{ trans('translation.'.(str_replace('_','-',$columnName))) }}" {{$is_required??'required'}} value="{{$modelItem[$columnName]??""}}">
</div>