@component('modals.add-modal-template',['modalName'=>'questions','modalRoute'=>'questions'])
    <input type="hidden" name="questionableId" value="{{$questionableId}}">
    <input type="hidden" name="questionableType" value="{{$questionableType}}">
    <div class=" row mb-4"> 
        <label for="edit_question_bank"
            class="col-md-3 form-label">{{ trans('translation.content') }}</label>
        <div class="col-md-9">
            <select class="selectpicker form-control check-empty-input" name="question_bank_organization_id" id="add_question_bank_id"
                placeholder="{{ trans('translation.choose-one') }}" data-live-search="true" required>
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
    <div id="options-row" class="row mb-4 d-none">
        <label for="options-div-container" class="col-md-3 form-label">{{ trans('translation.options')}}</label>
        <div id="options-div-container" class=" col-md-9" data-i="1">
            <div class="mb-2 option-row">
                <label for="inputContent" class="mb-2">{{ trans('translation.option') }} - 1</label>
                <div class="row col-md-12">
                    <div class="col-md-11">
                        <input type="text" class="form-control inputs-option check-empty-input" id="option-1" name="options[]" placeholder="{{ trans('translation.option-placeholder')}}">
                        <div class="d-none text-danger">{{ trans('translation.required-field') }}</div>
                        <p class="text-danger d-none option-error">{{ trans('translation.requires-one-option') }}</p>
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
            <!-- <div class="form-group">
                <div class="form-check form-switch form-switch-md">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_required" name="is_required" checked value="1">
                </div>
            </div> -->
            <select class="selectpicker form-control check-empty-input" name="is_required" id="is_required_select" placeholder="{{ trans('translation.choose-one') }}" required>
                <option class="" value="1"  >{{trans('translation.required')}}</option>
                <option class="" value="0"  >{{trans('translation.not-required')}}</option>
                <option class="" value="default"  >{{trans('translation.default')}}</option>
            </select>
        </div>
    </div>
    <div class=" row mb-4">
        <label for="is_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
        <div class="col-md-9">
            <!-- <div class="form-group">
                <div class="form-check form-switch form-switch-md">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" checked value="1">
                </div>
            </div> -->
            <select class="selectpicker form-control check-empty-input" name="is_visible" id="is_visible_select" placeholder="{{ trans('translation.choose-one') }}" required>
                <option class="" value="1"  >{{trans('translation.visible')}}</option>
                <option class="" value="0"  >{{trans('translation.not-visible')}}</option>
                <option class="" value="default"  >{{trans('translation.default')}}</option>
            </select>
        </div>
    </div>
@endcomponent

@push('after-scripts')
<script>
    $(document).ready(function() {
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
                        <label for="inputContent" class="mb-2">{{ trans('translation.option') }} - ${i}</label>
                        <div class="row col-md-12">
                            <div class="col-md-11">
                                <input type="text" class="form-control inputs-option check-empty-input" id="${i}" name="options[${i}]" placeholder="{{ trans('translation.option-placeholder') }}" value="${value}">
                            <div class="d-none text-danger">{{ trans('translation.required-field') }}</div>
                                <p class="text-danger d-none option-error">{{ trans('translation.requires-one-option') }}</p>
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

        $("#submit-questions-btn").on("click", function(event) {
            var is_option_required = $('#add_question_bank_id option:selected').attr('data-has-option') == 1;

            let valid = true;
            valid = valid & is_empty(add_question_bank_id, $('#add_question_bank_id').parent()) // & is_empty(placeholder, '#placeholder') &

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