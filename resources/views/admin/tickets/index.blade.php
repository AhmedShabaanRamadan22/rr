@extends('layouts.master')
@section('title', __('Tickets'))

@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.tickets') }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ trans('translation.tickets') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('root') }}">{{ trans('translation.home') }}</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- start filters -->
    <div class="row">
        <div class="col-md-12  col-xl-12">
            <div class="card ">
                <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                    <h3 class="card-title">{{ trans('translation.tickets') }}</h3>
                </div>
                <div class="card-body">
                    @include('admin.tickets.components.filters')
                </div>
            </div>
        </div>
    </div>
    <!-- end filters -->

    <!-- ROW-2 -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-head"><h3 class="p-3 mb-3">{{ trans('translation.all-tickets') }}</h3></div>

                <div class="card-body">
                    <x-data-table id="ticket-datatable" :columns="$columns"/>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW-2 -->

    @push('after-scripts')
        @vite(['resources/js/bootstrap.js'])
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                localStorage.setItem('goBackHref',location.href);
                window.datatable = $('#ticket-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.tickets.datatable') }}",
                    "data": function(d) {
                        d.sector_id = $('#sector_filter').val();
                        d.reason_id = $('#reason_filter').val();
                        d.danger_id = $('#danger_filter').val();
                        d.status_id = $('#status_filter').val();
                        d.organization_id = $('#organization_filter').val();
                        d.isPaginated = true;
                    },
                    complete: function(data) {
                    }
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
                "columns": [{
                        "data": 'id',
                        render:  (data, type, row, meta) => { return ++meta.row; }
                    },
                    {
                        "data": 'code',
                    },
                    {
                        "data": 'level',
                    },
                    {
                        "data": 'ticket_reason_id',
                    },
                    {
                        "data": 'label',
                    },
                    {
                        "data": 'sight',
                    },
                    {
                        "data": 'provider_name',
                    },
                    {
                        "data": 'reporter_name',
                    },
                    {
                        "data": 'monitor',
                    },
                    {
                        "data": 'bravo',
                    },
                    {
                        "data": 'organization_name',
                    },
                    {
                        "data": 'status_id',
                    },
                    {
                        "data": 'created_at',
                    },
                    {
                        "data": 'updated_at',
                    },
                    {
                        "data": 'closed_at',
                    },
                    {
                        "data": 'action',
                    },
                ],
                buttons: ['csv', 'excel'],
                dom: 'lfritpB',
                "ordering": false,
            });

            $('#ticket-filter-btn').click(function() {
                window.datatable.ajax.reload();
            });
            $('#ticket-reset-btn').click(function() {
                location.reload();
            });

            window.Echo.channel('ModelCRUD-changes').listen('.Ticket-changes',function(data) {
                window.datatable.ajax.reload();
            });

        }); // end document ready
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
                            url: '{{ url('admin/ticket-status') }}',
                            data: {
                                status_id: select.val(),
                                ticket_id: select.attr('data-ticket-id'),
                                old_status_id: select.attr('data-status-id')
                            },
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {
                                window.datatable.ajax.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Updated successfuly') }}"
                                });
                            },
                            error:function(response, jqXHR, xhr) {
                                window.datatable.ajax.reload();
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.You dont have permission') }}"
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
