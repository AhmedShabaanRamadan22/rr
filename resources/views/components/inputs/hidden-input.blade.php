
    <input type="hidden" class="form-control" id="input_{{$columnName}}" name="{{$columnName}}" placeholder="{{ trans('translation.'.(str_replace('_','-',$columnName))) }}" value="{{$hiddenValue[$columnName]?? null}}" >
