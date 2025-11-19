@component('modals.add-modal-template',['modalName'=>'tickets'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
@foreach ($columnTickets as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
    @if ($column == "attachments")
    @component('components.inputs.file-input',['attachment_label'=>$ticket_attachment,'col'=>'6','margin'=> 'mb-3', 'name' => 'attachments[' . $ticket_attachment->id . '][]', 'multiple' => 'true']) @endcomponent
    {{-- @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3', "columnOptions" => ($optionTickets??null), 'columnSubtextOptions'=> ($subtextOptionTickets??null)]) @endcomponent --}}
        @continue
    @endif
    @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3', "columnOptions" => ($optionTickets??null), 'columnSubtextOptions'=> ($subtextOptionTickets??null)]) @endcomponent
@endforeach
@push('after-scripts')
    <script>
        $(document).ready(function () {
            $('#order_sector_filter').on('change', function () {
                let current_order_sector = $(this).val();
                let order_sectors = @json($ticket_subtext_columns['order_sectors']) ;

                let selectedOrderSector = order_sectors.find(sector => sector.order_sector_id == current_order_sector);

                if (selectedOrderSector) {
                    let users = selectedOrderSector.users;

                    $('#monitor_filter').empty();
                    $('#monitor_filter').selectpicker('destroy');
                    $.each(users, function(index, user) {
                        $('#monitor_filter').append($('<option>', {
                            value: user.id,
                            text: user.name
                        }));
                    });
                    $('#monitor_filter').selectpicker();
                } else {
                    console.log('Selected order sector does not exist.');
                    $('#monitor_filter').empty();
                }
            });
        });
    </script>
@endpush
@endcomponent


