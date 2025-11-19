@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        @component('admin.export.facility.sections.info-facility',['data'=>['body_content'=>$data['facility']]])@endcomponent
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
                    <td style="text-align:center">
                        @if(isset($order_row['has_link']) && $order_row['has_link'])
                            @if ($order_row['value'])
                                <a href="{{ $order_row['value'] }}">
                                    اضغط هنا
                                </a>
                            @else
                                لا يوجد
                            @endif
                        @else
                        {{ $order_row['value'] }}
                        @endif
                    </td>
                </tr>
                
                @empty
                    
                @endforelse

            </table>
        </div>
    @endslot
@endcomponent
