@extends('admin.organizations.settings.layout.organization-settings')
@section('settings-content')

    @component('components.section-header', ['title' => 'categories', 'data' => $organization->categories->pluck("id")->implode(",")])@endcomponent
    <div class="row mt-4">
        @forelse ($organization->organization_categories as $organization_category)
            <div class="text-center mb-3 col-md-2 mx-2 border shadow rounded categoryCard">
                <div class="row">
                    <div class="d-flex p-2 justify-content-between align-items-center ">
                        <div>
                            {{ $organization_category->category->name }}
                        </div>
                        <button type="button" class="btn btn-danger btn-sm delete_categories"
                            value="{{ $organization_category->id }}"
                            {{-- data-category-id="{{ $organization_category->category->id }}" --}}
                            >
                            <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <p>{{ trans('translation.no-related-category') }}</p>
            </div>
        @endforelse
    </div>
@endsection

@section('modals')
    @include('admin.organizations.modals.add-category')
    
@endsection

@push('after-scripts')
<script>
$(document).ready(function() {
    $(document.body).on('click', '.delete_categories', function(e) {
        let deleteBtn = $(this);
        Swal
            .fire(window.deleteWarningPopupSetup).then((result) => {
                if (result.isConfirmed) {
                    var organization_category_id = $(this).val();
                    // var category_id = $(this).attr('data-category-id');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: '{{ url('/organization-categories') }}/' +
                            organization_category_id,
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                            let card = deleteBtn.closest('.categoryCard');
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
        })
    })
</script>
@endpush