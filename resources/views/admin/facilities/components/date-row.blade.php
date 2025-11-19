@component('components.data-row', ['id'=>$id, 'label_col' => $label_col ?? null, 'content_col' => $content_col ?? null])

    @if (isset($date_ad))
        {{$date_ad . ' ' . trans('translation.AD')}}
        @if (isset($date_hj))
        <span class="badge bg-primary mx-1"><small>
            {{$date_hj . ' ' . trans('translation.AH')}}
        </small></span>
        @endif
    @else
        {{trans('translation.no-data')}}
    @endif

@endcomponent