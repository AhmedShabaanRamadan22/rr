    <div class="{{$ColDiv ?? "col-lg-6 text-end "}}">
        {{$slot}}
        <button id="add-{{$title}}-button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="{{'#add' . $title}}" data-services="{{$data ?? ''}}" {{$disabled ?? ''}}>
            <i class="mdi mdi-plus align-baseline me-1"></i>
            {{ trans('translation.add-new-' . $title) }}</button>
    </div>
