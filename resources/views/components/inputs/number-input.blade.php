<div class="col-md-{{$col??3}} {{$margin??'mb-2'}}">
    <label for="input_{{$columnName}}" class=" form-label">
        {{ trans('translation.'.(str_replace('_','-',$columnName))) }}
        @if (!isset($is_required))
        <span class="text-danger">*</span>
        @endif
    </label>
    {{-- <input type="number" class="form-control check-empty-input" step="{{in_array($columnName,['latitude','longitude'])?'.000000000000001':($columnName=="price"?'.01':null)}}" id="input_{{$columnName}}" name="{{$columnName}}" placeholder="{{ trans('translation.'.(str_replace('_','-',$columnName))) }}" {{$is_required??'required'}} value="{{$modelItem[$columnName]??""}}" {{$disabled??''}} {{(isset($regex) && isset($error)) ? "data-regex=$regex" : ''}}> --}}
    <input type="number" class="form-control {{ !isset($is_required) ? 'check-empty-input':'' }}" step="{{in_array($columnName,['latitude','longitude','guest_value', 'arafah_longitude', 'arafah_latitude','mark'])?'.000000000000001':($columnName=="price"?'.01':null)}}" id="input_{{$columnName}}" name="{{$columnName}}" placeholder="{{ $placeholder ?? trans('translation.' . (str_replace('_', '-', $columnName))) }}"  {{$is_required??'required'}} value="{{$modelItem[$columnName]??""}}" {{$disabled??''}} {{(isset($regex) && isset($error)) ? "data-regex=$regex" : ''}}>
    @isset($info)
        <label id="info-{{$columnName}}" class="text-sm text-info">{{$info}}</label>
    @endisset
    @isset($error)
        <label id="error-{{$columnName}}" class="text-sm text-danger d-none">{{trans('translation.' . $error)}}</label>
    @endisset
</div>
