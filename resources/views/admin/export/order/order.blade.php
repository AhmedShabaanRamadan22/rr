@component('admin.export.pdf', ['data' => $data])
    @slot('content')
    @component('admin.export.order_details.facility-general-info', [
        'organization' => $data['organization_data'],
        'facility' => $data['facility'],
        'model_notes' => $data['body_content'],
        'rows' => [
            'رمز الطلب' => $data['body_content']->code,
            'حالة الطلب' => $data['body_content']->status->name,
            'تاريخ إنشاء الطلب' => \Carbon\Carbon::parse($data['body_content']->created_at)->format('h:i:sa | Y/m/d'),
            ]
    ])@endcomponent
        <pagebreak />
        @component('admin.export.facility.sections.info-facility',['data'=>['body_content'=>$data['facility']], 'organization' => $data['organization_data']])@endcomponent
        @component('admin.export.facility.sections.employee-table',['data'=>['body_content'=>$data['facility'],'employee_attachment_lables'=>$data['employee_attachment_lables']]])@endcomponent
        @component('admin.export.components.attachment', [
            'label' => 'مرفقات المنشأة',
            'organization' => [],
            'attachments' => $data['facility']->attachments,
        ])
        @endcomponent
        <pagebreak />
        <div class="">
            <h4 style="text-align: center;padding-top: 5%">بيانات مالك المنشأة</h4>
            <table class="body-table" style="width:100%">

                <tr>
                    <th>#</th>
                    <th>التعريف</th>
                    <th style="text-align:center">المعلومة</th>
                </tr>
                @forelse ($order_rows = [
                    [
                        'label' => 'اسم مالك المنشأة',
                        'value' => $data['body_content']->user->name,
                    ],
                    [
                        'label' => 'البريد الإلكتروني',
                        'value' => $data['body_content']->user->email,
                    ],
                    [
                        'label' => 'رقم الجوال',
                        'value' => $data['body_content']->user->phone,
                    ],
                    [
                        'label' => 'الجنسية',
                        'value' => $data['body_content']->user->nationality_name,
                    ],
                    [
                        'label' => 'تاريخ الميلاد',
                        'value' => $data['body_content']->user->birthday,
                    ],
                    [
                        'label' => 'رقم الهوية',
                        'value' => $data['body_content']->user->national_id,
                    ],
                    [
                        'label' => 'تاريخ إنتهاء الهوية',
                        'value' => $data['body_content']->user->national_id_expired,
                    ],
                    [
                        'label' => 'مصدر الهوية',
                        'value' => $data['body_content']->user->national_source_name,
                    ],
                    [
                        'label' => 'صورة الهوية',
                        'value' => $data['body_content']->user->national_id_attachment,
                        'has_link' => true,
                    ],
                    [
                        'label' => 'الصورة الشخصية',
                        'value' => $data['body_content']->user->profile_photo,
                        'has_link' => true,
                    ],
                ] as $order_row)
                <tr>
                    <th>{{++$loop->index}}</th>
                    <td>{{$order_row['label']}}</td>
                    @if(isset($order_row['has_link']) && $order_row['has_link'])
                        @if ($order_row['value'])
                            @component('admin.export.components.barcode-td', ['url' => $order_row['value']])@endcomponent
                        @else
                            <td style="text-align:center">
                                لا يوجد
                            </td>
                        @endif
                    @else
                        <td style="text-align:center">
                            {{ $order_row['value'] }}
                        </td>
                    @endif
                </tr>
                    
                @empty
                    
                @endforelse
                {{-- <tr>
                    <th>1</th>
                    <td>اسم مالك المنشأة</td>
                    <td style="text-align:center">{{ $data['body_content']->user->name }}</td>
                </tr>
                <tr>
                    <th>2</th>
                    <td>رقم الهوية</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->user->national_id }}</td>
                </tr>
                <tr>
                    <th>3</th>
                    <td>البريد الإلكتروني</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->user->email }}</td>
                </tr>
                <tr>
                    <th>4</th>
                    <td>رقم الجوال</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->user->phone }}</td>
                </tr>

                <tr>
                    <th>5</th>
                    <td>الجنسية</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->user->nationality_name }}</td>
                </tr>
                <tr>
                    <th>6</th>
                    <td>تاريخ إنتهاء الهوية</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->user->national_id_expired }}</td>
                </tr>
                <tr>
                    <th>7</th>
                    <td>تاريخ الميلاد</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->user->birthday }}</td>
                </tr>
                <tr>
                    <th>8</th>
                    <td>صورة الهوية</td>
                    <td style="text-align:center">
                        @if ($data['body_content']->user->national_id_attachment)
                            <a href="{{ $data['body_content']->user->national_id_attachment }}">
                                اضغط هنا
                            </a>
                        @else
                            لا يوجد
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>9</th>
                    <td>الصورة الشخصية</td>
                    <td style="text-align:center">
                        @if ($data['body_content']->user->profile_photo)
                            <a href="{{ $data['body_content']->user->profile_photo }}">
                                اضغط هنا
                            </a>
                        @else
                            لا يوجد
                        @endif
                    </td>
                </tr> --}}

            </table>
        </div>
        @component('admin.export.components.notes', [
            'notes' => $data['body_content']->notes,
        ])
        @endcomponent
    @endslot
@endcomponent
