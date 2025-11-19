@php
    $disabled = $organization->has_classifications ? null :'disabled';
@endphp
@component('components.section-header', ['title' => 'sectors', 'disabled' => $disabled, 'disabled_message' => trans('translation.add-classifications-first')])
    <small class=" text-primary my-auto {{($has_classifications = $organization->has_classifications) ? 'd-none':''}}">{{trans('translation.please-add-classification-first')}}!</small>
@endcomponent
<x-data-table id="sectors-datatable" :columns="$sector_columns"/>


@push('after-scripts')
    <script>
        $(document.body).on('click', '.deletesectors', function(e) {
                    let deleteBtn = $(this);
                    let model_id = $(this).attr('data-model-id');
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                // deleteBtn.closest('form').submit()
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: 'DELETE',
                                    url: "{{ url('/sectors') }}" + "/" + model_id,
                                    success: function(response) {
                                        $('#'+ model_id).remove();
                                        Toast.fire({
                                            icon: "success",
                                            title: "{{ trans('translation.delete-successfully') }}"
                                        });

                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Toast.fire({
                                            icon: "error",
                                            title:jqXHR.responseJSON.message
                                        });

                                    },
                                });
                            }
                        });
        })

        $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });

        window.sectorDatatable = $('#sectors-datatable').DataTable({
            "ajax": {
                "url": "{{ route('admin.sectors.datatable') }}",
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
            },
            "columns": [{
                "data": 'id',
                render:  (data, type, row, meta) => { return ++meta.row; }
            },
            @foreach ($sector_columns as $key => $column)
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
    </script>
@endpush
