@extends('layouts.master')
@section('title', trans('translation.submitted-form-sectors'))
@push('styles')

    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css"/>

@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.submitted-form-sectors') }} [{{ $form->form_full_name ?? trans('translation.no-data') }}]</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ trans('translation.submitted-form-sectors') }}</li>
                        <li class="breadcrumb-item"><a
                                href="{{route('root')}}">{{ trans('translation.submitted-form-sectors') }}</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <div class="card ">
                <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                    <h3 class="card-title">{{trans('translation.submitted-forms')}}</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.form_sectors.components.filters')
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
                            <h3 class="card-title">{{trans('translation.submitted-form-sectors')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <x-data-table id="form-sectors-datatable" :columns="$columns"/>
                </div>
            </div>
        </div>
    </div>


    <!-- END ROW-2 -->
    @push('after-scripts')
    @vite(['resources/js/bootstrap.js'])

        <script>
            $(document).ready(function () {
                function processDoc(doc) {
                    pdfMake.fonts = {
                        Roboto: {
                            normal: 'Roboto-Regular.ttf',
                            bold: 'Roboto-Medium.ttf',
                            italics: 'Roboto-Italic.ttf',
                            bolditalics: 'Roboto-MediumItalic.ttf'
                        }
                    }
                }

                $('.selectpicker').selectpicker({
                    width: '100%',
                });
                window.datatable = $('#form-sectors-datatable').DataTable({
                    "ajax": {
                        "url": "{{ route('form-sectors.datatable') }}",
                        "data": function (d) {
                            d.form_id = {{$form->id}};
                            d.date= $('#date_filter').val();
                            const startTime = $('#input_start_time_submit_form').val();
                            if (startTime) {
                                d.start_time = startTime;
                            }
                            const hasStart = $('#has_start_filter').val();
                            if (hasStart) {
                                d.has_start = hasStart;
                            }
                            const hasCompleted = $('#has_completed_filter').val();
                            if (hasCompleted) {
                                d.has_completed = hasCompleted;
                            }
                        },
                        error: function(data){
                            setLoading(false);
                            Toast.fire({
                                icon: "error",
                                title: data.responseJSON.error
                            });
                        },
                        complete: function (data) {
                            setLoading(false);
                        }
                    },
                    language: datatable_localized,
                    rowId: 'id',
                    'stateSave': true,
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    'createdRow': function (row, data, rowIndex) {
                        $(row).attr('data-id', data.id);
                    },
                    columns: [
                            @if(in_array('collapser', $columns))
                        {
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        },
                            @endif
                        {
                            data: 'id',
                            render: (data, type, row, meta) => {
                                return ++meta.row;
                            }
                        },
                    @foreach ($columns as $key => $column)
                    @if ($key == 'id' || $key == 'collapser')
                    @continue;
                    @else
                {
                    data: '{{ $key }}',
                        className
                :
                    ' text-center align-middle',
                }
            ,
                @endif
                @endforeach
            ],
                buttons: ['csv', 'excel'],
                    dom
            :
                'lfritpB',
                    "ordering"
            :
                false,
            })
                ;
                // $('#table').DataTable();


                $('#date-filter-btn').click(function() {
                    setLoading(true);
                    window.datatable.ajax.reload();
                    // $.ajaxSetup({
                    //     headers: {
                    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //     }
                    // });
                    // $.ajax({
                    //     type: "GET",
                    //     url: "{{ route('form-sectors.datatable') }}",
                    //     data: {
                    //         date: $('#date_filter').val(),
                    //         form_id : {{$form->id}},
                    //     },

                    //     dataType: "json",
                    //     success: function(response, jqXHR, xhr) {
                    //         window.datatable.ajax.reload();
                    //     },
                    //     error: function(response, jqXHR, xhr) {
                    //         Toast.fire({
                    //             icon: "error",
                    //             title: "{{ trans('translation.something went wrong') }}"
                    //         });
                    //     },
                    // });
                });
                $('#date-reset-btn').click(function() {
                    setLoading(true);
                    $('.selectpicker').selectpicker('deselectAll');
                    $('#input_start_time_submit_form').val("");
                    window.datatable.ajax.reload();
                });

                            
                // Pusher listen real-time
                window.Echo.channel('ModelCRUD-changes').listen('.SubmittedForm-changes',function(data) {
                    window.datatable.ajax.reload();
                });
            });


        </script>
    @endpush
@endsection
