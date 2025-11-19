@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- add new question_type --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :showAddButton=false :filterColumns="$filterColumns"> 
    <x-slot name="filters">
        @include('admin.assists.components.filters')
    </x-slot>
</x-crud-model>
@endsection

@push('after-scripts')
<script>
    $(document).ready(function() {
        $('#assists-filter-btn').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{ route('assists.datatable') }}",
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    window.assistsDatatable.ajax.reload();
                },
                error: function(response, jqXHR, xhr) {
                    Toast.fire({
                        icon: "error",
                        title: "{{ trans('translation.something went wrong') }}"
                    });
                },
            });
        });
        $('#assists-reset-btn').click(function() {
            $('.selectpicker').selectpicker('deselectAll');
            window.assistsDatatable.ajax.reload();
        });
        

    }); // end document rready
</script>
@endpush
