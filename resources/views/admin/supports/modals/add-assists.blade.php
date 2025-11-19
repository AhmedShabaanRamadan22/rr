@component('modals.add-modal-template',['modalName'=>'assists'])
    <input type="hidden" name="support_id" value="{{$support->id}}">
    @component('components.inputs.select-input',['columnName'=>'assist_from','col'=>'6','margin'=> 'mb-3',"columnOptions" => $assist_options])@endcomponent
    @component('components.inputs.select-input',['columnName'=>'assistant_id','col'=>'6','margin'=> 'mb-3',"columnOptions" => $assist_options])@endcomponent
    @component('components.inputs.number-input',['columnName'=>'quantity','col'=>'6'])
        @slot('info')    
            <small>{{trans('translation.remained-quantity') . ': ' . $support->remaining_quantity}}</small>
        @endslot 
    @endcomponent
@endcomponent