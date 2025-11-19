@component('modals.modal-template',[
"modalId"=>"show".$modalName,
"modalRoute"=>($modalRoute??$modalName).".show",
"modalMaxHeight" => '',
"modalRouteId"=>0??null,
])
    <!-- ================================================ -->
    @slot('modalHeader')

        <h5 class="modal-title text-white" id="{{$modalName}}Label">{{ trans('translation.'.$modalName) }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                id="close-modal"></button>
    @endslot
    <!-- ================================================ -->
    @slot('modalBody')

        <div class="modal-body">
            <div class="row">
                {{$slot}}
            </div>
            <!--end row-->
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
