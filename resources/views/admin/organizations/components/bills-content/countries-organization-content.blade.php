
{{-- ?? commented to optimize edit organization page --}}
{{-- @component('components.section-header', ['title' => 'organization-countries', 'data' => $organization->countries->pluck("id")->implode(",")])@endcomponent --}}
@component('components.section-header', ['title' => 'organization-countries', 'hide_button' => true])@endcomponent
<div class="row mt-4">
    @forelse ($organization->country_organization as $country_organization)
        <div class="text-center mb-3 col-md-2 mx-2 border shadow rounded countryCard">
            <div class="row">
                <div class="d-flex p-2 justify-content-between align-items-center ">
                    <div>
                        {{ $country_organization->country->name }}
                    </div>
                    <button type="button" class="btn btn-danger btn-sm delete_country"
                        value="{{ $country_organization->id }}"
                        data-country-id="{{ $country_organization->id }}">
                        <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center">{{ trans('translation.no-related-country') }}</p>
    @endforelse</div>



@push('after-scripts')
<script>

    $(document.body).on('click', '.delete_country', function(e) {
            let deleteBtn = $(this);
            let model_id = $(this).attr('data-country-id');
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        // deleteBtn.closest('form').submit()
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ url('/organization-countries') }}" + "/" + model_id,
                            success: function(response) {
                                let card = deleteBtn.closest('.countryCard');
                                card.remove();
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
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
        });
</script>
@endpush