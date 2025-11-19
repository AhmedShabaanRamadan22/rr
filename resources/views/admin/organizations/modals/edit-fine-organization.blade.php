{{-- Modal --}}
<div class="modal fade" id="editFineOrganization" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <input type="hidden" name="fine_organization_id" id="fine_organization_id" value="">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white">{{ trans('translation.edit-fine-organization') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>

            <div class="modal-body">
                {{-- edit_description --}}
                <div class="row">
                    @component('components.inputs.text-input',['columnName'=>'fine_edit','col'=>'6','margin'=>'mb-3', 'disabled'=>'disabled']) @endcomponent
                    @component('components.inputs.number-input',['columnName'=>'price_edit','col'=>'6','margin'=>'mb-3']) @endcomponent
                </div>
                @component('components.inputs.text-input',['columnName'=>'description_edit','col'=>'12','margin'=>'mb-3']) @endcomponent
            </div>

            <div class="border-dashed border-top mx-2 p-2"></div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                    <button id="update_fine_organization" type="button" class="btn btn-primary">{{ trans('translation.update') }}</button>
                </div>  
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {

        $('#editFineOrganization').on('show.bs.modal', function(e) {
            var row = $(e.relatedTarget).closest('tr');
            // Populate the modal input fields
            $('#fine_organization_id').val(row.attr('data-id')); // Assuming you have a data-id attribute on the row
            $('#input_description_edit').val(row.attr('data-description'));
            $('#input_price_edit').val(row.attr('data-price'));
            $('#input_fine_edit').val(row.attr('data-fine-name')); // Make sure this is the correct way to set the value for a dropdown
        });

        // Update handler
        $('#update_fine_organization').click(function() {
            let fine_organization_id = $('#fine_organization_id').val();
            let edit_description = $('#input_description_edit').val();
            let edit_price = $('#input_price_edit').val();

            let data = {
                'description': edit_description,
                'price': edit_price,
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: '{{ url("/fine-organizations") }}' + "/" + fine_organization_id,
                data: data,
                dataType: "json",
                success: function(response) {
                    $('#editFineOrganization').modal('hide');
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    window.fine_organization_datatable.ajax.reload();
                },
                error: function(jqXHR, responseJSON) {
                    Toast.fire({
                        icon: "error",
                        title: jqXHR.responseJSON.message
                    });

                },
            });
        });
    });
</script>
@endpush
