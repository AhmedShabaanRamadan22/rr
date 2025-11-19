@extends('layouts.master')
@section('title', trans('translation.submitted-forms'))

@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.submitted-forms') }} [{{ $form['full_name'] ?? trans('translation.no-data') }}]</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ trans('translation.submitted-forms') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('root') }}">{{ trans('translation.home') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
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
                    @include('admin.form_answers.components.filters') 
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <!-- ROW-2 -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">{{ trans('translation.submitted-forms') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($form['sections']) > 0)
                        <table id="form-answers-datatable"
                               class="table table-bordered table-hover table-striped text-nowrap key-buttons border-bottom align-middle text-center"
                               style="width: 100%">
                            <thead>
                            <tr>
                                <th class="border-bottom-0 text-center" rowspan="2">{{ trans('translation.id') }}</th>
                                <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.form-answers-created-at') }}</th>
                                <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.order-sector') }}</th>
                                <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.nationality') }}</th>
                                <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.facility-name') }}</th>
                                <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.monitor-name') }}</th>
                                <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.filled_by') }}</th>
                                <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.completed') }}</th>
                                <!-- <th class="border-bottom-0 text-center"
                                    rowspan="2">{{ trans('translation.yes_answers_percentage') }}</th> -->
                                {{-- {{-- --}}  
                                @foreach($form['sections'] as $section)
                                    <th class="border-bottom-0 text-center"
                                        colspan="{{ count($section['questions']) }}">{{ $section['name'] }}</th>
                                @endforeach 
                                {{-- --} } --}}
                            </tr>
                            <tr>
                                @foreach($form['sections'] as $section)
                                    @foreach($section['questions'] as $question)
                                        <th class="border-bottom-0 text-center" data-q-id="{{$question['id']}}">{{ $question['content'] }}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    @else
                        <p>{{ trans('translation.no-data') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW-2 -->
@endsection

@push('after-scripts')
    @vite(['resources/js/bootstrap.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sections = @json($form['sections']);
            console.log(sections);

            const columns = [
                {data: 'id', title: '{{ trans('translation.id') }}'},
                {data: 'created_at', title: '{{ trans('translation.form-answers-created-at') }}'},
                {data: 'label', title: '{{ trans('translation.order-sector') }}'},
                {data: 'nationality_name', title: '{{ trans('translation.nationality') }}'},
                {data: 'facility_name', title: '{{ trans('translation.facility-name') }}'},
                {
                    data: 'monitor_names', title: '{{ trans('translation.monitor-name') }}', render: function (data) {
                        return data.join('- ');
                    }
                },
                {data: 'filled_by_name', title: '{{ trans('translation.filled-by') }}'},
                // {data: 'yes_answers_percentage', title: '{{ trans('translation.yes_answers_percentage') }}'},
                {
                    data: 'completed',
                    title: '{{ trans('translation.completed') }}',
                    render: function(data) {
                        return data ? '{{ trans('translation.yes') }}' : '{{ trans('translation.no') }}';
                    }
                }
            ];

            sections.forEach(section => {
                section.questions.forEach(question => {
                    columns.push({
                        data: `answers_value.${question.id}`,
                        title: question.content,
                        defaultContent: '—',
                        render: function (data,type,row) {
                            return data || '—';
                        }
                    });
                });
            });

            let table = $('#form-answers-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('form-answers.datatable') }}",
                    type: 'POST',
                    data: function(d) {
                        d.form_id = "{{$form['id']}}";
                        d.monitors = $('#monitor_filter').val();
                        d.sectors = $('#sector_filter').val();
                        d.nationalities = $('#nationality_filter').val();
                        // d.completed = $('#completed_filter').val();
                        d.per_page = d.length; // Number of records per page (based on DataTables settings)
                        d.page = Math.ceil(d.start / d.length) + 1; // Calculate the current page
                        d._token = '{{ csrf_token() }}';
                        // d.user_id = $('#user_id_filter').val();
                        // d.service_id = $('#service_id_filter').val();
                        // d.organization_id = $('#organization_id_filter').val();
                    },
                },
                columns: columns,
                language: datatable_localized,
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                pageLength: 10, // Default number of records per page
                buttons: ['csv', 'excel'],
                dom: 'lfritpB',
                ordering: false,

                createdRow: function (row, data, rowIndex) {
                    $(row).attr('data-id', data.id);
                },

                columnDefs: [{
                    searchable: false,
                    orderable: false,
                    targets: 0,
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                }]
            });

            // $('#date-filter-btn').on('click', function () {
            //     const selectedDates = $('#date_filter').val();
            //     const selectedMonitors = $('#monitor_filter').val();
            //     const selectedSectors = $('#sector_filter').val();
            //     const selectedCompleted = $('#completed_filter').val();
            //     const selectedNationality = $('#nationality_filter').val();

            //     const filteredData = formsData.filter(function (form) {
            //         const createdAt = form.created_at.substring(0, 10); // Extract YYYY-MM-DD part

            //         const isMonitorSelected = selectedMonitors.length === 0 || selectedMonitors.includes(form.filled_by.id.toString()); //monitor_id
            //         const isDateSelected = selectedDates.length === 0 || selectedDates.includes(createdAt); // yyyy-mm-dd
            //         const isSectorSelected = selectedSectors.length === 0 || selectedSectors.includes(form.order_sector_label.id.toString()); //sector_id
            //         const isCompletedSelected = selectedCompleted.length === 0 || selectedCompleted.includes(form.completed.toString()); //true,false
            //         const isNationalitySelected = selectedNationality.length === 0 || selectedNationality.includes(form.nationality.id.toString()); //true,false

            //         return isMonitorSelected && isDateSelected && isSectorSelected && isCompletedSelected && isNationalitySelected;
            //     });

            //     // Re-draw DataTable with filtered data
            //     table.clear().rows.add(filteredData).draw();
            // });
            $('#date-filter-btn').on('click', function () {
                table.ajax.reload();
            });

            $('#date-reset-btn').on('click', function () {
                $('.selectpicker').selectpicker('deselectAll');
                table.ajax.reload();
            });


                            
            // Pusher listen real-time
            window.Echo.channel('ModelCRUD-changes').listen('.SubmittedForm-changes',function(data) {
                table.ajax.reload();
            });
        });

    </script>
@endpush
