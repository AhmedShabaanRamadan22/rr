<pagebreak />
{{-- <div class="">
    <div style="">
        <div class="" style="margin-top: 1%"></div>
        <div style="text-align: center;padding-left: 3%;padding-right: 3%">
            <h3>المعلومات البنكية</h3>
            <div style="border-bottom: 0.1% solid #CDCDCD;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr style="background-color: #f9f9f9;">
                        <th style="width:50%; text-align: right; padding: 10px;">التعريف</th>
                        <th style="width:50%;text-align: right; padding: 10px;">المعلومة</th>
                    </tr>
                </table>
            </div>
            @component('admin.export.components.table-child', [
                'key' => 'نوع البنك',
                'value' => optional($data['body_content']->bank_information)->bank_name,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'اسم صاحب الحساب',
                'value' => optional($data['body_content']->bank_information)->account_name,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'رقم الآيبان',
                'value' => optional($data['body_content']->bank_information)->iban,
            ])
            @endcomponent
            <h3>معلومات مالك المنشأة</h3>
            <div style="border-bottom: 0.1% solid #CDCDCD;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr style="background-color: #f9f9f9;">
                        <th style="width:50%; text-align: right; padding: 10px;">التعريف</th>
                        <th style="width:50%;text-align: right; padding: 10px;">المعلومة</th>
                    </tr>
                </table>
            </div>
            @component('admin.export.components.table-child', [
                'key' => 'اسم المستخدم',
                'value' => $data['body_content']->user->name,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'رقم الجوال',
                'value' => $data['body_content']->user->phone,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'البريد الإلكتروني',
                'value' => $data['body_content']->user->email,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'تاريخ الميلاد',
                'value' => $data['body_content']->user->birthday,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'الجنسية',
                'value' => $data['body_content']->user->nationality_name,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'رقم الهوية',
                'value' => $data['body_content']->user->national_id,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'تاريخ إنتهاء الهوية',
                'value' =>
                $data['body_content']->user->national_id_expired .
                '  الموافق  ' .
                $data['body_content']->user->national_id_expired_hj,
                ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'مصدر الهوية',
                'value' => $data['body_content']->user->national_source_name,
            ])
            @endcomponent
        </div>
        <div style="margin-top: 1%"></div>
    </div>
</div> --}}
<div class="">
    <h4 style="text-align: center;padding-top: 5%">المعلومات البنكية</h4>
    <table class="body-table" style="width:100%">
        <tr>
            <th>#</th>
            <th>التعريف</th>
            <th style="text-align:center">المعلومة</th>
        </tr>
        @forelse ($bank_rows = [
            [
                'label' => 'نوع البنك',
                'value' => $data['body_content']->bank_information?->bank_name ?? trans('translation.no-data'),
            ],
            [
                'label' => 'اسم صاحب الحساب',
                'value' => $data['body_content']->bank_information?->account_name ?? trans('translation.no-data'),
            ],
            [
                'label' => 'رقم الآيبان',
                'value' => $data['body_content']->bank_information?->iban ?? trans('translation.no-data'),
            ],
        ] as $bank_row)
            <tr>
                <th>{{++$loop->index}}</th>
                <td>{{$bank_row['label']}}</td>
                <td style="text-align:center">
                    @if(isset($bank_row['has_link']) && $bank_row['has_link'])
                        @if ($bank_row['value'])
                            @component('admin.export.components.barcode-td', ['url' => $bank_row['value'], 'organization' => $organization, 'index' => ++$loop->index])@endcomponent
                        @else
                            لا يوجد
                        @endif
                    @else
                    {{ $bank_row['value'] }}
                    @endif
                </td>
            </tr>
                
        @empty
            
        @endforelse
    </table>
</div>
<div class="">
    <h4 style="text-align: center;padding-top: 5%">معلومات مالك المنشأة</h4>
    <table class="body-table" style="width:100%">
        <tr>
            <th>#</th>
            <th>التعريف</th>
            <th style="text-align:center">المعلومة</th>
        </tr>
        @forelse ($owner_rows = [
            [
                'label' => 'اسم المستخدم',
                'value' => $data['body_content']->user->name,
            ],
            [
                'label' => 'رقم الجوال',
                'value' => $data['body_content']->user->phone,
            ],
            [
                'label' => 'البريد الالكتروني',
                'value' => $data['body_content']->user->email,
            ],
            [
                'label' => 'تاريخ الميلاد',
                'value' => $data['body_content']->user->birthday,
            ],
            [
                'label' => 'الجنسية',
                'value' => $data['body_content']->user->nationality_name,
            ],
            [
                'label' => 'رقم الهوية',
                'value' => $data['body_content']->user->national_id,
            ],
            [
                'label' => 'تاريخ انتهاء الهوية',
                'value' => $data['body_content']->user->national_id_expired .
                '  الموافق  ' .
                $data['body_content']->user->national_id_expired_hj,
            ],
            [
                'label' => 'مصدر الهوية',
                'value' => $data['body_content']->user->national_source_name,
            ],
        ] as $owner_row)
            <tr>
                <th>{{++$loop->index}}</th>
                <td>{{$owner_row['label']}}</td>
                @if(isset($owner_row['has_link']) && $owner_row['has_link'])
                    @if ($owner_row['value'])
                        @component('admin.export.components.barcode-td', ['url' => $owner_row['value']])@endcomponent
                    @else
                        <td style="text-align:center">
                            لا يوجد
                        </td>
                    @endif
                @else
                    <td style="text-align:center">
                        {{ $owner_row['value'] }}
                    </td>
                @endif
            </tr>
                
        @empty
            
        @endforelse
    </table>
</div>
