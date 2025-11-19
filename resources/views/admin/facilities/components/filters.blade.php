<div class="row">
    {{-- <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{ trans('translation.user') }}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" multiple name="user_id_filter" id="user_id_filter" data-actions-box="true"
            placeholder="{{ trans('translation.choose-user') }}" data-live-search="true">
            @foreach ($users->unique('name') as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div> --}}
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{ trans('translation.service') }}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" multiple name="service_id_filter" id="service_id_filter" data-actions-box="true"
            placeholder="{{ trans('translation.choose-service') }}" data-live-search="true">
            @foreach ($services as $service)
                <option class="col-user" value="{{ $service->id }}">{{ $service->name }}
                </option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-md-3 col-sm-12 mb-3">
        <h6>
            {{ trans('translation.organization') }}
        </h6>
        <!-- Select2 -->
        <select class="form-control selectpicker" multiple name="organization_id_filter" id="organization_id_filter"
            data-actions-box="true" placeholder="{{trans('translation.choose-organization') }}" data-live-search="true">
            @foreach ($organizations as $organization)
                <option class="col-user" value="{{ $organization->id }}">
                    {{ $organization->name_ar }}
                </option>
            @endforeach
        </select>
        <!-- End Select2 -->
    </div>
    <div class="col-lg-3 col-sm-12 my-auto">
        <button class="btn btn-primary" id="facility-filter-btn">{{ trans('translation.filter') }}</button>
        <button class="btn btn-secondary" id="facility-reset-btn">{{ trans('translation.reset') }}</button>
    </div>
</div>
