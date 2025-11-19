<div class="col-xl-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">عدد الطلبات حسب كل يوم</h4>
        </div><!-- end card header -->
        <div class="card-body">
            @component('components.charts.bar-chart', [
                'chartId' => 'orders-chart',
                // 'class' => 'col-6',
                'label' => 'orders',
                'data' => $orders_bar_chart_data,
                'categories' => $dateCategories,
                'color' => ['#CAB272'],
                'horizontal' => true,
            ])
            @endcomponent
        </div><!-- end card-body -->
    </div><!-- end card -->
</div>
