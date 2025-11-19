@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endsection
@section('content')
    {{-- //! convert role to permission --}}
    @hasrole(['superadmin', 'admin'])
        {{-- <div class="row">
            @include('admin.dashboard.temp-admin.sections.tracking-map')
        </div> --}}
        @elserole("organization chairman")
        <div class="opacity-75"> <!-- Use container-fluid for full width -->
            <div class="row justify-content-center">
                <div class="col-12"> <!-- Ensure the row takes full width -->
                    <div class="card overflow-hidden">
                        <div class="bg-primary pb-0">
                            <div class="row g-0"> <!-- Remove gaps between columns if needed -->

                                <div class="col-md-4 thumb">
                                    <a class="thumbnail" href="https://rakaya.sa/" target="_blank">
                                        <img class="img-fluid w-100" src="{{ URL::asset('build/images/cover/right.png') }}"
                                            alt="">
                                    </a>
                                </div>
                                {{-- <h1>{{}}</h1> --}}
                                <div class="col-md-4 thumb">
                                        <img class="img-fluid w-100"
                                            src="{{ URL::asset('build/images/cover/0.png') }}"
                                            alt="">
                                </div>
                                <div class="col-md-4 thumb">
                                    <a class="thumbnail" href="{{$current_organization->domain??'https://rakaya.sa/'}}">
                                        <img class="img-fluid w-100"
                                            src="{{ URL::asset('build/images/cover/' . ($current_organization->id ?? 0) . '.png') }}"
                                            alt="">
                                    </a>
                                </div>
                                {{-- <div class="col-md-4 thumb">
                                    <a class="thumbnail" href="https://www.haj.gov.sa/Home" target="_blank">
                                        <img class="img-fluid w-100" src="{{ URL::asset('build/images/cover/left.png') }}"
                                            alt="">
                                    </a>
                                </div> --}}

                            </div>

                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                </div>
            </div>
        </div>
    @endhasrole
    @hasrole(['superadmin','government'])
        <div class="row">
            @include('admin.dashboard.admin.sections.organizations')
        </div>
    @endhasrole
    <div class="row">
        @include('admin.dashboard.admin.sections.organizations-tab-content')
    </div>


    {{-- Use @vite to include your main JavaScript file --}}
    @vite(['resources/js/bootstrap.js'])
    @push('after-scripts')
        <!-- apexcharts -->
        <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/echarts/echarts.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
        <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
        <script src="{{ URL::asset('build/js/chartSettings.js') }}"></script>

        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let allOrganizations = @json($all_organizations);
                let allOrganizationsIds = allOrganizations.map((organization) => {
                    if (organization.id != 0)
                        return organization.id
                });
                $(document).ready(function() {
                    let models = [
                        {
                            name: "Facility",
                            functions: (response_model, target, charts, withDT = false) => {
                                setTotalsDataByOrganization("0", response_model.length??0, 'facilities');
                            }
                        },
                        {
                            name: "Sector",
                            functions: (response_model, target, charts, withDT = false) => {
                                setTotalsData(response_model, target);
                                $.each(response_model, (index, model) => {
                                    model['organization_total_guest_quantity'] = model.reduce((sum, arr) => sum + arr.guest_quantity, 0)
                                });
                                setTotalsData(response_model, 'sector_guest_quantity','organization_total_guest_quantity');
                            }
                        },
                        {
                            name: "Ticket",
                            functions: (response_model, target, charts, withDT = false) => {
                                setTotalsData(response_model, target);
                                updateModelChart("ChartOrg", charts.pie_chart_series, target)
                                updateModelChart("ChartDateOrg", charts.stacked_column_chart_series,
                                target)
                                updateModelChart("DangerChartOrg", charts.danger_pie_chart_series,
                                target)
                                updateModelChart("DangerChartDateOrg", charts
                                .danger_stacked_column_chart_series,
                                target)
                                
                                updateDatatables(target);
                                
                            }
                        },
                        {
                            name: "Support",
                            functions: (response_model, target, charts, withDT = false) => {
                                setTotalsData(response_model, target);
                                let response_model_meal_supports = getSupportByType(response_model, '2');
                                let response_model_water_supports = getSupportByType(response_model, '3');
                                let support_status_id_passed = @json($support_status_id_passed->pluck('id'));
                                setTotalsData(response_model_meal_supports, 'meal_supports');
                                setTotalsData(response_model_water_supports, 'water_supports');

                                $.each(response_model_meal_supports, (index, model) => {
                                    model['quantity_food_supports'] = model.reduce((sum, arr) => ( support_status_id_passed.includes(arr.status_id) ? sum + arr.delivered_quantity : sum), 0)
                                });
                                setTotalsData(response_model_meal_supports, 'quantity_food_supports','quantity_food_supports');
                                
                                $.each(response_model_water_supports, (index, model) => {
                                    model['quantity_water_supports'] = model.reduce((sum, arr) => (( support_status_id_passed.includes(arr.status_id) ? sum + arr.delivered_quantity : sum)), 0)
                                });
                                setTotalsData(response_model_water_supports, 'quantity_water_supports','quantity_water_supports');
                                
                                updateModelChart("ChartOrg", charts.pie_chart_series, target)
                                updateModelChart("ChartDateOrg", charts.stacked_column_chart_series,
                                    target)

                                updateDatatables(target);
                            }
                        },
                        {
                            name: "Meal",
                            functions: (response_model, target, charts, withDT = false) => {
                                const RECIEVED_MEAL = 27;
                                $.each(response_model, (index, model) => {
                                    model['guest_quantity'] = model.reduce((sum, arr) => sum + arr.guest_quantity, 0)
                                    model['recieved_guest_quantity'] = model.filter(meal => meal.status_id == RECIEVED_MEAL).reduce((sum, arr) => sum + arr.guest_quantity, 0)
                                });
                                setTotalsData(response_model, target, 'guest_quantity');
                                setTotalsData(response_model, 'recieved_meals', 'recieved_guest_quantity');
                                updateModelChart("ChartOrg", charts.pie_chart_series, target)
                                updateModelChart("ChartDateOrg", charts.stacked_column_chart_series,
                                    target)

                                updateDatatables(target);
                            }
                        },
                        {
                            name: "SubmittedForm",
                            functions: (response_model, target, charts, withDT = false) => {
                                setTotalsData(response_model, target);
                                // updateModelChart("ChartOrg",charts.pie_chart_series,target)
                                updateModelChart("ChartDateOrg", charts.stacked_column_chart_series,
                                    target)

                                updateDatatables(target);
                                
                            }
                        },
                        {
                            name: "User",
                            functions: (response_model, target, charts, withDT = false) => {
                                setTotalsData(getUsersByRoles(response_model, ['monitor']), 'monitors');
                                setTotalsData(getUsersByRoles(response_model, ['organization admin',
                                    'organization employee', 'organization chairman'
                                ]), 'employees');
                                // setTotalsData(response_model, target);
                            }
                        },
                        {
                            name: "Order",
                            functions: (response_model, target, charts, withDT = false) => {
                                setTotalsData(response_model, target);
                                setTotalsData(getDataFromOrders(response_model, 'user_id'),
                                    'providors');
                                setTotalsData(getDataFromOrders(response_model, 'facility_id'),
                                    'facilities',
                                    'length', false);
                                updateModelChart("ChartOrg", charts.pie_chart_series, target)
                                updateModelChart("ChartDateOrg", charts.stacked_column_chart_series,
                                    target)

                                updateDatatables(target);

                            }
                        },

                    ];
                    $.each(models, (index, model) => {
                        fetch_ajax_data(model);
                        window.Echo.channel('ModelCRUD-changes').listen('.' + model.name +
                            '-changes',
                            function(
                                data) {
                                let model = models.find(object => {
                                    return object.name == data.model_name
                                });
                                fetch_ajax_data(model, true)
                            });
                    });
                }) // document ready

                //?===================================================

                function fetch_ajax_data(model, withDT = false) {
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('dashboard-data') }}",
                        data: {
                            model: model.name,
                        },
                        success: function(response) {
                            // setTotalsData(response[model.name],model.target);
                            model.functions(response[model.name], response.tableName, response.charts,
                                withDT);

                        }
                    });
                }

                //?===================================================

                function updateModelChart(chartName, chartData, target) {
                    $.each(chartData, (key, data) => {

                        let newOptions = {
                            series: Object.values(data),
                            labels: Object.keys(data),
                        };
                        window[target + chartName + key].updateOptions(newOptions, true)

                    })
                    
                }

                //?===================================================

                function updateDatatables(target){
                    // $.each(Object.keys(response_model), (key, organization) => {
                    //     window[target + '_' + organization].ajax.reload();
                    // });
                    // window[target + '_0'].ajax.reload();
                    let current_organization_id = "{{$current_organization->id??0}}";
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('/') }}/dt/"+ target.replace('_','-'),
                        @if(count($all_organizations) <= 1)
                        data:{
                            organization_id: [current_organization_id]
                        },
                        @endif
                        success: function(response) {
                            // window.tickets_datatable_data = response.data
                            // console.log(response.data);
                            if (window.hasOwnProperty(target + '_0')) {
                                // Reset and update global table
                                window[target + '_0']?.clear().rows.add(response.data).draw();

                                // Reset and update per-organization tables
                                // $.each(response.data, (key, item) => {
                                //     if (isNaN(item.organization_id)) {
                                //         console.log(item);
                                //     }
                                //     window[target + '_' + item.organization_id]?.clear().rows.add([item]).draw();
                                // });

                                // Redraw all tables to ensure display is updated
                                allOrganizationsIds.forEach(organization_id => {
                                    const filteredData = response.data.filter(item => item.organization_id == organization_id);
                                    window[target + '_' + organization_id]?.clear();
                                    window[target + '_' + organization_id]?.rows.add(filteredData);
                                    window[target + '_' + organization_id]?.draw();
                                });
                            } else {
                                window[target + '_' + current_organization_id]?.clear().rows.add(response.data).draw();
                            }
                            
                            // const grouped = window.tickets_datatable_data.reduce((acc, item) => {
                            //     (acc[item.organization_id] ||= []).push(item);
                            //     return acc;
                            // }, {});
                            // console.log(grouped);

                        }
                    });
                }
                //?===================================================

                function setOrderData(response, model) {
                    setTotalsData(response[model.name], model.target);

                }
                //?===================================================

                function setTotalsData(response_model, model, attributeToCount = "length", with_total = true) {
                    let remainingOrganizations = [...allOrganizationsIds];
                    $.each(response_model, (organization_id, model_objects) => {
                        setTotalsDataByOrganization(organization_id, model_objects[attributeToCount], model)
                        remainingOrganizations = removeItemFromArray(remainingOrganizations, +organization_id);
                    });
                    $.each(remainingOrganizations, (index, organization_id) => {
                        setTotalsDataByOrganization(organization_id, 0, model);
                    });
                    if (with_total) {
                        setTotalsDataByOrganization("0", Object.values(response_model).reduce((sum, arr) => sum + arr[
                            attributeToCount], 0), model);
                    }

                }

                //?===================================================

                function setTotalsDataByOrganization(organization_id, total_count, model) {
                    $('#total_' + model + '_' + organization_id).html((total_count).toLocaleString('en-US'));
                }

                //?===================================================

                function removeItemFromArray(array, item) {
                    return $.grep(array, function(value) {
                        return value !== item;
                    });
                }
                //?===================================================

                function getSupportByType(supports, type) {
                    return Object.fromEntries(
                        Object.entries(supports).map(([key, value]) => [key, value.filter(item => item.type ===
                            type)])
                    );
                }

                //?===================================================

                function getUsersByRoles(users, roles) {
                    // return users.filter((user)=> user.role_ids_array.some(r=> roles.includes(r)));
                    return Object.fromEntries(
                        Object.entries(users).map(([key, value]) => [key, value.filter(item => item.role_name.split(
                            ',').some(
                            r => roles.includes(r)))])
                    );
                }

                //?===================================================

                function getDataFromOrders(orders, attribute) {
                    let data_result = {};

                    // Iterate over the properties of the orders object
                    for (let key in orders) {
                        if (orders.hasOwnProperty(key)) {
                            // Get the original array
                            let originalArray = orders[key];
                            // Count unique elements in the array based on the 'id' property
                            let count = getUniqueElements(originalArray, attribute);
                            // Create a new object with the original array and the count
                            data_result[key] = [...count];
                        }
                    }
                    return data_result;
                }

                //?===================================================

                // Function to count unique elements in an array based on the 'id' property
                function getUniqueElements(arr, attribute) {
                    let ids = new Set();
                    arr.forEach(item => ids.add(item[attribute]));
                    return ids;
                }

                //?===================================================

                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust().draw();
                });

                //?===================================================
            });
        </script>
        <script></script>
    @endpush
@endsection
