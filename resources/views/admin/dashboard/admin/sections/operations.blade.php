<div class="col-xl-12">
    <div class="card card-height-100 border-0 overflow-hidden">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">
                {{trans('translation.Statistics Based on Operation Type')}}

                @if($organization->id != 0)
                    <a href="{{route("meals-dashboard.index",[$organization->slug,date('Y-m-d')])}}" class="btn btn-primary float-end" > {{trans('translation.meals-dashboard-details')}} <i class="mdi mdi-open-in-new"></i> </a>
                @else
                    @can('view_all_meals_dashboard_details')
                        <a href="{{route("meals-dashboard.index",['all' ,date('Y-m-d')])}}" class="btn btn-primary float-end" > {{trans('translation.meals-dashboard-details')}} <i class="mdi mdi-open-in-new"></i> </a>
                    @endcan
                @endif
            </h4>

        </div>
        <div class="card-body">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <ul class="nav nav-pills gap-2 flex-grow-1 order-2 order-lg-1" role="tablist">
                    @foreach ($operations_labels = [
                    [
                        'label' => $label = 'orders',
                        'li_id' => $label . '-organization-'.($organization->id??0),
                        'text' => trans('translation.'.$label),
                        'chart_display' => 'chart-row',
                        'charts' => [
                                'pie-chart',
                                'column-stacked-chart',
                            ],
                    ],
                    [
                        'label' => $label = 'meals',
                        'li_id' => $label . '-organization-'.($organization->id??0),
                        'text' => trans('translation.'.$label),
                        'chart_display' => 'chart-row',
                        'charts' => [
                                    'pie-chart',
                                    'column-stacked-chart',
                                ],
                    ],
                    [
                        'label' => $label = 'tickets',
                        'li_id' => $label . '-organization-'.($organization->id??0),
                        'text' => trans('translation.'.$label),
                        'chart_display' => 'chart-row',
                        'charts' => [
                                'pie-chart',
                                'column-stacked-chart',
                                'danger-pie-chart',
                                'danger-column-stacked-chart',
                            ],
                    ],
                    [
                        'label' => $label = 'supports',
                        'li_id' => $label . '-organization-'.($organization->id??0),
                        'text' => trans('translation.'.$label),
                        'chart_display' => 'chart-row',
                        'charts' => [
                                'pie-chart',
                                'column-stacked-chart',
                            ],
                    ],
                    [
                        'label' => $label = 'submitted_forms',
                        'li_id' => $label . '-organization-'.($organization->id??0),
                        'text' => trans('translation.'.$label),
                        'chart_display' => 'chart-tabs-row',
                        'charts' => [
                                // 'pie-chart',
                                'column-stacked-chart',
                            ],
                    ],
                    ] as $operations_label )
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $loop->first ? 'show active' : '' }}" data-bs-toggle="tab" href="#{{$operations_label['li_id']}}" role="tab" aria-selected="true">
                            {{$operations_label['text']}}
                        </a>
                    </li>

                    @endforeach
                </ul>
            </div>
            <div class="tab-content-container">

                @foreach ( $operations_labels as $operations_label )
                <div class="tab-content">
                    <div class="tab-pane {{ $loop->first ? 'show active' : '' }}" id="{{$operations_label['li_id']}}" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                @include('admin.dashboard.admin.components.' . $operations_label['chart_display'])

                            </div>
                            <div class="row">
                                @include('admin.dashboard.admin.sections.' . $operations_label['label'] . '.table')
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
