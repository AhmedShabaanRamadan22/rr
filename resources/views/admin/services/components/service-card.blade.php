<div class=" serviceCard mb-3">
    <div class="card border h-100">
        <div class="card-header bg-light">
            <div class="row mb-2">
                <div class="col">
                    <h3 class="text-primary">{{$service->name}}</h3>
                </div>
                <div class="col text-end align-self-center">
                    <!-- <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a> -->
                    {{-- <a href="{{url('services/edit')}}" class="btn btn-outline-secondary " data-service-name="{{$service->name}}" data-service-price="{{$service->price}}" data-service-id="{{$service->id}}" data-bs-target="#editService" data-bs-toggle="modal"><i class="mdi mdi-clipboard-edit-outline"></i></a> --}}
                    {{-- <a href="" class="btn btn-outline-danger deleteService" data-service-id="{{$service->id}}" ><i class="mdi mdi-delete mdi-lg"></i></a> --}}

                </div>

            </div>
        </div>
        <div class="card-body overflow-scroll" style="max-height: 7rem">

            <!-- <div class="row">
                <div class="col-md-7 font-weight-bold">{{__('The Group')}}</div>
                <div class="col-md-5 text-nowrape font-weight-bold">{{__('Orders Count')}}</div>
            </div> -->
            @forelse ( $service->organizations as $organization)
            {{-- <div class="col-md-6 ">
                <div class="card">
                    <div class="card-body text-center border rounded">
                        <h6>
                            {{$organization->name_ar}}
                        </h6>
                        <div>
                            <span class="badge bg-light text-secondary mt-2">
                                {{($organization->organization_services->where('service_id',$service->id)->first()->orders->count()??0)}} {{__('orders')}}
                            </span>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row {{++$loop->index == $loop->count ? '' : 'border-bottom border-light'}}">
                <div class="d-flex justify-content-between align-items-center my-2">
                    <div class="h6">
                        {{$organization->name_ar}}
                    </div>
                    <div>
                        <span class="badge bg-light text-primary mt-2">
                            {{($organization->organization_services->where('service_id',$service->id)->first()->orders->count()??0)}} {{trans('translation.order-num')}}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="row">
                <div class="text-center">{{trans('translation.no-data')}}</div>
            </div>
            @endforelse
        </div>
        <div class="card-footer text-end">
            <a href="{{url('services/edit')}}" class="btn btn-secondary mx-1 " data-service-name-ar="{{$service->name_ar}}" data-service-name-en="{{$service->name_en}}" data-service-price="{{$service->price}}" data-service-id="{{$service->id}}" data-bs-target="#editService" data-bs-toggle="modal">{{trans('translation.edit')}}</a>
            <a href="" class="btn btn-danger deleteService" data-service-id="{{$service->id}}" >{{trans('translation.delete')}}</a>
        </div>
    </div>
</div>
