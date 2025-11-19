{{-- @component('components.section-header', ['title' => 'meal-preparation', 'hide_button'=>'true'])@endcomponent

<div class="tab-pane show active" id="custom-hover-meals">
    <ul class="nav nav-pills nav-custom-outline nav-primary mb-3" role="tablist">
        @foreach($tabs = [
            [ 'name'=> 'food-weights', 'description' => trans('translation.setup food weight')],
            [ 'name'=> 'menus', 'description' => trans('translation.menu')],
            [ 'name'=> 'stages', 'description' => trans('translation.stages')],
            [ 'name'=> 'meals', 'description' => trans('translation.meals')],
            ] as $column )
        <li class="nav-item">
            <a class="nav-link" id="border-tab-{{$column['name']}}" data-bs-toggle="tab" href="#border-nav-{{$column['name']}}" role="tab" onclick="setActiveTab('{{$column['name']}}', 'meals')">{{++$loop->index . ' - ' . $column['description']}}</a>
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
--}}
<a class="btn btn-primary" href="{{ route('meal-preparation.show',$organization->id) }}">تحضير الوجبات</a>
