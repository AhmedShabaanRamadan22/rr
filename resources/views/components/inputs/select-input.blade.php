{{-- @dd($modelItem) --}}
<div class="col-md-{{$col??3}} {{$margin??'mb-2'}}">
    <h6>
        {{ trans('translation.'.(str_replace('_','-',$columnName))) }}
        @if (!isset($is_required))
            <span class="text-danger">*</span>
        @endif 
    </h6>
    <!-- Select2 -->
    <select class="form-control selectpicker {{ !isset($is_required) ? 'check-empty-input':'' }} mt-1" name="{{$name ?? $columnName}}" id="{{$columnName}}_filter" {{$is_multiple??''}} data-actions-box="true" data-live-search="true" title="{{trans('translation.choose-one')}}" {{$is_required??'required'}} {{$disabled??''}}>
    @forelse ($columnOptions[$columnName] as $key => $columnOption)
        <option 
            value="{{$key}}" 
            data-content="{{$columnOption . (isset($columnSubtextOptions[$columnName][$key]) ? " <small>(".$columnSubtextOptions[$columnName][$key].")</small>":"") }}" 
            {{--{{ 
            isset($modelItem) ? 
            ((is_numeric($modelItem[$foreign_column ?? $columnName]) ?
                $key == ($modelItem[$foreign_column ?? $columnName]??0) :
                in_array($key , (is_array($modelItem[$foreign_column ?? $columnName]) ? 
                    array_values($modelItem[$foreign_column ?? $columnName])??[]:
                    $modelItem[$foreign_column ?? $columnName]?->pluck('id')->toArray()??[] ))) ? 'selected':'') :
            ''
             }}--}}
            {{ 
            isset($modelItem)? 
                (isset($is_multiple) ?
                    (is_array($modelItem[$foreign_column ?? $columnName]) ? 
                        (in_array($key , $modelItem[$foreign_column ?? $columnName]) ?
                            'selected'
                        :
                            $key == ($modelItem[$foreign_column ?? $columnName]??0))
                    :
                        (gettype($modelItem[$foreign_column ?? $columnName]) == 'object' && in_array($key , ($modelItem[$foreign_column ?? $columnName]->pluck('id')->toArray()))? 
                            'selected'
                        : 
                            ''))
                :
                    ($key == ($modelItem[$foreign_column ?? $columnName]??0) ? 
                        'selected'
                    :
                        '' ))
            :
                ''
            }}
            >
            {{$columnOption}}
        </option>
        @empty
        <option value="" disabled>{{trans('translation.no-data')}}</option>
        @endforelse
    </select>
    <!-- End Select2 -->
</div>