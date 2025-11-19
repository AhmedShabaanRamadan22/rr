{{-- @dd($modalName) --}}
@component('modals.modal-template',[
"modalId"=>"sort".$modalName,
"modalRoute"=>($modalRoute??null).".sort",
"modalMaxHeight" => $modalMaxHeight??null,
"modalRouteId"=>$modalRouteId??null,
"modalRouteMethod"=>'PUT'
])
<!-- ================================================ -->
@slot('modalHeader')
    <h5 class="modal-title text-white" id="{{$modalName}}Label">{{ trans('translation.'.$modalName) }}</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
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
    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
    <button type="submit" class="btn btn-primary" id="submit-sort-{{$modalName}}-btn" disabled>{{ trans('translation.submit') }}</button>
</div>
@endslot


@endcomponent

@push('after-scripts')
    <script>
        $(document).ready(function(){

            $('form').on('submit', function(e){
                var el = document.getElementById('sortable');
                var sortable = Sortable.create(el);
                var items = sortable.toArray();

                $(this).append('<input type="hidden" name="items" value="'+items+'" /> ');
                let sendBtn = $('#submit-sort-' + '{{$modalName}}' + '-btn');
                sendBtn.empty();
                sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
                sendBtn.prop('disabled', true)
                location.reload(true)
                {{ 'window.' . $modalName . 'Datatable' }}.ajax.reload();
            })
        });
    </script>
@endpush
