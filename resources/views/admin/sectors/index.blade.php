@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- add new question_type --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions" :showAddButton="false" :filterColumns="$filterColumns">
    <x-slot name="filters">
        @include('admin.sectors.components.filters')
    </x-slot>
</x-crud-model>

@endsection

@push('after-scripts')
    <script>
        $('#sectors-reset-btn').click(function() {
            $('.selectpicker').selectpicker('deselectAll');
            window.sectorsDatatable.ajax.reload();
        });
        $('#sectors-filter-btn').click(function() {
            window.sectorsDatatable.ajax.reload();
        });
    </script>
@endpush