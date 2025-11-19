@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <div class="row">
        <div class="col-xl-6">
            <div class="card card-height-100 border-0 overflow-hidden">
                <div class="card-header">
                    <h4 class="card-title mb-0">الإحصائيات</h4>
                </div><!-- end card header -->
                <div class="card-body p-0">
                    <div class="row g-0">
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-end-md border-bottom rounded-0 mb-0">
                                    <div class="card-body">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                                <i class="ph-users"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">
                                                {{ trans('translation.users') }}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value" data-target="{{$users->count()}}">0</span>
                                            </h4>

                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-end-md rounded-0 mb-0">
                                    <div class="card-body">

                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-body rounded-circle fs-3">
                                                <i class="ph-eye"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">{{ trans('translation.providors') }}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value"
                                                    data-target="{{$orders->whereIn('status_id',5)->count()}}">0</span>
                                            </h4>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-end-md rounded-0 mb-0">
                                    <div class="card-body">

                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-body rounded-circle fs-3">
                                                <i class="ph-eye"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">{{ trans('translation.monitors') }}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value"
                                                data-target="{{$monitorCount}}">0</span>
                                            </h4>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-bottom rounded-0 mb-0">
                                    <div class="card-body">

                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-dark-subtle text-dark rounded-circle fs-3">
                                                <i class="ph-bag"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">{{trans('translation.orders')}}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value" data-target="{{$orders->count()}}">0</span>
                                            </h4>

                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div>
                        <div class="row g-0">
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-top border-top-md-0 rounded-0 mb-0">
                                    <div class="card-body">

                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                                <i class="ph-users-three"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">{{ trans('translation.support water') }}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value"
                                                    data-target="2500">0</span>k </h4>

                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-top border-top-md-0 rounded-0 mb-0">
                                    <div class="card-body">

                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                                <i class="ph-users-three"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">{{ trans('translation.support meals') }}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value"
                                                    data-target="2500">0</span>k </h4>

                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-top border-top-md-0 rounded-0 mb-0">
                                    <div class="card-body">

                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                                <i class="ph-users-three"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">{{ trans('translation.meals') }}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value"
                                                    data-target="2500">0</span>k </h4>

                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <!-- card -->
                                <div class="card shadow-none border-end-md rounded-0 mb-0">
                                    <div class="card-body">

                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-body rounded-circle fs-3">
                                                <i class="ph-eye"></i>
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-uppercase fw-medium text-muted text-truncate fs-sm">{{ trans('translation.tickets') }}</p>
                                            <h4 class="fw-semibold mb-3"><span class="counter-value"
                                                    data-target="{{$tickets->count()}}">0</span></h4>

                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">عدد الوجبات التي تم تسليمها</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div id="bar_chart" data-colors='["--tb-success"]' class="apex-charts" dir="ltr"></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Revenue Overview</h5>
                    <div class="flex-shrink-0">
                        <input type="text" class="form-control form-control-sm" id="exampleInputPassword1"
                            data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y"
                            data-default-date="01 Feb 2023 to 28 Feb 2023">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-3">
                            <div class="nav flex-column nav-light nav-pills gap-3" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link d-flex p-2 gap-3 active" id="revenue-tab" data-bs-toggle="pill"
                                    href="#revenue" role="tab" aria-controls="revenue" aria-selected="true">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title rounded bg-warning-subtle text-warning fs-2xl">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="text-reset">$<span class="counter-value" data-target="2478">0</span>M
                                        </h5>
                                        <p class="mb-0">Total Revenue</p>
                                    </div>
                                </a>
                                <a class="nav-link d-flex p-2 gap-3" id="income-tab" data-bs-toggle="pill"
                                    href="#income" role="tab" aria-controls="income" aria-selected="false">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title rounded bg-success-subtle text-success fs-2xl">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="text-reset">$<span class="counter-value"
                                                data-target="14587.37">0</span></h5>
                                        <p class="mb-0">Total Income</p>
                                    </div>
                                </a>
                                <a class="nav-link d-flex p-2 gap-3" id="property-sale-tab" data-bs-toggle="pill"
                                    href="#property-sale" role="tab" aria-controls="property-sale"
                                    aria-selected="false">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title rounded bg-danger-subtle text-danger fs-2xl">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="text-reset"><span class="counter-value" data-target="2365">0</span>
                                        </h5>
                                        <p class="mb-0">Property Sell</p>
                                    </div>
                                </a>
                                <a class="nav-link d-flex p-2 gap-3" id="_-tab" data-bs-toggle="pill"
                                    href="#propetry-rent" role="tab" aria-controls="propetry-rent"
                                    aria-selected="false">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title rounded bg-primary-subtle text-primary fs-2xl">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="text-reset"><span class="counter-value" data-target="3456">0</span>
                                        </h5>
                                        <p class="mb-0">Property Rent</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-9">
                            <div class="tab-content text-muted">
                                <div class="tab-pane active" id="revenue" role="tabpanel">
                                    <div id="total_revenue" data-colors='["--tb-primary"]'
                                        class="apex-charts effect-chart" dir="ltr"></div>
                                </div>
                                <!--end tab-->
                                <div class="tab-pane" id="income" role="tabpanel">
                                    <div id="total_income" data-colors='["--tb-success"]' class="apex-charts"
                                        dir="ltr"></div>
                                </div>
                                <div class="tab-pane" id="property-sale" role="tabpanel">
                                    <div id="property_sale_chart" data-colors='["--tb-danger"]' class="apex-charts"
                                        dir="ltr"></div>
                                </div>
                                <div class="tab-pane" id="propetry-rent" role="tabpanel">
                                    <div id="propetry_rent" data-colors='["--tb-info"]' class="apex-charts"
                                        dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h6 class="card-title mb-0 flex-grow-1">{{trans('translation.tickets detail')}}</h6>
                    {{-- <div class="dropdown card-header-dropdown flex-shrink-0">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Current Years</a>
                            <a class="dropdown-item" href="#">Last Years</a>
                        </div>
                    </div> --}}
                </div>
                <div class="card-body">
                    <div id="multiRadialChart" data-colors='{{json_encode($dangerColor)}}' dir="ltr"></div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-height-100">
                <div class="card-header d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Sales</h4>
                    <div>
                        <button type="button" class="btn btn-subtle-secondary btn-sm">
                            ALL
                        </button>
                        <button type="button" class="btn btn-subtle-secondary btn-sm">
                            ORDERS
                        </button>
                        <button type="button" class="btn btn-subtle-secondary btn-sm">
                            NEW
                        </button>
                        <button type="button" class="btn btn-subtle-primary btn-sm">
                            FORM
                        </button>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div data-simplebar class="px-3" style="max-height: 360px;">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('build/images/users/48/avatar-2.jpg') }}"
                                                    alt="" class="avatar-sm rounded-circle p-1">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md mb-1">Bethany Nienow</h6>
                                                <p class="text-muted mb-0">03 Feb, 2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <h6 class="fs-md">$630.73</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('build/images/users/48/avatar-7.jpg') }}"
                                                    alt="" class="avatar-sm rounded-circle p-1">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md mb-1">Sonia Conn</h6>
                                                <p class="text-muted mb-0">03 Feb, 2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <h6 class="fs-md">$1,452.64</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('build/images/users/48/avatar-4.jpg') }}"
                                                    alt="" class="avatar-sm rounded-circle p-1">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md mb-1">Talon Bradtke</h6>
                                                <p class="text-muted mb-0">03 Feb, 2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <h6 class="fs-md">$478.87</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('build/images/users/48/avatar-5.jpg') }}"
                                                    alt="" class="avatar-sm rounded-circle p-1">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md mb-1">Tyrell Kerluke</h6>
                                                <p class="text-muted mb-0">03 Feb, 2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <h6 class="fs-md">$82.14</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('build/images/users/48/avatar-6.jpg') }}"
                                                    alt="" class="avatar-sm rounded-circle p-1">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md mb-1">Ross Zieme</h6>
                                                <p class="text-muted mb-0">03 Feb, 2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <h6 class="fs-md">$79.00</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('build/images/users/48/avatar-1.jpg') }}"
                                                    alt="" class="avatar-sm rounded-circle p-1">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md mb-1">Hollis Spencer</h6>
                                                <p class="text-muted mb-0">03 Feb, 2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <h6 class="fs-md">$849.05</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="flex-shrink-0">
                                                <img src="{{ URL::asset('build/images/users/48/avatar-8.jpg') }}"
                                                    alt="" class="avatar-sm rounded-circle p-1">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md mb-1">Cordia Grady</h6>
                                                <p class="text-muted mb-0">03 Feb, 2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <h6 class="fs-md">$254.32</h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Stroked Circle Chart</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div id="stroked_radialbar" data-colors='["--tb-success"]' class="apex-charts" dir="ltr"></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>

        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Learning Overview</h4>
                    <div>
                        <button type="button" class="btn btn-subtle-secondary btn-sm">
                            ALL
                        </button>
                        <button type="button" class="btn btn-subtle-secondary btn-sm">
                            1M
                        </button>
                        <button type="button" class="btn btn-subtle-secondary btn-sm">
                            6M
                        </button>
                        <button type="button" class="btn btn-subtle-primary btn-sm">
                            1Y
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="area_chart_spline" data-colors='["--tb-primary", "--tb-secondary"]' class="apex-charts ms-n3" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>


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

<script>

    var multiRadialBarChart = "";
    var totalRevenueChart = "";
    var totalIncomeChart = "";
    var propertySale2Chart = "";
    var propetryRentChart = "";
    var chartBarChart = "";
    var areachartSplineChart = "";
    var chartStorkeRadialbarChart = "";

    function getChartColorsArray(chartId) {
        const chartElement = document.getElementById(chartId);
        if (chartElement) {
            const colors = chartElement.dataset.colors;
            if (colors) {
                const parsedColors = JSON.parse(colors);
                const mappedColors = parsedColors.map((value) => {
                    const newValue = value.replace(/\s/g, "");
                    if (!newValue.includes(",")) {
                        const color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                        return color || newValue;
                    } else {
                        const val = value.split(",");
                        if (val.length === 2) {
                            const rgbaColor = `rgba(${getComputedStyle(document.documentElement).getPropertyValue(val[0])}, ${val[1]})`;
                            return rgbaColor;
                        } else {
                            return newValue;
                        }
                    }
                });
                return mappedColors;
            } else {
                console.warn(`data-colors attribute not found on: ${chartId}`);
            }
        }
    }

    function loadCharts() {
        //total_revenue
        var totalRevenueColors = "";
        totalRevenueColors = getChartColorsArray("total_revenue");
        if (totalRevenueColors) {
            var options = {
            series: [{
                name: 'Income',
                data: [26, 24.65, 18.24, 29.02, 23.65, 27, 21.18, 24.65, 27.32, 25, 24.65, 29.32]
            }],
            chart: {
                type: 'bar',
                height: 328,
                stacked: true,
                toolbar: {
                    show: false
                },
            },
            plotOptions: {
                bar: {
                    columnWidth: '30%',
                    lineCap: 'round',
                    borderRadiusOnAllStackedSeries: true

                },
            },
            grid: {
                padding: {
                    left: 0,
                    right: 0,
                    top: -15,
                    bottom: -15
                }
            },
            colors: totalRevenueColors,
            fill: {
                opacity: 1
            },
            dataLabels: {
                enabled: false,
                textAnchor: 'top',
            },
            yaxis: {
                labels: {
                    show: true,
                    formatter: function (y) {
                        return y.toFixed(0) + "k";
                    }
                },
            },
            legend: {
                show: false,
                position: 'top',
                horizontalAlign: 'right',
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: {
                    rotate: -90
                },
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    stroke: {
                        width: 1
                    },
                },
            }
        };

        if (totalRevenueChart != "")
            totalRevenueChart.destroy();
        totalRevenueChart = new ApexCharts(document.querySelector("#total_revenue"), options);
        totalRevenueChart.render();
        }

        // Multi-Radial Bar
        var multiRadialBarColor = "";
        multiRadialBarColor = getChartColorsArray("multiRadialChart");
        var dangerData = @json($dangerData);
        if (multiRadialBarColor) {
            var options = {
            series: Object.values(dangerData),
            chart: {
                height: 360,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    track: {
                        background: multiRadialBarColor,
                        opacity: 0.15,
                    },
                    dataLabels: {
                        name: {
                            fontSize: '22px',
                        },
                        value: {
                            fontSize: '16px',
                            color: "#87888a",

                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return {{$tickets->count()}}
                            }
                        }
                    },
                }
            },
            legend: {
                show: true,
                position: 'bottom',
            },
            labels: Object.keys(dangerData),
            colors: multiRadialBarColor
        };

        if (multiRadialBarChart != "")
        multiRadialBarChart.destroy();
        multiRadialBarChart = new ApexCharts(document.querySelector("#multiRadialChart"), options);
        multiRadialBarChart.render();
        }



        //  total_income
        var totalIncomeColors = "";
        totalIncomeColors = getChartColorsArray("total_income");
        if (totalIncomeColors) {
        var options = {
            series: [{
                name: "Income",
                data: [32, 18, 13, 17, 26, 34, 47, 51, 59, 63, 44, 38, 53, 69, 72, 83, 90, 110, 130, 117, 103, 92, 95, 119, 80, 96, 116, 125]
            }],
            chart: {
                height: 328,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            grid: {
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            markers: {
                size: 0,
                hover: {
                    sizeOffset: 4
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            colors: totalIncomeColors,
            xaxis: {
                type: 'datetime',
                categories: ['02/01/2023 GMT', '02/02/2023 GMT', '02/03/2023 GMT', '02/04/2023 GMT',
                    '02/05/2023 GMT', '02/06/2023 GMT', '02/07/2023 GMT', '02/08/2023 GMT', '02/09/2023 GMT', '02/10/2023 GMT', '02/11/2023 GMT', '02/12/2023 GMT', '02/13/2023 GMT',
                    '02/14/2023 GMT', '02/15/2023 GMT', '02/16/2023 GMT', '02/17/2023 GMT', '02/18/2023 GMT', '02/19/2023 GMT', '02/20/2023 GMT', '02/21/2023 GMT', '02/22/2023 GMT',
                    '02/23/2023 GMT', '02/24/2023 GMT', '02/25/2023 GMT', '02/26/2023 GMT', '02/27/2023 GMT', '02/28/2023 GMT'
                ]
            },
            yaxis: {
                labels: {
                    show: true,
                    formatter: function (y) {
                        return "$" + y.toFixed(0);
                    }
                },
            },
        };

        if (totalIncomeChart != "")
            totalIncomeChart.destroy();
        totalIncomeChart = new ApexCharts(document.querySelector("#total_income"), options);
        totalIncomeChart.render();
        }

        // property_sale_chart
        var propertySaleChartColors = "";
        propertySaleChartColors = getChartColorsArray("property_sale_chart");
        if (propertySaleChartColors) {
            var options = {
            series: [{
                name: "Property Rent",
                data: [30, 57, 25, 33, 20, 27, 38, 49, 42, 58, 33, 46, 40, 34, 41, 53, 19, 23, 36, 52, 58, 43]
            }],
            chart: {
                height: 328,
                type: 'bar',
                toolbar: {
                    show: false,
                }
            },
            colors: propertySaleChartColors,
            plotOptions: {
                bar: {
                    columnWidth: '30%',
                    distributed: true,
                    borderRadius: 5,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                type: 'datetime',
                categories: ['01/01/2023 GMT', '01/02/2023 GMT', '01/03/2023 GMT', '01/04/2023 GMT',
                    '01/05/2023 GMT', '01/06/2023 GMT', '01/07/2023 GMT', '01/08/2023 GMT', '01/09/2023 GMT', '01/10/2023 GMT', '01/11/2023 GMT', '01/12/2023 GMT', '01/13/2023 GMT',
                    '01/14/2023 GMT', '01/15/2023 GMT', '01/16/2023 GMT', '01/17/2023 GMT', '01/18/2023 GMT', '01/19/2023 GMT', '01/20/2023 GMT', '01/21/2023 GMT', '01/22/2023 GMT'
                ],
            },
        };

        if (propertySale2Chart != "")
            propertySale2Chart.destroy();
        propertySale2Chart = new ApexCharts(document.querySelector("#property_sale_chart"), options);
        propertySale2Chart.render();
        }

        // propetry_rent Charts
        var propetryRentColors = "";
        propetryRentColors = getChartColorsArray("propetry_rent");
        if (propetryRentColors) {
            var options = {
            series: [{
                name: 'Property Rent',
                data: [31, 40, 28, 43, 59, 87, 75, 60, 51, 66, 109, 100]
            }],
            chart: {
                height: 328,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            fill: {
                opacity: "0.01",
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            colors: propetryRentColors,
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: {
                    rotate: -90
                },
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    stroke: {
                        width: 1
                    },
                },
            }
        };

        if (propetryRentChart != "")
            propetryRentChart.destroy();
        propetryRentChart = new ApexCharts(document.querySelector("#propetry_rent"), options);
        propetryRentChart.render();
        }

        // Basic Bar chart
        var chartBarColors = "";
        var organizationsName = @json($organizations->pluck('name_ar'));
        var organizationClassificationCounts = @json($organizations->pluck('classifications_count'));
        chartBarColors = getChartColorsArray("bar_chart");

        if (chartBarColors) {
            var options = {
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false,
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                data: organizationClassificationCounts
            }],
            colors: chartBarColors,
            grid: {
                borderColor: '#f1f1f1',
            },
            xaxis: {
                categories: organizationsName
            }
        }

        if (chartBarChart != "")
            chartBarChart.destroy();
        chartBarChart = new ApexCharts(document.querySelector("#bar_chart"), options);
        chartBarChart.render();
        }




        //  Spline Area Charts
        var areachartSplineColors = "";
        areachartSplineColors = getChartColorsArray("area_chart_spline");
        if (areachartSplineColors) {
            var options = {
            series: [{
                name: 'This Month',
                data: [49, 54, 48, 54, 67, 88, 96, 102, 120, 133]
            }, {
                name: 'Last Month',
                data: [57, 66, 74, 63, 55, 70, 84, 97, 112, 99]
            }],
            chart: {
                height: 320,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            fill: {
                type: ['gradient', 'gradient'],
                gradient: {
                    shadeIntensity: 1,
                    type: "vertical",
                    inverseColors: false,
                    opacityFrom: 0.2,
                    opacityTo: 0.0,
                    stops: [50, 70, 100, 100]
                },
            },
            markers: {
                size: 4,
                strokeColors: areachartSplineColors,
                strokeWidth: 1,
                strokeOpacity: 0.9,
                fillOpacity: 1,
                hover: {
                    size: 6,
                }
            },
            grid: {
                show: false,
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                },
            },
            legend: {
                show: false,
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: {
                    rotate: -90
                },
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    stroke: {
                        width: 1
                    },
                },
            },
            stroke: {
                width: [2, 2],
                curve: 'smooth'
            },
            colors: areachartSplineColors,
        };

        if (areachartSplineChart != "")
            areachartSplineChart.destroy();
        areachartSplineChart = new ApexCharts(document.querySelector("#area_chart_spline"), options);
        areachartSplineChart.render();
        }

        // Stroked Gauge
        var chartStorkeRadialbarColors = "";
        chartStorkeRadialbarColors = getChartColorsArray("stroked_radialbar");
        if (chartStorkeRadialbarColors) {
            var options = {
                series: [67],
                chart: {
                    height: 326,
                    type: 'radialBar',
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                color: undefined,
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '22px',
                                color: undefined,
                                formatter: function (val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ['Median Ratio'],
                colors: chartStorkeRadialbarColors
            };

            if (chartStorkeRadialbarChart != "")
                chartStorkeRadialbarChart.destroy();
            chartStorkeRadialbarChart = new ApexCharts(document.querySelector("#stroked_radialbar"), options);
            chartStorkeRadialbarChart.render();
        }
    }

    window.addEventListener("resize", function () {
        setTimeout(() => {
            loadCharts();
        }, 250);
    });
    loadCharts();
    </script>

@endpush
@endsection

