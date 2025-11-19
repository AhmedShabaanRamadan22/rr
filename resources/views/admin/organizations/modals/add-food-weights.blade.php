@component('modals.add-modal-template',['modalName'=>'food-weights'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
@foreach ($foodWeightColumnInputs as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3', "columnOptions" => ($foodWeightColumnOptions??null)]) @endcomponent
@endforeach

@endcomponent