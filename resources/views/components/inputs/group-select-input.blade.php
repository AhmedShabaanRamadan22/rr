{{-- @dd($modelItem) --}}
<div class="col-md-{{$col??3}} {{$margin??'mb-2'}}">
    <h6>
        {{ trans('translation.'.(str_replace('_','-',$columnName))) }}
        @if (!isset($is_required))
            <span class="text-danger">*</span>
        @endif 
    </h6>
    <!-- Select2 -->
{{-- {{dd($columnOptions[$columnName])}} --}}

    <select class="form-control selectpicker {{ !isset($is_required) ? 'check-empty-input':'' }} mt-1" name="{{$columnName.(isset($is_multiple) && $is_multiple == 'multiple' ? '[]' : '' )}}" id="{{(str_replace('[]','',$columnName))}}_filter" {{$is_multiple??''}} data-live-search="true" title="{{trans('translation.choose-one')}}" {{$is_required??'required'}} {{$disabled??''}}>
        @php
            $previous_item = null;
        @endphp
        @forelse ($columnOptions[$columnName] as $columnOption)
            @if(!isset($previous_item))
                <optgroup label="{{ $columnOption['option_group_label'] }}">
            @elseif(isset($previous_item) && $columnOption['option_group_label'] != $previous_item['option_group_label'])
                </optgroup>
                <optgroup label="{{ $columnOption['option_group_label'] }}">
            @endif
            <option value="{{ $columnOption['id'] }}">
                {{ $columnOption['name'] }}
            </option>
            @php
                $previous_item = $columnOption;
            @endphp
            @empty
            <option value="" disabled>{{trans('translation.no-data')}}</option>
        @endforelse
        </optgroup>
    </select>
    <!-- End Select2 -->
</div>

{{-- ? an example of fetch query --}}
{{-- 'reason_dangers' => ReasonDanger::with('organization')->where('operation_type_id', 1)->orderBy('organization_id')->get()
    ->map(function ($items, $key) {
        $items->option_group_label = $items->organization?->name;
        return $items;
    })
    ->values()
    ->toArray(), --}}