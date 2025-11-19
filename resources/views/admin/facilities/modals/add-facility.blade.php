@component('modals.add-modal-template',['modalName'=>'facilities','modalRoute'=>'facilities', 'modalMaxHeight'=>'90vh'])
    <div class="col-12">
        <div class="row">
            @foreach ($columnInputs as $column => $type)
                @component('components.inputs.' . $type . '-input',[
                    'columnName'=>$column,
                    'col'=> "6",
                    'margin'=> 'mb-3', 
                    "columnOptions" => ($columnOptions??null),
                    "columnSubtextOptions" => ($columnSubtextOptions??null)]) 
                @endcomponent
            @endforeach
            @foreach ($required_attachments as $attachment)
                @component('components.inputs.file-input',['attachment_label'=>$attachment,'col'=>'6','margin'=> 'mb-3', 'name' => 'attachments[' . $attachment->id . ']']) @endcomponent
            @endforeach
        </div>
    </div>
@endcomponent


@push('after-scripts')

<script>
    $(document).ready(function() {
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