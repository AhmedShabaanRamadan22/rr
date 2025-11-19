<x-data-table :id="$model.'-datatable-'.$organization->id??0" :columns="$columns"/>

@push('after-scripts')

<script>
    $(document).ready(function(){

            window.{{$model . '_' . ($organization->id??0)}} = $('#{{$model}}-datatable-{{($organization->id??0)}}').DataTable({
                // "ajax": {
                //     "url": "{{ $datatableUrl }}",
                //     "data": function(d) {
                //         @if ($organization?->id != 0)
                //         d.organization_id = [{{$organization?->id}}];
                //         @endif
                //         d.in_dashboard = true;
                //     },
                //     complete: function(data) {}
                // },
                data:[],
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
                        @foreach ($columns as  $key => $column)
                            @if ($key == 'id')
                                {
                                    data: 'id',
                                    render:  (data, type, row, meta) => { return ++meta.row; }
                                },
                            @elseif ($key == 'collapser')
                                @continue;
                            @else
                            {
                                data: '{{ $key }}',
                                className: ' text-center align-middle',
                            },
                            @endif
                        @endforeach
                ],
                @hasrole(['superadmin','admin'])
                buttons: ['csv', 'excel'],
                @else
                buttons:[],
                @endhasrole
                dom: 'lfritpB',
                "ordering": false,
            });
        });



</script>

@endpush