<div class="card card-body p-4 overflow-scroll">

    {{--      Stages header      --}}
    @if(count($stages) > 0)
        <div class="d-flex gap-2 align-items-center fs-xs fw-bold flex-wrap">
            <div class="fw-bold">{{trans('translation.color-keys-meal-stages')}}</div>
            <div class="rounded-pill border border-2 lh-lg px-2">{{trans('translation.meal-not-started')}}</div>
            <div class="rounded-pill border border-2 border-danger bg-danger-subtle lh-lg px-2">{{trans('translation.stage-late')}}</div>
            <div class="rounded-pill border border-2 border-info bg-info-subtle lh-lg px-2">{{trans('translation.stage-in-progress')}}</div>
            <div class="rounded-pill border border-2 border-success bg-success-subtle lh-lg px-2">{{trans('translation.meal-finished')}}</div>
        </div>
    <div class="d-flex align-items-stretch justify-content-between fw-medium py-4 meal-steps w-100">
        <div></div>

        {{--      Sectors pills      --}}
        @foreach ($stages[0]['stages']->resolve() as $stage)
            <div id="stage_{{ $stage['organization_stage_id'] }}_{{ $stage['period_id'] }}"
                 data-organization-stage="{{$stage['organization_stage_id']}}"
                 class="d-flex flex-column gap-3 text-center step-status not-started stages rounded-4 pb-3" style="flex: 1 1 0%; min-width: 140px">
                <div class="step-container step-line d-flex justify-content-center w-100 mt-5">
                    <div data-stage-id="{{ $stage['organization_stage_id'] }}"
                         class="circle rounded-circle border border-3 d-flex align-items-center justify-content-center"
                         style="width: 58px; height: 58px; z-index:3; font-size: 1.25rem">
                        {{isset($meals[$stage['organization_stage_id']]) ? count($meals[$stage['organization_stage_id']]) : 0}}
                    </div>
                </div>
                <div class="step fs-5 px-2" style="height: 32px">{{ $stage['name'] }}</div>
                <div class="flex-grow-1 sectors-container d-flex align-items-center flex-column w-100 gap-2">
                    @if(isset($meals[$stage['organization_stage_id']]))
                        @foreach($meals[$stage['organization_stage_id']] as $meal)
                            <div id="meal-stage-{{$meal['id']}}"
                                 @can('view_all_meals_dashboard_details')
                                 data-bs-toggle="modal" data-bs-target="#sector_info_modal"
                                 data-order-sector-id="{{ $meal['order_sector_id'] }}"
                                 @endcan
                                 data-id="{{ $meal['id'] }}"
                                 class="mt-2 mx-2 border border-3 bg-white shadow-sm pt-2 pb-1 sector-stage rounded-pill px-2 {{ $meal['current_status'] }} {{$meal['is_in_last_stage'] ? 'last' : ''}}"
                                 style="cursor: pointer;">
                                <div class="text-break text-wrap w-100"
                                     data-bs-toggle="tooltip"
                                     data-bs-html="true"
                                     title='<div class="text-start"><i class="mdi mdi-account-group"></i> <strong>المراقبين</strong>: {{ $meal['monitors'] }} <br/><i class="bi bi-people-fill"></i> <strong>المشرف</strong>: {{ $meal['supervisor'] }} <br/><i class="bi bi-person-fill"></i> <strong>القائد التشغيلي</strong>: {{ $meal['boss'] }} </div>'>
                                    <strong>{!! $meal['sector_label'] . ' - ' . $meal['sector_flag'] !!}</strong>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @else
        <div class="text-center">
            <div class="fw-bold text-secondary py-1" style="color: #9C9C9C!important;">{{ trans('translation.no-data') }}
            </div>
        </div>
    @endif
</div>
