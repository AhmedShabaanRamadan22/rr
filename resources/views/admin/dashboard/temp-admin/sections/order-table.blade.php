<x-data-table id="orders-datatable" :columns="$order_columns"/>

@push('after-scripts')
    <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

<script>
    $(document).ready(function(){

            window.questions = $('#orders-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.orders.datatable') }}",
                    "data": function(d) {
                        d.organization_id = [{{$current_organization?->id}}] 
                    },
                    complete: function(data) {}
                },
                language: datatable_localized,
                rowId: 'order-id',
                'createdRow': function(row, data, rowIndex) {
                    // Per-cell function to do whatever needed with cells
                    $(row).attr('data-question-id', data.id);
                },
                "drawCallback": function(settings) {
                   $('.status-select').selectpicker({
                            width: '100%',
                        });     
                },
                'stateSave': true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                "columns": [
                    {
                            data: 'id',
                            render:  (data, type, row, meta) => { return ++meta.row; }
                        },
                        @foreach ($order_columns as  $key => $column)
                            @if ($key == 'id' || $key == 'collapser')
                                @continue;
                            @else
                            {
                                data: '{{ $key }}',
                                className: ' text-center align-middle',
                            },
                            @endif
                        @endforeach
                ],
                // buttons: ['csv', 'excel'],
                buttons: [],
                dom: 'lfritpB',
                "ordering": false,
            });
        });



</script>

@endpush