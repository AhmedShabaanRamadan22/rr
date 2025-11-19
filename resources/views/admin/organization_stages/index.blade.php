@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- index  --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions"/>

@endsection

