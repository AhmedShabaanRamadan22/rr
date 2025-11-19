
<div class="row">
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.assignee')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="assignees_id" id="assignees_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-assignee')}}">
            @foreach ($assignees as $assignee)
            <option value="{{$assignee->id}}">{{$assignee->name}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.organization')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="organization_id" id="organizations_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-organization')}}">
            @foreach ($organizations as $organization)
            <option value="{{$organization->id}}">{{$organization->name_ar}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.order-status')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="order_status" id="status_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-status')}}">
            @foreach ($statuses as $status)
            <option value="{{$status->id}}">{{$status->name}}</option>
            @endforeach


        </select>
        <!-- End Select2 -->
    </div>
    
    <div class="col-lg-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="order-filter-btn">{{trans('translation.filter')}}</button>
        <button class="btn btn-secondary" id="order-reset-btn">{{trans('translation.reset')}}</button>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {



    }); // end document ready
</script>
@endpush
