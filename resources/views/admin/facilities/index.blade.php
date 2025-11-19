@extends('layouts.master')
@section('title', __('Facilities'))
@push('styles')
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

<x-breadcrumb title="{{trans('translation.facilities')}}"/>

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans('translation.filter-facilities') }}</h3>
            </div>
            <div class="card-body">
                @include('admin.facilities.components.filters')
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                @component('components.section-header', ['title'=>'facilities', 'disabled' =>'disabled', 'hide_button' => 'true'])@endcomponent
            </div>
            <div class="card-body">
                <x-data-table id="facilities-datatable" :columns="$columns"/>
                {{-- <div id="no-data-filter" class="d-none text-center col">{{ trans('translation.no-data') }}</div> --}}
            </div>
        </div>
    </div>
</div>
{{--@include('admin.facilities.modals.add-facility')--}}


    @push('after-scripts')
        <!-- SelectPicker -->
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

        <script>
            $(document).ready(function() {

                $('.selectpicker').selectpicker({
                    width: '100%',
                });
                window.facilityDatatable = $('#facilities-datatable').DataTable({
                    "ajax": {
                        "url": "{{ route('admin.facilities.datatable') }}",
                        "data": function(d) {
                            // d.user_id = $('#user_id_filter').val();
                            d.service_id = $('#service_id_filter').val();
                            d.organization_id = $('#organization_id_filter').val();
                        },
                    },
                    language: datatable_localized,
                    rowId: 'id',
                    'stateSave': true,
                    'createdRow': function(row, data, rowIndex) {
                        $(row).attr('data-id', data.id);
                    },
                    "columns": [{
                            "data": 'id',
                            render:  (data, type, row, meta) => { return ++meta.row; }
                        },
                        {
                            "data": 'name',
                        },
                        {
                            "data": 'user_name',
                        },
                        {
                            "data": 'user_email',
                        },
                        {
                            "data": 'user_birthday',
                        },
                        {
                            "data": 'user_nationality',
                        },
                        {
                            "data": 'user_national_id',
                        },
                        {
                            "data": 'user_national_id_issue_city',
                        },
                        {
                            "data": 'user_phone_number',
                        },
                        {
                            "data": 'registration_number',
                        },
                        {
                            "data": 'version_date',
                        },
                        {
                            "data": 'end_date',
                        },
                        {
                            "data": 'registration_source',
                        },
                        {
                            "data": 'license',
                        },
                        {
                            "data": 'national_address',
                        },
                        
                        // {
                        //     "data": 'label',
                        //     render: function(data, type, row) {
                        //         if(data.length == 0){
                        //             return '-';
                        //         }
                        //         var html = '';
                        //         var i = 1;
                        //         data = JSON.parse(data)
                        //         data.forEach(function(option) {
                        //             html += ' <span class="badge bg-primary m-1">' + option + '</span>';
                        //             html += (i % 3 == 0 ? '<br>' : '');
                        //             i++;
                        //         });
                        //         return html;
                        //     }
                        // },
                        {
                            "data": 'service_name',
                            render: function(data, type, row) {
                                if(data== ""){
                                   return '{{trans("translation.no-selected-service")}}';
                                }
                                var html = '';
                                var i = 1;
                                data = data.split(',');
                                data.forEach(function(option) {
                                    html += ' <span class="badge bg-primary m-1">' + option + '</span>';
                                    html += (i % 3 == 0 ? '<br>' : '');
                                    i++;
                                });
                                return html;
                            }
                        },
                        {
                            "data": 'organization_name',
                            render: function(data, type, row) {
                                if(data.length == 2){
                                    return '{{trans("translation.no-selected-organization")}}';
                                }
                                var html = '';
                                var i = 1;
                                data = JSON.parse(data)
                                Object.entries(data).forEach(([key,option]) => {
                                    i != 1 ? html += ' - ' : '';
                                    html += ' <span class="badge bg-primary m-1">' + option + '</span>';
                                    html += (i % 3 == 0 ? '<br>' : '');
                                    i++;
                                });
                                return html;
                            }
                        },
                        // {
                        //     "data" : "remain-capacity",
                        // },
                        {
                            "data": '1444-h',
                        },
                        {
                            "data": '1445-h',
                        },
                        {
                            "data": '1446-h',
                        },
                        {
                            "data": '1447-h',
                        },
                        {
                            "data": 'more_details',
                        }
                    ],
                    buttons: ['csv', 'excel'],
                    dom: 'lfritpB',
                    "ordering": false,
                });
                $('select').on('change', function() {
                    if ($("#user_id_filter").val() != -1 || $("#service_id_filter").val() != -1 || $(
                            "#organization_id_filter").val() != -1)
                        document.getElementById('facility-reset-btn').disabled = false;
                });
                $('#facility-filter-btn').click(function() {
                    setLoading(true);
                    window.facilityDatatable.ajax.reload();
                });

                $(document.body).on('click', '.delete-facility', function(e) {
                    var facility_id  = $(this).attr('data-facility-id')
                    Swal
                        .fire(window.deleteWarningPopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                // var question_id = $(this).attr('data-question-id');
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: 'DELETE',
                                    url: "{{ url('facilities') }}/" + facility_id,
                                    success: function(response) {
                                        window.facilityDatatable.ajax.reload();
                                        Toast.fire({
                                                icon: "success",
                                                title: response.message
                                            });
                                        // card_section.remove();
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        // alert(jqXHR.responseJSON.message);
                                        Swal
                                            .fire({
                                                title: "{{ trans('translation.Warning') }}",
                                                text: jqXHR.responseJSON.message,
                                                icon: "error",
                                                showConfirmButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonText: "{{ trans('translation.OK') }}"
                                            })

                                    },
                                });
                            }
                        });
                });
                $('#facility-reset-btn').click(function() {
                    setLoading(true);
                    $('.selectpicker').selectpicker('deselectAll');
                    window.facilityDatatable.ajax.reload();
                });
                $('#facilities-datatable').on( 'draw.dt', function () {
                    setLoading(false);
                })
            }); //end ready
        </script>
    @endpush
@endsection
