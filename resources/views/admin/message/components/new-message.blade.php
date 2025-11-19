<div class="row">
    <div class="card-body">
        <div class="row">
            <div class="text-info">
                <span><i class="mdi mdi mdi-numeric-1-box-outline"></i>
                    {{ trans('translation.choose-sender-identity') }}</span>
                <br>
                <span><i class="mdi mdi mdi-numeric-2-box-outline"></i>
                    {{ trans('translation.choose-user-type') }}</span>
                <br>
                <span><i class="mdi mdi mdi-numeric-3-box-outline"></i>
                    {{ trans('translation.choose-customization') }}</span>
                <br>
                <span><i class="mdi mdi mdi-numeric-4-box-outline"></i> {{ trans('translation.write-title') }}</span>
                <br>
                <span><i class="mdi mdi mdi-numeric-5-box-outline"></i>
                    {{ trans('translation.write-content-new-line') }}</span>
            </div>
            <div class="mt-3"></div>
            @component('admin.message.components.input-row', ['title' => 'from', 'id' => 'sender'])
            <select class="form-control selectpicker mb-3" id="sender"  name="choices-multiple-remove-button">
                <option value="" selected disabled>{{ trans('translation.choose-sender') }}</option>
                @foreach ($senders as $sender)
                <option value="{{ $sender->id }}" data-has-whatsapp="{{$sender->has_whatsapp}}" data-has-email="{{$sender->has_email}}" data-has-sms="{{$sender->has_sms}}" data-organization-id="{{$sender->organization->id??""}}" data-organization-name="{{$sender->organization->name??""}}">{{ $sender->name??(trans('translation.no-determine-sender')) }}</option>
                @endforeach
            </select>
            <label for="role" class="h6 text-primary">{{ trans('translation.by') }}:</label>
            <br>
            @foreach (['whatsapp','sms'] as $send_with)
            <div class="form-check form-check-inline">
                <input class="form-check-input send_with" name="send_{{$send_with}}" type="checkbox" value="1" id="send_{{$send_with}}">
                <label class="form-check-label" for="send_{{$send_with}}">{{trans('translation.'.$send_with)}}</label>
            </div>

            @endforeach
            <!-- <div id="send_with_container" class="d-none"></div> -->

            @endcomponent

            @component('admin.message.components.input-row', ['title' => 'to', 'id' => 'users'])
            <label for="role" class="h6 text-primary">{{ trans('translation.roles') }}:</label>
            <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input roles" name="roles[]" type="checkbox" value="0" id="all_roles">
                <label class="form-check-label" for="all_roles">{{ trans('translation.all') }}</label>
            </div>

            @foreach ($roles as $role)
            <div class="form-check form-check-inline">
                <input class="form-check-input roles" name="roles[]" type="checkbox" value="{{ $role->id }}" id="{{ $role->id }}">
                <label class="form-check-label" for="{{ $role->id }}">{{ trans('translation.' . $role->name) }}</label>
            </div>
            @endforeach

            <br>
            <div class="mt-3"></div>

            <label for="customization" class="h6 text-primary">{{ trans('translation.customization') }}:</label>
            <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input customization" value="all" type="radio" name="customization" id="all_users" checked>
                <label class="form-check-label" for="all_users">{{ trans('translation.all-users') }} <span  id="all_users_span"></span></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input customization" value="customization" type="radio" name="customization" id="customize">
                <label class="form-check-label" for="customize">{{ trans('translation.customize') }}</label>
            </div>

            <div id="users_container" class="d-none mt-2"></div>
            @endcomponent

            <div class="mt-2"></div>

            @component('admin.message.components.input-row', ['title' => 'subject', 'id' => 'subject'])
            <input id="subject" type="text" class="form-control" placeholder="{{ trans('translation.subject') }}">
            @endcomponent

            @component('admin.message.components.input-row', ['title' => 'message', 'id' => 'message'])
            <textarea class="w-100 border-primary-subtle" name="" id="message" rows="10"></textarea>
            @endcomponent

            <div class=" text-end">
                <button id="sendBtn" type="button" class="btn btn-primary">{{ trans('translation.send') }}</button>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {
        // Constants
        const senderError = $('#senderError');
        const rolesError = $('#rolesError');
        const customizationError = $('#customizationError');
        const sendBtn = $('#sendBtn');
        const messageTextarea = $('#message');

        let users = [];
        let selectedUsers = [];
        let senderOrganizationId ;
        let rolesArray;
        let receivers = @json($receivers);

        // Event listener for Enter key in textarea
        messageTextarea.keydown(function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                insertNewline();
            }
        });

        // Function to insert newline character
        function insertNewline() {
            let currentText = messageTextarea.val();
            let newText = currentText + ' \\n';
            messageTextarea.val(newText);
        }

        // Function to print entered value to console
        window.printEnteredValue = function() {
            let enteredValue = messageTextarea.val();
        };
        
        // Initialize customization value
        let customizationVal = $("input[name='customization']:checked").val();
        
        // Event listener for customization change
        $('.customization').on('change', function() {
            customizationVal = $("input[name='customization']:checked").val();
            rolesArray = selectedRoles();
            senderOrganizationId = +$("#sender option:selected").attr('data-organization-id');
            
            if (customizationVal == 'all') {
                updateSendButtonState();
                $('#users_container').empty();
                $('#customizationError').html('');
                selectedUsers = receivers.filter((receiver) => {
                        let userRoles = receiver.role_ids_array;
                        return rolesArray.some(item => userRoles.includes(item)) && senderOrganizationId == receiver.organization_id;
                    }).map(function(user, index) {
                    return {
                        id: user.id,
                        phone: user.phone,
                        phone_code: user.phone_code
                    };
                });
            } else {
                updateSendButtonState();
                selectedUsers = []
                $('#users_container').removeClass('d-none');
                let newSelect = $('<select>', {
                    class: 'form-control',
                    id: 'newSelect',
                    name: 'newSelect',
                    multiple: true
                });
                receivers.forEach(function(receiver) {
                    let userRoles = receiver.role_ids_array;
                    if (rolesArray.some(item => userRoles.includes(item)) ) {
                        newSelect.append($('<option>', {
                            value: receiver.id,
                            text: receiver.name
                        }))
                    }
                });
                // for (let i = 0; i < receivers.length; i++) {
                //     let optionData = receivers[i];
                //     newSelect.append($('<option>', {
                //         value: optionData.id,
                //         text: optionData.name
                //     }));
                // }
                $('#users_container').append(newSelect);
                let choices = new Choices('#newSelect', {
                    allowHTML: true,
                    removeItemButton: true,
                    searchEnabled: true,
                    searchChoices: true,

                    placeholder: true,
                    placeholderValue: '{{ trans("translation.choose-persons") }}',
                    noChoicesText: users.length !== 0 ?
                        '{{ trans("translation.no-options-available") }}' : '{{ trans("translation.select-user-types-first") }}',
                    noResultsText: '{{ trans("translation.no-results-found") }}',
                    itemSelectText: '',
                    loadingText: '{{ trans("translation.loading") }}...',
                });

                newSelect.on('change', function(event) {
                    let selectedValues = $(this).val();
                    selectedUsers = receivers.filter(function(user) {
                        return (selectedValues.includes(user.id.toString()) ||
                            selectedValues.includes(user.name) ) ;
                    }).map(function(user, index) {
                        return {
                            id: user.id,
                            phone: user.phone,
                            phone_code: user.phone_code
                        };
                    });
                    updateSendButtonState();
                });
                $('#customizationError').html('');
            }
        });

        // Event listener for roles change
        $('.roles').change(function() {
            if (!$(this).prop('checked')) {
                $('#all_roles').prop('checked', false);
                users = [];
                selectedUsers = [];
                $('#all_users').prop('checked', true);
                $('#users_container').empty();
                updateSendButtonState()
            } else {
                $('#users_container').empty();
                $('#all_users').prop('checked', true);
                if ($('.roles:checked').length === $('.roles').length) {
                    $('#all_roles').prop('checked', true);
                    $('#all_users').prop('checked', true);
                }
                updateSendButtonState();
            }
        });

        // Event listener for all_roles change
        $('#all_roles').change(function() {
            $('.roles').prop('checked', $(this).prop('checked'));
            $('#all_users').prop('checked', true);
            updateSendButtonState();
        });

        // Event listener for sender change
        $('#sender').on('change', function() {
            $('.roles').prop('checked', false);
            $('#all_roles').prop('checked', false);
            $('#all_users').prop('checked', true);
            users = [];
            selectedUsers = [];
            $('#users_container').empty();
            $('#all_users_span').text($("#sender option:selected").attr('data-organization-name'));
            updateSendWithOptions()
            updateSendButtonState();
        });
        
        $('.send_with').on('change',function(){
            updateSendButtonState();
        });

        // Function to update send button state
        function updateSendButtonState() {
            // Get selected values and states
            let selectedSender = $('#sender').val();
            let checkedSendWith = $('.send_with:checked');
            let selectedRoleValues = selectedRoles();
            let isAllUsersChecked = $('#all_users').is(':checked');
            let isCustomizeChecked = $('#customize').is(':checked');
            senderOrganizationId = $("#sender option:selected").attr('data-organization-id');

            // Clear error messages
            senderError.html('');
            rolesError.html('');
            customizationError.html('');

            // Check if sender is not selected


            // Check if no roles are selected
            if (selectedRoleValues.length === 0 || checkedSendWith.length === 0) {
                sendBtn.prop('disabled', true).removeClass('btn-primary').addClass('btn-outline-danger');
                return;
            } else {
                if (isAllUsersChecked) {
                    selectedUsers = receivers.filter((receiver) => {
                        let userRoles = receiver.role_ids_array;
                        return selectedRoleValues.some(item => userRoles.includes(item))  && senderOrganizationId == receiver.organization_id;
                    }).map(function(user, index) {
                            return {
                                id: user.id,
                                phone: user.phone,
                                phone_code: user.phone_code
                            };
                        
                    });
                }
                sendBtn.prop('disabled', selectedUsers.length === 0).removeClass(
                    'btn-outline-danger').addClass('btn-primary');
            }
        }

        function selectedRoles() {
            return rolesArray = $('.roles:checked').map(function() {
                return +$(this).val();
            }).get();
        }

        function updateSendWithOptions(){
            let optionSelected = $('#sender option:selected');
            $('.send_with').each(function(){
                $(this).prop('checked',false);
                let sendWith = ($(this).attr('id').replace('send_',''));
                // $(this).prop('checked',optionSelected.attr('data-has-'+sendWith));
                $(this).prop('disabled',!optionSelected.attr('data-has-'+sendWith));
            })

        }

        function resetInputs(){
            $("#subject").val('');
            $("#message").val('');
            $('#sender').val('').selectpicker('destroy').selectpicker();
            $('.roles').prop('checked',false);
            $('.send_with').prop('checked',false);
            $('#all_users_span').text('');

        }

        // Event listener for send button click
        $('#sendBtn').on('click', function(e) {
            let sendBtn = $(this);
            if (!$('#subject').val()) {
                Swal.fire('{{trans("translation.Subject field is Required")}}');
                return
            }
            if (!$('#message').val()) {
                Swal.fire('{{trans("translation.Message field is Required")}}');
                return
            }
            let data = {
                sender: $('#sender').val(),
                send_whatsapp: $('#send_whatsapp').is(':checked'),
                send_email: $('#send_email').is(':checked'),
                send_sms: $('#send_sms').is(':checked'),
                receivers: selectedUsers,
                subject: $('#subject').val(),
                message: $('#message').val()
            }

            sendBtn.empty();
            sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
            // Perform AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST', // or 'GET' depending on your backend route
                url: '{{ url("admin/messages/send") }}',
                data: data,
                dataType: 'json',
                success: function(responseData) {
                    // Handle success
                    Toast.fire({
                        icon: "success",
                        title: "{{ trans('translation.send-messages-successfully') }}"
                    });
                    sendBtn.empty();
                    sendBtn.append('{{trans("translation.send")}}')
                    resetInputs();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle error
                    sendBtn.empty();
                    sendBtn.append('{{trans("translation.send")}}')
                }
            });
        });

        // Initial state check
        updateSendButtonState();
        updateSendWithOptions();

        // $('#customize').change(function(){
        //     // let choices ;
        //     if($(this).is(':checked')){
        //         $('#users_container').removeClass('d-none');


        //         $('#selectUsers').empty();
        //         receivers.forEach(function(receiver){
        //             let userRoles = receiver.role_ids_array;
        //             if(rolesArray.some( item => userRoles.includes(item))  ){
        //                 $("#selectUsers").append($('<option>', {
        //                     value: receiver.id,
        //                     text: receiver.name
        //                 }))
        //             }
        //         });

        //         let choices = new Choices('#selectUsers', {
        //             allowHTML: true,
        //             removeItemButton: true,
        //             searchEnabled: true,
        //             searchChoices: true,

        //             placeholder: true,
        //             placeholderValue: '{{ trans("translation.choose-persons") }}',
        //             noChoicesText: //users.length !== 0 ?
        //                 '{{ trans("translation.no-options-available") }}' ,
        //                 //:'{{ trans("translation.select-user-types-first") }}',
        //             noResultsText: '{{ trans("translation.no-results-found") }}',
        //             itemSelectText: '',
        //             loadingText: '{{ trans("translation.loading") }}...',
        //         });
        //     }else{
        //         $('#usersContainer').addClass('d-none');

        //     }
        // })
    }); //end document ready 
</script>
@endpush