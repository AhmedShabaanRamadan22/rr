@extends('layouts.master')
@section('title', $pageTitle)
@section('content')
@include('admin.monitors.modals.edit-monitor', ['columnOptions' => $columnOptions ])

{{-- add new question_type --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :columnOptions="$columnOptions" :pageTitle="$pageTitle"/>

@endsection
@push('after-scripts')
    <script>
        $(document).ready(function(){
            localStorage.setItem('goBackHref',location.href);
        })
    </script>
@endpush