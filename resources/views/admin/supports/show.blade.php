@extends('layouts.master')
@section('title', __('Support'))

@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

@endpush
@section('content')
    @php
        $i = 0;
                $disabled = (in_array($support->status_id, $closed_statuses)) ? 'disabled' : '';
                if($disabled != ''){
                    $html= "<span class='badge ' style='background:" . $support->status->color . "' >" . $support->status->name . "</span>";
                }else{
                    $html = '<select class="selectpicker status-select w-25"' . $disabled . 'name="support_id" style="background:' . $support->status->color . '" data-status-id="' . $support->status_id . '" data-support-id="' . $support->id . '" onchange="changeSelectPicker(this)">';
                        foreach ($statuses as $status) {
                            $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
                            $html .= '<option value="' . $status->id . '" ' . ($status->id == $support->status->id ? 'selected' : '') . ' ' . $span . ' >' . $status->name . '</option>';
                        }
                        $html .= "</select>";
                }
                     // dd($disabled);
    @endphp

        <!-- start page title -->
    <x-breadcrumb title="{{ $support->type_name  . ' / ' .  $support->order_sector->sector->label}}">
        <li class="breadcrumb-item"><a href="{{ route('supports.index') }}">{{ trans('translation.support') }}</a></li>
    </x-breadcrumb>
    <!-- end page title -->

    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <ul class="nav nav-pills custom-hover-nav-tabs">
                        @foreach($tabs = ['support'=>'mdi mdi-truck-delivery-outline','attachment'=>'ri-file-text-line','assists'=>'ri-hotel-line'] as $key => $icon )
                            @component('components.nav-pills.pills', ['id' =>$key, 'icon' => $icon])@endcomponent
                        @endforeach
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="text-end p-0 p-md-4">
                            <a>{!! $html !!}</a>
                            <a target="_blank"
                                class="btn btn-outline-primary on-default mx-2"
                                href="{{ (route('admin.supports.report', $support->uuid ?? fakeUuid())) }}"
                                ><i class="mdi mdi-file-document-outline"></i> {{trans('translation.download-support-report')}}
                            </a>
                        </div>
                        @foreach($tabs as $key => $icon)
                            @include('admin.supports.tabs.'.$key.'-tab')
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <button class="btn btn-secondary px-5 mx-2" type="button" onclick="goBack()" id="backButton">{{ trans('translation.back') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.supports.modals.add-assists')
    @include('admin.supports.modals.edit-assists')
    @push('after-scripts')
        @vite(['resources/js/bootstrap.js'])
        <!-- SelectPicker -->
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/apexcharts-pie.init.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="{{ URL::asset('build/js/chartSettings.js') }}"></script>
        <script>
            // reload the page then fire the toast
            if (localStorage.getItem('reloadPending') != null) {
                let msg = localStorage.getItem('reloadPending');
                localStorage.removeItem('reloadPending');
                // Display the toast after the reload
                Toast.fire({
                    icon: "success",
                    title: msg
                });
            }
        </script>
        <script>
            $(document).ready(function() {
                $('.selectPicker').selectpicker({
                    width: '100%',
                });
            });
            function changeSelectPicker(select) {
                var select = $(select);
                Swal.fire(window.confirmChangeStatusPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ url('admin/supports-status') }}",
                            data: {
                                status_id: select.val(),
                                support_id: select.attr('data-support-id')
                            },
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {
                                window.location.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                            },
                            error:function(response, jqXHR, xhr) {
                                window.location.reload();
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.You dont have permission') }}"
                                });
                            },
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

            function goBack() {
                location.href=localStorage.getItem('goBackHref');
            }

            document.addEventListener('DOMContentLoaded', function() {
                const activeTab = JSON.parse(sessionStorage.getItem('support'))?.tab;
                if (activeTab == null) {
                    openTab(document.getElementById('support-tab'));
                } else {
                    openTab(document.getElementById(`${activeTab}-tab`));
                }
            });

            const setActiveTab = (tab) => {
                let state = JSON.parse(sessionStorage.getItem('support'));
                state = {
                    ...state,
                    tab: tab
                };
                sessionStorage.setItem('support', JSON.stringify(state));
            };

            const openTab = (elem) => {
                elem.click();
            };

            $(document).ready(function() {
                let newOptions = {
                    series: [{{$support->delivered_quantity}}, {{$support->quantity - $support->delivered_quantity }}],
                    labels: ["{{trans('translation.given-quantity')}}", "{{trans('translation.remaining-quantity')}}"],
                };
                window['supports_pie'].updateOptions(newOptions, true)

                let assistant_representer = @json($assist_options['assistant_id']);
                let monitors = @json($assist_options['monitors']);
                let subtext_options = @json($assist_subtext_options['monitors']);
                console.log(subtext_options)
                let current_assistant = $('#assist_from_filter').val()
                $('#assist_from_filter').on('change', function(e){
                    if(!(current_assistant != 0 && $(this).val() != 0)){
                        $('#assistant_id_filter').empty();
                        $('#assistant_id_filter').selectpicker('destroy');
                        $.each($(this).val() == 0 ? assistant_representer : monitors, function(id,name){
                            $('#assistant_id_filter').append($('<option>', {
                                value: id,
                                text: name,
                                'data-subtext': ' ' + subtext_options[id]
                            }))
                        });
                        $('#assistant_id_filter').selectpicker();
                        current_assistant = $(this).val()
                    }
                })
                $('.cancel-assist').on('click', function(){
                    let support_id = "{{$support->id}}"
                    let assist_id = $(this).attr('data-assist-id')
                    Swal
                        .fire(window.confirmCancelAssistWithNotePopupSetup).then((result) => {
                        if (result.isConfirmed) {
                            const noteText = result.value.note??null;
                            if(noteText != null){
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    url: '{{ url("admin/cancel-assist") }}',
                                    data: {
                                        assist_id: assist_id,
                                        note:noteText,
                                        support_id:support_id
                                    },
                                    dataType: "json",
                                    success: function(response, jqXHR, xhr) {
                                        localStorage.setItem('reloadPending', response.message);
                                        window.location.reload();
                                        setLoading(false);
                                    },
                                    error:function(response, jqXHR, xhr) {
                                        setLoading(false);
                                        Toast.fire({
                                            icon: "error",
                                            title: response.responseJSON.message ??
                                                "{{ trans('translation.something went wrong') }}"
                                        });
                                    },
                                });
                            }
                        }
                    });
                })
                let submitted_percentage = {
                    label: $('#given-quantity').prev().text(),
                    value: $('#submitted_quantity').text() / $('#total_quantity').text() * 100
                };
                let unsubmitted_percentage = {
                    label: $('#support-quantity').prev().text(),
                    value: 100 - submitted_percentage.value
                };

                // var chartDonutupdatingColors = "";
                // chartDonutupdatingColors = getChartColorsArray("support_chart");
                // if (chartDonutupdatingColors) {
                //     var options = {
                //         series: [submitted_percentage.value, unsubmitted_percentage.value],
                //         labels: [submitted_percentage.label, unsubmitted_percentage.label],
                //         chart: {
                //             height: 200,
                //             type: 'donut',
                //             toolbar: {
                //                 show: true,
                //             }
                //         },
                //         dataLabels: {
                //             enabled: false
                //         },
                //         legend: {
                //             position: 'bottom',
                //         },
                //         toolbar: {
                //             show: true,
                //             offsetX: 0,
                //             offsetY: 0,
                //             tools: {
                //                 download: true,
                //                 selection: true,
                //                 zoom: true,
                //                 zoomin: true,
                //                 zoomout: true,
                //                 pan: true,
                //                 reset: true | '<img src="/static/icons/reset.png" width="20">',
                //                 customIcons: []
                //             },
                //             export: {
                //                 csv: {
                //                     filename: '{{ $support->order_sector->sector->label }}',
                //                     columnDelimiter: ',',
                //                     headerCategory: 'category',
                //                     headerValue: 'value',
                //                     dateFormatter(timestamp) {
                //                         return new Date(timestamp).toDateString()
                //                     }
                //                 },
                //                 svg: {
                //                     filename: '{{ $support->order_sector->sector->label }}',
                //                 },
                //                 png: {
                //                     filename: '{{ $support->order_sector->sector->label }}',
                //                 }
                //             },
                //             autoSelected: 'zoom'
                //         },

                //         colors: chartDonutupdatingColors
                //     };

                //     if (chartDonutupdatingchart != "")
                //         chartDonutupdatingchart.destroy();
                //     chartDonutupdatingchart = new ApexCharts(document.querySelector("#support_chart"), options);
                //     chartDonutupdatingchart.render();
                // }
            }); // end document ready
        </script>
    @endpush
@endsection
