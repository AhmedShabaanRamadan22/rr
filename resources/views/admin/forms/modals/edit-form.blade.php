<!--  modal -->
<div class="modal fade modal-lg" id="editFormModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content modal-content-demo">
            <form id="edit-form" action="{{ route('admin.forms.update') }}" method="post" onsubmit="formSubmitted(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_id" id="form_id" value="">
                <div class="modal-header bg-primary p-3">
                    <h6 class="modal-title text-white">{{trans('translation.edit-form')}}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <div class="modal-body">

                    {{-- <div class=" row mb-4">
                        <label for="content" class="col-md-3 form-label">{{ trans('translation.name') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control check-empty-input" id="form_name" name="name"
                                placeholder="{{ trans('translation.enter-name') }}" required>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div> --}}
                    <div class="row">
                        @component('components.inputs.text-input',['columnName'=>'form_name','col'=>'6','margin'=>'mb-3']) @endcomponent
                        @component('components.inputs.text-input',['columnName'=>'code','col'=>'6','margin'=>'mb-3']) @endcomponent
                        @component('components.inputs.text-input',['columnName'=>'description','col'=>'6','margin'=>'mb-3']) @endcomponent
                        @component('components.inputs.select-input',['columnName'=>'display_edit','col'=>'6','margin'=>'mb-3','columnOptions'=>$columnOptions]) @endcomponent
                        @component('components.inputs.select-input',['columnName'=>'submissions_times_edit','col'=>'6','margin'=>'mb-3','columnOptions'=> $columnOptions]) @endcomponent
                        @component('components.inputs.select-input',['columnName'=>'submissions_by_edit','col'=>'6','margin'=>'mb-3','columnOptions'=>$columnOptions]) @endcomponent
                        @component('components.inputs.switch-input',['columnName'=>'form_visible','col'=>'6','margin'=>'mb-3']) @endcomponent
                    </div>
                    {{-- <input class=" d-none" type="checkbox" role="switch" name="is_visible" checked value="0">
                    <div class=" row mb-4">
                        <label for="form_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="form-check form-switch form-switch-md">
                                    <input class="form-check-input check-empty-input" type="checkbox" role="switch" id="form_visible"
                                        name="is_visible" value="1">
                                </div>
                            </div>
                        </div> --}}



                </div>
                <div class="border-dashed border-top mx-2 p-2"></div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button class="btn ripple btn-danger" data-bs-dismiss="modal"  type="button">{{trans('translation.cancel')}}</button>
                        <button type="submit" class="btn btn-primary" disabled data-form-id="" id="a-form-btn">{{ trans('translation.save-change') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End  modal -->

@push('after-scripts')
    <script>

        $(document).ready(function() {
            $('#editFormModal').on('show.bs.modal', function(e) {

                // var form_id = $(this).attr('data-form-id');
                // var form_id = e.relatedTarget.id;
                let target = e.relatedTarget;
                let form_id = target.getAttribute('data-form-id');
                let form_name = target.getAttribute('data-form-name');
                let form_code = target.getAttribute('data-form-code');
                let form_description = target.getAttribute('data-form-description');
                let form_submissions_times = target.getAttribute('data-form-submissions-times');
                let form_submissions_by = target.getAttribute('data-form-submissions-by');
                let form_display = target.getAttribute('data-form-display');
                let form_visible = target.getAttribute('data-form-visible');
                $('#form_id').val(form_id);
                $('#input_form_name').val(form_name);
                $('#input_code').val(form_code);
                $('#input_description').val(form_description);
                // $('#display_edit_filter').val(form_display);
                // $('#submissions_by_edit_filter').val(form_submissions_by);
                // $('#submissions_times_edit_filter').val(form_submissions_times);

                $('#display_edit_filter').selectpicker('destroy')
                $('#display_edit_filter').selectpicker('val',[form_display])

                $('#submissions_times_edit_filter').selectpicker('destroy')
                $('#submissions_times_edit_filter').selectpicker('val',[form_submissions_times])

                $('#submissions_by_edit_filter').selectpicker('destroy')
                $('#submissions_by_edit_filter').selectpicker('val',[form_submissions_by])
                
                // $('#editFormModal select').selectpicker('destroy');
                // $('#editFormModal select').selectpicker({});

                if (form_visible == 1) {
                    $('#input_form_visible').attr('checked', "checked");
                } else {
                    $('#input_form_visible').attr('checked', false);

                }

            });
            $('#editFormModal .check-empty-input').on('change', function(){
                let flag = true;
                let data_regex = $(this).attr("data-regex");
                let column_name = $(this).attr("name");
                                if (typeof data_regex !== 'undefined' && data_regex !== false) {
                    var regex = new RegExp(data_regex);
                    if (!regex.test($(this).val())) {
                        $('#error-' + column_name).removeClass('d-none')
                        $('#input_' + column_name).addClass('border-danger')
                        return
                    }
                    $('#error-' + column_name).addClass('d-none')
                    $('#input_' + column_name).removeClass('border-danger')
                }
                $('#editFormModal .check-empty-input').each(function() {
                    if (!$(this).is('div')){
                        if($(this).prop('required') && $(this).val() == ''){
                            flag = false;
                            return;
                        }
                    }
                });
                $('#a-form-btn').prop('disabled', !flag)
            })

            // $('form').on('submit', function(e){
            //     let sendBtn = $('#a-form-btn');
            //     sendBtn.empty();
            //     sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
            //     sendBtn.prop('disabled', true)
            // })

        //     $('#a-form-btn').on('click', function(e){
        //     e.preventDefault(); // Prevent the default form submission
        //     let sendBtn = $('#a-form-btn');
        //     let form_id = sendBtn.attr('data-form-id');
        //     sendBtn.empty();
        //     sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
        //     sendBtn.prop('disabled', true);
            
        //     let formData = $(this).serialize(); // Serialize form data
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         url: '{{ url("forms") }}' + "/" + form_id,
        //         type: 'PUT',
        //         data:{
        //             form_name: $('#input_form_name').val(),
        //             form_display: $('#display_filter').val(),
        //             form_submissions_times: $('#submissions_times_filter').val(),
        //             form_submissions_by: $('#submissions_by_filter').val(),
        //             form_visible: $('#input_form_visible').val(),
        //         },
        //         // beforeSend: function() {
                    
        //         // },
        //         success: function (data) {
        //             Toast.fire({
        //                 icon: "success",
        //                 title: response.message
        //             });
        //         },
        //         error: function (error) {
        //         },
        //         complete: function() {
        //             let sendBtn = $('#a-form-btn');
        //             sendBtn.empty().text('Submit');
        //             sendBtn.prop('disabled', false);
        //         }
        //     });
        // });

        })
    </script>
@endpush
