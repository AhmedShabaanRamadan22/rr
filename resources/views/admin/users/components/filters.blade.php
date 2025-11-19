
<div class="row">
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.role-name')}}
        </h6>
        <!-- Select1 -->
        <select class="form-control selectpicker" multiple name="role_name_filter" id="role_name_filter" data-actions-box="true"
            placeholder="{{ trans('translation.choose-role')}}" data-live-search="true">
            @foreach ($columnOptions['all_roles'] as $key => $role)
                <option value="{{$key}}">{{$role}}</option>
            @endforeach
        </select>
        <!-- End Select1 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.organization-name')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="organization_id" id="organizations_filter" multiple data-actions-box="true" placeholder="{{trans('translation.choose-organization')}}">
            @foreach ($columnOptions['organizations'] as $key => $organization)
            <option value="{{$key}}">{{$organization}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-lg-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="user-filter-btn">{{trans('translation.filter')}}</button>
        <button class="btn btn-secondary" id="user-reset-btn">{{trans('translation.reset')}}</button>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {
        
    }); // end document ready
</script>
@endpush