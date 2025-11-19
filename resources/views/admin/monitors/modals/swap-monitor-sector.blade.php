@component('modals.modal-template',[
"modalId"=>"swapMonitorSector",
"modalRoute"=>"admin.monitor-order-sectors.swap",
])
<!-- ================================================ -->
@slot('modalHeader')
<h5 class="modal-title text-white" id="swapMonitorSectorLabel">{{ trans('translation.swapMonitorSector') }}</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
@endslot
<!-- ================================================ -->
@slot('modalBody')

<div class="modal-body">
    <input type="hidden" name="monitor_id" value="{{$monitor->id}}">
    <div class="row">
        {{-- {{dd($monitor->monitors_in_same_sector())}} --}}
        @component('components.inputs.select-input',['columnName'=>'swap_from','col'=>'6','margin'=>'mb-3', 'modelItem'=>$monitor, 'columnOptions' => ['swap_from' => $monitor->assigned_sectors()->toArray()]])@endcomponent
        @component('components.inputs.select-input',['columnName'=>'swap_to','col'=>'6','margin'=>'mb-3', 'modelItem'=>$monitor, 'disabled'=>'disabled', 'columnOptions' => ['swap_to' => $monitor->swap_unassigned()->toArray()], 'columnSubtextOptions'=>['swap_to' => $monitor->swap_monitors()->pluck('monitor.name', 'id')->toArray()]])@endcomponent
    </div>
</div>
@endslot
<!-- ================================================ -->
@slot('modalFooter')

<div class="hstack gap-2 justify-content-end">
    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
    <button type="submit" class="btn btn-primary" id="submit-swapMonitorSector-btn" disabled>{{ trans('translation.update') }}</button>
</div>
@endslot


@endcomponent

@push('after-scripts')
    <script>
        function filter_swapped_monitors(order_sector){
            let swap_to_monitors = Object.values(@json($monitor->swap_monitors()));
            let monitors_in_same_sector = Object.values(@json($monitor->monitors_in_same_sector()));
            let not_allowed_monitors;
            not_allowed_monitors = swap_to_monitors.filter((monitor)=>{
                return monitor.order_sector_id == order_sector;
            }).map((monitor) => { return monitor.order_sector_id });

            $('#swap_to_filter option').each(function(){
                $(this).show();
                if(not_allowed_monitors.includes(+$(this).val())){
                    $(this).hide();
                }
            })
        }
        $(document).ready(function(){
            $('#swap_from_filter').on('change', function(){
                $('#swap_to_filter').prop('disabled', false)
                let current_order_sector = $(this).val()
                filter_swapped_monitors(current_order_sector);
                $('#swap_to_filter').selectpicker('destroy').selectpicker({});
            })
            $('#swapMonitorSector .check-empty-input').on('change', function(){
                let flag = true;
                $('#swapMonitorSector .check-empty-input').each(function() {
                    if (!$(this).is('div')){
                        if($(this).prop('required') && $(this).val() == ''){
                            flag = false;
                            return;
                        }
                    }
                });
                $('#submit-swapMonitorSector-btn').prop('disabled', !flag)
            })

            $('form').on('submit', function(e){
                let sendBtn = $('#submit-swapMonitorSector-btn');
                sendBtn.empty();
                sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
                sendBtn.prop('disabled', true)
            })
        });
    </script>
@endpush