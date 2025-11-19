<div class="col-xl-6">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h6 class="card-title text-primary mb-0 flex-grow-1">{{trans("translation.total_order")}}</h6>
        </div>
        <div class="card-body">
            @component('components.charts.pie-chart', [
                // 'chartId' => 'order-multi-radial-chart',
                'chartId' => 'order-pie-chart',
                // 'chartId' => 'order-multi-radial-chart',
                'labels' => $order_series->keys()->all(),
                'data' => $order_series->values()->all(),
                'colors' => $chart_colors['order_statuses_color']->toArray(),
                'Label'=> trans("translation.total_order"),
                'totalData'=> ($orders->count()),

            ])
            @endcomponent
        </div>
    </div>
</div>
