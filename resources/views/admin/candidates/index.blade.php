@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

    {{-- index  --}}
    <x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle"
                  :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions" :filterColumns="$filterColumns" :showAddButton="false" :canAllColumns="$can_all_columns" datatableAjaxType="POST">
                <x-slot name="filters">
                    @include('admin.candidates.components.filters')
                </x-slot>
    </x-crud-model>
    @component('admin.candidates.modals.show-modal-template', [
        'modalName' => $tableName,
        'modalRoute' => str_replace('_', '-', $tableName),
    ])

        <x-row-info id="candidate_profile_personal_attachment_url"
                    label="{{ trans('translation.candidate_profile_personal_attachment_url') }}"></x-row-info>
        @foreach ($columnInputs as $key => $value)
            @component('components.data-row', ['id' => $key, 'div_col' => 'col-lg-6','label_col'=>'col-lg-6'])
            @endcomponent
        @endforeach
        @component('components.data-row', ['id' => 'self_description', 'div_col' => 'col-lg-8','label_col'=>''])
        @endcomponent

    @endcomponent

    @push('after-scripts')
        <script>
            $(document).ready(function() {
                localStorage.setItem('goBackHref',location.href);
                // $('body').on('click','.send-to-candidate',function(){
                //     let candidate_id = $(this).attr('data-candidate-id');
                //     Swal
                //         .fire(window.confirmSendMessagePopupSetup).then((result) => {
                //             if (result.isConfirmed) {
                //                 $.ajaxSetup({
                //                     headers: {
                //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //                     }
                //                 });
                //                 $.ajax({
                //                         type: "POST",
                //                         url: "{{ url('admin/candidates-message') }}",
                //                         data: {
                //                             candidate_id: candidate_id
                //                         },
                //                         dataType: "json",
                //                         success: function (response, jqXHR, xhr) {
                //                             window.candidatesDatatable.ajax.reload();
                //                             Toast.fire({
                //                                 icon: "success",
                //                                 title: "{{ trans('translation.message sent successfully') }}"
                //                             });
                //                         },
                //                         error: function (response, jqXHR, xhr) {
                //                             Toast.fire({
                //                                 icon: "error",
                //                                 title: "{{ trans('translation.something went wrong') }}"
                //                             });
                //                         },
                //                 });
                //             }
                //         });
                // })
                $(document.body).on('click', '.delete-candidate', function(e) {
                    var candidate_id  = $(this).attr('data-candidate-id')
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: 'DELETE',
                                    url: "{{ url('candidates') }}/" + candidate_id,
                                    success: function(response) {
                                        window.candidatesDatatable.ajax.reload();
                                        Toast.fire({
                                                icon: "success",
                                                title: response.message
                                            });
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Swal
                                            .fire({
                                                title: "{{ trans('translation.Warning') }}",
                                                text: jqXHR.responseJSON.message,
                                                icon: "error",
                                                showConfirmButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonText: "{{ trans('translation.OK') }}"
                                            })

                                    },
                                });
                            }
                        });
                });
                    $('#candidate-filter-btn').click(function() {
                        setLoading(true);
                        window.candidatesDatatable.ajax.reload(); 
                    //     $.ajaxSetup({
                    //         headers: {
                    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //         }
                    //     });
                    // $.ajax({
                    //     type: "GET",
                    //     url: "{{ route('candidates.datatable') }}",
                    //     data: {
                    //         depatment_name: $('#department_name_filter').val(),
                    //         status_id: $('#status_id_filter').val(),
                    //         years_of_experience: $('#years_of_experience_filter').val(),
                    //         resident_status: $('#resident_status_filter').val(),
                    //     },
                    //     dataType: "json",
                    //     success: function(response, jqXHR, xhr) {
                    //         window.candidatesDatatable.ajax.reload();
                    //     },
                    //     error: function(response, jqXHR, xhr) {
                    //         Toast.fire({
                    //             icon: "error",
                    //             title: "{{ trans('translation.something went wrong') }}"
                    //         });
                    //     },
                    // });
                });
                $('#candidate-reset-btn').click(function() {
                    setLoading(true);
                    $('.selectpicker').selectpicker('deselectAll');
                    window.candidatesDatatable.ajax.reload();
                    
                });

                $('#candidates-datatable').on('draw.dt', function () {
                    setLoading(false);
                });

            }); // end document rready
            // function changeSelectPicker(select) {

            //     var select = $(select);
            //     Swal
            //         .fire(window.confirmChangeStatusPopupSetup).then((result) => {
            //         if (result.isConfirmed) {
            //             $.ajaxSetup({
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //                 }
            //             });
            //             $.ajax({
            //                 type: "POST",
            //                 url: "{{ url('admin/candidates-status') }}",
            //                 data: {
            //                     status_id: select.val(),
            //                     candidate_id: select.attr('data-candidate-id')
            //                 },
            //                 dataType: "json",
            //                 success: function (response, jqXHR, xhr) {
            //                     window.candidatesDatatable.ajax.reload();
            //                     Toast.fire({
            //                         icon: "success",
            //                         title: "{{ trans('translation.Updated successfuly') }}"
            //                     });
            //                 },
            //                 error: function (response, jqXHR, xhr) {
            //                     Toast.fire({
            //                         icon: "error",
            //                         title: "{{ trans('translation.You dont have permission') }}"
            //                     });
            //                 },
            //             });
            //         } else {
            //             select.val(select.attr('data-status-id'));
            //             select.selectpicker({
            //                 width: '100%',
            //             });
            //         }
            //     });
            // }

            // Event listener for showing the candidate modal
            // $('body').on('click', '.show' + '{{$tableName}}', function () {
            //     // get the data-model-id attribute value
            //     var model_id = $(this).attr('data-model-id');

            //     $.ajax({
            //         url: "{{ url('/' . str_replace('_', '-', $tableName)) }}" + "/" + model_id,
            //         type: 'GET',
            //         success: function (data) {
            //             $.each(data, function (key, value) {
            //                 var infoElement = $('[id="' + key + '"]');
            //                 if (infoElement.length) {
            //                     if (key === 'candidate_cv_attachment_url' || key === 'candidate_portfolio_attachment_url' || key === 'candidate_national_id_attachment_url' || key === 'candidate_iban_attachment_url') {
            //                         var attachmentLink = '';
            //                         if (value && value !== '-') {
            //                             attachmentLink = '<a class="btn btn-outline-secondary btn-sm m-1 on-default" target="_blank" href="' + value + '"><i class="mdi mdi-eye"></i></a>';
            //                             attachmentLink += '<a href="' + value + '" class="btn btn-outline-primary btn-sm m-1 on-default m-r-5" download><i class="mdi mdi-file-download-outline"></i></a>';
            //                         } else {
            //                             attachmentLink = "{{trans('translation.no-data')}}";
            //                         }
            //                         infoElement.html(attachmentLink);
            //                     } else if (key === 'candidate_profile_personal_attachment_url') {
            //                         if (value && value !== '-') {
            //                             // Display the image using an <img> tag
            //                             var imageTag = '<div class="col-lg-2 text-center position-relative">'; // Add position-relative to the container
            //                             imageTag += '<div class=" mt-md-0">';
            //                             imageTag += '<img class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image bg-primary" alt="200x200" id="candidate_profile_personal_attachment_url" data-holder-rendered="true" src="' + value + '">';
            //                             imageTag += '<div class="avatar-xs p-0 rounded-circle profile-photo-edit position-absolute bottom-0 end-0">'; // Adjust position of the edit icon
            //                             imageTag += '<a href="' + value + '" target="_blank">'; // Open the avatar image in a new tab
            //                             imageTag += '<label for="profile-img-file-input" class="profile-photo-edit avatar-xs">';
            //                             imageTag += '<span class="avatar-title rounded-circle bg-light text-body" style="cursor: pointer;"><i class="bi bi-eye"></i></span>'; // Eye icon for image preview
            //                             imageTag += '</label>';
            //                             imageTag += '</a>';
            //                             imageTag += '</div>';
            //                             imageTag += '</div>';
            //                             imageTag += '</div>';
            //                             infoElement.html(imageTag);
            //                         } else {
            //                             infoElement.html("{{trans('translation.no-data')}}");
            //                         }
            //                     } else {
            //                         infoElement.text(value || "{{trans('translation.no-data')}}");
            //                     }

            //                 }
            //             });

            //             var statusBadge = "<span class='badge my-2 p-2 mx-3 font-xl fs-6 ' style='background:" + (data.candidate_status_name ? data.candidate_status_name.color : '') + "'>" + (data.candidate_status_name ? data.candidate_status_name.name_ar : "{{trans('translation.no-data')}}") + "</span>";
            //             $('[id="status"]').html(statusBadge);
            //             $('#show' + '{{$tableName}}').modal('show');
            //         },
            //         error: function (error) {
            //             console.error('Error fetching data:', error);
            //         }
            //     });
            // });
        </script>
    @endpush
@endsection
