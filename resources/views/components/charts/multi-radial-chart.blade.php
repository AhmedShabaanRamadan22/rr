<div class="{{ $class ?? '' }} alert align-content-center d-flex justify-content-center">
    <div id="{{ $chartId }}" class="apex-charts" dir="ltr"></div>
</div>

@push('after-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let options = {
                chart: {
                    type: 'radialBar',
                    ...CHART,
                    height: 450,
                },
                plotOptions: {
                    radialBar: {
                        track: {
                            background: @json($colors),
                        },
                        dataLabels: {
                            show: true,
                            total: {
                                show: true,
                                label: '{{$Label}}' ?? '',
                                fontSize: '18px',
                                fontFamily: "IBM Plex Sans Arabic",
                                formatter: function(w) {
                                    return {{ $totalData }}
                                }
                            },
                            value: {
                                formatter: function(val) {
                                    return val;
                                }
                            }
                        },
                    }
                },
                series: @json($data),
                legend: {
                    show: true,
                    position: 'bottom',
                },
                colors: @json($colors).length != 0 ? @json($colors) : chartColors,
                labels: @json($labels),
            };
            if (document.getElementById('#{{ $chartId }}')) {
            chart.destroy();
        } else {
            let chart = new ApexCharts(document.querySelector('#{{ $chartId }}'), options);
            chart.render();
        }
    });
</script>
@endpush
