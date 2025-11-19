{{-- ?? commented to optimize edit organization page --}}
{{-- @component('components.section-header', ['title' => 'services', 'data' => $organization->services->pluck("id")->implode(",")])@endcomponent --}}
@component('components.section-header', ['title' => 'services', 'hide_button' => true])@endcomponent

<div class="row mt-4">
    @forelse ($organization->organization_services as $organization_service)
        <div class="text-center mb-3 col-md-2 mx-2 border shadow rounded serviceCard">
            <div class="row">
                <div class="d-flex p-2 justify-content-between align-items-center ">
                    <div>
                        {{ $organization_service->service->name }}
                    </div>
                    <button type="button" class="btn btn-danger btn-sm edit_services"
                        value="{{ $organization_service->id }}"
                        data-service-id="{{ $organization_service->service->id }}">
                        <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center">{{ trans('translation.no-related-service') }}</p>
    @endforelse
</div>


{{-- <div class="tab-pane fade" id="custom-v-pills-services" role="tabpanel" aria-labelledby="custom-v-pills-services-tab">
    <div class="mt-2">
        <form class="form-horizontal" action="{{ route('organizations.update', $organization->id) }}" method="post"
            enctype="multipart/form-data" onsubmit="formSubmitted()">
            @csrf
            @method('PUT')
            <div class="row">
                <label for="edit_services" class="form-label">{{ trans('translation.services') }}</label>
                <div class="row">
                    @foreach ($organization->organization_services as $organization_service)
                        <div class="text-center mb-3 col-md-2 mx-2 border shadow rounded serviceCard">
                            <div class="row">
                                <div class="d-flex p-2 justify-content-between align-items-center ">
                                    <div>
                                        {{ $organization_service->service->name }}
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm edit_services"
                                        value="{{ $organization_service->id }}"
                                        data-service-id="{{ $organization_service->service->id }}">
                                        <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="">
                    <button type="button" id="add-services-button" class="btn btn-primary btn-sm"
                        data-organization-id="{{ $organization->id }}" data-bs-target="#addservices"
                        data-services="{{ $organization->services->pluck('id')->implode(',') }}" data-bs-toggle="modal">
                        <i class="mdi mdi-plus mdi-lg align-middle plus plus-bigger"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> --}}
