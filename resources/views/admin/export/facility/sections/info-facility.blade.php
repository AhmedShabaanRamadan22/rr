{{-- <div class="">
    <div style="">
        <div style="text-align: center;padding-left: 3%;padding-right: 3%">
            <h3>معلومات عامة عن المنشأة</h3>
            <div style="border-bottom: 0.1% solid #CDCDCD;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr style="background-color: #f9f9f9;">
                        <th style="width:50%; text-align: right; padding: 10px;">التعريف</th>
                        <th style="width:50%;text-align: right; padding: 10px;">المعلومة</th>
                    </tr>
                </table>
            </div>
            @component('admin.export.components.table-child', [
                'key' => 'اسم المنشأة',
                'value' => $data['body_content']->name,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'رقم السجل التجاري',
                'value' => $data['body_content']->registration_number,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'تاريخ الإصدار',
                'value' => $data['body_content']->version_date . '  الموافق  ' . $data['body_content']->version_date_hj,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'تاريخ إنتهاء السجل التجاري',
                'value' => $data['body_content']->end_date . '  الموافق  ' . $data['body_content']->end_date_hj,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'مصدر السجل التجاري',
                'value' => $data['body_content']->registration_source_name,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'رقم رخصة مزاولة المهنة',
                'value' => $data['body_content']->license,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'تاريخ إنتهاء الرخصة',
                'value' => $data['body_content']->license_expired . '  الموافق  ' . $data['body_content']->license_expired_hj,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'الطاقة الاستيعابية المصرّحة',
                'value' => $data['body_content']->capacity,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'شهادة الرقم الضريبي',
                'value' => $data['body_content']->tax_certificate,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'عدد الطباخين',
                'value' => $data['body_content']->chefs_number,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'عدد الموظفين',
                'value' => $data['body_content']->employee_number,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'مساحة المطبخ',
                'value' => $data['body_content']->kitchen_space,
            ])
            @endcomponent
            <h3>معلومات عنوان المنشأة</h3>
            <div style="border-bottom: 0.1% solid #CDCDCD;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr style="background-color: #f9f9f9;">
                        <th style="width:50%; text-align: right; padding: 10px;">التعريف</th>
                        <th style="width:50%;text-align: right; padding: 10px;">المعلومة</th>
                    </tr>
                </table>
            </div>
            @component('admin.export.components.table-child', [
                'key' => 'المدينة',
                'value' => $data['body_content']->city,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'الحيّ',
                'value' => $data['body_content']->district,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'اسم الشارع',
                'value' => $data['body_content']->street_name,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'رقم المبنى',
                'value' => $data['body_content']->building_number,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'الرمز البريدي',
                'value' => $data['body_content']->postal_code,
            ])
            @endcomponent
            @component('admin.export.components.table-child', [
                'key' => 'الرقم الفرعي',
                'value' => $data['body_content']->sub_number,
            ])
            @endcomponent
        </div>
        <div style="margin-top: 1%"></div>
    </div>
</div> --}}

<div class="">
    <h4 style="text-align: center;padding-top: 5%">معلومات عامة عن المنشأة</h4>
    <table class="body-table" style="width:100%">
        <tr>
            <th>#</th>
            <th>التعريف</th>
            <th style="text-align:center">المعلومة</th>
        </tr>
        @forelse ($facility_rows = [
            [
                'label' => 'اسم المنشأة',
                'value' => $data['body_content']->name,
            ],
            [
                'label' => 'رقم السجل التجاري',
                'value' => $data['body_content']->registration_number,
            ],
            [
                'label' => 'تاريخ إصدار السجل التجاري',
                'value' => $data['body_content']->version_date . '  الموافق  ' . $data['body_content']->version_date_hj,
            ],
            [
                'label' => 'تاريخ انتهاء السجل التجاري',
                'value' => $data['body_content']->end_date . '  الموافق  ' . $data['body_content']->end_date_hj,
            ],
            [
                'label' => 'مصدر السجل التجاري',
                'value' => $data['body_content']->registration_source_name,
            ],
            [
                'label' => 'رقم رخصة مزاولة المهنة',
                'value' => $data['body_content']->license,
            ],
            [
                'label' => 'تاريخ إنتهاء الرخصة',
                'value' => $data['body_content']->license_expired . '  الموافق  ' . $data['body_content']->license_expired_hj,
            ],
            [
                'label' => 'الطاقة الاستيعابية المصرّحة',
                'value' => $data['body_content']->capacity,
            ],
            [
                'label' => 'شهادة الرقم الضريبي',
                'value' => $data['body_content']->tax_certificate,
            ],
            [
                'label' => 'عدد الطباخين',
                'value' => $data['body_content']->chefs_number,
            ],
            [
                'label' => 'عدد الموظفين',
                'value' => $data['body_content']->employee_number,
            ],
            [
                'label' => 'مساحة المطبخ',
                'value' => $data['body_content']->kitchen_space,
            ],
        ] as $facility_row)
            <tr>
                <th>{{++$loop->index}}</th>
                <td>{{$facility_row['label']}}</td>
                <td style="text-align:center">
                    @if(isset($facility_row['has_link']) && $facility_row['has_link'])
                        @if ($facility_row['value'])
                            @component('admin.export.components.barcode-td', ['url' => $facility_row['value'], 'organization' => $organization, 'index' => ++$loop->index])@endcomponent
                        @else
                            لا يوجد
                        @endif
                    @else
                    {{ $facility_row['value'] }}
                    @endif
                </td>
            </tr>
                
        @empty
            
        @endforelse
    </table>
</div>
<pagebreak/>
<div class="">
    <h4 style="text-align: center;padding-top: 5%">معلومات العنوان الوطني للمنشأة</h4>
    <table class="body-table" style="width:100%">
        <tr>
            <th>#</th>
            <th>التعريف</th>
            <th style="text-align:center">المعلومة</th>
        </tr>
        @forelse ($address_rows = [
            [
                'label' => 'المدينة',
                'value' => $data['body_content']->city->name,
            ],
            [
                'label' => 'الحيّ',
                'value' => $data['body_content']->district->name,
            ],
            [
                'label' => 'اسم الشارع',
                'value' => $data['body_content']->street_name,
            ],
            [
                'label' => 'رقم المبنى',
                'value' => $data['body_content']->building_number,
            ],
            [
                'label' => 'الرمز البريدي',
                'value' => $data['body_content']->postal_code,
            ],
            [
                'label' => 'الرقم الفرعي',
                'value' => $data['body_content']->sub_number,
            ],
        ] as $address_row)
            <tr>
                <th>{{++$loop->index}}</th>
                <td>{{$address_row['label']}}</td>
                @if(isset($address_row['has_link']) && $address_row['has_link'])
                    @if ($address_row['value'])
                        @component('admin.export.components.barcode-td', ['url' => $address_row['value']])@endcomponent
                    @else
                        <td style="text-align:center">
                            لا يوجد
                        </td>
                    @endif
                @else
                    <td style="text-align:center">
                        {{ $address_row['value'] }}
                    </td>
                @endif
            </tr>
                
        @empty
            
        @endforelse
    </table>
</div>
