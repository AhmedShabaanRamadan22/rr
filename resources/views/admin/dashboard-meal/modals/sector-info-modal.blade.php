@component('modals.modal-template',[
"modalId"=>"sector_info_modal",
"modalRoute"=>'dashboard',
"modalMaxHeight" => '',
])
    <!-- ================================================ -->
    @slot('modalHeader')
        <h5 class="modal-title text-white">{{ trans('translation.sector-info') }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                id="close-modal"></button>
    @endslot
    <!-- ================================================ -->
    @slot('modalBody')
        <div class="modal-body" id="sector_info_body">
            <span class="spinner-border spinner-border-sm  text-center" role="status" aria-hidden="true"></span>
        </div>
    @endslot
    <!-- ================================================ -->
    @slot('modalFooter')
        <div class="hstack gap-2 justify-content-end">
            <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i
                        class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
        </div>
    @endslot

@endcomponent

@push('after-scripts')

@endpush
