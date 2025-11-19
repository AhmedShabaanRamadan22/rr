<div class="col-xl-6">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h6 class="card-title text-primary mb-0 flex-grow-1">{{trans("translation.total_" . $operations_label['label'] . " by danger by day")}}</h6>
        </div>
        <div class="card-body">
            @component('components.charts.column-stacked', [
                'chartId' =>  $operations_label['label'] . 'DangerChartDateOrg' . ($organization->id??0),
                'categories' => $dateCategories,
                'colors' => $chart_colors['danger_colors']->toArray(),
            ])
            @endcomponent
        </div>
    </div>
</div>
