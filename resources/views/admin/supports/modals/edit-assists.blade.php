<div class="modal fade" id="editAssist" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <input type="hidden" name="assist_id" id="assist_id" value="">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white">{{ trans('translation.edit-assist') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>

            <div class="modal-body">
                {{-- description --}}
                <div class="row">
                    @component('components.inputs.select-input',['columnName'=>'assist_from','col'=>'6','margin'=>'mb-3', 'columnOptions'=>$assist_options]) @endcomponent
                    @component('components.inputs.select-input',['columnName'=>'assistant_id','col'=>'6','margin'=>'mb-3', 'columnOptions'=>$assist_options]) @endcomponent
                    @component('components.inputs.number-input',['columnName'=>'quantity','col'=>'6','margin'=>'mb-3']) @endcomponent
                </div>
            </div>

            <div class="border-dashed border-top mx-2 p-2"></div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                    <button id="update_assist" type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ trans('translation.update') }}</button>
                </div>  
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function(){
        var assistId;
        let assistant_representer = @json($assist_options['assistant_id']);
        let monitors = @json($assist_options['monitors']);
        let subtext_options = @json($assist_subtext_options['monitors']);
        console.log(@json($assist_options['assist_from']))

        $('#editAssist').on('show.bs.modal', function(e) {
            var assist = $(e.relatedTarget);

            assistId = assist.attr('data-assist-id');

            $('#editAssist #assist_from_filter').val(assist.attr('data-assist-from-id'));
            $('#editAssist #assist_from_filter').selectpicker('destroy').selectpicker();

            $('#editAssist #input_quantity').val(assist.attr('data-quantity'));
            
            $('#editAssist #assistant_id_filter').empty();
            $.each( (assist.attr('data-assist-from-id') == 0 ? assistant_representer : monitors), function(id,name){
                $('#editAssist #assistant_id_filter').append($('<option>', {
                    value: id,
                    text: name,
                    'data-subtext': ' ' + subtext_options[id],
                    'selected': id == assist.attr('data-assistant-id')
                }))
            });
            $('#editAssist #assistant_id_filter').selectpicker('destroy').selectpicker({});

        });
        let current_assistant = $('#editAssist #assist_from_filter').val()
        $('#editAssist #assist_from_filter').on('change', function(e){
            if(!(current_assistant != 0 && $(this).val() != 0)){
                $('#editAssist #assistant_id_filter').empty();
                $('#editAssist #assistant_id_filter').selectpicker('destroy');
                $.each(($(this).val() == 0 ? assistant_representer : monitors), function(id,name){
                    $('#editAssist #assistant_id_filter').append($('<option>', {
                        value: id,
                        text: name,
                        'data-subtext': ' ' + subtext_options[id]
                    }))
                });
                $('#editAssist #assistant_id_filter').selectpicker();
                current_assistant = $(this).val()
            }
        })
        $("#update_assist").on('click', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: '{{ url("assists") }}' + "/" + assistId,
                data: {
                    assist_id: assistId,
                    assist_from_id: $("#editAssist #assist_from_filter").val(),
                    assistant_id: $("#editAssist #assistant_id_filter").val(),
                    quantity: $("#editAssist #input_quantity").val(),
                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    localStorage.setItem('reloadPending', response.message);
                    window.location.reload();
                },
                error: function(response, jqXHR, xhr) {
                    Toast.fire({
                        icon: "error",
                        title: response.responseJSON.message
                    });
                }
            });
        })
    })
</script>
@endpush
