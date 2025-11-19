@component('modals.add-modal-template',['modalName'=>'NewMonitorSector', 'modalRoute'=>'monitor-order-sectors'])
    <input type="hidden" name="monitor_id" value="{{$monitor->id}}">
    @component('components.inputs.select-input',['columnName'=>'order_sector[]','col'=>'12','margin'=>'mb-3', 'is_multiple'=>'multiple', 'modelItem'=>$monitor, 'columnOptions' => ['order_sector[]' => $monitor->unassigned_sectors()->toArray()]])@endcomponent
@endcomponent