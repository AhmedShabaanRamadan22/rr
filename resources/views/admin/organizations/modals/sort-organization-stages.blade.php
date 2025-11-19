@component('modals.sort-modal-template', [
        'modalName' => 'organization-stages',
        'modalRoute' => 'organization-stages',
    ])
    @component('components.sort-model', [
        'modalName' => $modalName = 'organization-stages',
        'modalRoute' => str_replace('_', '-', $modalName) . '.datatable',
        'organization' => $organization??null,
    ])
    @endcomponent
@endcomponent
