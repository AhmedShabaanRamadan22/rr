<div class="col-xl-6">
    <div class="card card-height-100">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title text-primary flex-grow-1 mb-0">إحصائيات الوجبات</h5>

        </div>
        <div class="card-body">
            <h4 class="text-center">الهدف اليومي للوجبات</h4>
            <div class="row">
                <div class="col-lg-3">
                    <div data-simplebar style="max-height: 380px;"class="nav flex-column nav-light nav-pills gap-3"
                        id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach ($all_organizations as $index => $org)
                            <a class="nav-link d-flex p-2 gap-3 {{ $loop->first ? 'active' : '' }}"
                                id="v-pills-{{ $index }}-tab" data-bs-toggle="pill"
                                href="#v-pills-{{ $index }}" role="tab"
                                aria-controls="v-pills-{{ $index }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title rounded text-warning fs-2xl"
                                        style="background-color: {{ $org->primary_color }}">
                                        <i class="mdi mdi-food-outline text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="text-reset">{{ $org->name }}</h5>
                                    <p class="mb-0">اجمالي الوجبات</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="tab-content text-muted">
                        @foreach ($all_organizations as $index => $org)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="v-pills-{{ $index }}"
                                role="tabpanel" aria-labelledby="v-pills-{{ $index }}-tab">
                                <!-- Unique chart container for each organization -->
                                <div id="meals-chart-{{ $org->id }}" dir="ltr"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
