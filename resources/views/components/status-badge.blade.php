@props(['label','col-span' => 3, 'margin' => 'mb-2'])


<div class="col-md-{{$col}} {{$margin}}">
    <label class=" form-label">
        {{ trans('translation.'.(str_replace('_','-',$label))) }}
    </label>
    
    <br>
    <span id='{{$label}}_badge' class="badge me-2"></span>
    <div id='{{$label}}_spinner' class="spinner-border text-primary" style="width: 2rem; height: 2rem;" role="status"></div>
    <label id='{{$label}}_error' class="text-sm text-danger"></label>

</div>