@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        <h4 style="text-align: center;font-size: 20px; font-weight: bold; background-color: {{$data['organization_data']->primary_color}}; color: white; padding: .5cm">الملخص التنفيذي لمنظمة {{$data['organization_data']->name}} يوم: {{$data['date']}}</h4>

        @if (count($data['data']) > 0)
            @component('admin.export.components.final-report-template', ['items' => $data['data'], 'dangers' => $data['dangers'], 'meal_statuses' => $data['meal_statuses'], 'key_label' => 'مركز'])@endcomponent
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
