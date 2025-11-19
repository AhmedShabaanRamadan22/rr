@extends('admin.organizations.settings.layout.organization-settings')
@push('styles')
    <style>
        .icon-medium{
            font-size: 16px;
        }
        </style>
@endpush

@section('settings-content')
        @php
            $disabled = $organization->has_sectors ? null :'disabled';
        @endphp
        @component('components.section-header', ['title' => 'providors', 'disabled' => $disabled, 'disabled_message' => trans('translation.add-sectors-first')])
            <small class=" text-primary my-auto {{($has_sectors = $organization->has_sectors) ? 'd-none':''}}">{{trans('translation.please-add-sector-first')}}!</small>
        @endcomponent
        <div class="p-4">
            <ul class="nav nav-pills nav-custom-outline nav-primary mb-3" role="tablist">
                @foreach ($organization->organization_services as $organization_service)
                <li class="nav-item ">
                    <a class="nav-link {{$loop->index == 0 ?'active' : ''}}" data-bs-toggle="tab" href="#providor-border-nav-{{$organization_service->id}}" role="tab">{{$organization_service->service->name}}</a>
                </li>
                @endforeach
            </ul>
            <!-- Tab panes -->
            <div class="tab-content text-muted">

                @foreach ($organization->organization_services as $organization_service)
                <div class="tab-pane {{$loop->index == 0 ?'active' : ''}}" id="providor-border-nav-{{$organization_service->id}}" role="tabpanel">
                    <div class="row">
                    @forelse ($organization->sectors as $sector)
                    <div class="col-lg-6 py-3 border-bottom border-dashed" id="card-none3">
                        <div>
                            <div class="row d-flex align-items-between">
                                <div type="button" class="btn form-control d-flex align-items-center {{ ($no_sector = $sector->order_sector_service($organization_service->id)->isEmpty() )? 'disabled border-0 text-secondary' : 'text-light'}}" id="{{$sector->id . '-' . $organization_service->id}}" onclick="this.blur();">
                                    <div class="flex-grow-1 providor-collapser" data-toggler-id="{{$sector->id . '-' . $organization_service->id}}">
                                        <h6 class="card-title text-start text-{{$no_sector ? 'gray':'primary'}} align-items-center">{{trans('translation.sector') .': '. $sector->label}}</h6>
                                    </div>
                                    <div class="flex-shrink-0 providor-collapser" data-toggler-id="{{$sector->id . '-' . $organization_service->id}}">
                                        <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                            <li class="list-inline-item">
                                                <a class="align-middle minimize-card collapse-toggle collapsed" id="providor-toggler-{{$sector->id . '-' . $organization_service->id}}" data-bs-toggle="collapse" href="#providor-{{$sector->id . '-' . $organization_service->id}}" role="button" aria-expanded="true" aria-controls="providors">
                                                    <i class="mdi mdi-chevron-up align-middle minus icon-bigger"></i>
                                                    <i class="mdi mdi-chevron-down align-middle plus icon-bigger"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <a class="text-reset dropdown-btn " href="#" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted"><i
                                                    class="mdi mdi-dots-vertical icon-bigger"></i></span>
                                        </a>
                                        @if ($order_sector_service = $sector->active_order_sector_service($organization_service->id)->first())
                                            <div class="dropdown-menu dropdown-menu-end" style="cursor:not-allowed;">
                                                @if (!$order_sector_service->contract)
                                                <button class="dropdown-item text-danger disabled {{$has_contract_template = ($order_sector_service != null && $organization_service->has_contract_template) ? 'd-none':''}}" disabled >
                                                    <small>
                                                        {{trans('translation.write-contract-template-first')}}
                                                    </small>
                                                </button>
                                                {{-- <a {{$order_sector_service != null && $organization_service->has_contract_template ? 'href='.route('admin.contracts.preview',['contractable_id' => $order_sector_service->id, 'contractable_type' => 'App\Models\OrderSector', 'contract_template' => 'service_'.$organization_service->id]) : ''}} target="_blank">
                                                    <button class="dropdown-item preview-contract-button" 
                                                    {{($order_sector_service != null && $organization_service->has_contract_template ? '' : ' disabled ')}}
                                                    data-sector-id="{{ $sector->id }}" data-service-id="{{ $organization_service->id }}">
                                                        {{trans('translation.preview-contract')}}
                                                    </button>
                                                </a> --}}
                                                <button class="dropdown-item create-contract" 
                                                    data-order-sector-service="{{$order_sector_service->id ?? 0}}"
                                                    data-organization-service="{{$organization_service->id}}"
                                                    {{($order_sector_service != null && $organization_service->has_contract_template ? '' : ' disabled ')}}
                                                    >
                                                        {{trans('translation.create-contract')}}
                                                </button>
                                                @else
                                                    {{-- <a {{$order_sector_service != null && $organization_service->has_contract_template ? 'href='.route('admin.contracts.download',['contractable_id' => $order_sector_service->id, 'contractable_type' => 'App\Models\OrderSector', 'contract_template' => 'service_'.$organization_service->id]) : ''}}>
                                                        <button class="dropdown-item download-contract-button" 
                                                        {{($order_sector_service != null && $organization_service->has_contract_template ? '' : ' disabled ')}}
                                                        data-sector-id="{{ $sector->id }}" data-service-id="{{ $organization_service->id }}">
                                                            {{trans('translation.download-contract')}}
                                                        </button>
                                                    </a> --}}
                                                    {{-- <a href="{{$order_sector_service->contract->attachment->url??''}}" target="_blank">
                                                        <button class="dropdown-item" 
                                                            data-order-sector-service="{{$order_sector_service->id ?? 0}}"
                                                            data-organization-service="{{$organization_service->id}}"
                                                            >
                                                            {{trans('translation.view-contract')}}
                                                        </button>
                                                    </a> --}}
                                                    {{-- <button class="dropdown-item recreate-contract" data-contract="{{$order_sector_service->contract->id ?? 0}}">
                                                        {{trans('translation.recreate-contract')}}
                                                    </button> --}}
                                                    <button class="dropdown-item delete-contract" data-contract="{{$order_sector_service->contract->id ?? 0}}" >
                                                        {{trans('translation.delete-contract')}}
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="collapse providors" id="providor-{{$sector->id . '-' . $organization_service->id}}" >
                                @if($sector->order_sector_service($organization_service->id)->isEmpty())
                                    <p class="p-3">{{trans('translation.no-related-providor')}}</p>
                                @else
                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ trans('translation.providor') }}</th>
                                                <th scope="col">{{ trans('translation.name') }}</th>
                                                <th scope="col">{{ trans('translation.service') }}</th>
                                                <th scope="col">{{ trans('translation.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($sector->order_sector_service($organization_service->id) as $order_sector)
                                                <tr>
                                                    <th class=" {{$order_sector->is_active  ?"text-primary":""}}" scope="row">{{$order_sector->order->facility->name??'-'}}</th>
                                                    <td>{{$order_sector->order->user->name}}</td>
                                                    <td>{{$order_sector->order->service->name}}</td>
                                                    <td>
                                                        {{-- @if ($order_sector->is_active)
                                                            @if ($order_sector_service->contract)
                                                                @if ($order_sector->contract->has_signed_contract)
                                                                    <a class="btn btn-sm btn-outline-secondary" href="{{$order_sector->contract->signedContract->url}}" target="_blank"><i class="mdi mdi-eye"></i></a>
                                                                    <a class="btn btn-sm btn-outline-primary" href="{{$order_sector->contract->signedContract->url}}" target="_blank" download><i class="mdi mdi-file-download-outline"></i></a>
                                                                    <button class="btn btn-sm btn-outline-danger"><i class="mdi mdi-trash-can-outline"></i></button>
                                                                @else
                                                                    <button class="btn btn-sm btn-outline-primary" data-bs-target="#addsigned_contract" data-bs-toggle="modal" data-original-title="Add" data-contract-id="{{$order_sector_service->contract->id}}"><i class="mdi mdi-cloud-upload-outline"></i></button>
                                                                @endif
                                                            @else
                                                                <div class="text-danger">{{trans('translation.generate-contract-first')}}</div>
                                                            @endif
                                                        @else
                                                            <button class="btn btn-sm btn-outline-info">{{trans('translation.set-active')}}</button>
                                                        @endif --}}
                                                        @if ($order_sector->is_active)
                                                            @if ($order_sector_service->contract)
                                                                @if ($order_sector->contract->has_signed_contract)
                                                                    <a class="btn btn-sm btn-outline-secondary" href="{{$order_sector->contract->signedContract->url}}" target="_blank"><i class="mdi mdi-eye"></i></a>
                                                                    <a class="btn btn-sm btn-outline-primary" href="{{$order_sector->contract->signedContract->url}}" target="_blank" download><i class="mdi mdi-file-download-outline"></i></a>
                                                                    <button class="btn btn-sm btn-outline-danger delete-signed-contract" data-contract-id="{{$order_sector->contract->id}}"><i class="mdi mdi-trash-can-outline"></i></button>
                                                                @else
                                                                    <button class="btn btn-sm btn-outline-primary" data-bs-target="#addsigned_contract" data-bs-toggle="modal" data-original-title="Add" data-contract-id="{{$order_sector_service->contract->id}}"><i class="mdi mdi-cloud-upload-outline"></i></button>
                                                                @endif
                                                            @else
                                                                <button class="btn btn-sm btn-outline-danger delete-order-sector" data-order-sector-id="{{$order_sector->id}}"><i class="mdi mdi-trash-can-outline"></i></button>
                                                            @endif
                                                        @else
                                                            @if (!$order_sector_service->contract)
                                                                <button class="btn btn-sm btn-outline-info set-active" data-order-sector-id="{{$order_sector->id}}"><i class="mdi mdi-crown-outline"></i></button>
                                                                <button class="btn btn-sm btn-outline-danger delete-order-sector" data-order-sector-id="{{$order_sector->id}}"><i class="mdi mdi-trash-can-outline"></i></button>
                                                            @endif
                                                        @endif

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class=" p-3">{{trans('translation.no-related-providor')}}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @empty
                    <p>{{trans('translation.no-related-sector')}}</p>
                    @endforelse
                </div>
                </div>
                @endforeach
            </div>
        </div>

@endsection

@section('modals')
    @include('admin.organizations.modals.add-providor')
    @include('admin.organizations.modals.add-signed-contract')
    @include('admin.organizations.modals.add-employee-contracts')

@endsection

@push('after-scripts')
<script>
    // reload the page then fire the toast
    if (localStorage.getItem('reloadPending') != null) {
        let msg = localStorage.getItem('reloadPending');
        localStorage.removeItem('reloadPending');
        // Display the toast after the reload
        let icon = 'success'
        if(localStorage.getItem('icon') != null){
            icon = localStorage.getItem('icon');
            localStorage.removeItem('icon');
        }
        Toast.fire({
            icon: icon,
            title: msg
        });
    }
</script>
<script>
    $(document.body).on('click', '.delete_providor', function(e) {
        let deleteBtn = $(this);
        let model_id = $(this).attr('data-providor-id');
        Swal
            .fire(window.deleteWarningPopupSetup).then((result) => {
                if (result.isConfirmed) {
                    // deleteBtn.closest('form').submit()
                    setLoading(true)
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ url('/providors') }}" + "/" + model_id,
                        success: function(response) {
                            setLoading(false);
                            deleteBtn.closest('.providorCard').remove();
                            Toast.fire({
                                icon: "success",
                                title: "{{trans('translation.delete-successfully') }}"
                            });
                        },
                        error: function(jqXHR, responseJSON) {
                            setLoading(false);
                            Toast.fire({
                                icon: "error",
                                title: "{{ trans('translation.something went wrong!') }}"
                            });

                        },
                    });
                }
            });
    });
    $(document).ready(function(){
        $('.create-contract').on('click', function(){
            let order_sector_id = $(this).attr('data-order-sector-service');
            let contract_template = 'service_' + $(this).attr('data-organization-service');
            Swal
                .fire(window.confirmGeneratePopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        setLoading(true);
                        // deleteBtn.closest('form').submit()
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ url('admin/contracts/store') }}",
                            data:{
                                contractable_id: order_sector_id,
                                contractable_type: 'App\\Models\\OrderSector',
                                contract_template: contract_template
                            },
                            success: function(response) {
                                localStorage.setItem('reloadPending', "{{trans('translation.Added successfully')}}");
                                // Reload the page immediately
                                window.location.reload();
                                setLoading(false);
                            },
                            error: function(jqXHR, responseJSON) {
                                setLoading(false);
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.something went wrong!') }}"
                                });
    
                            },
                        });
                    }
                });
        });
        $('.recreate-contract').on('click', function(){
            let contract = $(this).attr('data-contract');
            Swal
                .fire(window.confirmRecreatePopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        setLoading(true);
                        $.ajaxSetup({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ url('admin/contracts/regenerate') }}" + "/" + contract,
                            success: function(response) {
                                localStorage.setItem('reloadPending', response.message);
                                // Reload the page immediately
                                window.location.reload();
                                setLoading(false);
                            },
                            error: function(jqXHR, responseJSON) {
                                localStorage.setItem('reloadPending', '{{trans('translation.something went wrong!')}}');
                                localStorage.setItem('icon', 'error');
                                window.location.reload();
                                setLoading(false);
                            },
                        });
                    }
                });
        });
        $('.delete-contract').on('click', function(){
            let contract = $(this).attr('data-contract');
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        setLoading(true);
                        $.ajaxSetup({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ url('admin/contracts/destroy') }}" + "/" + contract,
                            success: function(response) {
                                localStorage.setItem('reloadPending', "{{trans('translation.delete-successfully')}}");
                                // Reload the page immediately
                                window.location.reload();
                                setLoading(false);
                            },
                            error: function(jqXHR, responseJSON) {
                                setLoading(false);
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.something went wrong!') }}"
                                });
    
                            },
                        });
                    }
                });
        });
        $('.delete-signed-contract').on('click', function(){
            let contract_id = $(this).attr('data-contract-id')
                    Swal
            .fire(window.deleteWarningPopupSetup).then((result) => {
                if (result.isConfirmed) {
                    setLoading(true)
                    // deleteBtn.closest('form').submit()
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ url('admin/contracts/delete-signed-contract') }}" + "/" + contract_id,
                        success: function(response) {
                            localStorage.setItem('reloadPending', "{{ trans('translation.Deleted successfully') }}");
                            window.location.reload();
                            setLoading(false);
                        },
                        error: function(jqXHR, responseJSON) {
                            setLoading(false);
                            Toast.fire({
                                icon: "error",
                                title: "{{ trans('translation.something went wrong!') }}"
                            });

                        },
                    });
                }
            });
        })
        $('.delete-order-sector').on('click', function(){
            let order_sector_id = $(this).attr('data-order-sector-id')
                Swal.fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        setLoading(true)
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ url('order-sectors') }}" + "/" + order_sector_id,
                            success: function(response) {
                                localStorage.setItem('reloadPending', "{{ trans('translation.Deleted successfully') }}");
                                window.location.reload();
                                setLoading(false);
                            },
                            error: function(jqXHR, responseJSON) {
                                setLoading(false);
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.something went wrong!') }}"
                                });

                            },
                        });
                    }
                });
        })
        $('.set-active').on('click', function(){
            let order_sector_id = $(this).attr('data-order-sector-id')
                    Swal
            .fire(window.confirmUpdatePopupSetup).then((result) => {
                if (result.isConfirmed) {
                    // deleteBtn.closest('form').submit()
                    setLoading(true)
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('admin/order-sectors/set-active') }}" + "/" + order_sector_id,
                        success: function(response) {
                            localStorage.setItem('reloadPending', "{{ trans('translation.updated successfuly') }}");
                            window.location.reload();
                            setLoading(false);
                        },
                        error: function(jqXHR, responseJSON) {
                            setLoading(false);
                            Toast.fire({
                                icon: "error",
                                title: "{{ trans('translation.something went wrong!') }}"
                            });

                        },
                    });
                }
            });
        })

        $('.selectPicker').selectpicker({
            width: '100%',
        });
    })
    $('.providor-collapser').on('click', function(){
        let id = $(this).attr('data-toggler-id');
        let chevron = document.getElementById(`providor-toggler-${id}`).click();
    });
</script>
@endpush
