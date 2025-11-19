<div class="{{ $class ?? '' }} alert align-content-center d-flex justify-content-center">
    <div id="{{ $chartId }}" class="apex-charts" dir="ltr"></div>
</div>

@push('after-scripts')
    @php
        $color = auth()->user()->organization?->primary_color;
    @endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let organizationColor = '{{ $color }}'
        var options = {
          series: @json($series),
          chart: {
          type: 'area',
          ...CHART
        },
        xaxis: {
          type: 'datetime',
          categories: @json($categories),
        },
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
