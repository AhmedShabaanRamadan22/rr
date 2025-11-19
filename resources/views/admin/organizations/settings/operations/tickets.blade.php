
@component('components.section-header', ['title' => 'tickets'])@endcomponent

<x-data-table id="tickets-datatable" :columns="$ticket_columns"/>


@push('after-scripts')
    <script>
        $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });

        window.TicketsDatatable = $('#tickets-datatable').DataTable({
            "ajax": {
                "url": "{{ route('admin.tickets.datatable') }}",
                "data": function(d) {
                    d.organization_id = ['{{$organization->id}}'];
                    d.isPaginated = true;
                },
                complete: function(data) {}
            },
            language: datatable_localized,
            rowId: 'id',
            serverSide: true,
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
                {
                    "data": 'code',
                },
                {
                    "data": 'level',
                },
                {
                    "data": 'ticket_reason_id',
                },
                {
                    "data": 'label',
                },
                {
                    "data": 'sight',
                },
                {
                    "data": 'provider_name',
                },
                {
                    "data": 'reporter_name',
                },
                {
                    "data": 'monitor',
                },
                {
                    "data": 'bravo',
                },
                {
                    "data": 'organization_name',
                },
                {
                    "data": 'status_id',
                },
                {
                    "data": 'created_at',
                },
                {
                    "data": 'updated_at',
                },
                {
                    "data": 'closed_at',
                },
                {
                    "data": 'action',
                },
            ],
            buttons: ['csv', 'excel'],
            dom: 'lfritpB',
            "ordering": false,
        });


        window.Echo.channel('ModelCRUD-changes').listen('.Ticket-changes',function(data) {
            window.TicketsDatatable.ajax.reload();
        });
    });
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
                        url: '{{ url('admin/ticket-status') }}',
                        data: {
                            status_id: select.val(),
                            ticket_id: select.attr('data-ticket-id'),
                            old_status_id: select.attr('data-status-id')
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            window.TicketsDatatable.ajax.reload();
                            Toast.fire({
                                icon: "success",
                                title: "{{ trans('translation.Updated successfuly') }}"
                            });
                        },
                        error:function(response, jqXHR, xhr) {
                            window.TicketsDatatable.ajax.reload();
                            Toast.fire({
                                icon: "error",
                                title: "{{ trans('translation.You dont have permission') }}"
                            });
                        },
                    });
                } else {
                    select.selectpicker('destroy');
                    select.val(select.attr('data-status-id'));
                    select.selectpicker({
                        width: '100%',
                    });
                }
            });
        }
    </script>
@endpush
