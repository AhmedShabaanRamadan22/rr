@extends('admin.organizations.settings.layout.organization-settings')
@section('settings-content')
    <div class=" d-flex justify-content-center">
        <ul class="nav nav-pills custom-hover-nav-tabs">
            @foreach($tabs = [
                [ 'name'=> 'about', 'icon' => 'ri-information-line'],
                [ 'name'=> 'info', 'icon' => 'ri-profile-line'],
                [ 'name'=> 'settings', 'icon' => 'ri-settings-3-line'],
                [ 'name'=> 'national-address', 'icon' => 'mdi mdi-office-building-marker-outline'],
                [ 'name'=> 'news', 'icon' => 'ri-newspaper-line'],
                // [ 'name'=> 'statistics', 'icon' => 'ri-numbers-line'],
                ] as $column )
                @component('components.nav-pills.pills', ['id' =>$column['name'], 'icon' => $column['icon'], 'parent' => 'information'])@endcomponent
            @endforeach
        </ul>
    </div>

    <div class="tab-content">
        @foreach($tabs as $column)
            @include('admin.organizations.settings.information.' . $column['name'])
        @endforeach
    </div>

@endsection
    
@section('modals')
    @include('admin.organizations.modals.add-news')
    
@endsection
    
@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeInformationTab = JSON.parse(localStorage.getItem('information'))?.tab;
            // if it was null we will set it to first one

            checkNullTap(activeInformationTab, 'about-tab', `${activeInformationTab}-tab`)

        });

        const checkNullTap = (sessionTap, defaultTap, clickTap) => {
            if (sessionTap == null) {
                openTab(document.getElementById(defaultTap));
            } else {
                openTab(document.getElementById(clickTap))
            }
        }
        const openTab = (elem) => {
            elem?.click();
        }
        const setActiveTab = (tab, parent) => {
            let state = JSON.parse(localStorage.getItem(parent))
            state = {
                ...state,
                tab: tab
            };
            localStorage.setItem(parent, JSON.stringify(state));
        }
        $('#inputLogo').change(function() {
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" ||
                    ext == "jpg")) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#img').attr('src', '/assets/no_preview.png');
            }
        });
    </script>
@endpush