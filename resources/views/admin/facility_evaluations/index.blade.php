@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- index  --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions" :filterColumns="$filterColumns">
    <x-slot name="filters">
        @include('admin.facility_evaluations.components.filters')
    </x-slot>
</x-crud-model>

@endsection
