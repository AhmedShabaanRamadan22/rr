
<div class="card-body">

    <x-data-table id="supports-datatable" :columns="$columns" />
</div>

@push('after-scripts')
<script>
        $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });

        window.datatable = $('#supports-datatable').DataTable({
            "ajax": {
                "url": "{{ route('admin.supports.datatable') }}",
                "data": function(d) {
                    d.type = $('#types_filter').val();
                    d.period_id = $('#periods_filter').val();
                    d.sector_id = $('#sectors_filter').val();
                    d.status_id = $('#status_filter').val();
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
            'createdRow': function(row, data, rowIndex) {
                $(row).attr('data-id', data.id);
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            "columns": [
                {
                    data: 'id',
                    render:  (data, type, row, meta) => { return ++meta.row; }
                },
                @foreach ($columns as $key => $column)
                    @if ($key == 'id')
                        @continue;
                    @else
                    {
                        data: '{{ $key }}',
                        className: ' text-center align-middle',
                    },
                    @endif
                @endforeach
            ],
            buttons: ['csv', 'excel'],
            // dom: 'Bfrtip',
            dom: 'lfritpB',
            "ordering": false,
        });
        $('#support-datatable tbody').on('click', '.select-checkbox', function() {
            $(this).parent('tr').toggleClass('selected');
        });

        $('#support-filter-btn').click(function() {
            // $("#services_filter").val();
            // $("#organizations_filter").val();
            // $("#status_filter").val();
            window.datatable.ajax.reload();
        });
        $('#support-reset-btn').click(function() {
            $('.selectpicker').selectpicker('deselectAll');
            window.datatable.ajax.reload();
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
                        url: "{{ url('admin/supports-status') }}",
                        data: {
                            status_id: select.val(),
                            support_id: select.attr('data-support-id')
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            if (xhr.status === 200) {

                            }
                        }
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
