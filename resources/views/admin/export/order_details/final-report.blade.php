@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        @component('admin.export.order_details.facility-general-info', ['order_sector' => $data['order_sector'], 'organization' => $data['organization_data']])@endcomponent
        <pagebreak/>
        @if (count($data['data']) > 0)
            @component('admin.export.components.final-report-template', ['items' => $data['data'], 'dangers' => $data['dangers'], 'meal_statuses' => $data['meal_statuses'], 'key_label' => 'يوم'])@endcomponent
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
