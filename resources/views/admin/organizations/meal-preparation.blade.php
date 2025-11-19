@extends('layouts.master')
@section('title', $pageTitle .' '. $organization->name)
@push('styles')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush
@section('content')

<x-breadcrumb :title="$pageTitle">
    <li class="breadcrumb-item"><a href="{{ route('organizations.edit',$organization->id) }}">{{ $organization->name }}</a>
    </li>

</x-breadcrumb>

<!-- ROW-2 -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">

                @component('components.section-header', ['title' => 'meal-preparation', 'hide_button'=>'true'])@endcomponent

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



            </div>
            <div class="card-footer text-center">
            <button class="btn btn-secondary px-5 mx-auto" type="button" onclick="goBack()"
                                    id="backButton">{{ trans('translation.back') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Modals Add -->
@include('admin.organizations.modals.add-food-weights')
@include('admin.organizations.modals.add-menu')
@include('admin.organizations.modals.add-stage')
@include('admin.organizations.modals.add-meal-by-nationality')


<!-- Modals Edit -->
@include('admin.organizations.modals.edit-food-weight')
@include('admin.organizations.modals.edit-nationality-organizations')

<!-- Modals Sort -->
@include('admin.organizations.modals.sort-organization-stages')

@vite(['resources/js/bootstrap.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        localStorage.setItem('goBackHrefMealPreparation',location.href);
        const activeMealsTab = JSON.parse(localStorage.getItem('meals'))?.tab;
        // if it was null we will set it to first one
        checkNullTap(activeMealsTab, 'border-tab-food-weights', `border-tab-${activeMealsTab}`)

        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true))
                .DataTable()
                .columns.adjust()
                .responsive.recalc();
        });


        window.Echo.channel('ModelCRUD-changes').listen('.Meal-changes',function(data) {
            window.mealDatatable.ajax.reload();
        });
    });

    function goBack() {
        location.href=localStorage.getItem('goBackHref');
    }

    const checkNullTap = (sessionTap, defaultTap, clickTap) => {
        if (sessionTap == null) {
            openTab(document.getElementById(defaultTap));
        } else {
            openTab(document.getElementById(clickTap))
        }
    }

    const setActiveTab = (tab, parent) => {
        let state = JSON.parse(localStorage.getItem(parent))
        state = {
            ...state,
            tab: tab
        };
        localStorage.setItem(parent, JSON.stringify(state));

    }


    const openTab = (elem) => {
        elem?.click();
    }
</script>
@push('after-script')
@endpush

@endsection
