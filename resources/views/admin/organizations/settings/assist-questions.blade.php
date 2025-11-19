@extends('layouts.master')
@section('title', __('Assist question'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush
@section('content')


 <x-question-component
    :title="trans('translation.assist-question')"
    :questionableId="$organization->assist_question->id"
    questionableType="AssistQuestion"
    subRoute="organizations"
    :organization="$organization"
    />

@include('admin.organizations.modals.sort-assist-questions-arrangment')
@endsection
{{-- // TODO: need to be generalized  --}}

@push('after-scripts')
    <script>
        function goBack() {
            location.href=localStorage.getItem('goBackOrganizationSettingsHref');
        }
    </script>
@endpush
