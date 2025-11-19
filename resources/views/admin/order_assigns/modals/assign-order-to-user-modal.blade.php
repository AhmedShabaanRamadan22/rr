@component('modals.add-modal-template',['modalName'=>'assign-to-user','modalRoute'=>'admin.order-assigns',])
    <input type="hidden" name="order_id" value="" id="hidden_order_id">

    <h6>{{trans('translation.assignees')}}</h6>

    <select class="form-control selectPicker check-empty-input" name="user_ids[]" id="select_order_assignees" data-live-search="true" multiple data-actions-box="true" title="{{trans('translation.choose-assignees')}}">
        <!-- <option value="choose_one" disabled selected>{{trans('translation.choose-assignees')}}</option> -->
        @foreach ($users_assignable as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
    </select>


@endcomponent

@push('after-scripts')

<script>
     $(document).ready(function() {
        // set values when modal shown
        $('#addassign-to-user').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            let assignButton = $(e.relatedTarget);
            let assignRow = assignButton.closest('tr');
            let orderId = assignButton.attr('data-order-id');
            let assigneesIds = assignRow.attr('data-assginee-ids');
            
            $(e.currentTarget).find('#hidden_order_id').val(orderId);
            
            assigneesIds = assigneesIds ? assigneesIds.split(',').map(id => id.trim()) : [];
            let selectElement = $(e.currentTarget).find('#select_order_assignees');

            selectElement.find('option').each((index, assignee) => {
                let isSelected = assigneesIds.includes(assignee.value);
                $(assignee).prop('selected', isSelected);
            });      

            selectElement.selectpicker('destroy').selectpicker({
                width: '100%',
            }); 
        });

    });


</script>
@endpush
