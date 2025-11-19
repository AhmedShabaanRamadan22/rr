<div class="{{ $class ?? '' }} alert align-content-center d-flex justify-content-center">
    <div id="{{ $chartId }}" class="apex-charts" dir="ltr"></div>
</div>

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [@json($data)],
                chart: {
                    type: 'radialBar',
                    ...CHART
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                color: @json($color),
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '22px',
                                color: @json($color),
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                stroke: {
                    dashArray: 3
                },
                labels: [@json($label)],
            }
            window.{{ $chartId }} = new ApexCharts(document.querySelector('#{{ $chartId }}'), options);
            window.{{ $chartId }}.render();
        });
    </script>
@endpush
