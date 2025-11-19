{{-- before use this comp be sure add dependency Apexchart for it  --}}
<div class="{{ $class ?? '' }}  alert align-content-center d-flex justify-content-center">
    <div id="{{ $chartId }}" class="apex-charts" dir="ltr"></div>
</div>

@push('after-scripts')
    <script data-name="init-barchart-order">
        let {{ $chartId }};
        document.addEventListener('DOMContentLoaded', function() {
            let options = {
                chart: {
                    type: 'bar',
                    ...CHART,
                    @hasrole(['organization chairman'])
                    toolbar:{show:false}
                    @endhasrole
                },
                series: [{
                    name: '{{ $label }}',
                    data: @json($data)
                }],
                xaxis: {
                    categories: @json($categories),
                },
                fill: {
                    opacity: 0.75
                },
                plotOptions: {
                    bar: {
                        horizontal: '{{ $horizontal ?? false }}',
                        borderRadius: 10
                    },
                },
                colors: @json($color).length != 0 ? @json($color) : chartColors,
            }
            {{ $chartId }} = new ApexCharts(document.querySelector('#{{ $chartId }}'), options);
            {{ $chartId }}.render();

        });

//         $(document).ready(function() {
//         var channel = window.Echo.channel('model-changes')
//             channel.listen('.ModelCRUDEvent', (data) => {
//         // Check if the event pertains to the data this chart displays
//         if (data.model_name + '-chart' === '{{ $chartId }}') {
//             refreshChart('{{ $chartId }}');
//             //chart.updateSeries([/* new series data based on the event */]);

//         }
//     });
// });


        $(document).ready(function() {
            var channel = window.Echo.channel('charts');
                channel.listen('.chart-event', function(data) {
                    let newOptions = {
                        series: [{
                            data: data.ordersData
                        }],
                    };
                    if ({{ $chartId }}) {
                        {{ $chartId }}.updateOptions(newOptions, true);
                    }
                });
        });

        // function refreshChart(chartId) {
        //     // Example: reload the page or the part of the page containing the chart
        //     location.reload();
        //     // Or, for a more sophisticated approach, replace the chart container's HTML via AJAX
        // }

        // function refreshChart(chartId) {
        //     const chartContainer = document.getElementById(chartId);
        //     fetch(`/path-to-chart-html?chartId=${chartId}`)
        //         .then(response => response.text())
        //         .then(html => {
        //             chartContainer.innerHTML = html;
        //             // You might need to re-initialize the chart here if necessary
        //         })
        //         .catch(error => console.error('Error refreshing chart:', error));
        // }
    </script>
@endpush
