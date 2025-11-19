@component('modals.add-modal-template',['modalName'=>'water-support', 'modalRoute' => 'supports'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
<input type="hidden" name="operation_type_id" value="{{ 3 }}">
@foreach ($columnInputSupports as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @if ($column == "attachments")
    @component('components.inputs.file-input',['attachment_label'=>$support_attachment,'col'=>'6','margin'=> 'mb-3', 'name' => 'attachments[' . $support_attachment->id . '][]', 'multiple' => 'true']) @endcomponent
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3', "columnOptions" => ($columnWaterSupports??null), 'columnSubtextOptions'=> ($subtextOptionSupports??null)]) @endcomponent
@endforeach
@push('after-scripts')
    <script>
        // $(document).ready(function () {
            $('#addwater-support #order_sector_filter').change( function () {
                let current_order_sector = $(this).val();
                let order_sectors = @json($support_subtext_columns['order_sectors']) ;
                let selectedOrderSector = order_sectors.find(sector => sector.order_sector_id == current_order_sector);
                if (selectedOrderSector) {
                    let users = selectedOrderSector.users;

                    $('#addwater-support #monitor_filter').empty();
                    $.each(users, function(index, user) {
                        $('#addwater-support #monitor_filter').append($('<option>', {
                            value: user.id,
                            text: user.name
                        }));
                    });
                    $('#addwater-support #monitor_filter').selectpicker('destroy');
                    $('#addwater-support #monitor_filter').selectpicker();
                } else {
                    console.log('Selected order sector does not exist.');
                    $('#addwater-support #monitor_filter').empty();
                }
            });
        // });
    </script>
@endpush
@endcomponent

