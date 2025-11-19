@component('modals.delete-modal-template',['modalName'=>'MonitorSector', 'modalRoute'=>'monitor-order-sectors', 'modalRouteId'=>$monitor->id])
    <input type="hidden" name="monitor_id" value="{{$monitor->id}}">
    @component('components.inputs.select-input',['columnName'=>'order_sector[]','col'=>'12','margin'=>'mb-3', 'is_multiple'=>'multiple', 'modelItem'=>$monitor, 'columnOptions' => ['order_sector[]' => $monitor->assigned_sectors()->toArray()]])@endcomponent
@endcomponent