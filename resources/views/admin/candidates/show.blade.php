@extends('layouts.master')
@section('title', __('Candidates'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

@endpush
@section('content')

    <x-breadcrumb title="{{ $candidate->name }}">
        <li class="breadcrumb-item"><a href="{{ route('candidates.index') }}">{{ trans('translation.candidates') }}</a></li>
    </x-breadcrumb>

    <div class="row">
        <div class="col-xxl-12">
            <div class="card">

                <div class="card-header d-flex justify-content-center">
                    <ul class="nav nav-pills custom-hover-nav-tabs">
                        {{-- tabs --}}
                        @component('components.nav-pills.pills', ['id' => 'info', 'icon' => 'ri-user-line'])
                        @endcomponent
                        @component('components.nav-pills.pills', ['id' => 'attachment', 'icon' => 'ri-file-text-line'])
                        @endcomponent
{{--                        @component('components.nav-pills.pills', ['id' => 'audits', 'icon' => 'ri-time-line'])--}}
{{--                        @endcomponent--}}
                    </ul>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col text-end">
                            @php
                                $deleteButton = '<button class="btn btn-outline-danger btn-sm m-1 on-default m-r-5 delete-candidate" data-candidate-id="' . $candidate->id . '"><i class="mdi mdi-delete"></i> "'. trans('translation.delete_candidate') .'"</button>';
                                $sendButton = '';
                                if ($candidate->status_id == \App\Models\Status::APPROVED_CANDIDATE){
                                    $sendButton = '<button class="btn btn-outline-primary btn-sm m-1 m-r-5 send-to-candidate" data-candidate-id="' . $candidate->id . '"><i class="mdi mdi-email-send-outline"></i>  "'. trans('translation.send_candidate_whatsapp') .'"</button>' ;
                                } elseif (in_array($candidate->status_id, [\App\Models\Status::COMPLETED_DATA_CANDIDATE,\App\Models\Status::ACCEPTED_CANDIDATE]) && !($candidate->is_cloned)){
                                    $sendButton = '<button class="btn btn-outline-primary btn-sm m-1 m-r-5 clone-to-users" data-candidate-id="' . $candidate->id . '"><i class="mdi mdi-content-duplicate"></i>  "'. trans('translation.clone-candidate-to-uesr') .'"</button>' ;
                                }
                                // $sendButton = ($candidate->status_id == App\Models\Status::APPROVED_CANDIDATE) ? '<button class="btn btn-outline-primary btn-sm m-1 m-r-5 send-to-candidate" data-candidate-id="' . $candidate->id . '"><i class="mdi mdi-email-send-outline"></i>  "'. trans('translation.send_candidate_whatsapp') .'"</button>' : '';
                                // $sendButton = ($candidate->status_id == App\Models\Status::COMPLETED_DATA_CANDIDATE) ? '<button class="btn btn-outline-primary btn-sm m-1 m-r-5 clone-to-users" data-candidate-id="' . $candidate->id . '"><i class="mdi mdi-content-duplicate"></i>  "'. trans('translation.clone-candidate-to-uesr') .'"</button>' : '';


                                $html = '<div><select class="form-control selectpicker status-select w-25" name="candidate_id" style="background:' . $candidate->status->color . '" data-status-id="' . $candidate->status_id . '" data-candidate-id="' . $candidate->id . '" onchange="changeSelectPicker(this)" >';
                                foreach ($statuses as $status) {
                                    $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
                                    $html .= '<option value="' . $status->id . '" ' . ($status->id == $candidate->status->id ? 'selected' : '') . ' ' . $span . ' data-note-required="' . ($status->is_note_required) . '" >' . $status->name . '</option>';
                                }
                                $html .= "</select></div>";
                            @endphp

                            <div class="row">
                                <div class="">
                                    {!! $html !!}
                                </div>
                                <div class="col-auto text-end">
                                    {!! $sendButton !!}
                                </div>
                                @can('delete_candidate')
                                <div class="col-3">
                                    {!! $deleteButton !!}
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>



                    <div class="tab-content">

                        {{-- معلومات المرشح --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'info', 'title' => 'info'])
                            @component('admin.candidates.components.candidate-tab', ['candidate' => $candidate])
                            @endcomponent
                        @endcomponent

                        {{-- المرفقات --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'attachment', 'title' => 'attachments'])
                            @component('admin.candidates.components.attachment-tab', ['candidate' => $candidate, 'remaining_attachments' => $remaining_attachments])
                            @endcomponent
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('after-scripts')
        {{-- save tab state --}}
        <script>
            $(document).ready(function() {
                localStorage.setItem('goBackHref',location.href);
                $('body').on('click','.send-to-candidate',function(){
                    let candidate_id = $(this).attr('data-candidate-id');
                    Swal
                        .fire(window.confirmSendMessagePopupSetup).then((result) => {
                        if (result.isConfirmed) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "{{ url('admin/candidates-message') }}",
                                data: {
                                    candidate_id: candidate_id
                                },
                                dataType: "json",
                                success: function (response, jqXHR, xhr) {
                                    window.location.reload();
                                    Toast.fire({
                                        icon: "success",
                                        title: "{{ trans('translation.message sent successfully') }}"
                                    });
                                },
                                error: function (response, jqXHR, xhr) {
                                    Toast.fire({
                                        icon: "error",
                                        title: "{{ trans('translation.something went wrong') }}"
                                    });
                                },
                            });
                        }
                    });
                })
                $('body').on('click','.clone-to-users',function(){
                    let candidate_id = $(this).attr('data-candidate-id');
                    let newValues = {
                        title : "{{trans("translation.Warning")}}",
                        text: "{{trans("translation.Do you really want to clone this Candidate info to Users?")}}",
                    }
                    Swal
                        .fire({ ...window.confirmRecreatePopupSetup, ...newValues }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "{{ url('admin/clone-candidate-to-users') }}",
                                data: {
                                    candidate_id: candidate_id
                                },
                                dataType: "json",
                                success: function (response, jqXHR, xhr) {
                                    window.location.reload();
                                    Toast.fire({
                                        icon: "success",
                                        title: "{{ trans('translation.cloned successfully') }}"
                                    });
                                },
                                error: function (response, jqXHR, xhr) {
                                    Toast.fire({
                                        icon: "error",
                                        title: response.responseJSON.message ??
                                            "{{ trans('translation.something went wrong') }}"
                                    });
                                },
                            });
                        }
                    });
                })
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
                                    Toast.fire({
                                        icon: "success",
                                        title: response.message
                                    });
                                    window.location.href = "{{ route('candidates.index') }}";
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
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "GET",
                        url: "{{ route('candidates.datatable') }}",
                        data: {
                            depatment_name: $('#department_name_filter').val(),
                            status_id: $('#status_id_filter').val(),
                            years_of_experience: $('#years_of_experience_filter').val(),
                            resident_status: $('#resident_status_filter').val(),
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            window.candidatesDatatable.ajax.reload();
                        },
                        error: function(response, jqXHR, xhr) {
                            Toast.fire({
                                icon: "error",
                                title: "{{ trans('translation.something went wrong') }}"
                            });
                        },
                    });
                });
                $('#candidate-reset-btn').click(function() {
                    $('.selectpicker').selectpicker('deselectAll');
                    window.candidatesDatatable.ajax.reload();
                });

            }); // end document rready
            function changeSelectPicker(select) {

                var select = $(select);
                Swal
                    .fire(window.confirmChangeStatusPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ url('admin/candidates-status') }}",
                            data: {
                                status_id: select.val(),
                                candidate_id: select.attr('data-candidate-id')
                            },
                            dataType: "json",
                            success: function (response, jqXHR, xhr) {
                                window.location.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Updated successfuly') }}"
                                });
                            },
                            error: function (response, jqXHR, xhr) {
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.You dont have permission') }}"
                                });
                            },
                        });
                    } else {
                        select.val(select.attr('data-status-id'));
                        select.selectpicker({
                            width: '100%',
                        });
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const activeTab = JSON.parse(sessionStorage.getItem('candidates'))?.tab;
                // if it was null we will set it to first one
                if (activeTab == null) {
                    openTab(document.getElementById('info-tab'));
                } else {
                    openTab(document.getElementById(`${activeTab}-tab`))
                }
            });

            const setActiveTab = (tab) => {
                let state = JSON.parse(sessionStorage.getItem('candidates'))
                state = {
                    ...state,
                    tab: tab
                };
                sessionStorage.setItem('candidates', JSON.stringify(state));
            }

            const openTab = (elem) => {
                elem.click();
            }
        </script>

    @endpush

@endsection
