@extends('layouts.master')
@section('title', __('Section'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush
@section('content')

{{-- {{(dd($organization_service->id))}} --}}
<x-question-component
    title="{{$organization_service->name}}"
    questionableId="{{$organization_service->id}}"
    :organization="$organization_service->organization"
    questionableType="OrganizationService"
    subRoute="organizations"
    />

@endsection
