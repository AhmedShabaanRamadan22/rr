@extends('layouts.master')
@section('title')
    @lang('meals')
@endsection

@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <style>

        {{--    Stages progress bar styling    --}}
        .step-container {
            position: relative;
            flex: 0 0 auto;
        }
        .step-container:after {
            content: "";
            position: absolute;
            top: 50%;
            right: calc(-50% + 14px);
            width: 90%;
            height: 4px;
        }
        .circle {
            pointer-events: none;
        }
        .meal-steps>div:nth-child(2) .step-container:after {
            display: none;
        }
        .meal-steps > .stages:nth-child(even){
            background-color: #fafafa;
        }

        {{--    dark theme styling    --}}
        [data-bs-theme=dark]{
            #organization-title{
                color: #2c3639!important;
            }
            .not-started .time{
                color: var(--tb-body-color)!important;
            }
            .not-started .step-container:after{
                background-color: var(--tb-body-color)!important;
            }
            .meal-steps > .stages:nth-child(even),
            .not-started .circle{
                background-color: #3b4245;
            }
        }

        {{--    stages statuses styling    --}}
        .done {
            .mdi-check {
                color: var(--tb-white);
            }

            @can('view_all_meals_dashboard')
                .circle {
                    pointer-events: all !important;
                    cursor: pointer;
                }
            @endcan

            &.on-time {
                .circle {
                    background-color: var(--tb-success);
                    border-color: var(--tb-success) !important;
                }

                .step-container:after {
                    background-color: var(--tb-success);
                }

                .time {
                    color: var(--tb-success);
                }
            }

            &.late {
                .circle {
                    background-color: var(--tb-danger);
                    border-color: var(--tb-danger) !important;
                }

                .step-container:after {
                    background-color: var(--tb-danger);
                }

                .time {
                    color: var(--tb-danger);
                }
            }

            &.last.sector-stage{
                border-color: var(--tb-success)!important;
                background-color: var(--tb-success-bg-subtle)!important;
            }
        }

        .in-progress {
            &.on-time {
                .step-container:after {
                    background-color: var(--tb-info);
                }

                .circle {
                    background-color: var(--tb-white);
                    border-color: var(--tb-info) !important;
                }

                .time {
                    color: var(--tb-info);
                }

                .mdi-check {
                    color: var(--tb-info);
                }
                &.sector-stage{
                    border-color: var(--tb-info)!important;
                    background-color: var(--tb-info-bg-subtle)!important;
                }
            }

            &.late {
                .circle {
                    background-color: var(--tb-white);
                    border-color: var(--tb-danger) !important;
                }

                .step-container:after {
                    background-color: var(--tb-danger);
                }

                .time {
                    color: var(--tb-danger);
                }

                .mdi-check {
                    color: var(--tb-danger);
                }
                &.sector-stage{
                    border-color: var(--tb-danger)!important;
                    background-color: var(--tb-danger-bg-subtle)!important;
                }
            }
        }

        .not-started {
            .time {
                color: var(--tb-secondary-bg-subtle);
            }

            .circle {
                background-color: var(--tb-white);
            }

            .step-container:after {
                background-color: var(--tb-secondary-bg-subtle);
            }

            .mdi-check {
                display: none;
            }
        }

        {{--    header styling    --}}
        #organization-title{
            color: {{$organization->primary_color}}!important;
        }
        .header-bar {
            background: linear-gradient(88.87deg, #80A496 0.3%, #FFFFFF 135.66%);
            border-bottom: 1px solid #959595;
            height: 120px;
            border-radius: 10px 10px 0px 0px;

        }
        .show-on-print {
            display: none;
        }
        .nav-pills .nav-link {
            color: #b6afaf;
            /* اللون الأساسي */
        }
        .nav-pills .nav-link {
            background-color: transparent !important;
            border-radius: 0 !important;
            border-bottom: 3px solid transparent;
            color: #9C9C9C;
            /* اللون العادي */
            font-weight: 500;
        }
        .nav-pills .nav-link.active {
            border-bottom: 3px solid #05AABB !important;
            color: #05AABB !important;
        }
        .nav-pills .nav-link i {
            transition: color 0.3s;
        }
        .nav-pills .nav-link.active i {
            color: #05AABB !important;
        }

        {{--    stage answers styling    --}}
        .meal_answers {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.6s ease, opacity 0.4s ease;
            pointer-events: none;
            padding: 0;
        }
        .meal_answers.show {
            max-height: 320px;
            opacity: 1;
            overflow-y: auto;
            pointer-events: auto;
            padding: 10px 15px;
            border-radius: 10px;
        }
        .sector-stage{
            animation: fadein 0.8s ease;
        }
        /* Spinner داخل الكارد */
        .meal_answers .spinner-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
            animation: fadein 0.3s ease;
        }
        @keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
        /* Scrollbar شكله أنيق */
        .meal_answers::-webkit-scrollbar {
            width: 6px;
        }
        .meal_answers::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        .meal_answers::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        {{--    new tickets and supports alert     --}}
        .notification-dot {
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            animation: pulseDot 1s infinite ease-in-out;
            opacity: 0;
        }
        @keyframes pulseDot {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @media print {
            @page {
                size: landscape;
            }

            .hide-on-print {
                display: none !important;
            }

            .show-on-print {
                display: block;
            }

            .no-page-break {
                break-inside: avoid;
                page-break-inside: avoid;
                /* For compatibility with older browsers */
            }
        }
    </style>
@endpush
@section('content')

    {{--  breadcrumb  --}}
    @component('components.breadcrumb', ['pageTitle' => trans('meals')])@endcomponent

    <div class="card-body p-0">
        <div class="card border-0 shadow-none mb-0">
            <!-- Gradient Header -->
            <div class="card-body p-0"
                style="
                background: linear-gradient(88.87deg, {{ $organization->primary_color }} 0.3%, #FFFFFF 135.66%);
                border-bottom: 1px solid #9c9c9c;
                height: 100px;
                border-radius: 10px 10px 0 0;">
            </div>

            <!-- Logo + Title -->
            <div class="card-body pt-0">
                <div class="row mt-n5">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="">
                            <img src="{{ $organization->logo ?? URL::asset('build/images/users/32/logo.png') }}"
                                alt="Logo" class="avatar-md rounded-circle"
                                style="border: 3px solid {{ $organization->primary_color }};">
                        </div>
                        <div class="flex-grow-1">
                            <h6 id="organization-title" class="fs-lg px-2 mb-3">{{ $organization->name }}</h6>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-md-6 gap-3">
                        <!-- Date Input -->
                        @if(!$isToday)
                            <div id="day-alert" class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                <i class="ri-alert-line label-icon"></i>{{trans('translation.day-alert')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row align-items-center">
                            <div class="col-12 col-md-8">
                                @component('components.inputs.date-input', [
                                    'columnName' => 'date',
                                    'col' => '12',
                                    'margin' => 'mb-0',
                                    'modelItem' => ['date' => $date],
                                ])
                                @endcomponent
                            </div>
                            @can('view_all_meals_dashboard_details')
                                <!-- Print Button -->
                                <div class="col-12 col-md-4 text-md-end">
                                    <button class="btn btn-primary btn-label hide-on-print w-100 w-md-auto"
                                        onclick="window.print();">
                                        <i class="ri-printer-fill label-icon align-middle fs-lg me-2"></i> طباعة
                                    </button>
                                </div>
                            @endcan
                        </div>
                        <!-- Nav Search, search and cancel btn -->
                        <div class="row align-items-center">
                            <div class="col-12 col-md-8 pt-4">
                                <label for="sector_label" class="pb-2">{{ trans('translation.sector_id') }}</label>
                                <select class="form-control selectpicker" name="sector_label" id="sector_label"
                                    data-live-search="true" multiple data-actions-box="true"
                                    placeholder={{ trans('translation.choose-sector_id') }}>
                                    @foreach ($sectors['sectors'] as $label => $name)
                                        <option value="{{ $label }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 text-md-end pt-4">
                                <div class="d-flex gap-2 justify-content-end pt-4">
                                    <button class="btn btn-primary w-50" id="sector-filter-btn">
                                        {{ trans('translation.filter') }}
                                    </button>
                                    <button class="btn btn-secondary w-50" id="sector-select-reset-btn">
                                        {{ trans('translation.reset') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Tabs -->
                        <div class="pt-4">
                            <ul class="nav nav-pills d-flex flex-wrap px-2 align-items-center" role="tablist">
                                @foreach ($periods as $period)
                                    @php
                                        $iconView = view()->exists('admin.dashboard-meal.icons.' . $period->name)
                                            ? 'admin.dashboard-meal.icons.' . $period->name
                                            : 'admin.dashboard-meal.icons.default';
                                    @endphp
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link period-tabs d-flex align-items-center"
                                            data-period="{{ $period->id }}" id="meals_period_{{ $period->id }}_tab"
                                            data-bs-toggle="tab" data-bs-target="#meals_period_{{ $period->id }}"
                                            role="tab" aria-selected="false"
                                            onclick="setActiveTab('meals_period_{{ $period->id }}_tab');">
                                            <span style="width: 18px; height: 18px;">
                                                @include($iconView)
                                            </span>
                                            <label class="px-2  active" style="font-size:0.95rem">
                                                {{ $period->name }}
                                            </label>
                                        </a>
                                    </li>
                                @endforeach
                                <div class="ps-3 text-info fs-2xs">{{trans("translation.coresponding-hijri") . ': ' . $date}}</div>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @component('components.charts.pie-chart', [
                            'chartId' => 'meal_pie',
                            'colors' => [$organization->primary_color, '#D3D3D3'],
                            'Label' => trans('translation.meals-count'),
                            'totalData' => 22,
                            'size' => 190,
                            'fontSize' => '12px',
                        ])
                        @endcomponent
                    </div>


                </div>
            </div>
        </div>

        {{--    View selection is only shown when meals are for a specific organization    --}}
        @if(!$showAllMeals)
            <!-- Choose view -->
            <div class="d-flex w-100 justify-content-between mt-3">
                @if(auth()->user()->can('view_all_meals_dashboard_details'))
                    <a href="{{route('meals-dashboard.daily-report', ['organization_slug' => $organization->slug, 'date' => $date ])}}" target="_blank" class="btn btn-outline-primary">{{trans('translation.daily-operation-report')}}</a>
                @else
                    <div></div>
                @endif
                <div class="btn-group align-self-end" role="group" aria-label="View toggle">
                    <input type="radio" class="btn-check" name="layout" id="listView" autocomplete="off" checked>
                    <label class="btn btn-outline-primary fs-5 px-3 py-1" for="listView" style="border-top-right-radius: 1rem;border-bottom-right-radius: 1rem;"><span class="mdi mdi-view-headline"></span></label>

                    <input type="radio" class="btn-check" name="layout" id="boardView" autocomplete="off">
                    <label class="btn btn-outline-primary fs-5 px-3 py-1" for="boardView" style="border-top-left-radius: 1rem;border-bottom-left-radius: 1rem;"><span class="mdi mdi-reorder-vertical"></span></label>
                </div>
            </div>
        @endif

        <!-- Tab Content -->
        <div class="row mt-3">
            <div class="col">
                <div class="tab-content">
                    @foreach ($periods as $period)
                        <div class="tab-pane fade" id="meals_period_{{ $period->id }}" role="tabpanel">
                            <div class="card card-body text-center loader" id="meal_period_container_{{ $period->id }}">
                                <div>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="list-view"></div>
                            <div class="board-view" style="display: none"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{--  Include new tickets and supports sounds only when the user has permission  --}}
    @can('view_all_meals_dashboard_details')
        <audio id="new-ticket-alert" class="d-none" src="{{ asset('build/audios/tickets/new-tickets-' . $organization->slug . '.mp3')}}"></audio>
        <audio id="new-support-alert" class="d-none" src="{{ asset('build/audios/supports/new-supports.mp3')}}"></audio>
    @endcan


    @include('admin.dashboard-meal.modals.sector-info-modal')
    @include('admin.dashboard-meal.modals.new-tickets-modal')
    @include('admin.dashboard-meal.modals.new-supports-modal')
@endsection


@push('after-scripts')
    @vite(['resources/js/bootstrap.js'])
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/apexcharts-pie.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/chartSettings.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

    <script>
        const initPeriodsIds = @json($periods->pluck('id'));
        let activePeriodTabId;
        let mealsCount = 0;
        let deliveredMealsCount = 0;
        let remainingMealsCount = 0;
        let canViewAllDetails = '{{$canViewAllDetails}}' == 1;

        // =====================================
        // ============ Handle Tabs ============
        const parent = 'meals-periods';
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = JSON.parse(localStorage.getItem(parent))?.tab;
            checkNullTap(activeTab)
        });

        const checkNullTap = (sessionTap) => {

            let clickTap = sessionTap;
            if (sessionTap == null) {
                clickTap = document.getElementsByClassName('period-tabs')[0].id;
            }
            openTab(document.getElementById(clickTap))
            activePeriodTabId = clickTap.split("_").at(-2)

        }
        const openTab = (elem) => {
            elem?.click();
        }
        const setActiveTab = (tab) => {
            let state = JSON.parse(localStorage.getItem(parent))
            state = {
                ...state,
                tab: tab
            };
            localStorage.setItem(parent, JSON.stringify(state));
            mealChartCount($(`#${tab}`).data('period'))
        }
        // ============ End Handle Tabs ============
        // =========================================



        // ================================================
        // ============ Fetch meals into cards ============
        const fetchPeriodMeals = (periodId) => {

            var date = '{{ $date }}';
            var timestamp = Date.parse(date);

            if (isNaN(timestamp) === true) {
                Toast.fire({
                    icon: "error",
                    title: "{{ trans('translation.Invalid date') }}"
                });
                $('#meals_period_' + periodId).html("<p>{{ trans('translation.no-data') }}</p>");
                return;
            }

            setHijriDate($('#input_date'));

            return $.ajax({
                type: "GET",
                url: "{{ route('meals-dashboard.meals') }}",
                data: {
                    organization_id: "{{ $showAllMeals ? 0 : $organization->id }}",
                    date: date,
                    period_id: periodId
                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    let container = $('#meals_period_' + periodId);

                    container.find('.loader').hide();
                    container.find('.list-view').html(response.listView);
                    container.find('.board-view').html(response.boardView);

                    setTimeout(() => {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                            '[data-bs-toggle="tooltip"]'));

                        tooltipTriggerList.forEach(function(el) {
                            try {
                                const existingTooltip = bootstrap.Tooltip.getInstance(el);
                                if (existingTooltip) {
                                    existingTooltip.dispose();
                                }

                                new bootstrap.Tooltip(el, {
                                    trigger: 'hover',
                                    delay: {
                                        show: 100,
                                        hide: 100
                                    }
                                });

                            } catch (e) {
                                console.warn('Tooltip error on element:', el, e);
                            }
                        });
                    }, 100);
                },
                error: function(response, jqXHR, xhr) {
                    console.log(response);
                    Toast.fire({
                        icon: "error",
                        title: "{{ trans('translation.something went wrong') }}"
                    });
                },
            });
        }

        // ============ End Fetch meals into cards ============
        // ====================================================



        // ================================================
        // ============ Check late stages real-time ============

        const checkLateStages = () => {
            let inProgressStages = $('.in-progress.on-time');

            if (inProgressStages.length > 0) {
                const now = new Date();
                const day_date = '{{$date}}';
                inProgressStages.each(function() {
                    const stage = $(this);
                    const expectedEndTime = $(this).data('expected-end');
                    const backendDate = new Date(`${day_date} ${expectedEndTime}`);
                    if (now > backendDate)
                        stage.removeClass('on-time').addClass('late');
                });
            }
        }

        // ============ End Check late stages real-time ============
        // ====================================================



        // ================================================
        // ============ Pusher fetch real-time ============

        document.addEventListener('DOMContentLoaded', function() {

            const periodIds = [...new Set([+activePeriodTabId, ...
                initPeriodsIds
            ])]; // to sort ids and make active tab period id in first

            const ajaxCalls = periodIds.map((periodId) => fetchPeriodMeals(periodId));

            // update pie chart when ajax calls are done
            Promise.all(ajaxCalls).then(() => {
                mealChartCount($('.period-tabs.active').data('period'));
            });

            // check stage statuses each minute
            setInterval(checkLateStages, 60 * 1000);

            // listener for meal stages updates
            window.Echo.channel('MealStage-changes-channal').listen('.MealStage-changes', function(meal) {
                // update progress bar in list view
                let all_stages_container = $("#meal_container_" + meal.id);
                meal.stages.forEach((stage) => {
                    all_stages_container.find(`#stage_${stage.id} .time`).html(stage.time + stage
                        .day);
                    all_stages_container.find(`#stage_${stage.id}`)
                        .removeClass(['done', 'in-progress', 'not-started', 'late', 'on-time'])
                        .addClass(stage.time_status)
                        .attr('data-expected-end', stage.expected_end_time);
                });

                // move sector to new stage in board view
                const tempMeal = $(`#meal-stage-${meal.id}`),
                    tempMealHtml = tempMeal.prop('outerHTML'),
                    previousCircle = tempMeal.closest(".stages").find(".circle"),
                    currentStage = $(`#stage_${meal.current_organization_stage_id}_${meal.period_id}`),
                    currentCircle = currentStage.find('.circle');

                previousCircle.text((parseInt(previousCircle.text(), 10) - 1))
                tempMeal.remove();

                currentStage.find('.sectors-container').append(tempMealHtml);
                currentCircle.text((parseInt(currentCircle.text(), 10) + 1));

                mealChartCount($('.period-tabs.active').data('period'));
            });

            // listen to new tickets and supports events when the user has permission
            if(canViewAllDetails)
            {
                window.Echo.channel('ModelCRUD-changes')
                    .listen('.Ticket-changes', function(ticket) {
                        if (ticket.change_type == 'created') {
                            $(`.ticket-dot-${ticket.extra_data['order_sector_id']}`).show();
                            $('#new-ticket-alert')[0].play();
                        }
                    })
                    .listen('.Support-changes', function(support) {
                        // listen to updated because support is created first then updated to be attached to a meal
                        if (support.change_type == 'updated' && support.extra_data['status'] == '{{ $newSupportStatusId }}')
                        {
                            if(support.extra_data['type'] == '{{$foodSupportType}}')
                            {
                                $(`.support-dot-${support.extra_data['meal_id']}`).show();
                                $('#new-support-alert')[0].play()
                            }
                            else if(support.extra_data['type'] == '{{$waterSupportType}}') // water support
                            {
                                $(`.support-dot-${support.extra_data['order_sector_id']}`).show();
                                $('#new-support-alert')[0].play()
                            }
                        }
                    });
            }

            $('input[name="layout"]').on('change', function () {
                $('.list-view').toggle($('#listView').is(':checked'));
                $('.board-view').toggle($('#boardView').is(':checked'));
            });
        });

        // ============ End Pusher fetch real-time ============
        // ====================================================




        // ====================================================
        // ============ Shown sector info modal  ============

        const dataRow = (sector) => {
            return `
                <div class=" d-flex py-3 border-bottom border-light justify-content-between">
                    <label for="${ sector.label }"
                        class="fw-semibold col-lg-4 col-5 ">${ sector.label }</label>
                    <div id="${ sector.label }" class="col-lg-8 col-7 text-end text-lg-start">
                        ${ sector.content == '' ? '{{trans('translation.no-data')}}' : sector.content }
                    </div>
                </div>
            `;
        }

        $('#sector_info_modal').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            const orderSectorId = button.attr('data-order-sector-id');
            const mealId = button.attr('data-id');
            const sector_info_body = $('#sector_info_body');

            $.ajax({
                type: "GET",
                url: "{{ route('api.sector_info') }}",
                data: {
                    order_sector_id: orderSectorId,
                    meal_id: mealId,
                },
                dataType: "json",
                headers: {
                    'Accept-Language': 'ar',
                },
                success: function(response, jqXHR, xhr) {
                    console.log(response);
                    sector_info_body.empty();
                    if (response.data.length > 0) {
                        response.data.forEach((sectorData) => {
                            sector_info_body.append(dataRow(sectorData));
                        })
                    } else {
                        sector_info_body.append("<p>{{ trans('translation.no-data') }}</p>");
                    }

                },
                error: function(response, jqXHR, xhr) {
                    console.log(response);
                    Toast.fire({
                        icon: "error",
                        title: "{{ trans('translation.something went wrong') }}"
                    });
                },
            });
            // console.log(orderSectorId);
        })

        // ============ End Shown sector info modal  ============
        // ====================================================



        // ====================================================
        // ============ Change Date Handler  ==================

        $('#input_date').change(function() {
            let dateSelected = $(this).val();
            setLoading(true);
            let redirectTo =
                '{{ url('/') }}/meals-dashboard/{{ $showAllMeals ? 'all' : $organization->slug }}/' +
                dateSelected;
            window.location.href = redirectTo;
        });

        // ============ End Change Date Handler  ==============
        // ====================================================



        // ====================================================
        // ============ Stage answers collapse  ==================

        const MAX_CACHE_SIZE = 30;
        let currentStageId = 0;
        let currentMealId = 0;
        let answerCache = {};

        // cache recently opened answers
        const setCache = (stageId, html) => {
            if (Object.keys(answerCache).length >= MAX_CACHE_SIZE) {
                // Remove the oldest cached entry
                delete answerCache[Object.keys(answerCache)[0]];
            }
            answerCache[stageId] = html;
        }

        $(document)
            .on('click', '.circle', function(e) {
                e.stopPropagation();

                const stageId = $(this).data('stage-id');
                const mealId = $(this).data('meal-id');
                const $target = $(`#answers_stage_${mealId}`);

                // إذا ضغط على نفس المرحلة المفتوحة، أغلقها
                if (currentStageId === stageId && currentMealId === mealId) {
                    $target.removeClass('show');
                    currentStageId = 0;
                    currentMealId = 0;
                    return;
                }

                // أغلق كل العناصر المفتوحة
                $('.meal_answers').removeClass('show');

                // إذا فيه كاش، استخدمه مباشرة
                if (answerCache[stageId]) {
                    $target.html(answerCache[stageId]).removeClass('show');
                    requestAnimationFrame(() => {
                        $target.addClass('show');
                    });
                    currentStageId = stageId;
                    currentMealId = mealId;
                    return;
                }

                // Spinner داخل الكارد
                $target.html(`
            <div class="spinner-wrapper">
                <div class="spinner-border text-primary avatar-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `).removeClass('show');

                requestAnimationFrame(() => {
                    $target.addClass('show');
                });

                // جلب البيانات
                $.ajax({
                    type: "GET",
                    url: `/admin/${stageId}/answers`,
                    dataType: "json",
                    success: function(response) {
                        setCache(stageId, response.html);
                        $target.html(response.html);
                    },
                    error: function(response) {
                        console.log(response);
                        Toast.fire({
                            icon: "error",
                            title: "{{ trans('translation.something went wrong') }}"
                        });
                    },
                });

                currentStageId = stageId;
                currentMealId = mealId;
            })

            .on('click', function(e) {
                if (!$(e.target).closest('.meal-containers').length) {
                    $('.meal_answers').removeClass('show');
                    currentStageId = 0;
                    currentMealId = 0;
                }
            });

        // ============ filter sectors  ==============
        // ====================================================
        $(function() {
            // عند الضغط على زر الفلترة
            $('#sector-filter-btn').click(function() {
                setLoading(true);
                $('.meal-containers').hide();

                let selectedLabels = $('#sector_label').val(); // مصفوفة من القيم المختارة

                if (!selectedLabels || selectedLabels.length === 0) {
                    $('.meal-containers').show();
                    return setLoading(false);
                }

                // نظهر كل العناصر اللي تتطابق مع أي قيمة
                selectedLabels.forEach(function(label) {
                    const safe = CSS.escape(label);
                    $(`.meal-containers[data-label*="${safe}"]`).show();
                });

                setLoading(false);
            });

            // عند الضغط على زر إعادة التعيين
            $('#sector-select-reset-btn').click(function() {
                setLoading(true);
                $('#sector_label').val([]).selectpicker('deselectAll');
                $('.meal-containers').show();
                setLoading(false);
            });
        });



        // ====================================================
        // ============ Pie Chart Settings  ==============
        const mealChartCount = (period) => {
            mealsCount = $(`.meal-containers[data-period="${period}"]`)
                .toArray()
                .reduce((sum, elem) => sum + $(elem).data('quantity'), 0);

            deliveredMealsCount = $(`.meal-containers[data-period="${period}"] .last.done`)
                .toArray()
                .reduce((sum, elem) => sum + $(elem).closest('.meal-containers').data('quantity'), 0);

            remainingMealsCount = mealsCount - deliveredMealsCount;

            let newOptions = {
                series: [deliveredMealsCount, remainingMealsCount],
                labels: ["{{ trans('translation.delivered-meal-count') }}",
                    "{{ trans('translation.remaining-meal-count') }}"
                ],
            };

            window['meal_pie'].updateOptions(newOptions, true);
        }

        // ============ End Pie Chart Settings  ==============
        // ====================================================
    </script>
@endpush
