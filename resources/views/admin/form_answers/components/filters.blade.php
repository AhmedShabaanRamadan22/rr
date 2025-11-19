<div class="row">
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>{{ trans('translation.date') }}</h6>
        <select class="form-control selectpicker" name="date_filter" id="date_filter" data-live-search="true" data-actions-box="true" multiple
                placeholder="{{ trans('translation.choose-date') }}">
            @foreach($filters['dates'] as $date)
                <option value="{{ $date }}">{{ $date }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 col-sm-12 mb-3">
        <h6>{{ trans('translation.filled_by') }}</h6>
        <select class="form-control selectpicker" name="monitor_filter" id="monitor_filter" data-live-search="true" data-actions-box="true"
                multiple placeholder="{{ trans('translation.choose-monitor') }}">
            @foreach($filters['monitors'] as $monitor)
                <option value="{{ $monitor->id }}">{{ $monitor->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 col-sm-12 mb-3">
        <h6>{{ trans('translation.sectors') }}</h6>
        <select class="form-control selectpicker" name="sector_filter" id="sector_filter" data-live-search="true" data-actions-box="true"
                multiple placeholder="{{ trans('translation.choose-sector') }}">
            @foreach($filters['sectors'] as $sector)
                <option value="{{ $sector->id }}">
                    {{ ($sector->label ?? '-') }} - {{ trans('translation.sight') }} ( {{ ($sector->sight ?? '-')  }} )
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 col-sm-12 mb-3">
        <h6>{{ trans('translation.nationalities') }}</h6>
        <select class="form-control selectpicker" name="nationality_filter" id="nationality_filter" data-live-search="true" data-actions-box="true"
                multiple placeholder="{{ trans('translation.choose-nationality') }}">
            @foreach($filters['nationalities'] as $nationality)
                <option value="{{ $nationality->id }}">
                    {{ ($nationality->name ?? '-') }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="date-filter-btn">{{ trans('translation.filter') }}</button>
        <button class="btn btn-secondary" id="date-reset-btn">{{ trans('translation.reset') }}</button>
    </div>
</div>
