@component('modals.modal-template',[
"modalId"=>"monitorRole",
"modalRoute"=>"admin.monitor.roles",
])
<!-- ================================================ -->
@slot('modalHeader')
<h5 class="modal-title text-white" id="monitorRoleLable">{{ trans('translation.monitorRole') }}</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
@endslot
<!-- ================================================ -->
@slot('modalBody')

<div class="modal-body">
    <input type="hidden" name="monitor_id" id="monitor_id" value="">
    <div class="row">
        @component('components.inputs.switch-input',['columnName'=>'supervisor','col'=>'6','margin'=>'mb-3'])@endcomponent
        @component('components.inputs.switch-input',['columnName'=>'boss','col'=>'6','margin'=>'mb-3'])@endcomponent
    </div>
</div>
@endslot
<!-- ================================================ -->
@slot('modalFooter')

<div class="hstack gap-2 justify-content-end">
    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
    <button type="submit" class="btn btn-primary" id="submit-monitorRole-btn" disabled>{{ trans('translation.update') }}</button>
</div>
@endslot


@endcomponent

@push('after-scripts')
    <script>
        $(document).ready(function(){
            $('#monitorRole').on('show.bs.modal', function(e){
            var supervisor = $(e.relatedTarget).attr('data-supervisor');
            var boss = $(e.relatedTarget).attr('data-boss');
            var monitorId = $(e.relatedTarget).attr('data-monitor-id');

            console.log(supervisor, 'hellloo', boss);

            $('#monitor_id').val(monitorId);

            // Set the checked property of the checkboxes
            $('#input_boss').prop('checked', boss == 1);
            $('#input_supervisor').prop('checked', supervisor == 1);
                
            })
            
            $('#monitorRole .check-empty-input').on('change', function(){
                let flag = true;
                $('#monitorRole .check-empty-input').each(function() {
                    if (!$(this).is('div')){
                        if($(this).prop('required') && $(this).val() == ''){
                            flag = false;
                            return;
                        }
                    }
                });
                $('#submit-monitorRole-btn').prop('disabled', !flag)
            })
            
            $('form').on('submit', function(e){
                var form = $(this);
                var sendBtn = $('#submit-monitorRole-btn');
                sendBtn.empty();
                sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
                sendBtn.prop('disabled', true);
            });
            
        });
    </script>
@endpush