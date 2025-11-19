@component('modals.add-modal-template',['modalName'=>'organizations'])
    @component('components.inputs.text-input',['columnName'=>'name_ar','col'=>'6','margin'=>'mb-3']) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'name_en','col'=>'6','margin'=>'mb-3']) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'domain','col'=>'12','margin'=>'mb-3', 'error' => 'domain-regex-error', 'regex' => '^(https?|ftp):\/\/[^\s\/$.?#].[^\s]*$']) @endcomponent
@endcomponent