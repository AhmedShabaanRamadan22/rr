@component('components.nav-pills.tab-pane', ['id' => $column['name'], 'padding' => 'p-1'])
@component('components.section-header', ['title' => 'general-info', 'hide_button'=>'true'])@endcomponent
@component('admin.organizations.components.edit-organization-form', ['organization' => $organization])
<div class="row justify-content-center">
    <div class="col-xxl-3">
        <div class="card overflow-hidden">
            <div>
                <img id="organization-backgroundImage" src="{{ $organization->background_image ?? URL::asset('build/images/users/32/background_image.png') }}" alt="" class="card-img-top profile-wid-img object-fit-cover" style="height: 200px;">
                <div>
                    <input id="profile-foreground-img-file-input" name="background_image" type="file" accept="image/*" class="profile-foreground-img-file-input d-none" onchange="document.getElementById('organization-backgroundImage').src = window.URL.createObjectURL(this.files[0])">
                    <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light btn-sm position-absolute top-0 m-3">
                        <i class="ri-image-edit-line align-bottom me-1"></i>
                        {{ trans('translation.edit-interface-org') }}
                    </label>
                </div>
            </div>
            <div class="card-body pt-0 mt-n5">
                <div class="text-center">
                    <div class="profile-user position-relative d-inline-block mx-auto">
                        <img id="organization-img" src="{{ $organization->logo ?? URL::asset('build/images/users/32/logo.png') }}" alt="" class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image">
                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit position-absolute end-0 bottom-0">
                            <input id="profile-img-file-input" name="logo" type="file" accept="image/*" class="profile-img-file-input d-none" onchange="document.getElementById('organization-img').src = window.URL.createObjectURL(this.files[0])">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="bi bi-camera"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row information">
    @component('components.inputs.text-input',['columnName'=>'name_ar','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization]) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'name_en','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization]) @endcomponent

    <!-- domain specific records -->
    @component('components.inputs.text-input',['columnName'=>'domain','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization, 'error' => 'domain-regex-error', 'regex' => '^(https?|ftp):\/\/[^\s\/$.?#].[^\s]*$']) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'cloudflare_hostname_txt_record_name','col'=>'4','margin'=>'mb-3','is_required' => false, 'disabled' => 'disabled']) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'cloudflare_hostname_txt_record_value','col'=>'4','margin'=>'mb-3','is_required' => false, 'disabled' => 'disabled']) @endcomponent
    @component('components.status-badge',['label'=>'cloudflare_hostname_txt_status','col'=>'4','margin'=>'mb-3','is_required' => false]) @endcomponent
    <!-- domain ssl specific records -->
    @component('components.inputs.text-input',['columnName'=>'cloudflare_ssl_txt_record_name','col'=>'4','margin'=>'mb-3','is_required' => false,'disabled' => 'disabled']) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'cloudflare_ssl_txt_record_value','col'=>'4','margin'=>'mb-3','is_required' => false,'disabled' => 'disabled']) @endcomponent
    @component('components.status-badge',['label'=>'cloudflare_ssl_txt_status','col'=>'4','margin'=>'mb-3','is_required' => false]) @endcomponent

    {{-- ?? commented to optimize edit organization page --}}
    {{-- @component('components.inputs.select-input',['columnName'=>'organization_chairman','col'=>'4','margin'=>'mb-3', 'columnOptions'=>$columnOptions, 'modelItem'=>$organization->chairman, 'foreign_column'=>'id']) @endcomponent --}}
    @component('components.inputs.text-input',['columnName'=>'organization_chairman','foreignColumn' => 'name', 'col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization->chairman, 'disabled' => 'disabled']) @endcomponent

    @component('components.inputs.text-input',['columnName'=>'phone','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization, 'error' => 'number-regex-error', 'regex' => '^5\d{8}$']) @endcomponent
    @component('components.inputs.email-input',['columnName'=>'email','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization, 'error' => 'email-regex-error', 'regex' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$']) @endcomponent

    @component('components.inputs.number-input',['columnName'=>'registration_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization]) @endcomponent

    {{-- ?? commented to optimize edit organization page --}}
    {{-- @component('components.inputs.select-input',['columnName'=>'registration_source','col'=>'4','margin'=>'mb-3', 'columnOptions'=>$columnOptions, 'modelItem'=>$organization]) @endcomponent --}}
    @component('components.inputs.text-input',['columnName'=>'registration_source','foreignColumn' => 'name','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization->registration_source_relation, 'disabled' => 'disabled']) @endcomponent

    @component('components.inputs.date-input',['columnName'=>'release_date','col'=>'4','margin'=>'mb-1', 'modelItem'=>$organization]) @endcomponent

    {{-- ?? commented to optimize edit organization page --}}
    {{-- @component('components.inputs.select-input',['columnName'=>'sender_id','col'=>'6','margin'=>'mb-3', 'columnOptions'=>$columnOptions, 'modelItem'=>$organization]) @endcomponent --}}
    @component('components.inputs.text-input',['columnName'=>'sender_id', 'foreignColumn' => 'name', 'col'=>'6','margin'=>'mb-3', 'modelItem'=>$organization->sender, 'disabled' => 'disabled']) @endcomponent

    <div class="col-md-6">
        <div class="row mb-4">
            <div class="col">
                <label for="inputProfileFile" class=" form-label">{{ trans('translation.profile-file') }}</label>
                <input type="file" accept="application/pdf" class="form-control" id="inputProfileFile" name="organization_profile" value="{{ $organization->profile_file }}" placeholder="{{ trans('translation.profile-file') }}">
            </div>
        </div>
    </div>

    @if (isset( $organization->profile_file))
    <div class="card-body">
        <div class="d-flex justify-between align-center col-12 bg-primary-subtle px-3 pt-2">
            <div class="col-lg-6 mt-2 h6">{{ trans('translation.profile-file') }}</div>
            <div class="card-title col-lg-6 text-end">
                <button class="btn btn-danger" type="button" id="delete-portfolio"> {{ trans('translation.delete') }} <i class="mdi mdi-trash-can"></i></button>
            </div>
        </div>
        <div class="ratio ratio-16x9 text-center">
            <iframe src="{{ $organization->profile_file}}" title="organization-profile" allowfullscreen class="align-center border"></iframe>
        </div>
    </div>
    @endif
</div>
<div class="row">
    <div class="col-md-12 text-center">
        <button class="btn btn-primary col-6" id="update-organization-info">{{ trans('translation.update') }}</button>
    </div>
</div>
@endcomponent
@endcomponent

@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/dayjs.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('.information .check-empty-input').on('change', function() {
            let flag = true;
            let data_regex = $(this).attr("data-regex");
            let column_name = $(this).attr("name");
            if (typeof data_regex !== 'undefined' && data_regex !== false) {
                var regex = new RegExp(data_regex);
                if (!regex.test($(this).val())) {
                    flag = false;
                    $('#error-' + column_name).removeClass('d-none')
                    $('#input_' + column_name).addClass('border-danger')
                    $('#update-organization-info').prop('disabled', !flag)
                    return
                }
                $('#error-' + column_name).addClass('d-none')
                $('#input_' + column_name).removeClass('border-danger')
            }
            $('#update-organization-info').prop('disabled', !flag)
        })
        $('#delete-portfolio').on('click', function() {
            let organization_id = "{{$organization->id}}"
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.portfolio.delete') }}",
                            data: {
                                organization_id: organization_id,
                            },
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {
                                window.location.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                            },
                            error: function(response, jqXHR, xhr) {
                                Toast.fire({
                                    icon: "error",
                                    title: response.jqXHR.message
                                });
                            },
                        });
                    }
                });
        })
    })
</script>
<script>
    async function getHostnameStatus() {
        // domain verification inputs
        const hostname_txt_name = document.getElementById('input_cloudflare_hostname_txt_record_name');
        const hostname_txt_value = document.getElementById('input_cloudflare_hostname_txt_record_value');
        const hostname_txt_spinner = document.getElementById('cloudflare_hostname_txt_status_spinner');
        const hostname_txt_badge = document.getElementById('cloudflare_hostname_txt_status_badge');
        const hostname_txt_error = document.getElementById('cloudflare_hostname_txt_status_error');
        // ssl verification inputs
        const ssl_txt_name = document.getElementById('input_cloudflare_ssl_txt_record_name');
        const ssl_txt_value = document.getElementById('input_cloudflare_ssl_txt_record_value');
        const ssl_txt_spinner = document.getElementById('cloudflare_ssl_txt_status_spinner');
        const ssl_txt_badge = document.getElementById('cloudflare_ssl_txt_status_badge');
        const ssl_txt_error = document.getElementById('cloudflare_ssl_txt_status_error');

        const res = await fetch("{{ route('organizations.getCustomHostnameStatus', $organization->id) }}");
        hostname_txt_spinner.classList.toggle('d-none')
        ssl_txt_spinner.classList.toggle('d-none')
        if (res.ok) {
            const json = await res.json();
            const data = json.data;
            // update domain verification inputs
            hostname_txt_name.value = data.hostname_txt_verification_record.name
            hostname_txt_value.value = data.hostname_txt_verification_record.value
            hostname_txt_badge.innerText = data.hostname_txt_verification_record.status
            hostname_txt_badge.classList.add(data.hostname_txt_verification_record.status == 'active' ? 'bg-success' : 'bg-danger')
            hostname_txt_error.innerText = data.hostname_txt_verification_record.error
            // update ssl verification inputs
            ssl_txt_name.value = data.ssl_txt_verification_record.name
            ssl_txt_value.value = data.ssl_txt_verification_record.value
            ssl_txt_badge.innerText = data.ssl_txt_verification_record.status
            ssl_txt_badge.classList.add(data.ssl_txt_verification_record.status == 'active' ? 'bg-success' : 'bg-danger')
            ssl_txt_error.innerText = data.ssl_txt_verification_record.error
        } else {
            hostname_txt_badge.innerText = 'error occurred'
            hostname_txt_badge.classList.add( 'bg-danger')
            ssl_txt_badge.innerText = 'error occurred'
            ssl_txt_badge.classList.add('bg-danger')
        }

    }

    getHostnameStatus();
</script>
@endpush