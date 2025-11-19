@component('components.nav-pills.tab-pane', ['id' => $column['name'], 'padding' => 'p-1'])
@component('admin.organizations.components.edit-organization-form', ['organization' => $organization])

@component('components.section-header', ['title' => 'national-address', 'hide_button'=>'true'])@endcomponent
    <div class="row">
        {{-- ?? commented to optimize edit organization page --}}
        {{-- @component('components.inputs.select-input',['columnName'=>'city_id','col'=>'4','margin'=>'mb-3', 'columnOptions'=>$columnOptions, 'modelItem'=>$organization]) @endcomponent --}}
        @component('components.inputs.text-input',['columnName'=>'city_id','foreignColumn' => 'name', 'col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization->city, 'disabled' => 'disabled']) @endcomponent
        {{-- ?? commented to optimize edit organization page --}}
        {{-- @component('components.inputs.select-input',['columnName'=>'district_id','col'=>'4','margin'=>'mb-3', 'columnOptions'=>$columnOptions, 'modelItem'=>$organization]) @endcomponent --}}
        @component('components.inputs.text-input',['columnName'=>'district_id','foreignColumn' => 'name', 'col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization->district, 'disabled' => 'disabled']) @endcomponent
        @component('components.inputs.text-input',['columnName'=>'street_name','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization]) @endcomponent
        @component('components.inputs.number-input',['columnName'=>'postal_code','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization]) @endcomponent
        @component('components.inputs.number-input',['columnName'=>'building_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization]) @endcomponent
        @component('components.inputs.number-input',['columnName'=>'sub_number','col'=>'4','margin'=>'mb-3', 'modelItem'=>$organization]) @endcomponent
    </div>
    <div class="row">
        <div class="col-md-12 text-center pt-4">
            <button class="btn btn-primary col-6">{{ trans('translation.update') }}</button>
        </div>
    </div>

@endcomponent
@endcomponent

@push('after-scripts')
    {{-- ?? commented to optimize edit organization page --}}
    {{-- <script>
    $(document).ready(function(){
        $('#city_id_filter').on('change',function(){
            $('#district_id_filter').val(null).selectpicker('destroy');
            $('#district_id_filter').selectpicker({});
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
    </script> --}}
@endpush