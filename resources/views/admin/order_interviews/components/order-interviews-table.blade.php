<div class="card-head">

</div>
<div class="card-body">
    <x-data-table id="order-datatable" :columns="$columns" />
</div>

@push('after-scripts')
    <script>
        $(document).ready(function(){
            localStorage.setItem('goBackHref',location.href);
        })

        $(document).ready(function() {
            window.datatable = $('#order-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('interview-orders.datatable') }}",
                    "data": function(d) {
                        d.service_id = $('#service_filter').val();
                        d.organization_id = $('#organizations_filter').val();
                        d.interview_status_id = $('#status_filter').val();
                        d.status_id = [{{ App\Models\Status::CONFIRMED_ORDER}}];
                        @if (isset($facility))
                            d.facility_id = [{{ $facility->id }}];
                        @endif
                    },
                    complete: function(data) {
                    }
                },
                language: datatable_localized,
                rowId: 'id',
                "drawCallback": function(settings) {
                    // alert('DataTables ' + settings);
                    $('.selectpicker').selectpicker({
                        width: '100%',
                    });
                },
                // "iDisplayStart": 28,
                'stateSave': true,
                // createdRow: function(row, data, indice) {

                //     $(row).find("td:eq(7)").attr('data-name', data.id).attr('data-type');
                // },
                'createdRow': function(row, data, rowIndex) {
                    // Per-cell function to do whatever needed with cells
                    // $(row).attr('data-question-id', data.id);
                    // $(row).attr('data-question-content', data.content);
                    // $(row).attr('data-question-placeholder', data.placeholder);
                    // $(row).attr('data-question-type', data.type);
                    // $(row).attr('data-question-is_required', data.is_required);
                    // $(row).attr('data-question-is_visible', data.is_visible);
                    // $(row).attr('data-action-bo-ids', data.bo_ids);
                    // $(row).attr('data-action-program-id', data.program_id);
                    // $(row).attr('data-action-stream-id', data.stream_id);
                    // if (data.status == "ACTIVE") {
                    //     $(row).addClass('row-status-active');
                    // } else {
                    //     $(row).addClass('row-status-closed');
                    // }
                    // $.each($('td', row), function(colIndex) {
                    //     // For example, adding data-* attributes to the cell
                    //     if ([2, 9].includes(colIndex)) {
                    //         $(this).attr('data-editable', '');
                    //         $(this).attr('data-type', '');
                    //     }
                    // });
                },
                // responsive: {
                //     details: {
                //         type: 'column',
                //         target: -1,
                //     }
                // },
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                'createdRow': function(row, data, rowIndex) {
                    $(row).attr('data-id', data.id);
                },
                "columns": [{
                        "data": 'id',
                        render: (data, type, row, meta) => {
                            return ++meta.row;
                        }
                    },
                    @foreach ($columns as $key => $key)
                        @if ($key == 'id')
                            @continue;
                        @else
                        {
                            data: '{{ $key }}',
                        },
                        @endif
                    @endforeach
                ],
                buttons: ['csv', 'excel'],
                // dom: 'Bfrtip',
                dom: 'lfritpB',
                "ordering": false,
            });
            // $('#order-datatable tbody').on('click', '.select-checkbox', function() {
            //     $(this).parent('tr').toggleClass('selected');
            // });

            $('#order-filter-btn').click(function() {
                // $("#services_filter").val();
                // $("#organizations_filter").val();
                // $("#status_filter").val();
                window.datatable.ajax.reload();
            });
            $('#order-reset-btn').click(function() {
                $('.selectpicker').selectpicker('deselectAll');
                window.datatable.ajax.reload();
            });


        }); // end document rready
        function changeSelectPicker(select) {

            var select = $(select);
            let is_note_required = select.find(":selected").attr('data-note-required');
            let popupSetup = is_note_required ? window.confirmChangeStatusWithNotePopupSetup : window
                .confirmChangeStatusPopupSetup;
            Swal
                .fire(popupSetup).then((result) => {
                    if (result.isConfirmed) {
                        const noteText = result.value ?? null;
                        setLoading(true)
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ url('order-interviews-status') }}",
                            data: {
                                status_id: select.val(),
                                order_id: select.attr('data-order-id'),
                                old_status_id: select.attr('data-status-id'),
                                note: noteText
                            },
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {
                                window.datatable.ajax.reload();
                                setLoading(false)
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                            },
                            error: function(response, jqXHR, xhr) {
                                window.datatable.ajax.reload();
                                setLoading(false)
                                Toast.fire({
                                    icon: "error",
                                    title: response.responseJSON.message ??
                                        "{{ trans('translation.something went wrong') }}"
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
