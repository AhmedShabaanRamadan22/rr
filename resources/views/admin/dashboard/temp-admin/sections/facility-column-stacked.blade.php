<div class="col-xl-6">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h6 class="card-title text-primary mb-0 flex-grow-1">انواع الطلبات</h6>
        </div>
        <div class="card-body">
            @component('components.charts.column-stacked', [
                'chartId' => 'facility-stacked-chart',
                'series' => $seriesData,
                'categories' => $dateCategories,
                'colors' => null
            ])
            @endcomponent
        </div>
    </div>
</div>
