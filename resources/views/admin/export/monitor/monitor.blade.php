@component('admin.export.pdf', ['data' => $data])
@slot('content')
    <div>
        <h4 style="text-align: center">معلومات المراقب</h4>
        <table class="body-table" style="width:100%">
            <tr>
                <th style="text-align:center">التعريف</th>
                <th style="text-align:center">المعلومة</th>
            </tr>
            <tr>
                <th style="text-align:center">اسم المراقب</th>
                <td style="text-align:center">{{$data['monitor']->name}}</td>
            </tr>
            <tr>
                <th style="text-align:center">رقم الجوال</th>
                <td style="text-align:center">{{$data['monitor']->phone}}</td>
            </tr>
            <tr>
                <th style="text-align:center">رمز المراقب</th>
                <td style="text-align:center">{{$data['monitor']->monitor->code}}</td>
            </tr>
        </table>
    </div>
    <pagebreak/>
    {{-- ?? tickets --}}
    <div>
        <h4 style="text-align: center">بلاغات المراقب</h4>
        @if ($data['tickets']->isNotEmpty())
            @component('admin.export.components.ticket-template', ['tickets' => $data['tickets'], 'organization_data' => $data['organization_data'], 'monitor_reports' => true])@endcomponent
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
                {{trans('translation.no-data')}}
            </div>
        @endif
    </div>
    {{-- ?? supports --}}
    <div>
        <h4 style="text-align: center">إسناد المراقب</h4>
        @if ($data['supports']->isNotEmpty())
            @component('admin.export.support.supports-template', ['supports' => $data['supports'], 'organization' => $data['organization_data'], 'monitor_report' => true])@endcomponent
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
                {{trans('translation.no-data')}}
            </div>
        @endif
    </div>
    <pagebreak/>
    {{-- ?? submitted forms --}}
    <div>
        <h4 style="text-align: center">الاستمارات المسلمة للمراقب</h4>
        @if ($data['submitted_forms']->isNotEmpty())
            @foreach ($data['submitted_forms'] as $submitted_form)
                @component('admin.export.components.submitted-form-template', ['submitted_form' => $submitted_form, 'answer_service' => $data['answer_service']])@endcomponent
            @endforeach
        @else
            <div style="text-align:center; font-size:large; padding: 5rem">
                {{trans('translation.no-data')}}
            </div>
        @endif
    </div>
    @endslot
@endcomponent
