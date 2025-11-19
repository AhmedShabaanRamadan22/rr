<div class="col-xl-6">
    <div class="card">
        <div class="card-header">
            <h6 class="card-title text-primary mb-0 flex-grow-1">{{trans("translation.total_" . $operations_label['label'])}}</h6>
            <!-- <h4 class="card-title mb-0 text-primary">عدد {{trans('translation.' . $operations_label['label'])}} حسب الأيام</h4> -->
        </div>
        <div class="card-body">
            @component('components.charts.pie-chart', [
                'chartId' => $operations_label['label'] . 'ChartOrg' . ($organization->id??0),
                // 'label' => 'أهلاً بك، نحنُ نستطيع' . $operations_label['label'],
                
                'colors' => $chart_colors['ticket_statuses_color']->toArray(),
                'Label'=> trans("translation.total_" . $operations_label['label']),
                // 'horizontal' => true,
            ])
            @endcomponent
        </div>
    </div>
</div>