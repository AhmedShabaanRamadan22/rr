@component('modals.add-modal-template',['modalName'=>'classifications'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
@foreach ($columnClassifications as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3']) @endcomponent
@endforeach

@endcomponent