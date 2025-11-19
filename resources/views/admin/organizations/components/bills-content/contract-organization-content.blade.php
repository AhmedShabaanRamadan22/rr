{{-- ?? commented to optimize edit organization page --}}
            {{-- @component('components.section-header', ['title' => 'contract-org', 'hide_button'=>'true'])@endcomponent
    <div class="row">
        <div class="card">
            <div class="mx-auto">
                <ul class="nav nav-pills custom-hover-nav-tabs">
                    <li class="nav-item">
                        <a href="#custom-hover-services" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="mdi mdi-format-list-checkbox nav-icon nav-tab-position"></i>
                            <h5 class="nav-titl nav-tab-position m-0">{{trans('translation.services')}}</h5>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#custom-hover-employees" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                            <i class="mdi mdi-account-group-outline nav-icon nav-tab-position"></i>
                            <h5 class="nav-titl nav-tab-position m-0">{{trans('translation.employees')}}</h5>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content text-muted">
                    <div class="tab-pane show active" id="custom-hover-services">
                            <ul class="nav nav-pills nav-custom-outline nav-primary mb-3" role="tablist">
                                @foreach ($organization->organization_services as $service)
                                <li class="nav-item">
                                    <a class="nav-link {{$loop->index == 0 ? ' active' : ''}}" data-bs-toggle="tab" href="#border-nav-{{$service->id}}" role="tab">{{$service->service->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content text-muted">
                                @foreach ($organization->organization_services as $service)
                                <div class="tab-pane {{$loop->index == 0 ? 'active' : ''}}" id="border-nav-{{$service->id}}" role="tabpanel">
                                    @component('admin.organizations.components.bills-content.contract-template', ['organization'=>$organization, 'name' => $service->service->name, 'template_type' => 'service_' . $service->id, 'dictionaries'=>$order_sector_dictionaries])@endcomponent
                                </div>
                                @endforeach
                            </div>
                    </div>




                    <div class="tab-pane" id="custom-hover-employees">
                        @component('admin.organizations.components.bills-content.contract-template', ['organization'=>$organization, 'name' => trans('translation.employees'), 'template_type' => 'employee_' . $organization->id, 'dictionaries'=>$user_dictionaries])@endcomponent
                    </div>
                </div>
            </div><!-- end card-body -->
        </div>
    <!--end col-->

    </div> --}}


@component('components.section-header', ['title' => 'contract-org', 'hide_button'=>'true'])@endcomponent
    <div class="row">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills nav-custom-outline nav-primary mb-3" role="tablist">
                    @foreach ($organization->organization_services as $service)
                    <li class="nav-item">
                        <a class="nav-link {{$loop->index == 0 ? ' active' : ''}}" data-bs-toggle="tab" href="#border-nav-{{$service->id}}" role="tab">{{$service->service->name}}</a>
                    </li>
                    @endforeach
                </ul>
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    @foreach ($organization->organization_services as $service)
                    <div class="tab-pane {{$loop->index == 0 ? 'active' : ''}}" id="border-nav-{{$service->id}}" role="tabpanel">
                        @component('admin.organizations.components.bills-content.contract-template', ['organization'=>$organization, 'name' => $service->service->name, 'template_type' => 'service_' . $service->id, 'dictionaries'=>$order_sector_dictionaries])@endcomponent
                    </div>
                    @endforeach
                </div>
            </div><!-- end card-body -->
        </div>
    <!--end col-->

    </div>
