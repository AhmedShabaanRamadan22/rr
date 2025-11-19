@extends('layouts.master')
@section('title', __('Section'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush
@section('content')


 <x-question-component
    :title="$organization_stage->stage_bank->name"
    :questionableId="$organization_stage->id"
    questionableType="OrganizationStage"
    subRoute="organizations"
    :organization="$organization_stage->organization"
    />

@endsection
{{-- // TODO: need to be generalized  --}}
@include('admin.organization_stages.modals.sort-questions-arrangment')
