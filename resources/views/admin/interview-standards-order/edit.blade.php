@extends('layouts.master')
@section('title', trans('translation.interview-standard-orders'))

@section('content')

@push('styles')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SelectPicker -->
<link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
<!-- Sweet Alert -->
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

@endpush
<x-breadcrumb title="{{ $order->code }}">
    <li class="breadcrumb-item"><a href="{{ route('order-interviews.index') }}">{{ trans('translation.order-interviews') }}</a></li>
</x-breadcrumb>
<div class="card">
    <div class="card-body">
        <div class="row">
            <x-row-info id="id" label="{{ trans('translation.facility-owner-name') }}">{{$order->user->name ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.facility-name') }}">{{ $order->facility->name ?? '-'}}</x-row-info>

            <x-row-info id="id" label="{{ trans('translation.code') }}">{{$order->code}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.organization-name') }}">{{$order->organization->name}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.service-name') }}">{{$order->organization_service->service_name}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.user-name') }}">{{$order->user->name ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.facility-name') }}">{{ $order->facility->name ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.facility-user-phone') }}">{{ $order->user->phone  ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.facility-user-national_id') }}"> {{ $order->user->national_id  ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.registration-number') }}">{{ $order->facility->registration_number  ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.version-date') }}">{{ $order->facility->version_date . ' (' . \Carbon\Carbon::parse($order->facility->version_date)->diffForHumans() .')'   ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.license') }}">{{ $order->facility->license ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.license-expired') }}">{{ $order->facility->license_expired ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.capacity') }}">{{ $order->facility->capacity ?? '-'}}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.tax-certificate') }}">{{ $order->facility->tax_certificate ?? '-'}}</x-row-info>
            <!-- <x-row-info id="id" label="{{ trans('translation.chefs-number') }}">{{ $order->facility->chefs_number ?? '-'}}</x-row-info> -->
            <x-row-info id="id" label="{{ trans('translation.kitchen-space') }}">{{ $order->facility->kitchen_space ?? '-' }}</x-row-info>
            <x-row-info id="id" label="{{ trans('translation.employee-number') }}">{{ $order->facility->employee_number ?? '-' }}</x-row-info>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">{{trans('translation.order-interview')}}</div>
        {{-- <ul class="nav nav-pills custom-hover-nav-tabs  d-flex justify-content-center">
            {{-- tabs --}
            @component('components.nav-pills.pills', ['id' => 'questions', 'icon' => 'ri-question-line'])
            @endcomponent
            @component('components.nav-pills.pills', ['id' => 'info', 'icon' => 'ri-information-line'])
            @endcomponent
        </ul> --}}
    </div>
    <div class="card-body">
        <!-- <div class="tab-content"> -->

            {{-- معلومات مالك المنشأة --}}
            @component('components.nav-pills.tab-pane', ['id' => 'questions', 'title' => 'interview'])
            <form id="addNewInterview" action="{{route('order-interviews.update-scores',$order->id)}}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{$order->id}}">
                @forelse ($order_interviews as $order_interview)
                <input type="hidden" name="max_scores[{{$order_interview->id}}]" value="{{$order_interview->max_score}}">
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label for="input-{{$order_interview->id}}" class="form-label">{{$order_interview->name ?? '-'}}
                            <span class="text-danger">*</span>
                        </label>
                        <span class="text-muted fs-xs mb-0 mx-2 ">{{trans('translation.max-score')." ".$order_interview->max_score}} </span>
                        <p class="text-muted " style="font-size: 12px;;">{!! $order_interview->interview_standard->description !!}</p>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-select" id="input-{{$order_interview->id}}" name="scores[{{$order_interview->id}}]" required>
                            @for ($i = 0; $i <= $order_interview->max_score; $i++)
                                <option value="{{$i}}" {{$i == $order_interview->score ? 'selected':''}} >{{$i}}</option>
                                @endfor
                        </select>
                    </div>

                </div>

                @empty

                @endforelse
                <div class="row mb-3">
                    {{-- <div class="col-lg-3">
                        <label for="input-interview-bonus" class="form-label">{{trans('translation.interview-bonus')}}
                        </label>
                        <span class="text-muted fs-xs mb-0 mx-2 ">({{trans('translation.optional')}}) </span>
                    </div>
                    <div class="col-lg-9">
                        <input type="number" class="form-select" id="input-interview-bonus" min="0" name="bonus" value="{{$order->bonus}}" placeholder="{{trans('translation.interview-bonus')}}">
                    </div> --}}
                </div>
                <div class="text-end">
                    <button class="btn btn-secondary px-5 mx-2" type="button" onclick="goBack()" id="backButton">{{ trans('translation.back') }}</button>
                    <button type="button" id="submitButton" class="btn btn-primary">{{ trans('translation.submit')}} </button>
                </div>
            </form>
            @endcomponent

            {{-- معلومات المنشأة --}}
            {{--@component('components.nav-pills.tab-pane', ['id' => 'info', 'title' => 'facility-info'])
            <div class="col-6">

            </div>

            @endcomponent --}}
        <!-- </div> -->


    </div>
</div>
@push('after-scripts')
{{-- save tab state --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = JSON.parse(sessionStorage.getItem('interview'))?.tab;
        // if it was null we will set it to first one
        if (activeTab == null) {
            openTab(document.getElementById('questions-tab'));
        } else {
            openTab(document.getElementById(`${activeTab}-tab`))
        }
    });

    const setActiveTab = (tab) => {
        let state = JSON.parse(sessionStorage.getItem('interview'))
        state = {
            ...state,
            tab: tab
        };
        sessionStorage.setItem('interview', JSON.stringify(state));
    }

    const openTab = (elem) => {
        elem.click();
    }

    function goBack() {
        location.href = localStorage.getItem('goBackHref');
    }

    $(document).ready(function() {
        $(document.body).on('click', '#submitButton', function(e) {
            let submitBtn = $(this);
            Swal.fire(window.confirmUpdatePopupSetup).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.closest('form').submit();
                }
            });
        })
    })
</script>
@endpush
@endsection