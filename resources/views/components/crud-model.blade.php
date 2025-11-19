@push('styles')
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <!-- Sweet Alert -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css" rel="stylesheet" type="text/css" />
    <style>
        .icon-bigger {
            font-size: 22px;
        }
    </style>
@endpush
<x-breadcrumb :pageTitle="$pageTitle">

</x-breadcrumb>

<!-- add new question_type -->
@isset($filters)
<div class="row">
    <div class="col-md-12  col-xl-12">
        <div class="card ">
            <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                <h3 class="card-title">{{ trans('translation.' . str_replace('_', '-', $tableName)) }}</h3>
            </div>
            <div class="card-body">
                {{$filters}}
            </div>
        </div>
    </div>
</div>
@endisset

<div class="row">
    <div class="col-md-12  col-xl-12">
        <div class="card card-collapsed">
            <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                <div class="row">
                    <h3 class="card-title col-6">{{ trans('translation.all-' . str_replace('_', '-', $tableName)) }} </h3>
                    <div class="col-6 text-end">
                        <div class="d-flex gap-2 text-end justify-content-end">
                            @if($showAddButton??true)
                            <button class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="{{ '#add' . $tableName }}"><i
                                    class="mdi mdi-plus align-baseline me-1"></i>
                                {{ trans('translation.add_new_' . $tableName) }}</button>
                            @endif
                            @if($showSortButton??false)
                            <button class="btn btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="{{ '#sort' . $tableName }}"><i
                                    class="mdi mdi-sort align-baseline me-1"></i>
                                {{ trans('translation.sort_' . $tableName) }}</button>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- <h3 class="card-title">{{ trans('translation.add_new_' . $tableName) }}</h3>
                <div class="card-options">
                    <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                </div> --}}
            </div>
            <div class="card-body">
                {{-- <form class="form-horizontal" action="{{ route((str_replace('_','-',$tableName)).'.store') }}" method="post">
                    @csrf
                    <div class=" row "> --}}
                <!-- <div class="col-md-3">
                            <label for="inputName" class=" form-label">{{ trans('translation.question_type_name') }}</label>
                            <input type="text" class="form-control" id="inputName" name="name" placeholder="{{ 'Question-Type Name' }}" required>
                        </div> -->

                {{--
                            @switch($columnInput)
                                @case('text')
                                    <x-crud-add-text-input :columnName="$key" />
                                    @break

                                @case('switch')
                                    <x-crud-add-switch-input :columnName="$key" />
                                    @break

                                @case('color')
                                    <x-crud-add-color-input :columnName="$key" />
                                    @break

                                @default
                            @endswitch
                            --}}
                <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputEsnad" class="form-label">{{ trans('transaltion.has-option') }}</label>
                                <input class=" d-none" type="checkbox" role="switch" name="has_option" checked value="0">
                                <div class="form-group">
                                    <div class="form-check form-switch form-switch-md">
                                        <input class="form-check-input" type="checkbox" role="switch" id="has_option" name="has_option" checked value="1">
                                    </div>
                                </div>
                            </div>
                        </div> -->
                {{-- <div class="col-md-3 align-self-center">
                            <button class="btn btn-primary" type="submit" id="submitButton">{{ trans('translation.add') }}</button>
                        </div> --}}
                {{-- </div>
                </form> --}}
                <x-data-table id="{{ str_replace('_', '-', $tableName) }}-datatable" :columns="$columns" />

            </div>
        </div>
        {{-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans('translation.'.$tableName) }}</h3>
            </div>
            <div class="card-body">
                <x-data-table id="{{(str_replace('_','-',$tableName))}}-datatable" :columns="$columns" />

            </div>
        </div> --}}
    </div>
</div>

@component('modals.add-modal-template', [
    'modalName' => $tableName,
    'modalRoute' => str_replace('_', '-', $tableName),
])
    @forelse ($columnInputs as $key => $columnInput)
        @component('components.inputs.' . $columnInput . '-input', [
            'columnName' => $key,
            'col' => '6',
            'columnOptions' => $columnOptions ?? null,
            'columnSubtextOptions' => $columnSubtextOptions ?? null,
            'hiddenValue' => $hiddenValue ?? null,
            'name' => $columnInput == 'file' ? 'attachments[' . ($attachmentLabels?->id??0) . ']' : $key,
            'is_required' => $notRequiredColumns[$key] ?? null,

        ])
        @endcomponent
    @endforeach
@endcomponent

@if($showSortButton??false)
    @component('modals.sort-modal-template', [
        'modalName' => $tableName,
        'modalRoute' => str_replace('_', '-', $tableName),
    ])
        @component('components.sort-model', [
            'modalName' => $modalName = $tableName ,
        'modalRoute' => str_replace('_', '-', $modalName) . '.datatable',
        ])
        @endcomponent
    @endcomponent
@endif



@push('after-scripts')
    <!-- SelectPicker -->
    <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            localStorage.setItem('goBackHref',location.href);

            function calculateBadgeWidth(text) {
                let tempSpan = document.createElement('span');
                tempSpan.innerText = text;
                tempSpan.style.visibility = 'hidden';
                tempSpan.style.position = 'absolute';
                document.body.appendChild(tempSpan);

                let width = tempSpan.offsetWidth;
                document.body.removeChild(tempSpan);

                return width;
            }

            function permissionFormat(data){
                if(data.permissions.length == 0){
                    return "{{trans('translation.no-data')}}"
                }
                let permissions = data.permissions;
                let html = '<div class="text-start" style="width: 100px !important;">';
                let i = 1;
                let totalWidth = 0;
                permissions.forEach(permission => {
                    let badgeWidth = calculateBadgeWidth(permission.name);
                    totalWidth += badgeWidth;
                    if (totalWidth > window.innerWidth * 0.7) {
                        html += '<br>';
                        totalWidth = badgeWidth;
                    }
                    html += '<span class="badge bg-primary mx-1 mb-2">' + permission.name + '</span>';
                })
                html += '</div>';
                return html;
            }
            {{ 'window.' . $tableName . 'Datatable' }} = $("#{{ str_replace('_', '-', $tableName) }}-datatable")
                .DataTable({
                    "ajax": {
                        "url": "{{ route(str_replace('_', '-', $tableName) . '.datatable') }}",
                        "type": "{{$datatableAjaxType ?? 'GET'}}",
                        "data": function(d) {
                            @isset($filterColumns)
                            @forelse($filterColumns as $key => $filter)
                                {!!'d.' . $key . ' = ' . '$("#'.$key.'_filter").val();'!!}
                            @empty
                            @endforelse
                            @endisset
                            @if(isset($canAllColumns) && $canAllColumns)
                            d.all_columns = true;
                            @endif
                            d.per_page = d.length; // Number of records per page (based on DataTables settings)
                            d.page = Math.ceil(d.start / d.length) + 1; // Calculate the current page
                            d.isPaginated = true;
                            @isset($datatableAjaxType)
                            d._token = '{{ csrf_token() }}';
                            @endif
                            // d.id = id.val();
                            //  d.name = data.val();
                        },
                    },
                    language: datatable_localized,
                    rowId: 'id',
                    serverSide: true,
                    "drawCallback": function(settings) {
                        $('.selectpicker').selectpicker({
                            width: '100%',
                        });
                    },
                    stateSave: true,
                    columns: [
                        @if(in_array('collapser', $columns))
                        {
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        },
                        @endif
                        @foreach ($columns as $key => $column)
                            @if ($key == 'id')
                            {
                                data: 'id',
                                render:  (data, type, row, meta) => { return ++meta.row; }
                            },
                            @elseif ($key == 'collapser')
                                @continue;
                            @else
                            {
                                data: '{{ $key }}',
                                className: ' text-center align-middle',
                            },
                            @endif
                        @endforeach
                    ],
                    buttons: ['excel','csv'],
                    lengthMenu: [10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
                    dom: 'lfritpB',
                    "ordering": false,
                });

            $('body').on('click', '.delete' + '{{$tableName}}', function() {
                var model_id = $(this).attr('data-model-id');
                Swal
                    .fire(window.deleteWarningPopupSetup).then((result) => {
                        if (result.isConfirmed) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: 'DELETE',
                                url: "{{ url('/' . str_replace('_', '-', $tableName)) }}" + "/" +
                                    model_id,
                                success: function(response) {
                                    {{ 'window.' . $tableName . 'Datatable' }}.ajax.reload();
                                    Toast.fire({
                                        icon: "success",
                                        title: response.message ??
                                            "{{ trans('translation.delete-successfully') }}"
                                    });
                                },
                                error: function(response, jqXHR, responseJSON) {
                                    Toast.fire({
                                        icon: "error",
                                        title: response.responseJSON.message ??
                                            "{{ trans('translation.something went wrong') }}"
                                    });
                                },
                            });
                        }
                    });
            })

                {{ $tableName . 'Datatable' }}.on('click', 'td.dt-control', function(e){
                let tr = e.target.closest('tr')
                let row = {{ $tableName . 'Datatable' }}.row(tr)
                if(row.child.isShown()){
                    row.child.hide()
                }
                else{
                    row.child(permissionFormat(row.data())).show()
                }
            })
        });

    </script>
@endpush
