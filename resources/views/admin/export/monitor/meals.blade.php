@component('admin.export.pdf', ['data' => $data])
@slot('content')
    {{-- ?? meals --}}
    <div>
        @if ($data['meals']->isNotEmpty())
            @component('admin.export.components.general-meals-template', ['meals' => $data['meals']])@endcomponent
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
                {{trans('translation.no-data')}}
            </div>
        @endif
    </div>
    @endslot
@endcomponent
