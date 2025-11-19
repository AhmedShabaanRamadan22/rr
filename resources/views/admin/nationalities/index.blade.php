@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- add new question_type --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions"/> 

@endsection
