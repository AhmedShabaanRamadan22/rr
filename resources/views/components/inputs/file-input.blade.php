@php
$is_attachment_label_model = isset($attachment_label) ;   
$input = "input_";
$input .= $is_attachment_label_model ? $attachment_label->label : $columnName;
$multiple = isset($multiple) ? 'multiple' : '' ;
$is_required = $is_attachment_label_model ? ($attachment_label->is_required == '1'? 'required':'') : ($is_required??'required');
@endphp
<div class="col-md-{{$col??3}} {{$margin??'mb-2'}}">
    <label for="{{$input}}" class=" form-label">
        {{ $is_attachment_label_model ? $attachment_label->placeholder : trans('translation.'.(str_replace('_','-',$columnName))) }}
        @if ($is_attachment_label_model ? $attachment_label->is_required : !isset($is_required))
        <span class="text-danger">*</span>
        @endif    
    </label>
    <input type="file" class="form-control {{ !isset($is_required) ? 'check-empty-input':'' }}" id="{{$input}}" name="{{$name}}" {{$multiple}} {{$is_required}}>
</div>