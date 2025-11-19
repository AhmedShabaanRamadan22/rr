<div class=""><!-- end card header -->
    <div class="card-body form-steps">
        <form action="#">
            <div class="text-center pt-3 pb-4 mb-1">
                <h5>{{$facility->name}}</h5>
            </div>
            <div id="custom-progress-bar" class="progress-nav mb-4">
                <div class="progress" style="height: 1px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill active" data-progressbar="custom-progress-bar" id="pills-gen-info-tab" data-bs-toggle="pill" data-bs-target="#pills-gen-info" type="button" role="tab" aria-controls="pills-gen-info" aria-selected="true">1</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar" id="pills-info-desc-tab" data-bs-toggle="pill" data-bs-target="#pills-info-desc" type="button" role="tab" aria-controls="pills-info-desc" aria-selected="false">2</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar" id="pills-success-tab" data-bs-toggle="pill" data-bs-target="#pills-success" type="button" role="tab" aria-controls="pills-success" aria-selected="false">3</button>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="pills-gen-info" role="tabpanel" aria-labelledby="pills-gen-info-tab">
                    <div>
                        <div class="mb-4">
                            <div>
                                <h5 class="mb-1">{{trans('translation.edit-facility-info')}}</h5>
                            </div>
                        </div>
                        <div class="row first-stage">
                            @component('components.inputs.text-input',['columnName'=>'name','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.text-input',['columnName'=>'registration_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.date-input',['columnName'=>'version_date','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.date-input',['columnName'=>'end_date','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.select-input',['columnName'=>'registration_source','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility, 'columnOptions' => $columnOptions]) @endcomponent
                            @component('components.inputs.text-input',['columnName'=>'license','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.date-input',['columnName'=>'license_expired','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.number-input',['columnName'=>'capacity','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.number-input',['columnName'=>'tax_certificate','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.select-input',['columnName'=>'bank','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility->iban?->bank, 'columnOptions' => $columnOptions, 'foreign_column'=>'id']) @endcomponent
                            @component('components.inputs.text-input',['columnName'=>'account_name','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility->iban]) @endcomponent
                            @component('components.inputs.text-input',['columnName'=>'iban','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility->iban]) @endcomponent
                            @component('components.inputs.number-input',['columnName'=>'employee_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.number-input',['columnName'=>'chefs_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                            @component('components.inputs.number-input',['columnName'=>'kitchen_space','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 mt-4" id="first-stage" >
                        <button type="button" id="first-next" class="btn btn-primary btn-label right ms-auto"><i class="ri-arrow-left-line label-icon align-middle fs-lg ms-2"></i>{{trans('translation.national-address')}}</button>
                        <button type="button" id="first-stage-next" class="btn btn-primary btn-label right ms-auto nexttab d-none" data-nexttab="pills-info-desc-tab"><i class="ri-arrow-left-line label-icon align-middle fs-lg ms-2"></i>{{trans('translation.national-address')}}</button>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-info-desc" role="tabpanel" aria-labelledby="pills-info-desc-tab">
                    <div>
                        <h5 class="mb-4">{{trans('translation.national-address')}}</h5>
                    </div>
                    <div class="row second-stage">
                        @component('components.inputs.select-input',['columnName'=>'city_id','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility, 'columnOptions' => $columnOptions]) @endcomponent
                        @component('components.inputs.select-input',['columnName'=>'district_id','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility, 'columnOptions' => $columnOptions]) @endcomponent
                        @component('components.inputs.text-input',['columnName'=>'street_name','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                        @component('components.inputs.number-input',['columnName'=>'building_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                        @component('components.inputs.number-input',['columnName'=>'postal_code','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                        @component('components.inputs.number-input',['columnName'=>'sub_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$facility]) @endcomponent
                    </div>
                    <div class="d-flex align-items-start gap-3 mt-4">
                        <button type="button" class="btn btn-link text-decoration-none btn-label previestab" data-previous="pills-gen-info-tab"><i class="ri-arrow-right-line label-icon align-middle fs-lg me-2"></i>{{trans('translation.general-info')}}</button>
                        <button id="submit" type="button" class="btn btn-primary btn-label right ms-auto "><i class="ri-arrow-left-line label-icon align-middle fs-lg ms-2"></i>{{trans('translation.submit')}}</button>
                        <button id="success-button" type="button" class="btn btn-primary btn-label right ms-auto d-none nexttab" data-nexttab="pills-success-tab"><i class="ri-arrow-left-line label-icon align-middle fs-lg ms-2"></i>{{trans('translation.submit')}}</button>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-success" role="tabpanel" aria-labelledby="pills-success-tab">
                    <div>
                        <div class="text-center">
                            <div class="mb-4">
                                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>
                            </div>
                            <h5>{{trans('translation.edit-facility-success')}}</h5>
                            <a href="{{route('facilities.index')}}" class="btn btn-outline-primary my-4">{{trans('translation.back-to-facilities')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@push('after-scripts')
<script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>
<script>
    $(document).ready(function(){
        // $('#city_id_filter').on('change',function(){

        //     let citySelected = $('#city_id_filter').val();
        //     if($('#city_id_filter').val() != ""){
        //         allowedDistricts = districts.filter((district)=>{
        //             return (citySelected == district.city_id) ;
        //         }).map((item)=>item.id);

        //         $('#district_id_filter option').each(function(){
        //             if(allowedDistricts.length > 0){
        //                 $(this).hide();
        //                 if(allowedDistricts.includes(+$(this).val())){
        //                     $(this).show();
        //                 }
        //             }
        //             else $(this).show();
        //         })
        //         $('#district_id_filter').selectpicker('destroy').selectpicker({
        //             width: '100%',
        //         });
        //     }

        // })
        $('#pills-info-desc-tab, #pills-gen-info-tab, #pills-success-tab').on('click', function(e){
            e.preventDefault();
        })

        $('#first-next').on('click', function(e){
            if(validate('.first-stage')){
                $('#first-stage-next').click()
            }
        })

        let validate = (stage) => {
            let flag = true;
            $(stage).children().each(function(index, element) {
                let input = $(element).children('input');
                let select = $(element).children('div').children('select')
                if(input.prop('type') == 'hidden') {return true}
                flag = flag & is_valid(typeof input.prop('required') === 'undefined' ? select : input)
            });
            return flag;
        }

        let is_valid = (input) => {
            if(input.prop('required')){
                if(input.val() == null || input.val() == '' ){
                    input.addClass('border-danger')
                    return false;
                }
                input.removeClass('border-danger')
                return true;
            }
        }

        $('#submit').on('click', function(){
            if(validate('.second-stage')) {
                Swal
                    .fire(window.confirmUpdatePopupSetup).then((result) => {
                        if (result.isConfirmed) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "PUT",
                                url: '{{ route('facilities.update', $facility) }}',
                                data: {
                                    name: $('#input_name').val(),
                                    registration_number: $('#input_registration_number').val(),
                                    version_date: $('#input_version_date').val(),
                                    version_date_hj: $('#version_date_hj').val(),
                                    end_date: $('#input_end_date').val(),
                                    end_date_hj: $('#end_date_hj').val(),
                                    registration_source: $('#registration_source_filter').val(),
                                    license: $('#input_license').val(),
                                    license_expired: $('#input_license_expired').val(),
                                    license_expired_hj: $('#license_expired_hj').val(),
                                    capacity: $('#input_capacity').val(),
                                    employee_number: $('#input_employee_number').val(),
                                    chefs_number: $('#input_chefs_number').val(),
                                    kitchen_space: $('#input_kitchen_space').val(),
                                    city_id: $('#city_id_filter').val(),
                                    district_id: $('#district_id_filter').val(),
                                    street_name: $('#input_street_name').val(),
                                    building_number: $('#input_building_number').val(),
                                    postal_code: $('#input_postal_code').val(),
                                    sub_number: $('#input_sub_number').val(),
                                    tax_certificate: $('#input_tax_certificate').val(),
                                    account_name: $('#input_account_name').val(),
                                    bank: $('#bank_filter').val(),
                                    iban: $('#input_iban').val(),
                                },
                                dataType: "json",
                                success: function(response, jqXHR, xhr) {
                                    // window.datatable.ajax.reload();
                                    $('#success-button').click()
                                }
                            });
                        } 
                    });
            }
        })

        $('#city_id_filter').on('change',function(){
            $('#district_id_filter').val([]).selectpicker('destroy').selectpicker({});
            let emptyDistrictFlag = retrieveSelect('#district_id_filter', '#city_id_filter');
        })
    });


    function retrieveSelect(child, parent){
        let districts = @json($districts);
        let cities = @json($columnOptions['city_id']);
        let notAllowedDistricts, allowedDistricts ;
        let city_id = $(parent).val();

        let citySelected = $(parent).val();
        if(citySelected != ""){
            allowedDistricts = districts.filter((district)=>{
                return (citySelected == district.city_id || district.city_id == 23696) ;
            }).map((item)=>item.id);

            $(child + ' option').each(function(){
                if(allowedDistricts.length > 0){
                    $(this).hide();
                    if(allowedDistricts.includes(+$(this).val())){
                        $(this).show();
                    }
                }
                else {
                    $(this).show();
                }
            })
        }
        $(child ).attr('title',"{{trans('translation.choose-one')}}");

        $(child).selectpicker('destroy').selectpicker({});
    }
</script>
@endpush
