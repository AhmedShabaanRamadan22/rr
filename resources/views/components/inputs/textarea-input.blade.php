<div class="col-md-{{12}} {{$margin??'mb-2'}}">
    <label for="input_{{$columnName}}" class=" form-label">
        {{ trans('translation.'.(str_replace('_','-',$columnName))) }}
        @if (!isset($is_required))
        <span class="text-danger">*</span>
        @endif    
    </label>
    <textarea 
        id="input_{{$columnName}}"
        name="{{$columnName}}" 
        class="form-control  {{ !isset($is_required) ? 'check-empty-input':'' }}" 
        placeholder="{{ trans('translation.'.(str_replace('_','-',$columnName))) }}"
        {{$is_required??'required'}}
        {{(isset($regex) && isset($error)) ? "data-regex=$regex" : ''}}
        rows="{{ $rowsValue ?? '5' }}"
    >{{$modelItem[$foreignColumn??$columnName]??""}}</textarea>
    @isset($error)
        <label id="error-{{$columnName}}" class="text-sm text-danger d-none">{{trans('translation.' . $error)}}</label>
    @endisset
</div>
