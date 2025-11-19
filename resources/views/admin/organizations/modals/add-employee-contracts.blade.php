{{-- ?? commented to optimize edit organization page --}}
{{-- @component('modals.add-modal-template',['modalName'=>'employee-contracts', 'modalRoute'=>'admin.api.contracts'])
    <input type="hidden" name="contractable_type" id="hidden_contractable_type" value="App\Models\User">
    <input type="hidden" name="contract_template" id="hidden_contract_template" value="{{'employee_' . $organization->id}}">
    <!-- Select2 -->
    @foreach ($user_contract_columns as $column => $type)
        @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3', "columnOptions" => ($userColumnOptions??null)]) @endcomponent
    @endforeach
@endcomponent  

@push('after-scripts')
<script>
    $(document).ready(function() {
        $('#input_salary').prop('disabled', 'disabled')
        let salaries = @json($userColumnOptions['salary']);
        $('#contractable_id_filter').on('change', function(){
            let user_id = $(this).val();
            $('#input_salary').prop('disabled', false)
            $('#input_salary').val(salaries[user_id])
        })
        $('#addservices').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            var organizationId = $(e.relatedTarget).data('organization-id');
            var used_service = $(e.relatedTarget).attr('data-services');
            var service_ids = used_service.split(',');
            //populate the textbox
            $(e.currentTarget).find('#hidden_organization_id').val(organizationId);
            $('#select_organization_service option').each(function() {
                $(this).removeClass('d-none');
                if (service_ids.includes($(this).val()) && $(this).val() != "choose_one") {
                    $(this).addClass('d-none');
                }
            });
            $('.selectPicker').selectpicker('destroy');
            $('.selectPicker').selectpicker();
        });

    }); // end document ready
</script>

@endpush --}}