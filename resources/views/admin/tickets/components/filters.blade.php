
<div class="row">
    @hasrole(['superadmin','admin'])
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.organization')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="organization_id" id="organization_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-organization')}}">
            @foreach ($organizations as $organization)
            <option value="{{$organization->id}}">{{$organization->name}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    @endhasrole
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.reason')}}
        </h6>
        <!-- Select2 -->
        {{-- <select class="form-control selectpicker" name="reason_id" id="reason_filter" multiple data-actions-box="true" placeholder="{{trans('translation.choose-reason')}}">
            @foreach ($ticket_reasons as $ticket_reason)
            <option value="{{$ticket_reason->id}}">{{$ticket_reason->name}}</option>
            @endforeach
        </select> --}}
        <select id="reason_filter" class=" mb-3 selectpicker form-control" data-live-search="true" placeholder="{{trans('translation.choose-reason')}}" multiple name="reason_id">
            @php
                $previous_item = null;
            @endphp
            @foreach ($reason_dangers as $index => $reason_danger)
                @if(!isset($previous_item))
                    <optgroup label="{{ $reason_danger->organization->name }}">
                @elseif(isset($previous_item) && $reason_danger->organization_id != $previous_item->organization_id)
                    </optgroup>
                    <optgroup label="{{ $reason_danger->organization->name }}">
                @endif
                <option value="{{ $reason_danger->id }}" data-organization-id="{{ $reason_danger->organization->id??0 }}">
                    {{ $reason_danger->name }}
                </option>
                @php
                    $previous_item = $reason_danger;
                @endphp
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.danger')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="danger_id" id="danger_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-danger')}}">
            @foreach ($dangers as $danger)
            <option value="{{$danger->id}}">{{$danger->level}}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.sector')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="sector_id" id="sector_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.choose-sector')}}">
            @foreach ($sectors as $sector)
            <option value="{{$sector->id}}" data-subtext="{{$sector->classification->organization->name}}" showSubtext="true" data-organization-id="{{ $sector->classification->organization->id??0 }}"> {{($sector->label??'') . ' (' . trans('translation.sight') .' '. ($sector->sight??'') . ')'}} </option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{trans('translation.status')}}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" name="status" id="status_filter" data-live-search="true" multiple data-actions-box="true" placeholder="{{trans('translation.status')}}">
            @foreach ($statuses as $status)
            <option value="{{$status->id}}">{{$status->name}}</option>
            @endforeach


        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-lg-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="ticket-filter-btn">{{trans('translation.filter')}}</button>
        <button class="btn btn-secondary" id="ticket-reset-btn">{{trans('translation.reset')}}</button>
    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {


        $('#organization_filter').on('change',function(){
            let emptyReasonFlag = retrieveSelect('#reason_filter');
            let emptySectorFlag = retrieveSelect('#sector_filter');
        })

    }); // end document ready

    function retrieveSelect(selector){
        let organization_ids = $('#organization_filter').val();
        let emptyFlag = true
        $(selector + ' option').each(function(){
            $(this).hide();
            if( organization_ids.includes($(this).attr('data-organization-id'))){
                $(this).show();
                emptyFlag = false;
            }
        })

        if(emptyFlag){
            $(selector ).attr('title','{{trans("translation.no-data")}}');
            $(selector).prop('disabled',true);
            // $(selector).prop('required',false);
        }else{
            $(selector ).attr('title',"{{trans('translation.choose-one')}}");
            $(selector).prop('disabled',false);
            // $(selector).prop('required',true);

        }

        $(selector).selectpicker('destroy').selectpicker({});

        return emptyFlag;
    }
</script>
@endpush
