@component('components.custom-v-pills.template')

    {{-- التابات --}}
    @slot('pills')
        {{-- تاب معلومات المرشح --}}
        @component('components.custom-v-pills.pill', ['active'=>'active show', 'id'=>'info', 'icon'=>'ri-hotel-line'])@endcomponent

        {{-- تاب معلومات البنك --}}
        @component('components.custom-v-pills.pill', ['active'=>'', 'id'=>'bank-info', 'icon'=>'mdi mdi-bank'])@endcomponent
    @endslot

    {{-- المحتوى --}}
    @slot('content')
        {{-- محتوى معلومات المرشح --}}
        @component('components.custom-v-pills.tab-content', ['active'=>'active show', 'id'=>'info'])
            <div class="row text-center">
                <div class="col-md-6 col-md-12">
                    <div class="profile-user position-relative d-inline-block mx-auto">
                        <img id="profile-img" src="{{ $candidate->getCandidateProfilePersonalAttachmentUrlAttribute() }}" alt="" class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image bg-primary">
                    </div>
                    <div class="mt-3"></div>
                </div>
            </div>
            @component('components.data-row', ['id'=>'name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])<span title="{{$candidate->uuid}}">{{$candidate->name}}</span>@endcomponent
            @component('components.data-row', ['id'=>'code', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getCodeAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'email', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->email}}@endcomponent
            @component('components.data-row', ['id'=>'phone', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->phone}}@endcomponent
            @component('components.data-row', ['id'=>'gender_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getGenderNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'qualification_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getQualificationNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'department_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getDepartmentNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'national_id', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->national_id}}@endcomponent
            @component('components.data-row', ['id'=>'nationality_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getNationalityNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'birthdate', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->birthdate}}@endcomponent
            @component('components.data-row', ['id'=>'birthdate_hj', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->birthdate_hj}}@endcomponent
            @component('components.data-row', ['id'=>'address', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->address}}@endcomponent
            @component('components.data-row', ['id'=>'previously_work_at_rakaya', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->previously_work_at_rakaya}}@endcomponent
            @component('components.data-row', ['id'=>'has_relative', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->has_relative}}@endcomponent
            @component('components.data-row', ['id'=>'scrub_size_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getScrubSizeNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'years_of_experience_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getYearsOfExperienceNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'resident_status_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getResidentStatusNameAttribute()}}@endcomponent

            @component('components.data-row', ['id'=>'job_category_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getJobCategoryNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'marital_status_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getMaritalStatusNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'salary_expectation', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->salary_expectation}}@endcomponent
            @component('components.data-row', ['id'=>'availability_to_start_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getAvailabilityToStartNameAttribute()}}@endcomponent

            @component('components.data-row', ['id'=>'self_description', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->self_description}}@endcomponent
            @component('components.data-row', ['id'=>'uuid', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5'])<span class="text-white">{{$candidate->uuid}}</span>@endcomponent
        @endcomponent

        {{-- محتوى معلومات البنك --}}
        @component('components.custom-v-pills.tab-content', ['active'=>'', 'id'=>'bank-info'])
            @component('components.data-row', ['id'=>'iban_number', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getIbanNumberAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'bank_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getBankNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'account_name', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getAccountNameAttribute()}}@endcomponent
            @component('components.data-row', ['id'=>'owner_national_id', 'label_col' => 'col-lg-4 col-7', 'content_col' => 'col-lg-8 col-5']){{$candidate->getOwnerNationalIdAttribute()}}@endcomponent
        @endcomponent

    @endslot

@endcomponent