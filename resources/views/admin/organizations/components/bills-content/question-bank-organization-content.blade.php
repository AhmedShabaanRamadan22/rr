@php
    $disabled = null;//$organization->has_classifications ? null : 'disabled';
@endphp
@component('components.section-header', ['title' => 'question-bank-organizations', 'disabled' => $disabled])
    <small
        class=" text-primary my-auto {{ ($has_classifications = $organization->has_classifications) ? 'd-none' : '' }}">{{ trans('translation.please-add-classification-first') }}!</small>
@endcomponent

<x-data-table id="question-bank-organization-datatable" :columns="$questions_bank_columns" />


@push('after-scripts')
    <script>
        $(document).ready(function() {
            $('.selectPicker').selectpicker({
                width: '100%',
            });

            window.questionBank = $('#question-bank-organization-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.question-bank-organizations.datatable') }}",
                    "data": function(d) {
                        d.organization_id = {{ $organization->id }};
                    },
                    complete: function(data) {}
                },
                language: datatable_localized,
                rowId: 'id',
                "drawCallback": function(settings) {
                    $('.selectpicker').selectpicker({
                        width: '100%',
                    });
                },
                'stateSave': true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                'createdRow': function(row, data, rowIndex) {
                    $(row).attr('data-id', data.id);
                },
                "columns": [{
                        "data": 'id',
                        render:  (data, type, row, meta) => { return ++meta.row; }
                    },
                    // {
                    //     "data": 'question_bank_id',
                    // },
                    {
                        "data": 'question_bank.content',
                    },
                    {
                        "data": 'question_bank.question_type.name',
                    },
                    {
                        "data": 'is_visible',
                    },
                    {
                        "data": 'is_required',
                    },
                    {
                        "data": 'description',
                    },
                    {
                        "data": 'action',
                    },
                ],
                buttons: ['csv', 'excel'],
                dom: 'lfritpB',
                "ordering": false,
            });
        });

        $(document.body).on('click', '.delete-button', function(e) {
            let deleteBtn = $(this);
            let question_id = $(this).attr('data-question-id');
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
                            url: "{{ url('question-bank-organizations') }}/" + question_id,
                            success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Deleted successfuly') }}"
                                });
                                window.questionBank.ajax.reload();
                            },
                            error: function(jqXHR, responseJSON) {
                                Toast.fire({
                                    icon: "error",
                                    title: jqXHR.responseJSON.message
                                });

                            },
                        });
                    }
                });
        })
    </script>
@endpush
