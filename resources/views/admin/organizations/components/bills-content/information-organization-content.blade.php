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
        @include('admin.organizations.components.bills-content.' . $column['name'] . '-organization-content')
    @endforeach
</div>