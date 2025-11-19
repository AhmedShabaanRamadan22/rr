@extends('layouts.master')
@section('title', __('Stages'))

@section('content')
    @push('styles')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SelectPicker -->
        <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
        <!-- Sweet Alert -->
        <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>

    @endpush
<x-breadcrumb :pageTitle="__('Stages')">
    {{-- organization_id --}}
    <li class="breadcrumb-item"><a href="{{ route('organizations.edit',$meal->sector->nationality_organization->organization_id) }}">{{ trans('translation.Meals') }}</a></li>

</x-breadcrumb>

@php

    $value = in_array($meal->status_id, [$done_status]);

    $html = '';

    if ($value) {
        $html .= "<span class='badge' style='background:" . $meal->status->color . "' >" . $meal->status->name . "</span>";
    }else{

    $html .= '<div><select class="selectpicker status-select w-25" name="meal_id" style="background:' . $meal->status->color . '" data-status-id="' . $meal->status_id . '" data-meal-id="' . $meal->id . '" onchange="changeSelectPicker(this)" >';

    foreach ($statuses as $status) {
        $span = " data-content=\"<span class='badge' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
        $selected = $status->id == $meal->status->id ? 'selected' : '';
        $html .= '<option value="' . $status->id . '" ' . $selected . ' ' . $span . ' >' . $status->name . '</option>';
    }

    $html .= "</select></div>";
    }

@endphp
{{-- add new question_type --}}
{{-- <x-crud-model tableName="meal_organization_stages" :columns="$columns" :pageTitle="$pageTitle" :columnInputs="false" /> --}}
<div class="card">
    <div class="card-header  text-end">
        <a target="_blank"
            class="btn btn-outline-primary on-default"
            href="{{ (route('admin.meal.report', $meal->uuid ?? fakeUuid())) }}"
            ><i class="mdi mdi-file-document-outline"></i> {{trans('translation.download-meal-report')}}
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-10">
                @component('components.data-row', ['id'=>'sector-providor']){{$meal->sector->label . ' - ' . $order_sector?->order->facility->name . ' - ' . $meal->sector->classification->organization->name}}@endcomponent
                @component('components.data-row', ['id'=>'menu']){{$meal->food_weights->implode('food_name',' | ')}}@endcomponent
                @component('components.data-row', ['id'=>'period']){{$meal->period->name}}@endcomponent
                    <x-row-info id="id" label="{{ trans('translation.status') }}">{!! $html !!}</x-row-info>
            </div>
            <div class="col-lg-2">
                <h5>{{ trans('translation.supports') }}</h5>
                @forelse ($meal->supports as $support)
                    <a href="{{ route('supports.show',$support->id) }}">{{$support->code}}</a>
                @empty
                    {{ trans('translation.no-supports') }}
                @endforelse
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
        <div class="row">
            <h3 class="card-title col-6">{{ trans('translation.all-stages') }} </h3>
        </div>
    </div>
    <div class="card-body">
        <x-data-table id="meal-organization-stages-datatable" :columns="$columns"/>
    </div>
    <div class="card-footer">
        <div class="text-center">
            <button class="btn btn-secondary px-5 mx-2" type="button" onclick="goBack()"
                id="backButton">{{ trans('translation.back') }}</button>
        </div>
    </div>
</div>

@push('after-scripts')
        <!-- SelectPicker -->
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script>
            localStorage.setItem('goBackHrefShow',location.href);

            function goBack() {
                location.href=localStorage.getItem('goBackHrefMealPreparation');
            }
            $(document).ready(function() {
                $('.selectPicker').selectpicker({
                    width: '100%',
                });
                window.organization_stages_datatable = $('#meal-organization-stages-datatable').DataTable({
                    "ajax": {
                        "url": "{{ route('meal-organization-stages.datatable') }}",
                        "data": function(d) {
                            d.organization_id = {{ $meal->sector->nationality_organization->organization_id }};
                            d.meal_id = {{ $meal->id }};
                        },

                    },
                    language: datatable_localized,
                    rowId: 'id',
                    "drawCallback": function(settings) {
                        $('.selectpicker').selectpicker({
                            width: '100%',
                        });
                    },
                    // 'createdRow': function(row, data, rowIndex) {
                    //     $(row).attr('data-id', data.id);
                    //     $(row).attr('data-fine', data.fine.name);
                    //     $(row).attr('data-description', data.description);
                    //     $(row).attr('data-price', data.price);
                    // },
                    'stateSave': true,
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    "columns": [{
                            "data": 'id',
                            render: (data, type, row, meta) => {
                                return ++meta.row;
                            }
                        },
                        {
                            "data": 'stage',
                        },
                        // {
                        //     "data": 'food-name',
                        // },
                        {
                            "data": 'status',
                        },
                        {
                            "data": 'done_by',
                        },
                        {
                            "data": 'done_at',
                        },
                        {
                            "data": 'duration',
                        },
                        {
                            "data": 'actual_duration',
                        },
                        {
                            "data": 'action',
                        },
                    ],
                    buttons: ['csv', 'excel'],
                    dom: 'lfritpB',
                    "ordering": false,
                });
            });

            function changeSelectPicker(select) {
                var select = $(select);
                Swal.fire(window.confirmChangeStatusPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ url('/meal-status') }}",
                            data: {
                                status_id: select.val(),
                                meal_id: select.attr('data-meal-id')
                            },
                            dataType: "json",
                            success: function(response) {
                                // Reload the page to reflect the updated status
                                location.reload();
                                // Optionally, show a success message
                                Toast.fire({
                                    icon: "success",
                                    title: response.message // Assuming the response contains a 'message' key
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle errors, optionally show an error message
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.You dont have permission') }}"
                                });
                            }
                        });
                    } else {
                        // If the user cancels, reset the select picker to its original value
                        select.selectpicker('val', select.attr('data-status-id'));
                    }
                });
            }
        </script>
    @endpush
@endsection
