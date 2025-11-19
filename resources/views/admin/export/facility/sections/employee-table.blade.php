@if ($data['body_content']->facility_employees->count() > 0)
    <pagebreak />
    <h4 style="text-align: center;padding-top: 2%">موظفين المنشأة</h4>
    <table class="body-table" style="width:100%;margin-top: 1%">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>رقم الهوية</th>
                <th>المنصب</th>
                @foreach ($data['employee_attachment_lables'] as $attachment_label )
                    <th> {{$attachment_label->placeholder_ar}} </th>
                @endforeach
                {{-- <th>صورة الهوية</th>
                <th>صورة بطاقة العمل</th>
                <th>صورة البطاقة الصحية</th>
                <th>الصورة الشخصية</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($data['body_content']->facility_employees as $index => $employee)
                <tr>
                    <th>{{ $index + 1 }}</th>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->national_id }}</td>
                    <td>{{ $employee->position_name }}</td>
                    @foreach ($data['employee_attachment_lables'] as $attachment_label )
                        @if ($attachment = $employee->attachments()->where('attachment_label_id', $attachment_label->id)->first())
                            @component('admin.export.components.barcode-td', ['url' => $attachment->url])@endcomponent
                        @else
                            <td>
                                لا يوجد
                            </td>
                        @endif
                    @endforeach
                    {{-- <td>
                        @if ($employee->attachment_work_card_photo)
                            <a style="color: #CAB272" href="{{ $employee->attachment_work_card_photo->url }}">
                                انقر هنا
                            </a>
                        @else
                            لا يوجد
                        @endif
                    </td>
                    <td>
                        @if ($employee->attachment_health_photo)
                            <a style="color: #CAB272" href="{{ $employee->attachment_health_photo->url }}">
                                انقر هنا
                            </a>
                        @else
                            لا يوجد
                        @endif
                    </td>
                    <td>
                        @if ($employee->attachment_personal_photo)
                            <a style="color: #CAB272" href="{{ $employee->attachment_personal_photo->url }}">
                                انقر هنا
                            </a>
                        @else
                            لا يوجد
                        @endif
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
