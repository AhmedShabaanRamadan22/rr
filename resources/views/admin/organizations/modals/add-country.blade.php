@component('modals.add-modal-template',['modalName'=>'organization-countries'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
@foreach ($columnCountries as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',
    ['columnName'=>$column,
    'col'=>'12','margin'=>'mb-3' ,
    "columnOptions" => ($optionCountries??null)])
    @endcomponent
@endforeach

@push('after-scripts')
    <script>

        let countryOrganization = @json($organization->countries);
        let notAllowedCountry;

        $('#operation_type_id_filter').change(function(){
            let operation_type_id = $(this).val();
            notAllowedReasons = reasonDangerOrganization.filter((reason)=>{
                return reason.operation_type_id == operation_type_id;
            }).map((item)=>{ return item.reason_id});

            $('#reason_id_filter option').each(function(){
                    $(this).hide();
                    if(!notAllowedReasons.includes(+$(this).val())){
                        $(this).show();
                    }
                })
            $('#reason_id_filter').selectpicker('destroy').selectpicker({
                width: '100%',
            });

        });

    </script>
@endpush
@endcomponent
