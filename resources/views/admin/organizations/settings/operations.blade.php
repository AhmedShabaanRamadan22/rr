@extends('admin.organizations.settings.layout.organization-settings')
@section('settings-content')
    <!-- Content -->
    @component('components.section-header', ['title' => 'operations', 'hide_button'=>'true'])@endcomponent

    <div class="tab-pane show active" id="custom-hover-sectors">
        <ul class="nav nav-pills nav-custom-outline nav-primary mb-3" role="tablist">
            @foreach($tabs = [
                [ 'name'=> 'tickets', 'description' => trans('translation.tickets')],
                [ 'name'=> 'fines', 'description' => trans('translation.fines')],
                [ 'name'=> 'supports', 'description' => trans('translation.supports')],
                ] as $column )
            <li class="nav-item">
                <a class="nav-link" id="border-tab-{{$column['name']}}" data-bs-toggle="tab" href="#border-nav-{{$column['name']}}" role="tab" onclick="setActiveTab('{{$column['name']}}', 'operations')">{{++$loop->index . ' - ' . $column['description']}}</a>
            </li>
            @endforeach
        </ul>
        <!-- Tab panes -->
        <div class="tab-content text-muted">
            @foreach ($tabs as $column)
                <div class="tab-pane" id="border-nav-{{$column['name']}}" role="tabpanel">
                    @include('admin.organizations.settings.operations.' . $column['name'])
                </div>
            @endforeach
        </div>
    </div>
    <!-- End Content -->


@endsection

@section('modals')
    @include('admin.organizations.modals.add-ticket')
    @include('admin.organizations.modals.add-support-water')
    @include('admin.organizations.modals.add-support-food')
    @include('admin.organizations.modals.add-fine')

@endsection

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeOperationTab = JSON.parse(localStorage.getItem('operations'))?.tab;
            // if it was null we will set it to first one

            checkNullTap(activeOperationTab, 'border-tab-tickets', `border-tab-${activeOperationTab}`)

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
