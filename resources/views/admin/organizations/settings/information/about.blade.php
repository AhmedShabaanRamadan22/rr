@component('components.nav-pills.tab-pane', ['id' => $column['name'], 'padding' => 'p-1'])
    @component('admin.organizations.components.edit-organization-form', ['organization' => $organization])
        <div class="row mb-3">
            <div class="col">
                <x-custom-ckeditor columnName="about-us">
                    {{ $organization->about_us }}
                </x-custom-ckeditor>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <x-custom-ckeditor columnName="policies">
                    {{ $organization->policies }}
                </x-custom-ckeditor>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button class="btn btn-primary col-6">{{ trans('translation.update') }}</button>
            </div>
        </div>
    @endcomponent
@endcomponent