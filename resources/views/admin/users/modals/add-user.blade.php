@component('modals.add-modal-template',['modalName'=>'users', 'modalMaxHeight'=>'90vh'])
    @component('components.inputs.text-input',['columnName'=>'name','col'=>'12','margin'=>'mb-3']) @endcomponent
    @component('components.inputs.number-input',['columnName'=>'phone','col'=>'6','margin'=>'mb-3', 'error' => 'number-regex-error', 'regex' => '^5\d{8}$', 'placeholder' => '5xxxxxxxx']) @endcomponent
    @component('components.inputs.email-input',['columnName'=>'email','col'=>'6','margin'=>'mb-3', 'error' => 'email-regex-error', 'regex' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$']) @endcomponent
    @component('components.inputs.password-input',['columnName'=>'password','col'=>'6','margin'=>'mb-3', 'is_required'=> ' ']) @endcomponent
    @component('components.inputs.number-input',['columnName'=>'national_id','col'=>'6','margin'=>'mb-3', 'error' => 'national_id-regex-error', 'regex' => '^\d{10}$']) @endcomponent
    @foreach ($required_attachments as $attachment)
    @component('components.inputs.file-input',['attachment_label'=>$attachment,'col'=>'6','margin'=> 'mb-3', 'name' => 'attachments[' . $attachment->id . ']']) @endcomponent
    @endforeach
    {{-- @component('components.inputs.file-input',['columnName'=>'national_id_attachment', 'name'=>'national_id_attachment', 'col'=>'6','margin'=>'mb-3']) @endcomponent --}}
    {{-- @component('components.inputs.file-input',['columnName'=>'profile_photo', 'name'=>'profile_photo', 'col'=>'6','margin'=>'mb-3']) @endcomponent --}}
    @component('components.inputs.text-input',['columnName'=>'address','col'=>'6','margin'=>'mb-3', 'is_required' => '']) @endcomponent
    @component('components.inputs.select-input',['columnName'=>'nationality','col'=>'6','margin'=>'mb-3', 'columnOptions'=>$columnOptions]) @endcomponent
    @component('components.inputs.select-input',['columnName'=>'role_name','col'=>'6','margin'=>'mb-3', 'columnOptions'=>$columnOptions, 'is_multiple'=>'multiple', 'name'=>'role[]','is_required' => '']) @endcomponent
    @component('components.inputs.date-input',['columnName'=>'birthday','col'=>'6','margin'=>'mb-3'])@endcomponent
    @component('components.inputs.date-input',['columnName'=>'national_id_expired','col'=>'6','margin'=>'mb-3'])@endcomponent
    <small class="text-info"><i class="mdi mdi-information-outline"></i> {{ trans('translation.default-password') }}</small>
@endcomponent