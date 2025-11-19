@component('modals.add-modal-template',['modalName'=>'question-bank-organizations'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
@foreach ($questions_bank_inputs as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3', "columnOptions" => ($questions_bank_options??null),"columnSubtextOptions" => ($subtextOptionSectors??null)]) @endcomponent
@endforeach

@endcomponent