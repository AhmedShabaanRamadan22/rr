@component('admin.export.pdf', ['data' => $data])
@slot('content')
    {{-- ?? tickets --}}
    <div>
        @if ($data['tickets']->isNotEmpty())
            @component('admin.export.components.ticket-template', ['tickets' => $data['tickets'], 'organization_data' => $data['organization_data'], 'monitor_reports' => true])@endcomponent
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
                {{trans('translation.no-data')}}
            </div>
        @endif
    </div>
    @endslot
@endcomponent
