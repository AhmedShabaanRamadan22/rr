@extends('admin.organizations.settings.layout.organization-settings')
@section('settings-content')
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

@endsection

@section('modals')
    @include('admin.organizations.modals.add-classification')
    @include('admin.organizations.modals.add-nationality')
    @include('admin.organizations.modals.add-sector')
    
@endsection

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeSectorTab = JSON.parse(localStorage.getItem('sectors'))?.tab;
            // if it was null we will set it to first one

            checkNullTap(activeSectorTab, 'border-tab-classifications', `border-tab-${activeSectorTab}`)

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
        
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust()
                        .responsive.recalc();
                });
    </script>
@endpush