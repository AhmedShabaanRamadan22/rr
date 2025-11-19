@extends('layouts.master')
@section('title', $pageTitle)

@section('content')
    <!-- start filters -->

    <!-- end filters -->
    {{-- index  --}}
    <x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions"
        :columnSubtextOptions="$columnSubtextOptions" :filterColumns="$filterColumns" :showAddButton="false">
        <x-slot name="filters">
            @include('admin.fines.components.filters')
        </x-slot>
    </x-crud-model>

    @push('after-scripts')
        <script>
            $(document).ready(function() {
                localStorage.setItem('goBackHref',location.href);
                $('#fine-filter-btn').click(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "GET",
                        url: "{{ route('admin.fines.datatable') }}",
                        data: {
                            organization_id: $('#organization_filter').val(),
                            sector_id: $('#sector_filter').val(),
                            user_id: $('#user_filter').val(),
                            fine_id: $('#fine_filter').val(),
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            window.finesDatatable.ajax.reload();
                        },
                        error: function(response, jqXHR, xhr) {
                            Toast.fire({
                                icon: "error",
                                title: "{{ trans('translation.something went wrong') }}"
                            });
                        },
                    });
                });
                $('#fine-reset-btn').click(function() {
                    $('.selectpicker').selectpicker('deselectAll');
                    window.finesDatatable.ajax.reload();
                });

            }); // end document rready
            function changeFineSelectPicker(select) {

                var select = $(select);
                let is_note_required = select.find(":selected").attr('data-note-required');
                let popupSetup = is_note_required ? window.confirmChangeStatusWithNotePopupSetup : window
                    .confirmChangeStatusPopupSetup;
                Swal
                    .fire(popupSetup).then((result) => {
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
                                    // old_status_id: select.attr('data-status-id'),
                                    // note:noteText
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

@endsection
