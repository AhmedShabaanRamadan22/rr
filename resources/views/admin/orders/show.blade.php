@extends('layouts.master')
@section('title', __('Order Type'))

@push('styles')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .icon-bigger{
            font-size: 20px;
        }
    </style>
@endpush
@section('content')
    <!-- start page title -->
    <x-breadcrumb :pageTitle="$order->id" title="{{ $order->facility->name }}-{{$order->organization_service->service_name}}">
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">{{ trans('translation.orders') }}</a></li>
    </x-breadcrumb>
    <!-- end page title -->

    <div class="row">
        <div class="col-xxl-12">
            <div class="card">

                <div class="card-header d-flex justify-content-center">
                    <ul class="nav nav-pills custom-hover-nav-tabs">
                        {{-- tabs --}}
                        @component('components.nav-pills.pills', ['id' => "orders", 'icon'=>"mdi mdi-order-bool-descending-variant"])@endcomponent
                        @component('components.nav-pills.pills', ['id' => "order-user", 'icon'=>"mdi mdi-account-outline"])@endcomponent
                        @component('components.nav-pills.pills', ['id' => "audits", 'icon'=>"ri-time-line"])@endcomponent
                        {{-- @component('components.nav-pills.pills', ['id' => "question", 'icon'=>"mdi mdi-chat-question-outline"])@endcomponent --}}
                        {{-- @component('components.nav-pills.pills', ['id' => "contract", 'icon'=>"ri-file-text-line"])@endcomponent --}}
                    </ul>
                </div>

                <div class="card-body">
                    <div class="row ">
                        <div class="col text-start">
                                <select class="form-control selectpicker status-select " {{ $is_atatus_disabled }} name="service_id" style="background:'{{ $order->status->color }}" data-status-id="{{ $order->status_id }}" data-order-id="{{ $order->id }}" onchange="changeSelectPicker(this)"  >';
                                @foreach ($order_statuses as $status) 
                                    <option 
                                        value="{{ $status->id }}" 
                                        data-note-required="{{$status->is_note_required}}"
                                        data-content="<span class='badge ' style='background:{{ $status->color }}' >{{ $status->name }} </span>"
                                        {{ ($status->id == $order->status->id ? 'selected' : '') }}   
                                    >
                                        {{ $status->name }}
                                    </option>
                                    
                                @endforeach
                                </select>
                        </div>
                        <div class="col text-end">
                             <a target="_blank"
                                 class="btn btn-outline-primary m-1 on-default "
                                 href="{{ (route('admin.orders.report', $order->uuid ?? fakeUuid())) }}"
                                 ><i class="mdi mdi-file-document-outline"></i> {{trans('translation.download-order-report')}}
                             </a>
                        </div>
                    </div>
                    <div class="tab-content">

                        {{-- معلومات الطلب --}}
                        @component('components.nav-pills.tab-pane', ['id' => "orders", 'title' => 'order-info'])
                            @component('admin.orders.components.orders-tab', ['order'=>$order, 'progress_statuses'=>$progress_statuses])@endcomponent

                            @component('components.data-row', ['id'=>'note', 'label_col'=>'col-md-2 col-6', 'content_col'=>'col-md-10 col-6'])
                                    @component('components.notes', ['id'=>'order', 'model'=>$order])@endcomponent
                                @endcomponent
                        @endcomponent

                        {{-- معلومات المستخدم --}}
                        @component('components.nav-pills.tab-pane', ['id' => "order-user", 'title' => 'order-user-info'])
                            @component('admin.orders.components.user-tab', ['order'=>$order])@endcomponent
                        @endcomponent
                        
                        {{-- سجل العمليات --}}
                        @component('components.nav-pills.tab-pane', ['id' => 'audits', 'title' => 'order-facility-audits'])
                            @component('components.audits', ['audits' => $audits])
                            @endcomponent
                            {{-- @component('admin.facilities.components.audits-tab', ['audits' => $audits])@endcomponent --}}
                        @endcomponent
                        {{-- الأسئلة --}}
                        {{-- @component('components.nav-pills.tab-pane', ['id' => "question", 'title' => 'question'])
                            @component('admin.orders.components.questions-tab', ['order'=>$order])@endcomponent
                        @endcomponent --}}

                        {{-- الموظفين --}}
                        {{-- @component('components.nav-pills.tab-pane', ['id' => "contract", 'title' => 'contract'])
                            @component('admin.orders.components.contract-tab', ['order'=>$order])@endcomponent
                        @endcomponent --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        @include('admin.orders.components.order-user-row')
    </div> --}}
    <!-- end row order-user-row -->

    {{-- <div class="row">
        @include('admin.orders.components.contract-facility-row')
    </div> --}}
    <!-- end contract-facility-row -->

    {{-- <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="mb-0">
                        <a href="{{route('facilities.show',$order->facility_id)}}">
                            {{trans('translation.facility-info')}} ->
                        </a>

                    </h5>
                </div>
            </div>
        </div>
    </div> --}}
    @push('after-scripts')
        <!-- Latest compiled and minified JavaScript -->
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const activeTab = JSON.parse(sessionStorage.getItem('orders'))?.tab;
                // if it was null we will set it to first one
                if (activeTab == null) {
                    openTab(document.getElementById('orders-tab'));
                } else {
                    openTab(document.getElementById(`${activeTab}-tab`))
                }
            });

            const setActiveTab = (tab) => {
                let state = JSON.parse(sessionStorage.getItem('orders'))
                state = {
                    ...state,
                    tab: tab
                };
                sessionStorage.setItem('orders', JSON.stringify(state));
            }

            const openTab = (elem) => {
                elem.click();
            }
        </script>
        <script>
            $(document).ready(function() {

                $('.selectPicker').selectpicker({});


            }); // end document ready
            function changeSelectPicker(select) {

                var select = $(select);
                let is_note_required = select.find(":selected").attr('data-note-required');
                let popupSetup = is_note_required ? window.confirmChangeStatusWithNotePopupSetup : window
                    .confirmChangeStatusPopupSetup;
                Swal.fire(popupSetup).then((result) => {
                        if (result.isConfirmed) {
                            const noteText = result.value ?? null;
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: '{{ url("admin/orders-status") }}',
                                data: {
                                    status_id: select.val(),
                                    order_id: select.attr('data-order-id'),
                                    note: noteText

                                },
                                dataType: "json",
                                success: function(response, jqXHR, xhr) {
                                    console.log(response);
                                    window.location.reload();
                                    if (xhr.status === 200) {
                                    }
                                }
                            });
                        } else {
                            select.selectpicker('destroy');
                            select.val(select.attr('data-status-id'));
                            select.selectpicker({
                                width: '100%',
                            });
                        }
                    });
            }
        </script>
    @endpush
@endsection
