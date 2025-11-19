@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- add new question_type --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions" :filterColumns="$filterColumns" :showAddButton="false">
    <x-slot name="filters">
        @include('admin.statuses.components.filters')
    </x-slot>
</x-crud-model>

@endsection
@push('after-scripts')
<script>
    $(document).ready(function() {
        localStorage.setItem('goBackHref', location.href);
        $('#type-filter-btn').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{ route('statuses.datatable') }}",
                data: {
                    type: $('#type_filter').val(),
                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    window.statusesDatatable.ajax.reload();
                },
                error: function(response, jqXHR, xhr) {
                    Toast.fire({
                        icon: "error",
                        title: "{{ trans('translation.something went wrong') }}"
                    });
                },
            });
        });
        $('#type-reset-btn').click(function() {
            $('.selectpicker').selectpicker('deselectAll');
            window.statusesDatatable.ajax.reload();
        });

    }); // end document rready
</script>
@endpush
