@component('admin.export.pdf', ['data' => $data])
@slot('content')
    {{-- ?? supports --}}
    <div>
        @if ($data['supports']->isNotEmpty())
            @component('admin.export.support.supports-template', ['supports' => $data['supports'], 'organization' => $data['organization_data'], 'monitor_report' => true])@endcomponent
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
                {{trans('translation.no-data')}}
            </div>
        @endif
    </div>
    @endslot
@endcomponent
