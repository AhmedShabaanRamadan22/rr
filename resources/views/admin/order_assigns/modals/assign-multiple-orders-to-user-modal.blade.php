@component('modals.add-modal-template',['modalName'=>'assigns-to-user','modalRoute'=>'admin.orders-assigns',])
    <input type="hidden" name="order_ids" value="" id="hidden_order_ids">

    <h6>{{trans('translation.assignee')}}</h6>

    <select class="form-control selectPicker check-empty-input" name="user_id" id="select_order_assignee" data-live-search="true"  data-actions-box="true" title="{{trans('translation.choose-assignees')}}">
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
        $('#addassigns-to-user').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            // let assignButton = $(e.relatedTarget);
            // let assignRow = assignButton.closest('tr');
            let orderIds = [];
            window.datatable.rows('.selected').every(function(){
                orderIds.push(+this.node().getAttribute('data-id'));
            });
            
            $(e.currentTarget).find('#hidden_order_ids').val(JSON.stringify(orderIds));
            

        });

    });


</script>
@endpush
