@extends('layouts.master')
@section('title')
    @lang('meals')
@endsection
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .header-bar {
            background: linear-gradient(88.87deg, #80A496 0.3%, #FFFFFF 135.66%);
            border-bottom: 1px solid #d9d9d9;
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
            color: #05AABB !important;
        }

        .nav-pills .nav-link i {
            transition: color 0.3s;
        }

        .nav-pills .nav-link.active i {
            color: #05AABB !important;
        }

        @media print {
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
    @component('components.breadcrumb', ['pageTitle' => trans('meals')])
    @endcomponent

    <div class="row row-logo show-on-print  text-center ">
        <div class="col col-logo">
            <img class="img-logo" src="{{ $organization->logo }}" alt="">
        </div>
    </div>

    <div class="row ">
        <div class="col">
            <div class="">
                <div class="header-bar">
                    <div class="">
                        <div class="d-flex align-items-center gap-2 pb-2">
                            <div class="avatar-sm flex-shrink-0">
                                <img src="{{ URL::asset('build/images/users/avatar-3.jpg') }}" alt=""
                                    class="img-fluid img-thumbnail">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fs-md">المنظمة: {{ $organization->name }}</h6>
                                <h6 class="fs-md">المنظمة: </h6>
                            </div>
                            <div class="flex-grow-1">
                                @component('components.inputs.date-input', [
                                    'columnName' => 'date',
                                    'col' => '4',
                                    'margin' => 'mb-1',
                                    'modelItem' => ['date' => $date],
                                ])
                                @endcomponent
                            </div>
                            <div class="flex-shrink-0">
                                <button class="btn btn-primary  btn-label" onclick="window.print();"><i
                                        class="ri-printer-fill label-icon align-middle fs-lg me-2"></i> طباعة</button>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="row card card-body">
                    <div class="col">
                        <div class="card-body p-0">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills d-flex flex-row flex-wrap border-top px-3" role="tablist">
                                @foreach ($periods as $period)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link period-tabs" id="meals_period_{{ $period->id }}_tab"
                                            data-bs-toggle="tab" data-bs-target="#meals_period_{{ $period->id }}"
                                            role="tab" aria-selected="false"
                                            onclick="setActiveTab('meals_period_{{ $period->id }}_tab');">
                                            <span style="width: 18px; height: 10px;">
                                                @includeIf('admin.dashboard-meal.icons.' . $period->name)
                                            </span>
                                            <label class="px-2  active" style="font-size:0.95rem">
                                                {{ $period->name }}
                                            </label>
                                        </a>
                                    </li>
                                @endforeach


                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- <div class="row mb-0 ">
                                                            <div class="col">
                                                                <div class="row mb-0">
                                                                    <div class="col">
                                                                        <div class="card card-body mb-0 py-0">
                                                                            <div class="row text-center">
                                                                                <div class="col">
                                                                                    <div class="card card-body mb-0">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-2 ">
                                                                                                {{ trans('translation.sector') }}
                                                                                            </div>
                                                                                            <div class="col-lg-10 px-3">
                                                                                                {{ trans('translation.stages') }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
    <div class="row">
        <div class="col">
            <div class="tab-content">
                @foreach ($periods as $period)
                    <div class="tab-pane fade " id="meals_period_{{ $period->id }}" role="tabpanel" tabindex="0">
                        <div class="row">
                            <div class="col ">
                                <div id="meal_period_container_{{ $period->id }}" class="card card-body text-center">
                                    <div>
                                        <span class="spinner-border spinner-border-sm " role="status"
                                            aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>


    @include('admin.dashboard-meal.modals.sector-info-modal')
@endsection


@push('after-scripts')
    @vite(['resources/js/bootstrap.js'])
    <script>
        const initPeriodsIds = @json($periods->pluck('id'));
        let activePeriodTabId;

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
        }
        // ============ End Handle Tabs ============
        // =========================================


        // =======================================================
        // ============ Meal Card Elements Components ============
        const arrowIconHtml = () => {
            return `
            <div class="col px-0">
                <i class="mdi mdi-arrow-left-bold-outline " class="big-arrow"></i>
            </div>
            `;
        }

        const mealButtonSectorHtml = (icon, meal, bgColor, classList = '') => {
            return `
            <div class="col-6 ">
                <a href="${meal.meal_route}" target="_blank" data-order-sector-id="${meal.order_sector_id}" class="btn btn-${bgColor}  d-block">
                    <i class="${icon} text-light big-icon "></i>
                </a>
            </div>
            `;
        }

        const infoButtonSectorHtml = (icon, meal, bgColor, classList = '') => {
            return `
            <div class="col-6 ">
                <a data-bs-toggle="modal" data-bs-target="#sector_info_modal" data-order-sector-id="${meal.order_sector_id}" class="btn btn-${bgColor}  d-block">
                    <i class="${icon} text-light big-icon"></i>
                </a>
            </div>
            `;
        }

        const stageCardHtml = (stage) => {
            return `
            <div class="col px-0">
                <a href="${stage.answers_route}" target="_blank">
                    <div class="card card-body border border-dark ${stage.stage_bg_class} text-light mb-0 p-2" >
                        ${stage.name}
                    </div>                                
                </a>
            </div>
            `;
        }

        const allStagesContainerHtml = (meal) => {
            let stages = meal.stages;
            let html = `
            <div class="col-lg-10 align-self-center"  id="all_stages_container_col_${meal.id}">
                <div class="container">
                    <div class="row align-items-center text-center">`;

            const lastStage = stages.at(-1);
            stages.forEach((stage) => {
                html += stageCardHtml(stage);
                if (stage !== lastStage) {
                    html += arrowIconHtml();
                }
            });

            html += `
                    </div>
                </div>
            </div>
            `;

            return html;
        }

        const SectorCardHtml = (meal) => {
            return `
            <div class="col-lg-2 align-self-center">
                <div class="card mb-0">
                    <div class="card-body p-2 pb-2">
                        <p>${meal.sector_name}</p>
                        <div class="row hide-on-print">` +
                mealButtonSectorHtml('mdi mdi-food', meal, 'primary') +
                infoButtonSectorHtml('bi bi-info-circle', meal, 'secondary', 'sector-info-btn') + `
                        </div>
                    </div>
                </div>
            </div>
            `;

        }

        const mealContainerHtml = (meal) => {
            return `
            <div class="row no-page-break">
                <div class="col">
                    <div class="card">
                        <div class="card-body" style="background:#dddddd">
                            <div class="row">` +
                SectorCardHtml(meal) +
                allStagesContainerHtml(meal) + `
                            </div>
                        </div>
                    </div>
                </div> 
            </div>    
            `;

        }
        // ============ End Meal Card Elements Components ============
        // ===========================================================


        // ================================================
        // ============ Fetch meals into cards ============
        const fetchPeriodMeals = (periodId) => {

            var date = '{{ $date }}';
            var timestamp = Date.parse(date);
            console.log(timestamp);

            if (isNaN(timestamp) == true) {
                Toast.fire({
                    icon: "error",
                    title: "{{ trans('translation.Invalid date') }}"
                });
                $('#meal_period_container_' + periodId).empty();
                $('#meal_period_container_' + periodId).append("<p>{{ trans('translation.no-data') }}</p>");
                return;
            }

            setHijriDate($('#input_date'));

            $.ajax({
                type: "GET",
                url: "{{ route('api.meal_dashboard') }}",
                data: {
                    organization_id: "{{ $organization->id }}",
                    date: date,
                    period_id: periodId
                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    console.log(response);
                    $('#meal_period_container_' + periodId).empty();
                    if (response.data.length > 0) {
                        response.data.forEach((meal) => {
                            $('#meal_period_container_' + periodId).append(mealContainerHtml(meal));
                        })
                    } else {
                        $('#meal_period_container_' + periodId).append(
                            "<p>{{ trans('translation.no-data') }}</p>");
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
        }

        // ============ End Fetch meals into cards ============
        // ====================================================

        // ================================================
        // ============ Pusher fetch real-time ============

        document.addEventListener('DOMContentLoaded', function() {

            const periodIds = [...new Set([+activePeriodTabId, ...
                initPeriodsIds
            ])]; // to sort ids and make active tab period id in first
            console.log(periodIds);
            periodIds.forEach((periodId) => {
                fetchPeriodMeals(periodId);
            });


            window.Echo.channel('MealStage-changes-channal').listen('.MealStage-changes', function(meal) {
                // console.log('Pusher Meal dashboard');
                // console.log('meal',meal);
                let all_stages_container_col = $("#all_stages_container_col_" + meal.id);
                let all_stages_container_row = all_stages_container_col.parent('.row');
                all_stages_container_col.remove();
                all_stages_container_row.append(allStagesContainerHtml(meal));

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
                        ${ sector.content == '' ? trans('translation.no-data') : sector.content }
                    </div>
                </div>
            `;
        }

        $('#sector_info_modal').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            const orderSectorId = button.attr('data-order-sector-id');
            const sector_info_body = $('#sector_info_body');

            $.ajax({
                type: "GET",
                url: "{{ route('api.sector_info') }}",
                data: {
                    order_sector_id: orderSectorId,
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
            console.log(orderSectorId);
        })

        // ============ End Shown sector info modal  ============
        // ====================================================

        // ====================================================
        // ============ Change Date Handler  ==================

        $('#input_date').change(function() {
            let dateSelected = $(this).val();
            setLoading(true);
            let redirectTo = '{{ url('/') }}/meals-dashboard/{{ $organization->slug }}/' + dateSelected;
            window.location.href = redirectTo;
        });

        // ============ End Change Date Handler  ==============
        // ====================================================
    </script>
@endpush

{{--
@foreach ($meals_by_period as $meal)
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body" style="background:#dddddd">
                    <div class="row">
                        <div class="col-lg-2 align-self-center">
                            <a href="{{route( 'meals.show', $meal->id )}}" class="btn btn-primary">{{$meal->order_sector->sector_name??''}} - {{$meal->order_sector->name}}</a>
                        </div>
                        <div class="col-lg-10">
                            <div class="container">
                                <div class="row align-items-center text-center">
                                    @php
                                        $current_stage_arrangement = $meal->meal_organization_stage->arrangement;
                                    @endphp
                                    @foreach ($meal->meal_organization_stages_arranged as $stage)
                                        <div class="col px-0">
                                            <div class="card card-body border border-dark {{$stage->stage_bg_class}} text-light mb-0 p-2" >
                                                {{$stage->organization_stage->stage_bank->name}}
                                            </div>
                                            <!-- <button class="btn btn-light "></button> -->
                                            
                                        </div>
                                        @if (!$loop->last)
                                            <div class="col px-0">
                                                <i class="mdi mdi-arrow-left-bold-outline " class="big-arrow"></i>

                                            </div>
                                        @endif
                                    @endforeach
                                    <!-- <a href="javascript:void(0);" class="progress-bar active" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Step 1</a>
                                    <a href="javascript:void(0);" class="progress-bar pending" role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"> Step 2</a> -->
                                    <!-- <a href="javascript:void(0);" class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> {{$stage->stage_bank_name}}</a> -->

                                </div>
                            </div>
                            
                        </div>
                    </div>
                    

                
                </div>
            </div>
        </div> 
        
    </div>
@endforeach($organization->sectors)
--}}
