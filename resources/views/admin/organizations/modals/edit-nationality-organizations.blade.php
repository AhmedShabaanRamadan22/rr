<div class="modal fade" id="editNationalityOrganization" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <input type="hidden" name="nationlity_organization_id" id="nationality_organization_id" value="">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white">{{ trans('translation.edit-nationality-organizations') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>

            <div class="modal-body">
                {{-- edit_description --}}
                @component('components.inputs.select-input',['columnName'=>'menu','col'=>'12','margin'=>'mb-3', 'columnOptions'=>$columnOptions, 'is_multiple'=>'multiple']) @endcomponent
            </div>

            <div class="border-dashed border-top mx-2 p-2"></div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                    <button id="update_nationality_organization" type="button" class="btn btn-primary" disabled data-bs-dismiss="modal">{{ trans('translation.update') }}</button>
                </div>  
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function(){
        var nationalityOrganizationId;
        $('#editNationalityOrganization').on('show.bs.modal', function(e) {
            nationalityOrganizationId = $(e.relatedTarget).closest('tr').attr('data-id');
            let foods = []
            $("tr td .menu-" + nationalityOrganizationId).each(function (index, element) {
                foods.push($(element).attr('data-food-weight-id'))
            });
            $("#menu_filter option").each(function() {
                $(this).prop('selected', false);
                if (foods.includes($(this).val())) {
                    $(this).prop('selected', true);
                }

            });
            $("#menu_filter").selectpicker('destroy').selectpicker()
        });
        $("#menu_filter").on('change', function(){
            $('#update_nationality_organization').prop('disabled', false)
        })
        $("#update_nationality_organization").on('click', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: '{{ url("nationality-organizations") }}' + "/" + nationalityOrganizationId,
                data: {
                    menu: $("#menu_filter").val()
                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    window.nationalitiesOrganizationDatatable.ajax.reload();
                },
                error: function(){
                    Toast.fire({
                        icon: "error",
                        title: jqXHR.responseJSON.message
                    });
                }
            });
        })
    })
</script>
@endpush
