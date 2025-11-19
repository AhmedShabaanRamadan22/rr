<div class="modal fade" id="editQuestion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            @csrf
            @method('PUT')
            <input type="hidden" name="question_id" id="question_id" value="">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white">{{ trans('translation.edit-question-type') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>

            <div class="modal-body">
                <div id="edit-options" class="row mb-4 d-none">
                    <label for="edit-options-div-container"
                        class="col-md-3 form-label">{{ trans('translation.options') }}</label>
                    <div id="edit-options-div-container" class="col-md-9" data-i="1">
                    </div>
                </div>

                {{-- new input --}}
                <div class=" row mb-4">
                    <label for="edit_is_required"
                        class="col-md-3 form-label">{{ trans('translation.required') }}</label>
                    <div class="col-md-9">
                        <select class="selectpicker form-control check-empty-input" name="is_required" id="edit_is_required" placeholder="{{ trans('translation.choose-one') }}" required>
                            <option class="" value="1"  >{{trans('translation.required')}}</option>
                            <option class="" value="0"  >{{trans('translation.not-required')}}</option>
                            <option class="" value="default"  >{{trans('translation.default')}}</option>
                        </select>
                    </div>
                </div>

                {{-- new input --}}
                <div class=" row mb-4">
                    <label for="edit_is_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
                    <div class="col-md-9">
                        <select class="selectpicker form-control check-empty-input" name="is_visible" id="edit_is_visible" placeholder="{{ trans('translation.choose-one') }}" required>
                            <option class="" value="1"  >{{trans('translation.visible')}}</option>
                            <option class="" value="0"  >{{trans('translation.not-visible')}}</option>
                            <option class="" value="default"  >{{trans('translation.default')}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-dashed border-top mx-2 p-2"></div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-subtle-danger"
                        data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                    <button id="edit_question" type="button" class="btn btn-primary">{{ trans('translation.update') }}</button>
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
            var i = 1;
            $('#editQuestion').on('show.bs.modal', function(e) {
                i = 1;
                var row = $(e.relatedTarget).closest('tr');
                $('#edit_is_required').selectpicker('val', row.attr('data-question-is_required')).selectpicker();
                $('#edit_is_visible').selectpicker('val', row.attr('data-question-is_visible')).selectpicker();
                $('#question_id').val(row.attr('data-question-id'));

                var options = $('.span-option-' + row.attr('data-question-id'));
                $('#edit-options-div-container').empty();

                if (options.length > 0) {
                    $('#edit-options-div-container').attr('data-i', 1);
                    $('#edit-options').removeClass('d-none');
                    $('#edit-add-option-btn').removeClass('d-none');
                    $('#edit-options-div-container').append(generateOptions(options));
                } else {
                    $('#edit-options').addClass('d-none');
                    $('#edit-add-option-btn').addClass('d-none');
                }
            });

            // Generate options on opining the modal ===============================================
            function generateOptions(options) {
                var html = '';
                Array.from(options).forEach((option) => {
                    html += new_option(option)
                })
                html += add_button_template()
                return html;
            }
            function add_button_template(){
                return '<button id="button-add-option" type="button" class="btn btn-outline-primary btn-sm px-2 mt-2"><i class="mdi mdi-plus"></i></button>';
            }

            // Add option handler ==================================================================
            $(document.body).on('click', '#button-add-option', function() {
                $('.option-error').addClass('d-none');
                $(this).remove();
                $('#edit-options-div-container').append(new_option());
                $('#edit-options-div-container').append(add_button_template());
            });

            // New option row on clicking add option button =========================================
            function new_option(option = null) {
                let option_id = i, option_value = '', option_type = 'new-option';
                if(option){
                    option_id = option.getAttribute('data-option-id')
                    option_value = option.innerText;
                    option_type = 'old-option'
                }
                var html =
                    `<div class="mb-2 option-row">
                        <label for="inputContent" class="mb-2">{{ trans('translation.option') . ': ' }}<span class="option-numbers">${i}</span></label>
                        <div class="row col-md-12">
                            <div class="col-md-11">
                                <input type="text" class="form-control edit-inputs-option ${option_type}" id="${option_id}" name="options[${option_id}]" placeholder="{{ trans('translation.content') }}" required value="${option_value}">
                            <div class="d-none text-danger">{{ trans('translation.required-field') }}</div>
                                <p class="text-danger d-none option-error">{{ __('Question must have at least one option') }}</p>
                            </div>
                            <div class=" col-md-1">
                                <a class="btn btn-danger btn-sm delete-edit-option-btn" >
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </div>
                        </div>
                     </div>`;
                ++i;
                $('#edit-options-div-container').attr('data-i', i);
                return html;
            }

            // Delete option handler ===============================================================
            $(document.body).on('click', '.delete-edit-option-btn', function() {
                i = 1;
                if ($('#edit-options-div-container').attr('data-i') == 1) {
                    $('.option-error').removeClass('d-none');
                    return;
                }
                $('.option-error').addClass('d-none');
                $(this).parents('.option-row').remove();
                Array.from($('.option-numbers')).forEach((number) => {
                    number.innerText = i++
                })
            });

            // Update question handler =============================================================
            $(document.body).on('click', '#edit_question', (function(e) {
                var edit_is_required = $('#edit_is_required').val();
                var edit_is_visible = $('#edit_is_visible').val();
                let question_id = $('#question_id').val();
                var options = [];
                var new_options = [];

                let valid = true;
                $('.old-option').each(function(index, element) {
                    valid = valid & !is_empty(element.value, element);
                    options.push([element.id, element.value]);
                });
                $('.new-option').each(function(index, element) {
                    valid = valid & !is_empty(element.value, element);
                    new_options.push([element.id, element.value]);
                });

                if (valid) {
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
                                    url: '{{ url("/questions") }}' + "/" + question_id,
                                    data: {
                                        'is_required': edit_is_required,
                                        'is_visible': edit_is_visible,
                                        'old_options': options,
                                        'new_options': new_options,
                                        'question_id': question_id,
                                    },
                                    dataType: "json",
                                    success: function(response, jqXHR, xhr) {
                                            window.questions.ajax.reload();
                                            $('#editQuestion').modal('hide');
                                            Toast.fire({
                                                icon: "success",
                                                title: "{{ trans('translation.Question was updated successfuly') }}"
                                            });
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Toast.fire({
                                            icon: "error",
                                            title: 'hhelo'
                                        });
                                    },
                                });
                            }
                        })
                }
            }));

            // Check empty fields ===========================================================
            function is_empty(field, input_id) {
                if (field == null || field == '') {
                    $(input_id).addClass('border border-danger')
                    $(input_id).next().removeClass('d-none')
                    return true;
                }
                $(input_id).removeClass('border border-danger')
                $(input_id).next().addClass('d-none')
                return false;
            }

        }); // End of ready function
    </script>
@endpush
