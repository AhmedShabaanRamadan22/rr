@component('modals.sort-modal-template', [
        'modalName' => 'questions',
        'modalRoute' => 'questions',
    ])
    @component('components.sort-model', [
        'modalName' => $modalName = 'questions',
        'modalRoute' => 'admin.'.str_replace('_', '-', $modalName) . '.datatable',
        'questions' => $questions??null,
        'questionableId'=> $section->id,
        'questionableType'=> 'Section',
        ])
    @endcomponent
@endcomponent
