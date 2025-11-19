@component('components.section-header', ['title' => 'sectors-setup', 'hide_button'=>'true'])@endcomponent

<div class="tab-pane show active" id="custom-hover-sectors">
    <ul class="nav nav-pills nav-custom-outline nav-primary mb-3" role="tablist">
        @foreach($tabs = [
            [ 'name'=> 'classifications', 'description' => trans('translation.classifications')],
            [ 'name'=> 'nationalities', 'description' => trans('translation.nationalities')],
            [ 'name'=> 'sectors', 'description' => trans('translation.sectors')],
            ] as $column )
        <li class="nav-item">
            <a class="nav-link" id="border-tab-{{$column['name']}}" data-bs-toggle="tab" href="#border-nav-{{$column['name']}}" role="tab" onclick="setActiveTab('{{$column['name']}}', 'sectors')">{{++$loop->index . ' - ' . $column['description']}}</a>
        </li>
        @endforeach
    </ul>
    <!-- Tab panes -->
    <div class="tab-content text-muted">
        @foreach ($tabs as $column)
            <div class="tab-pane" id="border-nav-{{$column['name']}}" role="tabpanel">
                @include('admin.organizations.components.bills-content.' . $column['name'] . '-organization-content')
            </div>
        @endforeach
    </div>
</div>
