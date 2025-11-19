    @extends('layouts.master')
    @push('styles')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css"> --}}

    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css"> --}}
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />

    @endpush
    @section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.users') }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ trans('translation.users') }}</li>
                        <li class="breadcrumb-item"><a href="{{route('root')}}">{{ trans('translation.home') }}</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-12  col-xl-12">
            <div class="card ">
                <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                    <h3 class="card-title">{{trans('translation.users')}}</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    @include('admin.users.components.filters')
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <!-- ROW-2 -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">{{trans('translation.all-users')}}</h3>
                        </div>
                        <div class="col-lg-3 text-end my-auto">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addusers"><i
                                        class="mdi mdi-account-group-outline align-baseline me-1"></i>
                                    {{ trans('translation.add-new-user') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <x-data-table id="user-datatable" :columns="$columns" />
                </div>
            </div>
        </div>
    </div>

@component('admin.users.modals.add-user',['columnOptions'=>$columnOptions, 'required_attachments'=>$required_attachments])@endcomponent
{{-- @component('admin.users.modals.user-map')@endcomponent --}}
<x-user-tracking-component/>

    <!-- END ROW-2 -->
    @push('after-scripts')

    <script>
        $(document).ready(function() {
            function processDoc(doc) {
            pdfMake.fonts = {
                        Roboto: {
                normal: 'Roboto-Regular.ttf',
                bold: 'Roboto-Medium.ttf',
                italics: 'Roboto-Italic.ttf',
                bolditalics: 'Roboto-MediumItalic.ttf'
                }}
        }

            $('.selectpicker').selectpicker({
                width: '100%',
            });
            window.datatable = $('#user-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.users.datatable') }}",
                    "data": function(d) {
                        d.role_id = $('#role_name_filter').val();
                        d.organization_id = $('#organizations_filter').val();
                    },
                    complete: function(data) {}
                },
                language: datatable_localized,
                rowId: 'id',
                'stateSave': true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                'createdRow': function(row, data, rowIndex) {
                    $(row).attr('data-id', data.id);
                    $(row).attr('data-name', data.name);
                },
                "columns": [{
                        "data": 'id',
                        render:  (data, type, row, meta) => { return ++meta.row; }
                    },
                    {
                        "data": 'name',
                    },
                    {
                        "data": 'phone',
                    },
                    {
                        "data": 'email',
                    },
                    {
                        "data": 'national_id',
                    },
                    {
                        "data": 'role_name',
                        render: function(data, type, row) {
                            // if(data.length == 2){
                                //     return '{{trans("translation.no-roles")}}';
                                // }
                            var html = '';
                            var i = 1;
                            data = data.split(",");
                            data.forEach((option) => {
                                html += ' <span class="badge bg-primary m-1">' + option + '</span>';
                                html += (i % 3 == 0 ? '<br>' : '');
                                i++;
                            });
                            return html;
                        }
                    },
                    {
                        "data": 'organization_name',
                        // render: function(data, type, row) {
                        //     if(data.length == 2){
                        //         return '{{trans("translation.no-selected-organization")}}';
                        //     }
                        //     var html = '';
                        //     var i = 1;
                        //     // data = JSON.parse(data)
                        //     data.forEach(function(option) {
                        //     html += ' <span class="badge bg-primary m-1">' + option + '</span>';
                        //         html += (i % 3 == 0 ? '<br>' : '');
                        //         i++;
                        //     });
                        //     return html;
                        // }
                    },
                    {
                        "data": 'bravo-number',
                    },
                    {
                        "data": 'bravo-code',
                    },
                    {
                        "data": 'action',
                    }
                ],
                buttons: ['csv', /*{
                    extend:'pdf',
                    text: 'PDF',
                    customize: function (doc) {
                        processDoc(doc);
                    doc.defaultStyle.font= 'Roboto';}
                }*/,'excel'],
                dom: 'lfritpB',
                "ordering": false,
            });
            $('#user-datatable tbody').on('click', '.select-checkbox', function() {
                $(this).parent('tr').toggleClass('selected');
            });
            $('#user-filter-btn').click(function() {
                window.datatable.ajax.reload();
            });
            $('#user-reset-btn').click(function() {
                $('.selectpicker').selectpicker('deselectAll');
                window.datatable.ajax.reload();
            });
            $('#table').DataTable();
        });
    </script>
    @endpush
    @endsection
