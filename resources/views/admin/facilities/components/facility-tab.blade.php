@component('components.custom-v-pills.template')

    {{-- التابات --}}
    @slot('pills')
        {{-- تاب معلومات المنشأة --}}
        @component('components.custom-v-pills.pill', ['active'=>'active show', 'id'=>'facility-info', 'icon'=>'ri-hotel-line'])@endcomponent
        {{-- تاب العنوان الوطني --}}
        @component('components.custom-v-pills.pill', ['active'=>'', 'id'=>'address-info', 'icon'=>'ri-home-4-line'])@endcomponent
        {{-- تاب معلومات البنك --}}
        @component('components.custom-v-pills.pill', ['active'=>'', 'id'=>'bank-info', 'icon'=>'mdi mdi-bank'])@endcomponent
        {{-- تاب معلومات إضافية --}}
        @component('components.custom-v-pills.pill', ['active'=>'', 'id'=>'more-info', 'icon'=>'mdi mdi-plus'])@endcomponent
    @endslot

    {{-- المحتوى --}}
    @slot('content')
        {{-- محتوى معلومات المنشأة --}}
        @component('components.custom-v-pills.tab-content', ['active'=>'active show', 'id'=>'facility-info'])
            @component('components.data-row', ['id'=>'facility-name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$facility->name}}@endcomponent
            @component('components.data-row', ['id'=>'registration-num', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$facility->registration_number}}@endcomponent
            @component('admin.facilities.components.date-row', ['id'=>'version_date', 'date_ad'=>$facility->version_date, 'date_hj'=>$facility->version_date_hj, 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])@endcomponent
            @component('admin.facilities.components.date-row', ['id'=>'expiration-date', 'date_ad'=>$facility->end_date, 'date_hj'=>$facility->end_date_hj, 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])@endcomponent
            @component('components.data-row', ['id'=>'registration-source', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$facility->registration_source_name}}@endcomponent
            @component('components.data-row', ['id'=>'license', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$facility->license}}@endcomponent
            @component('admin.facilities.components.date-row', ['id'=>'license-expiration-date', 'date_ad'=>$facility->license_expired, 'date_hj'=>$facility->license_expired_hj, 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])@endcomponent
            @component('components.data-row', ['id'=>'capacity', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$facility->capacity}}@endcomponent
            @component('components.data-row', ['id'=>'tax-certificate', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$facility->tax_certificate}}@endcomponent
        @endcomponent

        {{-- محتوى العنوان الوطني --}}
        @component('components.custom-v-pills.tab-content', ['active'=>'', 'id'=>'address-info'])
            @component('components.data-row', ['id'=>'city']){{$facility->city_name}}@endcomponent
            @component('components.data-row', ['id'=>'district']){{$facility->district_name}}@endcomponent
            @component('components.data-row', ['id'=>'street-name']){{$facility->street_name}}@endcomponent
            @component('components.data-row', ['id'=>'building-number']){{$facility->building_number}}@endcomponent
            @component('components.data-row', ['id'=>'postal-code']){{$facility->postal_code}}@endcomponent
            @component('components.data-row', ['id'=>'sub-number']){{$facility->sub_number}}@endcomponent
        @endcomponent

        {{-- محتوى معلومات البنك --}}
        @component('components.custom-v-pills.tab-content', ['active'=>'', 'id'=>'bank-info'])
            @component('components.data-row', ['id'=>'account-name']){{is_found($facility->iban->account_name)}}@endcomponent
            @component('components.data-row', ['id'=>'iban']){{is_found($facility->iban->iban)}}@endcomponent
            @component('components.data-row', ['id'=>'bank-name']){{is_found($facility->iban->bank->name)}}@endcomponent
        @endcomponent

        {{-- محتوى معلومات إضافية --}}
        @component('components.custom-v-pills.tab-content', ['active'=>'', 'id'=>'more-info'])
            @component('components.data-row', ['id'=>'chefs-number']){{$facility->chefs_number}}@endcomponent
            @component('components.data-row', ['id'=>'employee-number']){{$facility->employee_number}}@endcomponent
            @component('components.data-row', ['id'=>'kitchen-space']){{$facility->kitchen_space}}@endcomponent
        @endcomponent

    @endslot

@endcomponent
