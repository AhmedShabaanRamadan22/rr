
<div class="card-head">

</div>
<div class="card-body">
    <x-data-table id="order-datatable" :columns="$columns" />
</div>
<div class="card-footer">
    <div class=" d-flex  justify-content-end ">
        <button
            class="btn btn-primary me-2"
            data-bs-target="#addassigns-to-user"
            data-bs-toggle="modal"
            data-original-title="Assign">
            {{ trans('translation.assign') }}
        </button>
        <button
            class="btn btn-danger "
            data-bs-target="#deleteassigned-user"
            data-bs-toggle="modal"
            data-original-title="Assign">
            {{ trans('translation.unassign') }}
        </button>
    </div>
</div>
@push('after-scripts')
<script>
    $(document).ready(function() {
        localStorage.setItem('goBackHref', location.href);
    })

    $(document).ready(function() {
        window.datatable = $('#order-datatable').DataTable({
            "ajax": {
                "url": "{{ route('admin.order-assigns.datatable') }}",
                "data": function(d) {
                    d.assignees_id = $('#assignees_filter').val();
                    d.organization_id = $('#organizations_filter').val();
                    d.status_id = $('#status_filter').val();
                },
                complete: function(data) {}
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
            'stateSave': false,
            // createdRow: function(row, data, indice) {

            //     $(row).find("td:eq(7)").attr('data-name', data.id).attr('data-type');
            // },

            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            'createdRow': function(row, data, rowIndex) {
                $(row).attr('data-id', data.id);
                const assigneeIds = [...new Set(data.assigns.map(item => item.assignee_id))];
                $(row).attr('data-assginee-ids', assigneeIds);
            },
            "columns": [
                {
                    "data": null,
                    'defaultContent': '',
                    'className': 'select-checkbox align-self-center',
                    'orderable': false,
                    'bSort': false,
                },
                {
                    "data": 'id',
                    render: (data, type, row, meta) => {
                        return ++meta.row;
                    }
                },
                @foreach($columns as $key => $key)
                    @if(in_array($key, ['id', '#']))
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
</script>
@endpush