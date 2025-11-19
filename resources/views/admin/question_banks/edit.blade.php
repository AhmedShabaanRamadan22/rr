@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- add new question_type --}}
<x-crud-model-edit :tableName="$tableName" :columnInputs="$columnInputs" :columnOptions="$columnOptions" :pageTitle="$pageTitle" :modelItem="$modelItem"/>

@endsection
