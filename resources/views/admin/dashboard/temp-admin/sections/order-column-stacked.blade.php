<div class="col-xl-6">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h6 class="card-title text-primary mb-0 flex-grow-1">{{trans("translation.Orders Types")}}</h6>
        </div>
        <div class="card-body">
            @component('components.charts.column-stacked', [
                'chartId' => 'order-stacked-chart',
                'series' => $order_statuses_bar_chart_data,
                'categories' => $dateCategories,
                'colors' => $chart_colors['order_statuses_color']->toArray(),
            ])
            @endcomponent
        </div>
    </div>
</div>
