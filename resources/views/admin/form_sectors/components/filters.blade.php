<div class="row">
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>{{ trans('translation.date') }}</h6>
            <select class="form-control selectpicker" multiple name="date_filter" id="date_filter" data-live-search="true"
                    placeholder="{{ trans('translation.choose-date') }}">
                @forelse($filters['dates'] as $date)
                    <option value="{{ $date }}">{{ $date }}</option>
                
                @empty
                    <option disabled>{{ trans('translation.no-submitted-form') }}</option>
                @endforelse
            </select>

            
    </div>
    @component('components.inputs.time-input',['columnName'=>'start_time_submit_form','col'=> '3'])

    @endcomponent
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>{{ trans('translation.start-answering') }}</h6>
            <select class="form-control selectpicker" multiple name="has_start_filter" id="has_start_filter" data-live-search="true"
                    placeholder="{{ trans('translation.start-answering') }}">
                @forelse($filters['has_start'] as $key => $option)
                    <option value="{{ $key }}">{{ $option }}</option>
                @empty
                @endforelse
            </select>            
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>{{ trans('translation.answers-completed') }}</h6>
            <select class="form-control selectpicker" multiple name="has_completed_filter" id="has_completed_filter" data-live-search="true"
                    placeholder="{{ trans('translation.answers-completed') }}">
                @forelse($filters['has_completed'] as $key => $option)
                    <option value="{{ $key }}">{{ $option }}</option>
                @empty
                @endforelse
            </select>            
    </div>


    <div class="col-md-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="date-filter-btn">{{ trans('translation.filter') }}</button>
        <button class="btn btn-secondary" id="date-reset-btn">{{ trans('translation.reset') }}</button>
    </div>
</div>
