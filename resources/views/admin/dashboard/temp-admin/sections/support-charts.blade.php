<div class="col-xl-6">
    <div class="card card-height-100">
        <div class="card-header ">
            <h4 class="card-title mb-0 text-primary">طلبات الإسناد</h4>
        </div>
        <div class="card-body">
            @component('components.charts.stroked-gauge-chart', [
                'chartId' => 'meals-target-chart-1',
                'color' => '',
                'label' => 'إجمالي الإسناد',
                'data' => 10,
            ])
            @endcomponent
        </div>
    </div>
</div>
