
{{-- @php
    $disabled = $organization->has_classifications ? null :'disabled';
@endphp --}}
@component('components.section-header', ['title' => 'food-weights'])@endcomponent
<x-data-table id="food-weights-datatable" :columns="$foodWeightColumns"/>


@push('after-scripts')
    <script>
        $(document).ready(function() {
            $('.selectPicker').selectpicker({
                width: '100%',
            });

            window.foodWeightsDatatable = $('#food-weights-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.food-weights.datatable') }}",
                    "data": function(d) {
                        d.organization_id = ['{{$organization->id}}'];
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
                    $(row).attr('data-quantity', data.quantity);
                    $(row).attr('data-unit', data.unit);
                },
                "columns": [{
                    "data": 'id',
                    render:  (data, type, row, meta) => { return ++meta.row; }
                },
                @foreach ($foodWeightColumns as $key => $column)
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
                dom: 'lfritpB',
                "ordering": false,
            });
        });
        $('body').on('click', '.deletefood_weights', function() {
            var model_id = $(this).attr('data-model-id');
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
                            url: "{{ url('/food-weights') }}" + "/" + model_id,
                            success: function(response) {
                                window.foodWeightsDatatable.ajax.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                            },
                            error: function(response, jqXHR, responseJSON) {
                                Toast.fire({
                                    icon: "error",
                                    title: response.responseJSON.message
                                });
                            },
                        });
                    }
                });
        })
    </script>
@endpush
