
@component('components.section-header', ['title' => 'food-support'])
    @slot('moreButtons')
        @component('components.add-button', ['title' => 'water-support', 'ColDiv' => 'col-md-auto  mx-1', 'disabled' => $disabled ?? null])
        @endcomponent
    @endslot
@endcomponent

<x-data-table id="supports-datatable" :columns="$support_columns"/>


@push('after-scripts')
    <script>
        $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });
        window.supportsDatatable = $("#supports-datatable").DataTable({
            "ajax": {
                "url": "{{ route('supports.datatable') }}",
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
                @foreach ($support_columns as $key => $column)
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


        window.Echo.channel('ModelCRUD-changes').listen('.Support-changes',function(data) {
            window.supportsDatatable.ajax.reload();
        });
        });

        function changeSupportSelectPicker(select) {

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
                        url: "{{ url('admin/supports-status') }}",
                        data: {
                            status_id: select.val(),
                            support_id: select.attr('data-support-id'),
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            window.supportsDatatable.ajax.reload();
                            Toast.fire({
                                icon: "success",
                                title: response.message
                            });
                        },
                        error: function(response, jqXHR, xhr) {
                            window.supportsDatatable.ajax.reload();
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
