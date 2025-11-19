<div class="{{ $class ?? '' }} alert align-content-center d-flex justify-content-center">
    <div id="{{ $chartId }}" class="apex-charts" dir="ltr"></div>
</div>

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                dataLabels: {
                    enabled: true,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,

                                total: {
                                    show: true,
                                    showAlways: false,
                                    label: '{{$Label}}' ?? '',
                                    fontSize: '{{$fontSize ?? '18px'}}',
                                    fontFamily: "IBM Plex Sans Arabic",
                                    value:241,
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString('en-US');
                                    }
                                },
                            },
                        }
                    }
                },
                series:[0],
                chart: {
                    width: '{{$size ?? 380}}',
                    type: 'donut',
                    ...CHART,
                    @hasrole(['organization chairman'])
                    toolbar:{show:false}
                    @endhasrole
                },
                colors: @json($colors) ?? chartColors,
                labels: ["0"],
                legend: {
                    show: true,
                    position: 'bottom',
                },
            }
            window.{{ $chartId }} = new ApexCharts(document.querySelector('#{{ $chartId }}'), options);
            window.{{ $chartId }}.render();
        });

    </script>
@endpush
