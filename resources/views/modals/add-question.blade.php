<form id="add_question_form" action="{{ route('questions.store') }}" method="post">
    @csrf
    <input type="hidden" name="questionableId" value="{{$questionableId}}">
    <input type="hidden" name="questionableType" value="{{$questionableType}}">
    <div class="modal fade" id="addall-question" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ trans('translation.add-new-question') }}</h5>
                </div>
                <div class="modal-body">
                    <!-- <div class=" row mb-4">
                        <label for="content" class="col-md-3 form-label">{{ trans('translation.content') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="content" name="content" placeholder="{{ trans('translation.content') }}">
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div> -->
                    <div class=" row mb-4"> 
                        <label for="edit_question_bank"
                            class="col-md-3 form-label">{{ trans('translation.content') }}</label>
                        <div class="col-md-9">
                            <select class="selectpicker form-control" name="question_bank_organization_id" id="add_question_bank_id"
                                placeholder="{{ trans('translation.choose-one') }}" data-live-search="true">
                                @foreach ($organization->question_bank_organizations as $q)
                                    <option class="" value="{{ $q->id }}" data-has-option="{{$q->question_bank->question_type->has_option}}" data-question-type="{{$q->question_bank->question_type->name??'-'}}" data-placeholder="{{$q->question_bank->placeholder??'-'}}" data-description="{{$q->description??'-'}}">{{ $q->question_bank->content }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div>
                   <div class=" row mb-4">
                        <label for="placeholder" class="col-md-3 form-label">{{ trans('translation.placeholders') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_question_placeholder" name="placeholder" placeholder="{{ trans('translation.choose-question-first') }}" disabled>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div>
                   <div class=" row mb-4">
                        <label for="description" class="col-md-3 form-label">{{ trans('translation.description') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_question_description" name="description" placeholder="{{ trans('translation.choose-question-first') }}" disabled>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div>
                   <div class=" row mb-4">
                        <label for="question_type" class="col-md-3 form-label">{{ trans('translation.question_type') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_question_question_type" name="question_type" placeholder="{{ trans('translation.choose-question-first') }}" disabled>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div>
                     <!-- <div class=" row mb-4">
                        <label for="question_type" class="col-md-3 form-label">{{ trans('translation.question-type-name') }}</label>
                        <div class="col-md-9">
                            <select class="form-control selectpicker" name="question_type_id" id="question_type" data-actions-box="true" placeholder="{{ trans('translation.choose-one') }}" data-live-search="true">
                                @foreach ($questionTypes as $question_type)
                                <option class="" value="{{ $question_type->id }}" data-has-option="{{ $question_type->has_option }}">{{ $question_type->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div> 
                    <div class=" row mb-4">
                        <label for="regex_id" class="col-md-3 form-label">{{ trans('translation.regex') }}</label>
                        <div class="col-md-9">
                            <select class="form-control selectpicker" name="regex_id" id="regex_id" data-actions-box="true" placeholder="{{ trans('translation.choose-one') }}" data-live-search="true">
                                @foreach ($regexes as $regex)
                                <option class="" value="{{ $regex->id }}">{{ $regex->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div> -->
                    <div id="options-row" class="row mb-4 d-none">
                        <label for="options-div-container" class="col-md-3 form-label">{{ __('Options') }}</label>
                        <div id="options-div-container" class=" col-md-9" data-i="1">
                            <div class="mb-2 option-row">
                                <label for="inputContent" class="mb-2">{{ __('option-') }}1</label>
                                <div class="row col-md-12">
                                    <div class="col-md-11">
                                        <input type="text" class="form-control inputs-option" id="option-1" name="options[]" placeholder="{{ __('Option') }}">
                                        <div class="d-none text-danger">{{ __('This field is required') }}</div>
                                        <p class="text-danger d-none option-error">{{ __('Question must have at least one option') }}</p>
                                    </div>
                                    <div class=" col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm delete-option-btn" style="padding: 8px 10px"><i class="mdi mdi-delete"></i></button>
                                    </div>
                                </div>
                                <button id="add-option" type="button" class="btn btn-outline-primary btn-sm px-2 mt-2"><i class="mdi mdi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class=" row mb-4">
                        <label for="is_required" class="col-md-3 form-label">{{ trans('translation.required') }}</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="form-check form-switch form-switch-md">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_required" name="is_required" checked value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row mb-4">
                        <label for="is_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="form-check form-switch form-switch-md">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" checked value="1">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('translation.close') }}</button>
                    <button type="submit" id="add_question" class="btn btn-primary">{{ trans('translation.save-change') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('after-scripts')
<script>
    $(document).ready(function() {

        // window.notAllowedQuestionBankOrganization = @json($organization->question_bank_organizations->pluck('id'));
        // let notAllowedQuestionBankOrganizationIds;

        // $('#question_type').change(function() {
        //     var question_has_options_ids = JSON.parse('{{ json_encode($question_has_options_ids) }}');
        //     var showOption = $('#question_type').val();

        //     $('#options-row').addClass('d-none');
        //     $('#option-1').attr('disabled', true);

        //     if (question_has_options_ids.includes(+showOption)) {
        //         $('#options-row').removeClass('d-none');
        //         $('#option-1').attr('disabled', false);
        //     }
        // });

        $('#add_question_bank_id').change(function() {
            let option = $('#' + $(this).attr('id') + ' option:selected');
            $('#add_question_question_type').val(option.attr('data-question-type'));
            $('#add_question_description').val(option.attr('data-description'));
            $('#add_question_placeholder').val(option.attr('data-placeholder'));
            var showOption = option.attr('data-has-option');

            $('#options-row').addClass('d-none');
            $('#option-1').attr('disabled', true);

            if (showOption == 1) {
                $('#options-row').removeClass('d-none');
                $('#option-1').attr('disabled', false);
            }
        });

        // Add option handler ==================================================================
        $(document.body).on('click', '#add-option', function() {
            $('.option-error').addClass('d-none');
            $(this).remove();
            let i = $('#options-div-container').attr('data-i');
            i++;
            $('#options-div-container').append(new_option(i));
            html =
            '<button id="add-option" type="button" class="btn btn-outline-primary btn-sm px-2 mt-2"><i class="mdi mdi-plus"></i></button>';
            $('#options-div-container').append(html);
        });

        // options are regenerated when clicking delete button to preserve the index order
        function generateOptions(i, options) {
            let html = '';
            Array.from(options).forEach((option) => {
                html += new_option(i, option.value);
                i++;
            });
            html +=
            '<button id="add-option" type="button" class="btn btn-outline-primary btn-sm px-2 mt-2"><i class="mdi mdi-plus"></i></button>';
            $('#options-div-container').html(html)
            return html;
        }
        
        // New option row on clicking add option button =========================================
        function new_option(i, value = '') {
            var html =
                `<div class="mb-2 option-row">
                        <label for="inputContent" class="mb-2">{{ __('option-') }}${i}</label>
                        <div class="row col-md-12">
                            <div class="col-md-11">
                                <input type="text" class="form-control inputs-option" id="${i}" name="options[${i}]" placeholder="{{ __('Option') }}" value="${value}">
                            <div class="d-none text-danger">{{ __('This field is required') }}</div>
                                <p class="text-danger d-none option-error">{{ __('Question must have at least one option') }}</p>
                            </div>
                            <div class=" col-md-1">
                                <button type="button" class="btn btn-danger btn-sm delete-option-btn" style="padding: 8px 10px"><i class="mdi mdi-delete"></i></button>
                            </div>
                        </div>
                     </div>`;
            $('#options-div-container').attr('data-i', i);
            return html;
        }

        // Delete option handler ===============================================================
        $(document.body).on('click', '.delete-option-btn', function() {
            if ($('#options-div-container').attr('data-i') == 1) {
                $('.option-error').removeClass('d-none');
                return;
            }
            $('.option-error').addClass('d-none');
            delete_option($(this), $('#options-div-container'));
        });

        function delete_option(element, container) {
            var option = element.parents('.option-row');
            option.remove();
            // container.attr('data-i', container.attr('data-i') - 1);
            generateOptions(1, $('.inputs-option'))
        }

        $("#add_question_form").on("submit", function(event) {
            var add_question_bank_id = $('#add_question_bank_id').val();
            // var placeholder = $('#placeholder').val();
            // var regex_id = $('#regex_id').val();
            // var question_type = $('#question_type').val();
            var is_option_required = $('#add_question_bank_id option:selected').attr('data-has-option') == 1;

            let valid = true;
            valid = valid & is_empty(add_question_bank_id, $('#add_question_bank_id').parent()) // & is_empty(placeholder, '#placeholder') &
                // is_empty(question_type, $('#question_type').parent()); & is_empty(regex_id, $('#regex_id').parent());

            if (is_option_required) {
                $('.inputs-option').each(function(index, element) {
                    valid = valid & is_empty($(element).val(), element)
                });
            }

            if (!valid) {
                event.preventDefault();
            }
        });

        // Check empty fields ===========================================================
        function is_empty(field, input_id) {
            if (field == null || field == '') {
                $(input_id).addClass('border border-danger')
                $(input_id).next().removeClass('d-none')
                $(input_id).next().removeClass('d-none')
                return false;
            }
            $(input_id).removeClass('border border-danger')
            $(input_id).next().addClass('d-none')
            return true;
        }

    });
</script>
@endpush