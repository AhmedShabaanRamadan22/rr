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
        @include('admin.dashboard.superadmin.sections.general-info')
        @include('admin.dashboard.superadmin.sections.meals-charts')
    </div>

    <div class="row">
        @include('admin.dashboard.superadmin.sections.tickets-statics')
        @include('admin.dashboard.superadmin.sections.tickets-charts')
        @include('admin.dashboard.superadmin.sections.latest-update')
    </div>

    <div class="row">
        @include('admin.dashboard.superadmin.sections.meals-statics')
        @include('admin.dashboard.superadmin.sections.support-charts')
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
        {{-- <script src="{{ URL::asset('build/js/chartSettings.js') }}"></script> --}}

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
                                const color = getComputedStyle(document.documentElement).getPropertyValue(
                                    newValue);
                                return color || newValue;
                            } else {
                                const val = value.split(",");
                                if (val.length === 2) {
                                    const rgbaColor =
                                        `rgba(${getComputedStyle(document.documentElement).getPropertyValue(val[0])}, ${val[1]})`;
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

            function initChart(orgId, chartData) {
                let options = {
                    series: [{
                        name: 'عدد الوجبات',
                        data: [4, 5, 6] // This data would be specific to the organization
                    }],
                    chart: {
                        height: CHARTHEIGHT,
                        type: 'bar',
                        fontFamily: 'IBM Plex Sans Arabic', // Set font family for the chart
                        toolbar: toolbarSettings,
                        locales: [locales],
                        defaultLocale: 'custom'
                    },
                    dataLabels: {
                        enabled: true,
                        fontFamily: "IBM Plex Sans Arabic",
                    },
                    colors: chartData.primary_color ?? 'red',
                    plotOptions: {
                        bar: {
                            opacity: 0.7, // Adjusted opacity
                        }
                    },
                    fill: {
                        opacity: 0.5, // Alternative approach for opacity
                    },
                    // ... other configurations ...
                };

                var chart = new ApexCharts(document.querySelector("#chart-" + orgId), options);
                chart.render();
            }

            function initMealsGoalsChart(orgId, chartData) {
                var options = {
                    series: [67],
                    chart: {
                        height: CHARTHEIGHT,
                        type: 'radialBar',
                        fontFamily: 'IBM Plex Sans Arabic', // Set font family for the chart
                        toolbar: toolbarSettings,
                        locales: [locales],
                        defaultLocale: 'custom'

                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 135,
                            dataLabels: {
                                name: {
                                    fontSize: '16px',
                                    fontFamily: 'IBM Plex Sans Arabic', // Set font family for the chart
                                    color: undefined,
                                    offsetY: 120
                                },
                                value: {
                                    offsetY: 76,
                                    fontSize: '22px',
                                    fontFamily: 'IBM Plex Sans Arabic', // Set font family for the chart
                                    color: undefined,
                                    formatter: function(val) {
                                        return val;
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
                        dashArray: 2
                    },
                    labels: ['الهدف اليومي'],
                    colors: [chartData.primary_color ?? 'red'],
                };

                var chart = new ApexCharts(document.querySelector("#meals-chart-" + orgId), options);
                chart.render();
            }

            function loadCharts() {

                @foreach ($all_organizations as $org)
                    var chartData = @json($org);
                    initChart({{ $org->id }}, chartData);
                    initMealsGoalsChart({{ $org->id }}, chartData);
                @endforeach


                // Multi-Radial Bar
                var multiRadialBarColor = "";
                multiRadialBarColor = getChartColorsArray("multiRadialChart");
                var dangerData = @json($dangerData);
                if (multiRadialBarColor) {
                    var options = {
                        series: Object.values(dangerData),
                        chart: {
                            height: CHARTHEIGHT,
                            type: 'radialBar',
                            toolbar: toolbarSettings,
                            locales: [locales],
                            defaultLocale: 'custom'
                        },
                        plotOptions: {
                            radialBar: {
                                track: {
                                    background: multiRadialBarColor,
                                    opacity: 0.15,
                                },
                                dataLabels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'إجمالي البلاغات',
                                        fontSize: '18px',
                                        fontFamily: "IBM Plex Sans Arabic",
                                        formatter: function(w) {
                                            return {{ $tickets->count() }}
                                        }
                                    },
                                    name: {
                                        show: true,
                                        fontSize: '18px',
                                        fontFamily: "IBM Plex Sans Arabic",
                                        offsetY: -5
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '14px',
                                        fontFamily: undefined,
                                        fontFamily: "IBM Plex Sans Arabic",
                                        offsetY: 16,
                                        formatter: function(val) {
                                            return val;
                                        }
                                    }
                                },
                            }
                        },
                        legend: {
                            show: true,
                            fontFamily: "IBM Plex Sans Arabic",
                            position: 'bottom',
                        },
                        labels: Object.keys(dangerData),
                        colors: multiRadialBarColor,
                    };

                    if (multiRadialBarChart != "")
                        multiRadialBarChart.destroy();
                    multiRadialBarChart = new ApexCharts(document.querySelector("#multiRadialChart"), options);
                    multiRadialBarChart.render();
                }






                // Basic Bar chart
                var chartBarColors = "";
                var organizationsName = @json($organizations->pluck('name_ar'));
                var organizationClassificationCounts = @json($organizations->pluck('classifications_count'));
                chartBarColors = getChartColorsArray("bar_chart");

                if (chartBarColors) {
                    var options = {
                        chart: {
                            height: CHARTHEIGHT,
                            type: 'bar',
                            fontFamily: 'IBM Plex Sans Arabic', // Set font family for the chart
                            toolbar: toolbarSettings,
                            locales: [locales],
                            defaultLocale: 'custom'
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                distributed: true // Ensure each bar has its own color
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            fontFamily: "IBM Plex Sans Arabic",
                        },
                        series: [{
                            name: 'عدد الوجبات',
                            data: [3, 5, 66]
                        }],
                        colors: chartBarColors,
                        grid: {
                            borderColor: '#f1f1f1',
                        },
                        legend: {
                            fontFamily: "IBM Plex Sans Arabic",
                        },
                        xaxis: {
                            categories: organizationsName,
                            labels: {
                                style: {
                                    fontFamily: 'IBM Plex Sans Arabic' // Set font family for x-axis labels
                                }
                            }
                        }
                    }

                    if (chartBarChart != "") {
                        chartBarChart.destroy();
                    }
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
                            height: CHARTHEIGHT,
                            type: 'area',
                            fontFamily: 'IBM Plex Sans Arabic', // Set font family for the chart
                            toolbar: toolbarSettings,
                            locales: [locales],
                            defaultLocale: 'custom'
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
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                                'Nov',
                                'Dec'
                            ],
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
                            height: CHARTHEIGHT,
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
                                        formatter: function(val) {
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

            window.addEventListener("resize", function() {
                setTimeout(() => {
                    loadCharts();
                }, 250);
            });
            loadCharts();
        </script>
    @endpush
@endsection
