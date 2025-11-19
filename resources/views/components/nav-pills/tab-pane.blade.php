<div class="tab-pane {{$padding??'p-4'}}" id="custom-hover-{{$id}}">
    @isset($title)
    <div>
        <h4 class="text-secondary card-title">{{ trans('translation.' . $title) }}</h4>
    </div>
    @endisset
    <div class="my-3">
        {{$slot}}
    </div>
</div>