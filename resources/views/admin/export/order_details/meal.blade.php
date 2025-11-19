@component('admin.export.pdf', ['data' => $data])
    @slot('content')
    @component('admin.export.order_details.facility-general-info', ['order_sector' => $data['order_sector'], 'organization' => $data['organization_data']])@endcomponent
    <pagebreak/>
    @if ($data['meals']->isNotEmpty())
        @component('admin.export.components.general-meals-template', ['meals' => $data['meals']])@endcomponent
        @component('admin.export.components.color-keys-template')
            @component('admin.export.components.color-keys', ['items' => $data['statuses'], 'description' => 'description'])@endcomponent
        @endcomponent
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
            {{trans('translation.no-data')}}
        </div>
        @endif
    @endslot
@endcomponent
