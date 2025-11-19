{{-- Modal --}}
<div class="modal modal-lg fade" id="editFoodWeight" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <input type="hidden" name="food_weight_id" id="food_weight_id" value="">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white">{{ trans('translation.edit-food-wight') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    @component('components.inputs.number-input',['columnName'=>'quantity','col'=>'6','margin'=>'mb-3']) @endcomponent
                    @component('components.inputs.select-input',['columnName'=>'unit','col'=>'6','margin'=>'mb-3', 'columnOptions' => $foodWeightColumnOptions]) @endcomponent
                </div>
            </div>

            <div class="border-dashed border-top mx-2 p-2"></div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                    <button id="update_food_weight" type="button" class="btn btn-primary" data-bs-dismiss="modal" disabled>{{ trans('translation.update') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {

        $('#editFoodWeight').on('show.bs.modal', function(e) {
            var row = $(e.relatedTarget).closest('tr');
            console.log(row);
            $('#editFoodWeight #food_weight_id').val(row.attr('data-id'));
            $('#editFoodWeight #input_quantity').val(row.attr('data-quantity'));
            $('#editFoodWeight #unit_filter').val(row.attr('data-unit'));
            $('#editFoodWeight #unit_filter').selectpicker('destroy')
            $('#editFoodWeight #unit_filter').selectpicker()
        });


        // Update handler
        $('#editFoodWeight input').on('change', function(){
            $('#update_food_weight').prop('disabled', $(this).val() === '')
        })

        $('#update_food_weight').click(function() {
            let food_weight_id = $('#editFoodWeight #food_weight_id').val();
            let quantity = $('#editFoodWeight #input_quantity').val();
            let unit = $('#editFoodWeight #unit_filter').val();

            let data = {
                'food_weight_id': food_weight_id,
                'quantity': quantity,
                'unit': unit,
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: '{{ url("/food-weights") }}' + "/" + food_weight_id,
                data: data,
                dataType: "json",
                success: function(response) {
                    $('#editFoodWeight').modal('hide');
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                    window.foodWeightsDatatable.ajax.reload();
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
