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
            <tr>
                <th style="text-align:center">البريد الالكتروني</th>
                <td style="text-align:center">{{$data['monitor']->email}}</td>
            </tr>
            <tr>
                <th style="text-align:center">الصورة الشخصية</th>
                @component('admin.export.components.barcode-td', ['url' => $data['monitor']->profile_photo, 'organization' => $data['organization_data']])@endcomponent
            </tr>
        </table>
    </div>
    @endslot
@endcomponent
