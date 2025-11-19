@forelse($data as $meal)

    <div id="meal_container_{{ $meal['id'] }}" class="pb-3 meal-containers card rounded-4" data-period="{{ $meal['period_id'] }}" data-label="{{$meal['sector_label']}}"
        data-quantity="{{ $meal['guest_quantity'] }}">
        <div class="py-3 px-4 d-flex align-items-center gap-lg-5 gap-2 rounded-top-4 text-white fs-md flex-wrap"
            style="background-color: {{ $meal['organization_color'] }};">

            {{--      Main info      --}}
            <div class="row flex-grow-1">
                <div class="col-5 col-lg-2 d-flex gap-1 align-items-center">
                    <div>
                        <i class="ri-building-fill" data-bs-toggle="tooltip" title="مركز الخدمة"></i>
                    </div>
                    <div><span class="ps-2">{{ $meal['sector_label'] }}</span></div>
                </div>
                <div class="col-7 col-lg-2 d-flex gap-1 align-items-center">
                    <div>
                        <i class="ri-group-fill" data-bs-toggle="tooltip" title="عدد الحجاج"  ></i>
                    </div>
                    <div><span class="ps-2">{{ $meal['guest_quantity'] }}</span></div>
                </div>
                <div class="col-sm-5 col-lg-4  d-flex gap-1 align-items-center">
                    <div>
                        <i class="ri-flag-fill" data-bs-toggle="tooltip" title="جنسية الحجاج"></i>
                    </div>
                    <div><span class="ps-2">{{ $meal['nationality'] }}</span></div>
                </div>
                <div class="col-sm-7 col-lg-4 d-flex gap-1 align-items-center">
                    <div>
                        <i class="ri-store-3-fill" data-bs-toggle="tooltip" title="متعهد الاعاشة"></i>
                    </div>
                    <div><span class="ps-2">{{ $meal['facility_name'] }}</span></div>
                </div>
            </div>

            {{--      details btns      --}}
            @can('view_all_meals_dashboard_details')
                <div class="d-flex align-items-center justify-content-end gap-2 flex-grow-1">
                    <div data-bs-toggle="tooltip"
                       data-bs-html="true"
                       title='<div class="text-start"><i class="mdi mdi-account-group"></i> <strong>المراقبين</strong>: {{ $meal['monitors'] }} <br/><i class="bi bi-people-fill"></i> <strong>المشرف</strong>: {{ $meal['supervisor'] }} <br/><i class="bi bi-person-fill"></i> <strong>القائد التشغيلي</strong>: {{ $meal['boss'] }} </div>'
                        class="rounded-circle border border-white d-flex align-items-center justify-content-center text-white"
                        style="width: 32px; height: 32px;">
                        <span class="mdi mdi-account-group fs-5"></span>
                    </div>
                    <a href="{{ $meal['meal_route'] }}" target="_blank"
                        class="rounded-circle border border-white d-flex align-items-center justify-content-center text-white"
                        style="width: 32px; height: 32px;" data-bs-toggle="tooltip" title="تفاصيل الوجبة">
                        <span class="mdi mdi-food fs-5"></span>
                    </a>
                    <a data-bs-toggle="modal" data-bs-target="#sector_info_modal"
                        data-order-sector-id="{{ $meal['order_sector_id'] }}"
                        data-id="{{ $meal['id'] }}"
                        class="rounded-circle border border-white d-flex align-items-center justify-content-center pointer btn text-white"
                        style="width: 32px; height: 32px;">
                        <span
                        data-bs-toggle="tooltip" title="معلومات مركز الخدمة"
                        class="bi bi-info-circle-fill fs-5"></span>
                    </a>
                    <a href="{{ route('admin.meal.report', $meal['uuid']) }}" target="_blank"
                       class="rounded-circle border border-white d-flex align-items-center justify-content-center pointer btn text-white"
                       style="width: 32px; height: 32px;" data-bs-toggle="tooltip" title="تحميل تقرير الوجبة">
                        <i class="mdi mdi-file-document-outline fs-5"></i>
                    </a>
                    <div class="position-relative">
                        <a data-bs-toggle="modal"
                           data-bs-target="#new_tickets_modal"
                           data-order-sector-id="{{$meal['order_sector_id']}}"
                           data-label="{{$meal['sector_label']}}"
                           class="rounded-circle border border-white d-flex align-items-center justify-content-center pointer btn text-white"
                           style="width: 32px; height: 32px;">
                            <span data-bs-toggle="tooltip" title="البلاغات الجديدة" class="mdi mdi-ticket-confirmation-outline fs-5"></span>
                        </a>
                        <div class="position-absolute top-0">
                            <div class="bg-danger rounded-circle ticket-dot-{{$meal['order_sector_id']}} notification-dot" style="height: 12px; width: 12px; display: none;"></div>
                        </div>
                    </div>
                    <div class="position-relative">
                        <a data-bs-toggle="modal"
                           data-bs-target="#new_supports_modal"
                           data-order-sector-id="{{$meal['order_sector_id']}}"
                           data-meal-id="{{$meal['id']}}"
                           data-label="{{$meal['sector_label']}}"
                           class="rounded-circle border border-white d-flex align-items-center justify-content-center pointer btn text-white"
                           style="width: 32px; height: 32px;">
                            <span data-bs-toggle="tooltip" title="الإسناد الجديد" class="mdi mdi-truck-delivery-outline fs-5"></span>
                        </a>
                        <div class="position-absolute top-0">
                            <div class="bg-danger rounded-circle support-dot-{{$meal['id']}} support-dot-{{$meal['order_sector_id']}} notification-dot" style="height: 12px; width: 12px;display: none"></div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        {{--      progress bar      --}}
        <div class="px-4 overflow-auto card-body">
            <div class="overflow-auto" style="min-width: 1300px">
                <div class="d-flex align-items-start justify-content-between fw-medium py-4 meal-steps w-100">
                    <div class="d-flex flex-column gap-4 justify-content-center text-center" style="flex: 1 1 0%;">
                        <div>
                            <svg width="17" height="20" viewBox="0 0 17 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.5 1V11C1.5 11 16.25 11.0008 16.25 10.7505C16.25 10.5645 13.4918 8.03425 12.0726 6.74459C11.6331 6.34522 11.6331 5.65483 12.0726 5.2555C13.4919 3.96612 16.25 1.4365 16.25 1.25049C16.25 1.00023 1.5 1 1.5 1Z"
                                    fill="#00C569" stroke="#00C569" />
                                <path
                                    d="M2.25 1.24805V0.498047H0.75V1.24805H2.25ZM0.75 18.9997C0.75 19.4139 1.08579 19.7497 1.5 19.7497C1.91421 19.7497 2.25 19.4139 2.25 18.9997H0.75ZM1.5 1.24805H0.75V18.9997H1.5H2.25V1.24805H1.5Z"
                                    fill="#C4C4C4" />
                            </svg>
                        </div>
                        <div class="fw-normal">{{ trans('translation.meal-start-time') }}</div>
                        <div class="text-success">{{ $meal['start_time'] }}</div>
                    </div>
                    @foreach ($meal['stages']->resolve() as $stage)
                        <div id="stage_{{ $stage['id'] }}"
                             class="d-flex flex-column gap-3 justify-content-center text-center step-status {{ $stage['time_status'] . ' ' . ($loop->last ? 'last' : '') }}"
                             data-expected-end="{{ $stage['expected_end_time'] }}" style="flex: 1 1 0%;">
                            <div class="time fs-2xs">{{ $stage['time'] . $stage['day'] }}</div>
                            <div class="step-container step-line d-flex justify-content-center w-100">
                                <div data-stage-id="{{ $stage['id'] }}" data-meal-id="{{ $meal['id'] }}"
                                    class="circle rounded-circle border border-3 d-flex align-items-center justify-content-center"
                                    style="width: 28px; height: 28px; z-index:3">
                                    <span class="mdi mdi-check fs-5"></span>
                                </div>
                            </div>
                            <div class="step px-2">{{ $stage['name'] }}</div>
                        </div>
                    @endforeach
                    <div class="d-flex flex-column gap-4 justify-content-center text-center" style="flex: 1 1 0%;">
                        <div>
                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.5 1V11C1.5 11 16.25 11.0008 16.25 10.7505C16.25 10.5645 13.4918 8.03425 12.0726 6.74459C11.6331 6.34522 11.6331 5.65483 12.0726 5.2555C13.4919 3.96612 16.25 1.4365 16.25 1.25049C16.25 1.00023 1.5 1 1.5 1Z"
                                    fill="#9C9C9C" stroke="#9C9C9C" />
                                <path
                                    d="M2.25 1.24805V0.498047H0.75V1.24805H2.25ZM0.75 18.9997C0.75 19.4139 1.08579 19.7497 1.5 19.7497C1.91421 19.7497 2.25 19.4139 2.25 18.9997H0.75ZM1.5 1.24805H0.75V18.9997H1.5H2.25V1.24805H1.5Z"
                                    fill="#C4C4C4" />
                            </svg>
                        </div>
                        <div class="fw-normal">{{ trans('translation.meal-end-time') }}</div>
                        <div style="color: #9C9C9C">{{ $meal['end_time'] }}</div>
                    </div>
                </div>
                <div id="answers_stage_{{ $meal['id'] }}" data-stage="" class="meal_answers p-4 pt-0"></div>
            </div>

        </div>
    </div>

@empty
    <div class="card card-body text-center">
        <div class="fw-bold text-secondary py-2" style="color: #9C9C9C!important;">{{ trans('translation.no-data') }}
        </div>
    </div>
@endforelse
