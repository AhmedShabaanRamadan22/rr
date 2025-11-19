@component('components.section-header', ['title' => 'classifications'])@endcomponent
<div class="row mt-4">
@forelse ($organization->classifications as $classification)
    <div class="text-center mb-3 col-md-2 mx-2 border shadow rounded classificationCard">
        <div class="row">
            <div class="d-flex p-2 justify-content-between align-items-center ">
                <div>
                    <strong>{{ $classification->code }} </strong> <small>(SAR {{$classification->guest_value}})</small>
                </div>

                    <button type="button" class="btn btn-danger btn-sm delete_classification" value="{{ $classification->id }}" data-classification-id="{{ $classification->id }}">
                        <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                    </button>
            </div>
        </div>
    </div>
    @empty
    <p>{{trans('translation.no-related-classification')}}</p>
    @endforelse
</div>

@push('after-scripts')
    <script>
        $(document.body).on('click', '.delete_classification', function(e) {
                    let deleteBtn = $(this);
                    let model_id = $(this).attr('data-classification-id');
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
                                    url: "{{ url('/classifications') }}" + "/" + model_id,
                                    success: function(response) {
                                        deleteBtn.closest('.classificationCard').remove();
                                        Toast.fire({
                                            icon: "success",
                                            title: "{{trans('translation.delete-successfully') }}"
                                        });
                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Toast.fire({
                                            icon: "error",
                                            title: "{{ trans('translation.something went wrong!') }}"
                                        });

                                    },
                                });
                            }
                        });
                })
    </script>
@endpush
