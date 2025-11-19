@extends('layouts.master')
@section('title', __('Ticket'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

@endpush
@section('content')
    @php
        $i = 0;
                $disabled = ($ticket->status_id == $closed_status || $ticket->status_id == $false_status ) ? ( $canChangeTicketStatus ? '' : 'disabled ') : '';
                if($disabled){
                    $html= "<span class='badge ' style='background:" . $ticket->status->color . "' >" . $ticket->status->name . "</span>";
                }
                // dd($disabled);
                $html = '<select class="selectpicker status-select w-25"' . $disabled . 'name="service_id" style="background:' . $ticket->status->color . '" data-status-id="' . $ticket->status_id . '" data-ticket-id="' . $ticket->id . '" onchange="changeSelectPicker(this)">';
                foreach ($statuses as $status) {
                    $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
                    $html .= '<option value="' . $status->id . '" ' . ($status->id == $ticket->status->id ? 'selected' : '') . ' ' . $span . ' >' . $status->name . '</option>';
                }
                $html .= "</select>";
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.ticket-code') }}: {{ $ticket->code }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ $ticket->id }}</li>
                    <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">{{ trans('translation.tickets') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('root') }}">{{ trans('translation.home') }}</a></li>
                </ol>
            </div>

        </div>
    </div>
</div>

    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-body">
                    @if (in_array($ticket->status_id, $progress_statuses->pluck('id')->toArray()))
                    <div class="position-relative mx-lg-4  mt-5">
                        <div class="progress" style="height: 1px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex position-absolute translate-middle start-50 col-12 justify-content-between mb-4">
                            {{-- {{dd($statuses)}} --}}
                            @foreach($progress_statuses as $status)
                            <div class="mt-4 text-center">
                                <button class="btn btn-sm mb-2 rounded-pill {{ ($status->id < $ticket->status_id || ($status->id == $ticket->status_id && $status->id == $closed_status)) ? 'btn-primary' : 'btn-light'}} {{ $status->id == $ticket->status_id ? 'border border-primary border-2' : ''}}" style="width: 2rem; height:2rem;">{{++$loop->index}}</button>
                                <div>{{$status->name}}</div>
                            </div>
                            @if ($status->id < $ticket->status_id)
                                @php
                                    $i = $loop->index;
                                @endphp
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @else
                        <div class="alert alert-primary alert-dismissible alert-label-icon label-arrow fade show my-3" role="alert">
                            <i class="ri-error-warning-line label-icon"></i>{{trans('translation.ticket-status')}}: <strong> {{$ticket->status->name}}</strong>
                        </div>
                    @endif
                    <div class="row px-lg-4 px-2" style="margin-top: 70px;">
                        <div class="text-end mb-3">
                            <a>  {!! $html !!}</a>
                            <a class="btn btn-outline-primary" target="_blank" href="{{route('admin.ticket.report', $ticket->uuid ?? fakeUuid())}}">{{trans('translation.download')}}</a>
                        </div>

                    <div class=" col-lg-6 " >
                            <div class="row">
                                <div class="d-flex flex-lg-row flex-column justify-content-between">
                                    <div>
                                        <div class="text-secondary" style="font-size: 16px; font-weight: bold; ">{{ trans('translation.ticket-info') }}</div>
                                        <div class="text-secondary" style="font-size: 16px; font-weight: bold; ">{{$ticket->code}}</div>
                                    </div>
                                    <div class="badge p-2" style="background: {{$ticket->reason_danger->danger->color}}; max-height:25px">{{$ticket->reason_danger->reason->name}}</div>
                                </div>
                            </div>
                            <div class="p-3 row">
                                @component('components.data-row', ['id'=>'reporter-name']){{$ticket->user->name}}@endcomponent
                                @component('components.data-row', ['id'=>'label']){{$ticket->order_sector->sector->label}}@endcomponent
                                @component('components.data-row', ['id'=>'sight']){{$ticket->order_sector->sector->sight}}@endcomponent
                                @component('components.data-row', ['id'=>'monitor-name']){{implode(', ', $ticket->order_sector->monitors_name)}}@endcomponent
                                @component('components.data-row', ['id'=>'bravo-number']){{ ($ticket->user?->bravo?->number?? trans('translation.not-found')) }}@endcomponent
                                @component('components.data-row', ['id'=>'note'])
                                    @component('components.notes', ['id'=>'ticket', 'model'=>$ticket])@endcomponent
                                @endcomponent
                            </div>
                        </div>
                        <div class=" col-md-6 px-md-4">
                            <div class="">
                                <div class="text-secondary" style="font-size: 16px; font-weight: bold; ">{{ trans('translation.attachment') }}</div>
                            </div>
                            <div class="d-flex mt-4 row">
                                <div class="card-body">
                                    <div id="carouselExampleIndicators" class="carousel slide carousel-dark" data-bs-ride="carousel" style="min-height: 400px; max-height: 400px">
                                        <div class="carousel-inner" role="listbox" style="">
                                            @forelse ($ticket->attachments as $attachment)
                                                <div class="carousel-item {{$loop->index == 0 ? 'active' : ''}}" style="min-height: 400px; max-height: 400px">
                                                    @if ($attachment->type == 'IMAGE')
                                                        <div style="height: 400px; overflow: auto;">
                                                            <img class="d-block img-fluid mx-auto rounded" src="{{$attachment->url}}" style="object-fit: contain; width: 100%;">
                                                        </div>
                                                    @endif
                                                    @if ($attachment->type == 'VIDEO')
                                                        <div style="height: 400px; overflow: auto;">
                                                            <video autoplay loop muted controls class="d-block img-fluid mx-auto rounded" src="{{$attachment->url}}" style="object-fit: contain; width: 100%;"></video>
                                                        </div>
                                                    @endif
                                                    <div class="carousel-caption text-white bg-light bg-opacity-75" style="left: 0%; right: 0%; padding-top: 0.5rem; padding-bottom: 0.5rem; bottom: 0">
                                                        <a href="{{$attachment->url}}" download="{{$ticket->code . '_' . $attachment->attachment_label->placeholder}}" class="btn btn-primary">{{trans('translation.download')}}</a>
                                                        <a href="{{$attachment->url}}" type="video/quicktime" target="_blank" class="btn btn-secondary">{{trans('translation.view')}}</a>
                                                    </div>
                                                </div>
                                            @empty
                                                <div>{{trans('translation.no-data')}}</div>
                                            @endforelse
                                        </div>
                                        @if ($ticket->attachments->isNotEmpty())
                                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">{{trans('translation.next')}}</span>
                                            </a>
                                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">{{trans('translation.previous')}}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <button class="btn btn-secondary px-5 mx-2" type="button" onclick="goBack()"
                                id="backButton">{{ trans('translation.back') }}</button>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div>
    </div>

    @push('after-scripts')
        <script>
            function goBack() {
                location.href=localStorage.getItem('goBackHref');
            }
            $(document).ready(function() {
                $('.progress-bar').css('width',{{$i}} / ({{count($progress_statuses) - 1}}) * 100 + '%');
            });

            $(document).ready(function() {
                $('.selectPicker').selectpicker({
                    width: '100%',
                });
            });

            function changeSelectPicker(select) {
                var select = $(select);
                Swal
                    .fire(window.confirmChangeStatusPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: '{{ url('admin/ticket-status') }}',
                            data: {
                                status_id: select.val(),
                                ticket_id: select.attr('data-ticket-id'),
                                old_status_id: select.attr('data-status-id')
                            },
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {
                                window.location.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Updated successfuly') }}"
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

        </script>
    @endpush
@endsection
