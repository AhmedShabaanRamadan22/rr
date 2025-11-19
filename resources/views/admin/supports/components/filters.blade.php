
<div class="row">
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.type')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="periods_id" id="types_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-type')}}">
            @foreach ($types as $index => $value)
            <option value="{{$index}}" {{($type == $index)?'selected':''}}>{{$value}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.periods')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="periods_id" id="periods_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-period')}}">
            @foreach ($periods as $periods)
            <option value="{{$periods->id}}">{{$periods->name}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.sector')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="sector_id" id="sectors_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-sector')}}">
            @foreach ($sectors as $sector)
            <option value="{{$sector->id}}" data-subtext="{{$sector->classification->organization->name}}" showSubtext="true" data-organization-id="{{ $reason_danger->organization->id??0 }}"> {{($sector->label??'') . ' (' . trans('translation.sight') .' '. ($sector->sight??'') . ')'}} </option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.status')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="status" id="status_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-status')}}">
            @foreach ($statuses as $status)
            <option value="{{$status->id}}">{{$status->name}}</option>
            @endforeach


        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-lg-2 col-sm-12">
        <button class="btn btn-primary" id="support-filter-btn">{{trans('translation.filter')}}</button>
        <button class="btn btn-secondary" id="support-reset-btn">{{trans('translation.reset')}}</button>
    </div>
</div>

