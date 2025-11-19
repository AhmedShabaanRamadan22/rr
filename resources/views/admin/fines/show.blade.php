@extends('layouts.master')
@section('title', __('Fine'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
@php
    $i = 0;
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ trans('translation.fine-code') }}: {{ $fine->code }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ $fine->id }}</li>
                    <li class="breadcrumb-item"><a href="{{ route('fines.index') }}">{{ trans('translation.fines') }}</a></li>
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
                    <div class="row p-4">
                        <div class=" col-6 " >
                            <div class="row">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="text-secondary" style="font-size: 16px; font-weight: bold; ">{{ trans('translation.fine-info') }}</div>
                                        <div class="text-secondary" style="font-size: 16px; font-weight: bold; ">{{$fine->code}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 row">   
                                <x-row-info id="reporter_name" label="{{ trans('translation.issuer-name') }}">{{$fine->user->name}}</x-row-info>
                                <x-row-info id="sector_Label" label="{{ trans('translation.label') }}">{{$fine->order_sector->sector->label}}</x-row-info>
                                <x-row-info id="sector_sight" label="{{ trans('translation.sight') }}">{{$fine->order_sector->sector->sight}}</x-row-info>
                                <x-row-info id="user_name" label="{{ trans('translation.monitor-name') }}">{{implode(', ', $fine->order_sector->monitors_name)}}</x-row-info>
                                <x-row-info id="bravo_number" label="{{ trans('translation.bravo-number') }}">{{$fine->order_sector->monitor_order_sector()->monitor->user->bravo_number ?? trans('translation.no-data')}}</x-row-info>
                                @component('components.data-row', ['id'=>'note'])
                                    @component('components.notes', ['id'=>'fine', 'model'=>$fine])@endcomponent
                                @endcomponent 
                            </div>
                        </div>
                        <div class=" col-6 px-4">
                            <div class="">
                                <div class="text-secondary" style="font-size: 16px; font-weight: bold; ">{{ trans('translation.attachment') }}</div>
                            </div>
                            <div class="d-flex mt-4 row">
                                <div class="card-body">
                                    <div id="carouselExampleIndicators" class="carousel slide carousel-dark" data-bs-ride="carousel">
                                        <div class="carousel-inner" role="listbox" style="height: 25rem">
                                            @foreach ($fine->attachments as $attachment)
                                                <div class="carousel-item {{$loop->index == 0 ? 'active' : ''}} h-100">
                                                    @if (($attachment->type == 'IMAGE'))
                                                    <img class="d-block img-fluid mx-auto object-fit-contain rounded" src="{{$attachment->url}}" style="">
                                                    @endif
                                                    @if (($attachment->type == 'VIDEO'))
                                                    <video autoplay loop muted controls class="d-block img-fluid mx-auto object-fit-contain rounded" src="{{$attachment->url}}"></video>
                                                    @endif
                                                    <div class="carousel-caption text-white bg-light bg-opacity-75" style="left: 0%; right: 0%; padding-top: 0.5rem; padding-bottom: 0.5rem; bottom: 0">
                                                        <a href="{{$attachment->url}}" download="{{$fine->code . '_' . $attachment->attachment_label->placeholder}}" class="btn btn-primary">{{trans('translation.download')}}</a>
                                                        <a href="{{$attachment->url}}" type="video/quicktime" target="_blank" class="btn btn-secondary">{{trans('translation.view')}}</a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">{{trans('translation.next')}}</span>
                                        </a>
                                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">{{trans('translation.previous')}}</span>
                                        </a>
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
    </script>
@endpush
@endsection
