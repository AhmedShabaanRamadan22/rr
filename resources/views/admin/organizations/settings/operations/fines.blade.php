
@component('components.section-header', ['title' => 'fines', 'hide_button' => true])@endcomponent

<x-data-table id="fines-datatable" :columns="$fine_columns"/>


@push('after-scripts')
    <script>
        $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });
        let organization_id = '{{$organization->id}}'
        window.finesDatatable = $("#fines-datatable").DataTable({
            "ajax": {
                "url": "{{ route('fines.datatable') }}",
                "data": function(d) {
                    d.organization_id = [{{ $organization->id }}];
                },
            },
            language: datatable_localized,
            rowId: 'id',
            "drawCallback": function(settings) {
                $('.selectpicker').selectpicker({
                    width: '100%',
                });
            },
            stateSave: true,
            columns: [
                {
                    data: 'id',
                    render:  (data, type, row, meta) => { return ++meta.row; }
                },
                @foreach ($fine_columns as $key => $column)
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
                "ordering": false,
            });
        });
    
        function changeFineSelectPicker(select) {

            var select = $(select);
            let is_note_required = select.find(":selected").attr('data-note-required');
            let popupSetup = is_note_required ? window.confirmChangeStatusWithNotePopupSetup : window.confirmChangeStatusPopupSetup;
            Swal.fire(popupSetup).then((result) => {
                if (result.isConfirmed) {
                    const noteText = result.value ?? null;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ url('admin/fines-status') }}",
                        data: {
                            status_id: select.val(),
                            fine_id: select.attr('data-fine-id'),
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            window.finesDatatable.ajax.reload();
                            Toast.fire({
                                icon: "success",
                                title: response.message
                            });
                        },
                        error: function(response, jqXHR, xhr) {
                            window.finesDatatable.ajax.reload();
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
