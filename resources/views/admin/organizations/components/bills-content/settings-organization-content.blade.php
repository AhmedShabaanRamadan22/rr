@component('components.nav-pills.tab-pane', ['id' => $column['name'], 'padding' => 'p-1'])
@component('admin.organizations.components.edit-organization-form', ['organization' => $organization])
        @component('components.section-header', ['title' => 'settings', 'hide_button'=>'true'])@endcomponent
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class='row'>
                        <div class="col">
                            <label for="primary_color"
                                class="form-label">{{ trans('translation.primary-color') }}</label>
                            <input type="color" class="form-control form-control-color" id="primary_color"
                                value="{{ $organization->primary_color }}" name="primary_color">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="inputEsnad" class="form-label">{{ trans('translation.has_esnad') }}</label>
                        <input class=" d-none" type="checkbox" role="switch" name="has_esnad" checked value="0">
                        <div class="form-group">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" role="switch" id="has_esnad"
                                    name="has_esnad" {{ $organization->has_esnad == 1 ? 'checked' : '' }}
                                    value="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="close_registeration" class="form-label">{{ trans('translation.close_registeration') }}</label>
                        <input class=" d-none" type="checkbox" role="switch" name="close_registeration" checked value="0">
                        <div class="form-group">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" role="switch" id="close_registeration"
                                    name="close_registeration" {{ $organization->close_registeration == 1 ? 'checked' : '' }}
                                    value="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="form-group">
                        <label for="close_order" class="form-label">{{ trans('translation.close_order') }}</label>
                        <input class=" d-none" type="checkbox" role="switch" name="close_order" checked value="0">
                        <div class="form-group">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" role="switch" id="close_order"
                                    name="close_order" {{ $organization->close_order == 1 ? 'checked' : '' }}
                                    value="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center pt-3">
                    <button class="btn btn-primary col-6">{{ trans('translation.update') }}</button>
                </div>
            </div>
@endcomponent
@endcomponent