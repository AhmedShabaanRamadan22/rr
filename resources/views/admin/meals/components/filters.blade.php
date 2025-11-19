<div class="row">
    @hasrole(['superadmin','admin'])
    @forelse($filterColumns as $key => $filterColumn)
        <div class="col-md-3 col-sm-12 mb-3">
            <h6>
                {{trans('translation.' . $key)}}
            </h6>
            <!-- Select1 -->
            <select class="form-control selectpicker" name="{{$key}}" id="{{str_replace('[]','',$key)}}_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-'. $key)}}">
                @forelse ($filterColumn as $key => $filter)
                <option value="{{($key)}}">{{($filter)}}</option>
                @empty
                @endforelse
            </select>
            <!-- End Select1 -->
        </div>
    @empty
    @endforelse
    @endhasrole

    <div class="col-lg-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="meal-filter-btn">{{trans('translation.filter')}}</button>
        <button class="btn btn-secondary" id="meal-reset-btn">{{trans('translation.reset')}}</button>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {

    }); // end document ready
</script>
@endpush
