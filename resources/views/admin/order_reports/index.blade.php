@extends('layouts.master')
@section('title', trans('translation.order-reports'))

@section('content')

<x-crud-model tableName="order_reports" :columns="$columns" :columnInputs="$columnInputs" pageTitle="order-reports" :columnOptions="null" :showAddButton="false"/> 

@endsection
