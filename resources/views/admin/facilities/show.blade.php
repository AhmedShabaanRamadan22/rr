@extends('layouts.master')
@section('title', __('Facilities'))
@push('styles')
@endpush
@section('content')

    <x-breadcrumb title="{{ $facility->name }}">
        <li class="breadcrumb-item"><a href="{{ route('facilities.index') }}">{{ trans('translation.facilities') }}</a></li>
    </x-breadcrumb>

    <div class="row">
        <div class="col-xxl-12">
            <div class="card">

                <div class="card-header d-flex justify-content-center">
                    <ul class="nav nav-pills custom-hover-nav-tabs">
                        {{-- tabs --}}
                        @component('components.nav-pills.pills', ['id' => 'owner', 'icon' => 'ri-user-line'])
                        @endcomponent
                        @component('components.nav-pills.pills', ['id' => 'facility', 'icon' => 'ri-hotel-line'])
                        @endcomponent
                        @component('components.nav-pills.pills', ['id' => 'attachment', 'icon' => 'ri-file-text-line'])
                        @endcomponent
                        @component('components.nav-pills.pills', ['id' => 'employees', 'icon' => 'ri-team-line'])
                        @endcomponent
                        @component('components.nav-pills.pills', ['id' => 'orders', 'icon' => 'mdi mdi-order-bool-descending-variant'])
                        @endcomponent
                        @component('components.nav-pills.pills', ['id' => 'audits', 'icon' => 'ri-time-line'])
                        @endcomponent
                    </ul>
                </div>

                <div class="card-body">
                    <div class="row ">
                         <div class="col text-end">

                             <a target="_blank"
                                 class="btn btn-outline-primary m-1 on-default "
                                 href="{{ (route('admin.facilities.report', $facility->uuid?? fakeUuid())) }}"
                                 ><i class="mdi mdi-file-document-outline"></i> {{trans('translation.download-facility-report')}}
                             </a>
                        </div>
                    </div>
                    <div class="tab-content">

                        {{-- معلومات مالك المنشأة --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'owner', 'title' => 'facility-owner-info'])
                            @component('admin.facilities.components.owner-tab', [
                                'facility' => $facility,
                                'national_id' => $facility_owner_national_id,
                            ])
                            @endcomponent
                        @endcomponent

                        {{-- معلومات المنشأة --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'facility', 'title' => 'facility-info'])
                            @component('admin.facilities.components.facility-tab', ['facility' => $facility])
                            @endcomponent
                        @endcomponent

                        {{-- المرفقات --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'attachment', 'title' => 'facility-attachments'])
                            @component('admin.facilities.components.attachment-tab', ['facility' => $facility, 'remaining_attachments' => $remaining_attachments])
                            @endcomponent
                        @endcomponent

                        {{-- الموظفين --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'employees', 'title' => 'facility-employees'])
                            @component('admin.facilities.components.employees-tab', ['columns' => $employees_column])
                            @endcomponent
                        @endcomponent

                        {{-- الطلبات --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'orders', 'title' => 'facility-orders'])
                            @component('admin.orders.components.orders-table', ['columns' => $order_columns, 'facility' => $facility])
                            @endcomponent
                        @endcomponent

                        {{-- سجل العمليات --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'audits', 'title' => 'facility-audits'])
                            @component('components.audits', ['audits' => $audits])
                            @endcomponent
                            {{-- @component('admin.facilities.components.audits-tab', ['audits' => $audits])@endcomponent --}}
                        @endcomponent

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('after-scripts')
        {{-- save tab state --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const activeTab = JSON.parse(sessionStorage.getItem('facility'))?.tab;
                // if it was null we will set it to first one
                if (activeTab == null) {
                    openTab(document.getElementById('owner-tab'));
                } else {
                    openTab(document.getElementById(`${activeTab}-tab`))
                }
            });

            const setActiveTab = (tab) => {
                let state = JSON.parse(sessionStorage.getItem('facility'))
                state = {
                    ...state,
                    tab: tab
                };
                sessionStorage.setItem('facility', JSON.stringify(state));
            }

            const openTab = (elem) => {
                elem.click();
            }
        </script>

        <script>
            // data table ajax
            $(document).ready(function() {
                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust().draw();
                });
                window.datatable = $('#emplyees-datatable').DataTable({
                    "ajax": {
                        "url": "{{ route('facility_employees.datatable', $facility->id) }}",
                    },
                    rowId: 'id',
                    'stateSave': true,
                    createdRow: function(row, data, indice) {

                        $(row).find("td:eq(7)").attr('data-name', data.id).attr('data-type');
                    },
                    language: datatable_localized,
                    'createdRow': function(row, data, rowIndex) {
                        $(row).attr('data-id', data.id);
                    },
                    "columns": [{
                            "data": 'id',
                            render: (data, type, row, meta) => {
                                return ++meta.row;
                            }
                        },
                        {
                            "data": 'name',
                        },
                        {
                            "data": 'national_id',
                        },
                        {
                            "data": 'facility_employee_position',
                        },
                        {
                            "data": 'employee_attachments',
                        },
                        // {
                        //     "data": 'national_id_attachment',
                        // },
                        // {
                        //     "data": 'work_card_photo',
                        // },
                        // {
                        //     "data": 'health_card_photo',
                        // },
                        // {
                        //     "data": 'personal_photo',
                        // },
                        // {
                        //     "data": 'employee_cv_attachment',
                        // },
                    ],
                    buttons: ['csv', 'excel'],
                    dom: 'lfritpB',
                    "ordering": false,
                });

            });
        </script>
    @endpush
@endsection
