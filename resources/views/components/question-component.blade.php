@props(['title', 'questionableId', 'questionTypes', 'columns','subRoute', 'organization'])
<x-breadcrumb pageTitle="Questions"  :title="$title">
    <li class="breadcrumb-item"><a href="{{ route((str_replace('_','-',$subRoute)).'.index') }}">{{ trans('translation.'.str_replace('_',' ',$subRoute)) }}</a></li>
</x-breadcrumb>

{{-- {{dd($questionableId)}} --}}
<div class="row">
    <!-- Row -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    @component('components.section-header', ['title'=>'questions', 'showSortButton' => true])@endcomponent
                </div>
                {{-- <div class="card-header">
                    <h3 class="card-title">{{ trans('translation.all-question') }}</h3>
                </div> --}}
                <div class="card-body">
                    {{-- <div class="card-options text-end"> --}}
                        {{-- <button class="btn btn-primary waves-effect waves-light" data-target="#addQuestion" data-toggle="modal">{{ trans('translation.add-new-question') }}</button> --}}
                        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addQuestion">
                            {{ trans('translation.add-new-question') }}
                        </button> --}}
                    {{-- </div> --}}

                    <x-data-table id="questions-datatable" :columns="$columns"/>

                    {{-- @include('modals.add-question') --}}
                    @include('modals.edit-question')
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <button class="btn btn-secondary px-5 mx-2" type="button" onclick="goBack()"
                            id="backButton">{{ trans('translation.back') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.add-question-component')

@php
    $model = $questionableType;
    $type = $model;
    $model = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
    $organization_id = $organization->id;
    // $url = route('admin.' . $model . 's.datatable', $questionableId ?? 1);
    // dd($type, $model);
@endphp

@push('after-scripts')
    <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script>
        function goBack() {
            location.href=localStorage.getItem('goBackHref');
        }
        $(document).ready(function() {
            $('.selectPicker').selectpicker({
                width: '100%',
            });

            window.questions = $('#questions-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.questions.datatable') }}",
                    "data": function(d) {
                        // d.organization_id = {{ $organization_id }};
                        // d.organization_service_id = {{ $questionableId }};
                        d.question_id = {{ $questionableId }};
                        d.question_type = '{{ $type }}';
                    },
                    complete: function(data) {}
                },
                language: datatable_localized,
                rowId: 'id',
                "drawCallback": function(settings) {
                    $('.selectpicker').selectpicker({
                        width: '100%',
                    });
                    if(settings.json){
                        let notAllowedQuestionBankOrganization = settings.json.data.map((item)=>{return item.question_bank_organization_id});
                        let optionCounter = 0
                        $('#add_question_bank_id option').each(function(){
                            $(this).show();
                            if(notAllowedQuestionBankOrganization.includes(+$(this).val())){
                                $(this).hide()
                                optionCounter++;
                            }

                        })

                        $('#add_question_bank_id').prop('disabled',false);
                        $('#add_question_bank_id').attr('placeholder',"{{trans('translation.choose-one')}}");
                        if(optionCounter ==  $('#add_question_bank_id option').length-1){
                            $('#add_question_bank_id').prop('disabled',true);
                            $('#add_question_bank_id').attr('placeholder',"{{trans('translation.all-question-have-choosed')}}");

                        }

                        $('#add_question_bank_id').selectpicker('destroy').selectpicker({
                            width: '100%',
                        });
                    }
                },
                'createdRow': function(row, data, rowIndex) {
                    // Per-cell function to do whatever needed with cells
                    $(row).attr('data-question-id', data.id);
                    // $(row).attr('data-question-content', data.content);
                    // $(row).attr('data-question-placeholder', data.placeholder);
                    // $(row).attr('data-question-type', data.type);
                    $(row).attr('data-question-is_required', data.is_required);
                    $(row).attr('data-question-is_visible', data.is_visible);
                },
                'stateSave': true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                "columns": [{
                    "data": 'id',
                    render:  (data, type, row, meta) => { return ++meta.row; }
                },
                    // {
                    //     "data": 'Model_type',
                    // },
                    // {
                    //     "data": 'question_bank_organization_id',
                    // },
                    {
                        "data": 'content',
                    },
                    {
                        "data": 'description',
                    },
                    {
                        "data": 'question_type_name',
                    },
                    {
                        "data": 'arrangement',
                    },
                    {
                        "data": 'icon_is_required',
                    },
                    {
                        "data": 'icon_is_visible',
                    },
                    {
                        "data": 'options',
                        render: function(data, type, row) {
                            if(data.length == 0){
                                return '{{trans("translation.there-are-no-options")}}';
                            }

                            var html = '';
                            var i = 1;

                            data.forEach(function(option) {
                                html += '<span data-option-id="' + option.id +
                                    '" class="badge span-option-' + row.id +
                                    ' rounded-pill bg-primary text-light badge-sm me-1 mb-1 mt-1">' +
                                    option.content + '</span>';
                                html += (i % 3 == 0 ? '<br>' : '');
                                i++;
                            });
                            return html;
                        }
                    },
                    {
                        "data": "actions",
                        className: "text-center",
                    },
                ],
                buttons: ['csv', 'excel'],
                dom: 'lfritpB',
                "ordering": false,
            });
        });

        $(document.body).on('click', '.delete-question-btn', function(e) {
            let deleteBtn = $(this);
            let question_id = $(this).attr('question-id');
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
                        url: "{{ url('questions') }}/" + question_id,
                        success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Deleted successfuly') }}"
                                });
                                window.questions.ajax.reload();
                            },
                            error: function(jqXHR, responseJSON) {
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.something went wrong!') }}"
                                });

                            },
                        });
                    }
                });
        })



        // $(document).ready(function() {

        //     $('.selectPicker').selectpicker({
        //         width: '100%',
        //     });

        //     // Fetching questions to datatable =====================================================================
        //     // $('.modal').css('overflow-y', 'scroll');
        //     window.datatable = $('#questions-datatable').DataTable({
        //         "ajax": {
        //             "url": '{{-- $url  --}}',
        //         },
        //         language: {
        //             url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
        //         },
        //         rowId: 'id',
        //         'stateSave': true,
        //         // createdRow: function(row, data, indice) {
        //         //     $(row).find("td:eq(7)").attr('data-name', data.id).attr('data-type');
        //         // },
        //         'createdRow': function(row, data, rowIndex) {
        //             // Per-cell function to do whatever needed with cells
        //             $(row).attr('data-question-id', data.id);
        //             $(row).attr('data-question-content', data.content);
        //             $(row).attr('data-question-placeholder', data.placeholder);
        //             $(row).attr('data-question-type-id', data.question_type.id);
        //             $(row).attr('data-regex-id', data.regex_id);
        //             $(row).attr('data-question-is_required', data.is_required);
        //             $(row).attr('data-question-is_visible', data.is_visible);
        //         },
        //         "columns": [
        //             {
        //                 "data": 'id',
        //             },
        //             {
        //                 "data": 'questionable_type',
        //             },
        //             {
        //                 "data": 'question_bank_organization_id',
        //             },
        //             {
        //                 "data": 'arrangement',
        //             },
        //             {
        //                 "data": 'is_visible',
        //             },
        //             {
        //                 "data": 'is_required',
        //             },
        //             {
        //                 "data": 'question_bank_organization_id',
        //             },
        //             {
        //                 "data": 'content',
        //             },
        //             {
        //                 "data": "actions",
        //                 className: "text-center",
        //             },
        //         ],
        //         buttons: ['csv', 'excel'],
        //         // dom: 'Bfrtip',
        //         dom: 'lfritpB',
        //         "ordering": false,
        //     });

        //     // Deleting a question from a datatable ==================================================================
        //     $(document.body).on('click', '.delete-question-btn', function() {
        //         Swal
        //             .fire(window.deleteWarningPopupSetup).then((result) => {
        //                 if (result.isConfirmed) {
        //                     var question_id = $(this).attr('question-id');
        //                     $.ajaxSetup({
        //                         headers: {
        //                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                         }
        //                     });
        //                     $.ajax({
        //                         type: 'DELETE',
        //                         url: "{{ url('/questions') }}" + "/" + question_id,
        //                         success: function(response) {
        //                             window.datatable.ajax.reload();
        //                             Toast.fire({
        //                                 icon: "success",
        //                                 title: "{{trans('translation.delete-successfully') }}"
        //                             });
        //                         },
        //                         error: function(jqXHR, responseJSON) {
        //                             Toast.fire({
        //                             icon: "error",
        //                             title: "{{ trans('translation.something went wrong!') }}"
        //                         });
        //                             Swal
        //                                 .fire({
        //                                     title: "{{ trans('translation.Warning') }}",
        //                                     text: jqXHR.responseJSON.message,
        //                                     icon: "error",
        //                                     showConfirmButton: true,
        //                                     confirmButtonColor: '#d33',
        //                                     cancelButtonText: "{{ trans('translation.OK ') }}"
        //                                 })
        //                             },
        //                     });
        //                 }
        //             });
        //     });

        //     // $('#edit_is_required').on('change', function() {
        //     // });

        // }); // End of ready function
    </script>
@endpush
