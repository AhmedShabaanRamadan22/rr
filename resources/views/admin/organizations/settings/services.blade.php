@extends('admin.organizations.settings.layout.organization-settings')
@section('settings-content')
    
    @component('components.section-header', ['title' => 'services', 'data' => $organization->services->pluck("id")->implode(",")])@endcomponent

    <div class="row mt-4">
        @forelse ($organization->organization_services as $organization_service)
            <div class="text-center mb-3 col-md-2 mx-2 border shadow rounded serviceCard">
                <div class="row">
                    <div class="d-flex p-2 justify-content-between align-items-center ">
                        <div>
                            {{ $organization_service->service->name }}
                        </div>
                        <button type="button" class="btn btn-danger btn-sm deleteServices"
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
@endsection
@section('modals')
    @include('admin.organizations.modals.add-service')
    
@endsection

@push('after-scripts')
    <script>

        $(document.body).on('click', '.deleteServices', function(e) {
            let deleteBtn = $(this);
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        var organization_service_id = $(this).val();
                        var service_id = $(this).attr('data-service-id');
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "DELETE",
                            url: '{{ url('/organization-services') }}/' +
                                organization_service_id,
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {
                                localStorage.setItem('reloadPending', "{{ trans('translation.The Service Deleted successfully') }}");
                                window.location.reload();
                                // setLoading(false);
                            },
                            error: function(jqXHR, responseJSON) {
                                Toast.fire({
                                    icon: "error",
                                    title: jqXHR.responseJSON.message
                                });
                            },
                        });
                    }
                });
        })

        // reload the page then fire the toast
        if (localStorage.getItem('reloadPending') != null) {
            let msg = localStorage.getItem('reloadPending');
            localStorage.removeItem('reloadPending');
            // Display the toast after the reload
            Toast.fire({
                icon: "success",
                title: msg
            });
        }
    </script>
@endpush