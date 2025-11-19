@component('modals.add-modal-template',['modalName'=>'sectors'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
@foreach ($columnSectors as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @if ($type == "file")
    @component('components.inputs.file-input',['attachment_label'=>$sector_attachment,'col'=>'6','margin'=> 'mb-3', 'name' => 'attachments[' . $sector_attachment->id . ']']) @endcomponent
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3', "columnOptions" => ($optionSectors??null),"columnSubtextOptions" => ($subtextOptionSectors??null), 'is_required'=>$sector_not_required_columns[$column]??null]) @endcomponent
@endforeach

@endcomponent