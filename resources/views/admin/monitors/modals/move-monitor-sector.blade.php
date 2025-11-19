@component('modals.modal-template',[
"modalId"=>"moveMonitorSector",
"modalRoute"=>"admin.monitor-order-sectors.move",
])
<!-- ================================================ -->
@slot('modalHeader')
<h5 class="modal-title text-white" id="moveMonitorSectorLabel">{{ trans('translation.moveMonitorSector') }}</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
@endslot
<!-- ================================================ -->
@slot('modalBody')

<div class="modal-body">
    <input type="hidden" name="monitor_id" value="{{$monitor->id}}">
    <div class="row">
        @component('components.inputs.select-input',['columnName'=>'move_from','col'=>'6','margin'=>'mb-3', 'modelItem'=>$monitor, 'columnOptions' => ['move_from' => $monitor->assigned_sectors()->toArray()]])@endcomponent
        @component('components.inputs.select-input',['columnName'=>'move_to','col'=>'6','margin'=>'mb-3', 'modelItem'=>$monitor, 'disabled'=>'disabled', 'columnOptions' => ['move_to' => $monitor->unassigned_sectors()->toArray()]])@endcomponent
    </div>
</div>
@endslot
<!-- ================================================ -->
@slot('modalFooter')

<div class="hstack gap-2 justify-content-end">
    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
    <button type="submit" class="btn btn-primary" id="submit-moveMonitorSector-btn" disabled>{{ trans('translation.update') }}</button>
</div>
@endslot


@endcomponent

@push('after-scripts')
    <script>
        $(document).ready(function(){
            $('#move_from_filter').on('change', function(){
                $('#move_to_filter').prop('disabled', false)
                $('#move_to_filter').selectpicker('destroy').selectpicker({});
            })
            $('#moveMonitorSector .check-empty-input').on('change', function(){
                let flag = true;
                $('#moveMonitorSector .check-empty-input').each(function() {
                    if (!$(this).is('div')){
                        if($(this).prop('required') && $(this).val() == ''){
                            flag = false;
                            return;
                        }
                    }
                });
                $('#submit-moveMonitorSector-btn').prop('disabled', !flag)
            })

            $('form').on('submit', function(e){
                let sendBtn = $('#submit-moveMonitorSector-btn');
                sendBtn.empty();
                sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
                sendBtn.prop('disabled', true)
            })
        });
    </script>
@endpush