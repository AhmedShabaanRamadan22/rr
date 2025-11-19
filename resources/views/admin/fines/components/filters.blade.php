
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
    {{-- <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.sector')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="sector_id" id="sector_filter" multiple data-actions-box="true" placeholder="{{trans('translation.choose-sector')}}">
            @foreach ($filterColumns['sectors'] as $sector)
            <option value="{{$sector->id}}">{{$sector->label}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.user')}}
        </h6>
        <!-- Select3 -->
        <select class="form-control selectpicker" name="user_id" id="user_filter" multiple data-actions-box="true" placeholder="{{trans('translation.choose-user')}}">
            @foreach ($filterColumns['users'] as $user)
            <option value="{{$user->id}}">{{$user->name}}</option>
            @endforeach
        </select>
        <!-- End Select3 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.user')}}
        </h6>
        <!-- Select4 -->
        <select class="form-control selectpicker" name="fine_id" id="fine_filter" multiple data-actions-box="true" placeholder="{{trans('translation.choose-fine')}}">
            @foreach ($filterColumns['fine_names'] as $fine)
            <option value="{{$fine->id}}">{{$fine->name}}</option>
            @endforeach
        </select>
        <!-- End Select4 -->
    </div> --}}

    @endhasrole

    <div class="col-lg-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="fine-filter-btn">{{trans('translation.filter')}}</button>
        <button class="btn btn-secondary" id="fine-reset-btn">{{trans('translation.reset')}}</button>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {

    }); // end document ready
</script>
@endpush
