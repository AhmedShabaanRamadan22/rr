<div class="{{ $class ?? '' }} ">
    <div id="{{ $chartId }}" class="apex-charts" dir="ltr"></div>
</div>

@push('after-scripts')
    <script>
        // let chart2;
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                colors: @json($colors).length != 0 ? @json($colors) : chartColors,
                series: [],
                chart: {
                    type: 'bar',
                    stacked: true,
                    ...CHART,
                    @hasrole(['organization chairman'])
                    toolbar:{show:false}
                    @endhasrole
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10,
                        dataLabels: {
                            total: {
                                enabled: true,
                                style: {
                                    fontSize: '13px',
                                    fontWeight: 900
                                }
                            }
                        }
                    },
                },
                xaxis: {
                    // type: 'datetime',
                    categories: @json($categories),
                },
                legend: {
                    position: 'right',
                    offsetY: 40
                },
                fill: {
                    opacity: 20
                }
            };

            window.{{ $chartId }} = new ApexCharts(document.querySelector('#{{ $chartId }}'), options);
            window.{{ $chartId }}.render();

        });
    </script>
@endpush
