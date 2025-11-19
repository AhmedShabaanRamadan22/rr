@extends('layouts.master')
@section('title', __('Section'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush
@section('content')

<x-question-component
    :title="$section->name"
    :questionableId="$section->id"
    questionableType="Section"
    subRoute="forms"
    :organization="$section->form->organization()"
    />

@endsection
@include('admin.forms.modals.sort-questions-arrangment')
