@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- edit  --}}
<x-crud-model-edit :tableName="$tableName" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :modelItem="$modelItem" :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions"/>

@endsection
