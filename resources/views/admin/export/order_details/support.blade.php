@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        @component('admin.export.order_details.facility-general-info', ['order_sector' => $data['order_sector'], 'organization' => $data['organization_data']])@endcomponent
        <pagebreak/>
        @if ($data['supports']->isNotEmpty())
            @component('admin.export.support.supports-template', ['supports' => $data['supports'], 'order_sector' => $data['order_sector'], 'organization' => $data['organization_data']])@endcomponent
            @component('admin.export.components.color-keys-template')
                @component('admin.export.components.color-keys', ['items' => $data['statuses'], 'description' => 'description'])@endcomponent
                @component('admin.export.components.color-keys', ['items' => $data['danger_levels'], 'description' => 'danger_description'])@endcomponent
            @endcomponent
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
                {{trans('translation.no-data')}}
            </div>
        @endif
    @endslot
@endcomponent
