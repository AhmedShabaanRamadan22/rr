{{-- @props(['question_types', 'regexes', 'question_has_options_ids']) --}}

<div class="modal fade" id="editQuestionBankOrg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            {{-- <form action="{{route('admin.questions.destroy')}}" method="post">
                @csrf --}}
{{-- 
            @csrf
            @method('PUT') --}}

            <input type="hidden" name="question_bank_organization_id" id="question_bank_organization_id" value="">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white">{{ trans('translation.edit-question-type') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>

            <div class="modal-body">
                
                {{-- edit_description --}}
                <div class=" row mb-4">
                    <label for="edit_description"
                        class="col-md-3 form-label">{{ trans('translation.description') }}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="edit_description" name="description"
                            placeholder="{{ trans('translation.descriptions') }}">
                        {{-- <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div> --}}
                    </div>
                </div>
                
                {{-- edit_is_required --}}
                <div class=" row mb-4">
                    <label for="edit_is_required"
                        class="col-md-3 form-label">{{ trans('translation.required') }}</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" role="switch" id="edit_is_required"
                                    name="is_required" checked value="1">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- edit_is_visible --}}
                <div class=" row mb-4">
                    <label for="edit_is_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" role="switch" id="edit_is_visible"
                                    name="is_visible" checked value="1">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="border-dashed border-top mx-2 p-2"></div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-subtle-danger"
                        data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                    <button id="update_question" type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ trans('translation.update') }}</button>
                </div>
            </div>
            {{-- </form> --}}
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        $(document).ready(function() {

            // Open edit question modal ==================================================================
            $('#editQuestionBankOrg').on('show.bs.modal', function(e) {
                var row = $(e.relatedTarget).closest('tr');
                $('#edit_description').val(row.children().children('.data-description').text());
                // $('#edit_question_bank_id').val(row.attr('data-question_bank-id'));
                (row.children().children('.data-required').hasClass('ri-check-fill')) ? $('#edit_is_required').attr('checked',
                    'checked'): $('#edit_is_required').attr('checked', false);

                (row.children().children('.data-visible').hasClass('ri-check-fill')) ? $('#edit_is_visible').attr('checked',
                    'checked'): $('#edit_is_visible').attr('checked', false);

                $('#question_bank_organization_id').val(row.attr('id'));
                // $('#question_bank_organization_id').val(row.attr('data-question-bank-organization-id'));

            });



            // Update question handler =============================================================
            $(document.body).on('click', '#update_question', (function() {
                var edit_description = $('#edit_description').val();
                var edit_is_required = $('#edit_is_required').is(":checked") ? 1 : 0;
                var edit_is_visible = $('#edit_is_visible').is(":checked") ? 1 : 0;
                let question_bank_organization_id = $('#question_bank_organization_id').val();

                let valid = true;
                // valid = valid & is_empty(edit_content, '#edit_content') & is_empty(edit_description,
                //     '#edit_description');
                // $('.inputs-option').each(function(index, element) {
                //     valid = valid & is_empty(element.value, element);
                // });
               

                if (valid) {
                    Swal
                        .fire( {
                            title: "{{ trans('translation.Warning') }}",
                            text: "{{ trans('translation.update-question-effect') }}",
                            icon: "warning",
                            showCancelButton: true,
                            showConfirmButton: true,
                            confirmButtonText: "{{ trans('translation.update') }}",
                            confirmButtonColor: "#CAB272",
                            cancelButtonText: "{{ trans('translation.back') }}",
                            cancelButtonColor: '#2c3639'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var data = {
                                    'description': edit_description,
                                    'is_required': edit_is_required,
                                    'is_visible': edit_is_visible,
                                    'question_bank_organization_id': question_bank_organization_id,
                                }
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    }
                                });
                                // url: '{{ url('/question-bank-organizations') }}/' + question_bank_organization_id,

                                $.ajax({
                                    type: "PUT",
                                    url: '{{ url('/question-bank-organizations') }}/' + question_bank_organization_id,
                                    data: data,
                                    dataType: "json",
                                    success: function(response, jqXHR, xhr) {
                                            $('#editQuestionBankOrg').modal('hide');
                                            Toast.fire({
                                                icon: "success",
                                                title: "{{ trans('translation.Question was updated successfuly') }}"
                                            });
                                            window.questionBank.ajax.reload();
                                    },
                                });
                            }
                        })
                }
            }));


        }); // End of ready function
    </script>
@endpush
