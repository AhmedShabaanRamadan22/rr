@component('modals.add-modal-template',['modalName'=>'forms'])
    @component('components.inputs.text-input',['columnName'=>'name','col'=>'6','margin'=>'mb-3']) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'code','col'=>'6','margin'=>'mb-3']) @endcomponent
    @component('components.inputs.text-input',['columnName'=>'description','col'=>'6','margin'=>'mb-3']) @endcomponent
    @component('components.inputs.select-input',['columnName'=>'display','col'=>'6','margin'=>'mb-3','columnOptions'=>$columnOptions]) @endcomponent
    @component('components.inputs.select-input',['columnName'=>'submissions_times','col'=>'6','margin'=>'mb-3','columnOptions'=>$columnOptions]) @endcomponent
    @component('components.inputs.select-input',['columnName'=>'submissions_by','col'=>'6','margin'=>'mb-3','columnOptions'=>$columnOptions]) @endcomponent
    <div class="col-md-6 mb-3">
        <h6>
            {{ trans('translation.organization') }}
            <span class="text-danger">*</span>
        </h6>
        <select class="form-control selectpicker check-empty-input" name="organization_id" id="organization_id" data-actions-box="true" data-live-search="true" title="{{trans('translation.choose-one')}}" required>
            @forelse ($organizations as $organization)
                <option value="{{$organization->id}}">{{$organization->name}}</option>
            @empty
                
            @endforelse
        </select>

    </div>
    <div class="col-md-6 mb-3">
        <h6>
            {{ trans('translation.organization-service') }}
            <span class="text-danger">*</span>
        </h6>
        <select class="form-control selectpicker  check-empty-input" disabled name="organization_service_id" id="organization_service_id" data-actions-box="true" data-live-search="true" title="{{trans('translation.choose-organization-first')}}" required>
            <option value="" hidden>{{trans('translation.choose-organization-first')}}</option>
            @php
                $previous_item = null;
            @endphp
            @foreach ($organization_services as $index => $organization_service)
                @if (!isset($previous_item))
                    <optgroup label="{{ $organization_service->organization->name_ar }}">
                    @elseif(isset($previous_item) && $organization_service->organization_id != $previous_item->organization_id)
                    </optgroup>
                    <optgroup label="{{ $organization_service->organization->name_ar }}">
                @endif
                <option value="{{ $organization_service->id }}" data-organization-id="{{$organization_service->organization->id}}">
                    {{ $organization_service->service->name }}
                </option>
                @php
                    $previous_item = $organization_service;
                @endphp
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <h6>
            {{ trans('translation.category-organization') }}
            <span class="text-danger">*</span>
        </h6>
        <select class="form-control selectpicker  check-empty-input" disabled name="organization_category_id" id="organization_category_id" data-actions-box="true" data-live-search="true" title="{{trans('translation.choose-organization-first')}}" required>
            <option value="" hidden>{{trans('translation.choose-organization-first')}}</option>
            @php
                $previous_item = null;
            @endphp
            @foreach ($organization_categories as $index => $organization_category)
                @if (!isset($previous_item))
                    <optgroup label="{{ $organization_category->organization->name_ar }}">
                    @elseif(isset($previous_item) && $organization_category->organization_id != $previous_item->organization_id)
                    </optgroup>
                    <optgroup label="{{ $organization_category->organization->name_ar }}">
                @endif
                <option value="{{ $organization_category->id }}" data-organization-id="{{$organization_category->organization->id}}">
                    {{ $organization_category->category->name }}
                </option>
                @php
                    $previous_item = $organization_category;
                @endphp
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label for="is_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
        <div class="col-md-9">
            <div class="form-group">
                <div class="form-check form-switch form-switch-md">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" checked value="1">
                    <input type="hidden" name="is_visible" value="0">
                </div>
            </div>
        </div>
    </div>
@endcomponent