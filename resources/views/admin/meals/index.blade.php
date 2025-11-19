@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

    {{-- add new question_type --}}
    <x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle"
                  :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions"
                  :filterColumns="$filterColumns" :showAddButton="false"  :canAllColumns="$can_all_columns">
        <x-slot name="filters">
            @include('admin.meals.components.filters')
        </x-slot>
    </x-crud-model>

@endsection

@push('after-scripts')
    @vite(['resources/js/bootstrap.js'])
    <script>
        $(document).ready(function () {
            localStorage.setItem('goBackHrefMealPreparation',location.href);

            $('#meal-filter-btn').click(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "GET",
                    url: "{{ route('meals.datatable') }}",
                    data: {
                        period: $('#period_filter').val(),
                        day: $('#day_filter').val(),
                        meal_status: $('#meal_status_filter').val(),
                        // current_stage: $('#current_stage_filter').val(),
                        sector: $('#sector_filter').val(),

                    },
                    dataType: "json",
                    success: function (response, jqXHR, xhr) {
                        window.mealsDatatable.ajax.reload();
                    },
                    error: function (response, jqXHR, xhr) {
                        Toast.fire({
                            icon: "error",
                            title: "{{ trans('translation.something went wrong') }}"
                        });
                    },
                });
            });
            $('#meal-reset-btn').click(function() {
                $('.selectpicker').selectpicker('deselectAll');
                window.mealsDatatable.ajax.reload();
            });

            window.Echo.channel('ModelCRUD-changes').listen('.Meal-changes',function(data) {
                window.mealsDatatable.ajax.reload();
            });

        }); // end document rready

        function changeSelectPickerMeal(select) {
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
