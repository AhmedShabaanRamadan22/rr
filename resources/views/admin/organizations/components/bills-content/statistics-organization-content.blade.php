@component('components.nav-pills.tab-pane', ['id' => $column['name'], 'padding' => 'p-1'])
@component('components.section-header', ['title' => 'statistics', 'hide_button'=>'true'])@endcomponent
    {{-- @component('admin.organizations.components.edit-organization-form', ['organization' => $organization]) --}}
        <div class="row mb-3">
            <div class="col">
                @push('styles')
    <style>
        .icon-medium{
            font-size: 16px;
        }
    </style>
@endpush
            <div class="p-4">
                <!-- Tab panes -->
                <div class="tab-content text-muted">

                    {{-- @foreach ($organization->organization_services as $organization_service) --}}
                    {{-- <div class="tab-pane {{$loop->index == 0 ?'active' : ''}}" id="providor-border-nav-{{$organization_service->id}}" role="tabpanel"> --}}
                    <div class="row">
                        @forelse ($operations as $operation => $model)
                        <div class=" py-3 border-bottom border-dashed" id="card-none3">
                            <div>
                                <div class="row d-flex align-items-between">
                                    <div type="button" class="btn form-control d-flex align-items-center text-light" id="{{$operation . '-' . $model->first()?->id ?? null}}" onclick="this.blur();">
                                        <div class="flex-grow-1 operation-collapser" data-toggler-id="{{ $operation . '-' . $model->first()?->id ?? null }}">
                                            <h6 class="card-title text-start text-primary align-items-center">{{trans('translation.'. $operation) }}</h6>
                                        </div>
                                        <div class="flex-shrink-0 operation-collapser" data-toggler-id="{{ $operation . '-' . $model->first()?->id ?? null }}">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <a class="align-middle minimize-card collapse-toggle collapsed" id="operation-toggler-{{$operation . '-' . $model->first()?->id ?? null}}" data-bs-toggle="collapse" href="#operation-{{$operation}}" role="button" aria-expanded="true" aria-controls="operations">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse operations" id="operation-{{$operation}}" >
                                     <h6>hellooooooo</h6>
                                    {{-- <div class="table-responsive">
                                        <table class="table table-nowrap align-middle text-center">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ trans('translation.providor') }}</th>
                                                    <th scope="col">{{ trans('translation.name') }}</th>
                                                    <th scope="col">{{ trans('translation.service') }}</th>
                                                    <th scope="col">{{ trans('translation.action') }}</th>
                                                </tr>
                                            </thead>
                                            
                                        </table>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>{{trans('translation.no-related-sector')}}</p>
                        @endforelse
                    </div>
                    {{-- </div> --}}
                    {{-- @endforeach --}}
                </div>
    </div>

                {{-- @include('admin.dashboard.temp-admin.sections.ticket-pie-chart') --}}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                
            </div>
        </div>
        <div class="row">
           
        </div>
    {{-- @endcomponent --}}
@endcomponent

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
    // $(document.body).on('click', '.delete_providor', function(e) {
    //     let deleteBtn = $(this);
    //     let model_id = $(this).attr('data-providor-id');
    //     Swal
    //         .fire(window.deleteWarningPopupSetup).then((result) => {
    //             if (result.isConfirmed) {
    //                 // deleteBtn.closest('form').submit()
    //                 $.ajaxSetup({
    //                     headers: {
    //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                     }
    //                 });
    //                 $.ajax({
    //                     type: 'DELETE',
    //                     url: "{{ url('/providors') }}" + "/" + model_id,
    //                     success: function(response) {
    //                         deleteBtn.closest('.providorCard').remove();
    //                         Toast.fire({
    //                             icon: "success",
    //                             title: "{{trans('translation.delete-successfully') }}"
    //                         });
    //                     },
    //                     error: function(jqXHR, responseJSON) {
    //                         Toast.fire({
    //                             icon: "error",
    //                             title: "{{ trans('translation.something went wrong!') }}"
    //                         });

    //                     },
    //                 });
    //             }
    //         });
    // });
    $(document).ready(function(){
        // $('.create-contract').on('click', function(){
        //     let order_sector_id = $(this).attr('data-order-sector-service');
        //     let contract_template = 'service_' + $(this).attr('data-organization-service');
        //     Swal
        //         .fire(window.confirmGeneratePopupSetup).then((result) => {
        //             if (result.isConfirmed) {
        //                 setLoading(true);
        //                 // deleteBtn.closest('form').submit()
        //                 $.ajaxSetup({
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     }
        //                 });
        //                 $.ajax({
        //                     type: 'POST',
        //                     url: "{{ url('admin/contracts/store') }}",
        //                     data:{
        //                         contractable_id: order_sector_id,
        //                         contractable_type: 'App\\Models\\OrderSector',
        //                         contract_template: contract_template
        //                     },
        //                     success: function(response) {
        //                         localStorage.setItem('reloadPending', "{{trans('translation.Added successfully')}}");
        //                         // Reload the page immediately
        //                         window.location.reload();
        //                         setLoading(false);
        //                     },
        //                     error: function(jqXHR, responseJSON) {
        //                         setLoading(false);
        //                         Toast.fire({
        //                             icon: "error",
        //                             title: "{{ trans('translation.something went wrong!') }}"
        //                         });
    
        //                     },
        //                 });
        //             }
        //         });
        // });
        // $('.recreate-contract').on('click', function(){
        //     let contract = $(this).attr('data-contract');
        //     Swal
        //         .fire(window.confirmRecreatePopupSetup).then((result) => {
        //             if (result.isConfirmed) {
        //                 setLoading(true);
        //                 $.ajaxSetup({
        //                     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        //                 });
        //                 $.ajax({
        //                     type: 'POST',
        //                     url: "{{ url('admin/contracts/regenerate') }}" + "/" + contract,
        //                     success: function(response) {
        //                         localStorage.setItem('reloadPending', response.message);
        //                         // Reload the page immediately
        //                         window.location.reload();
        //                         setLoading(false);
        //                     },
        //                     error: function(jqXHR, responseJSON) {
        //                         localStorage.setItem('reloadPending', '{{trans('translation.something went wrong!')}}');
        //                         localStorage.setItem('icon', 'error');
        //                         window.location.reload();
        //                         setLoading(false);
        //                     },
        //                 });
        //             }
        //         });
        // });
        // $('.delete-contract').on('click', function(){
        //     let contract = $(this).attr('data-contract');
        //     Swal
        //         .fire(window.deleteWarningPopupSetup).then((result) => {
        //             if (result.isConfirmed) {
        //                 setLoading(true);
        //                 $.ajaxSetup({
        //                     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        //                 });
        //                 $.ajax({
        //                     type: 'DELETE',
        //                     url: "{{ url('admin/contracts/destroy') }}" + "/" + contract,
        //                     success: function(response) {
        //                         localStorage.setItem('reloadPending', "{{trans('translation.delete-successfully')}}");
        //                         // Reload the page immediately
        //                         window.location.reload();
        //                         setLoading(false);
        //                     },
        //                     error: function(jqXHR, responseJSON) {
        //                         setLoading(false);
        //                         Toast.fire({
        //                             icon: "error",
        //                             title: "{{ trans('translation.something went wrong!') }}"
        //                         });
    
        //                     },
        //                 });
        //             }
        //         });
        // });
        // $('.delete-signed-contract').on('click', function(){
        //     let contract_id = $(this).attr('data-contract-id')
        //             Swal
        //     .fire(window.deleteWarningPopupSetup).then((result) => {
        //         if (result.isConfirmed) {
        //             // deleteBtn.closest('form').submit()
        //             $.ajaxSetup({
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 }
        //             });
        //             $.ajax({
        //                 type: 'DELETE',
        //                 url: "{{ url('admin/contracts/delete-signed-contract') }}" + "/" + contract_id,
        //                 success: function(response) {
        //                     localStorage.setItem('reloadPending', "{{ trans('translation.Deleted successfully') }}");
        //                     window.location.reload();
        //                     setLoading(false);
        //                 },
        //                 error: function(jqXHR, responseJSON) {
        //                     Toast.fire({
        //                         icon: "error",
        //                         title: "{{ trans('translation.something went wrong!') }}"
        //                     });

        //                 },
        //             });
        //         }
        //     });
        // })
        // $('.delete-order-sector').on('click', function(){
        //     let order_sector_id = $(this).attr('data-order-sector-id')
        //         Swal.fire(window.deleteWarningPopupSetup).then((result) => {
        //             if (result.isConfirmed) {
        //                 $.ajaxSetup({
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     }
        //                 });
        //                 $.ajax({
        //                     type: 'DELETE',
        //                     url: "{{ url('order-sectors') }}" + "/" + order_sector_id,
        //                     success: function(response) {
        //                         localStorage.setItem('reloadPending', "{{ trans('translation.Deleted successfully') }}");
        //                         window.location.reload();
        //                         setLoading(false);
        //                     },
        //                     error: function(jqXHR, responseJSON) {
        //                         Toast.fire({
        //                             icon: "error",
        //                             title: "{{ trans('translation.something went wrong!') }}"
        //                         });

        //                     },
        //                 });
        //             }
        //         });
        // })
        // $('.set-active').on('click', function(){
        //     let order_sector_id = $(this).attr('data-order-sector-id')
        //             Swal
        //     .fire(window.confirmUpdatePopupSetup).then((result) => {
        //         if (result.isConfirmed) {
        //             // deleteBtn.closest('form').submit()
        //             $.ajaxSetup({
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 }
        //             });
        //             $.ajax({
        //                 type: 'POST',
        //                 url: "{{ url('admin/order-sectors/set-active') }}" + "/" + order_sector_id,
        //                 success: function(response) {
        //                     localStorage.setItem('reloadPending', "{{ trans('translation.updated successfuly') }}");
        //                     window.location.reload();
        //                     setLoading(false);
        //                 },
        //                 error: function(jqXHR, responseJSON) {
        //                     Toast.fire({
        //                         icon: "error",
        //                         title: "{{ trans('translation.something went wrong!') }}"
        //                     });

        //                 },
        //             });
        //         }
        //     });
        // })
    })
    $('.operation-collapser').on('click', function(){
        let id = $(this).attr('data-toggler-id');
        let chevron = document.getElementById(`operation-toggler-${id}`).click();
    });
</script>
@endpush