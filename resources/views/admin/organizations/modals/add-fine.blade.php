
@component('modals.add-modal-template',['modalName'=>'fine-organizations'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
{{-- {{dd($fine_organization_columns)}} --}}
@foreach ($fine_organization_inputs as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',
    ['columnName'=>$column,
    'col'=>$column == 'description' ? '12' : '6','margin'=> 'mb-3',
    "columnOptions" => ($fine_organization_options??null)])
    @endcomponent
@endforeach
@endcomponent
