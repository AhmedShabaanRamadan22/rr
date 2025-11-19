<div class="col-xl-12">
    <div class="card card-height-100">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title flex-grow-1 mb-0 text-primary">إحصائيات عامة</h5>
        </div>
        <div class="card-body">
            <div class="row ">
                <div class="col-lg-2">
                    <div data-simplebar style="height: 300px" class="nav flex-column nav-light nav-pills gap-3"
                        id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link d-flex p-2 gap-3 active" id="revenue-tab" data-bs-toggle="pill"
                            href="#revenue" role="tab" aria-controls="revenue" aria-selected="true">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title rounded bg-danger-subtle text-danger fs-2xl">
                                    <i class="mdi mdi-ticket-confirmation-outline"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="text-reset">
                                    البلاغات
                                </h5>
                                <p class="mb-0">الإجمالي: 5000</p>
                            </div>
                        </a>
                        <a class="nav-link d-flex p-2 gap-3" id="income-tab" data-bs-toggle="pill" href="#income"
                            role="tab" aria-controls="income" aria-selected="false">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title rounded bg-success-subtle text-success fs-2xl">
                                    <i class="mdi mdi-truck-delivery-outline"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="text-reset">
                                    الإسناد
                                </h5>
                                <p class="mb-0">الإجمالي: 5000</p>
                            </div>
                        </a>
                        <a class="nav-link d-flex p-2 gap-3" id="property-sale-tab" data-bs-toggle="pill"
                            href="#property-sale" role="tab" aria-controls="property-sale" aria-selected="false">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title rounded bg-info-subtle text-info fs-2xl">
                                    <i class="mdi mdi-food-outline"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="text-reset">
                                    الوجبات
                                </h5>
                                <p class="mb-0">الإجمالي: 5000</p>
                            </div>
                        </a>
                        <a class="nav-link d-flex p-2 gap-3" id="_-tab" data-bs-toggle="pill" href="#propetry-rent"
                            role="tab" aria-controls="propetry-rent" aria-selected="false">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title rounded bg-primary-subtle text-primary fs-2xl">
                                    <i class="mdi mdi-office-building-outline"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="text-reset">
                                    المنشآت
                                </h5>
                                <p class="mb-0">الإجمالي: 5000</p>
                            </div>
                        </a>
                    </div>
                </div>
                <!--end col-->
                <div class="col-lg-10">
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="revenue" role="tabpanel">
                            <div data-simplebar style="height: 300px">
                                @include('admin.dashboard.admin.sections.table')
                            </div>
                        </div>
                        <!--end tab-->
                        <div class="tab-pane" id="income" role="tabpanel">
                            <div id="total_income" data-colors='["--tb-success"]' class="apex-charts" dir="ltr">
                            </div>
                        </div>
                        <div class="tab-pane" id="property-sale" role="tabpanel">
                            <div id="property_sale_chart" data-colors='["--tb-danger"]' class="apex-charts"
                                dir="ltr"></div>
                        </div>
                        <div class="tab-pane" id="propetry-rent" role="tabpanel">
                            <div id="propetry_rent" data-colors='["--tb-info"]' class="apex-charts" dir="ltr">
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>
</div>
