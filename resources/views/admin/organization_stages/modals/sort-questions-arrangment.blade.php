@component('modals.sort-modal-template', [
        'modalName' => 'questions',
        'modalRoute' => 'questions',
    ])
    @component('components.sort-model', [
        'modalName' => $modalName = 'questions',
        'modalRoute' => 'admin.'.str_replace('_', '-', $modalName) . '.datatable',
        'questions' => $questions??null,
        'questionableId'=> $organization_stage->id,
        'questionableType'=> 'OrganizationStage',
        ])
    @endcomponent
@endcomponent
