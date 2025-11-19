@component('components.section-header', ['title' => 'nationality'])@endcomponent
<div class="row mt-4">
@forelse ($organization->nationality_organizations as $nationality_organization)
    <div class="text-center mb-3 col-md-2 mx-2 border shadow rounded nationalityCard">
        <div class="row">
            <div class="d-flex p-2 justify-content-between align-items-center ">
                <div>
                    {!! $nationality_organization->nationality->flag_icon !!}
                    <span>{{$nationality_organization->nationality->name}}</span>
                </div>

                <button type="button" class="btn btn-danger btn-sm delete_nationality_organization" value="{{ $nationality_organization->id }}" data-nationality-id="{{ $nationality_organization->id }}">
                    <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <p>{{trans('translation.no-related-nationality')}}</p>
    @endforelse
</div>

@push('after-scripts')
    <script>
        $(document.body).on('click', '.delete_nationality_organization', function(e) {
            let deleteNationalityBtn = $(this);
            let model_id = $(this).attr('data-nationality-id');
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ url('/nationality-organizations') }}" + "/" + model_id,
                            success: function(response) {
                                deleteNationalityBtn.closest('.nationalityCard').remove();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{trans('translation.delete-successfully') }}"
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
        })
    </script>
@endpush
