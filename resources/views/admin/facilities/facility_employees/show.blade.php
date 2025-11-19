@extends('layouts.master')
@section('title', __('Attachments'))
@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

<div class="row">
    <div class="col-12">

          <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ trans('translation.Employee Name') }}: {{ $facility_employee->name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ $facility_employee->name }}</li>
                    <li class="breadcrumb-item"><a href="{{ route('facilities.show', ['facility' => $facility_employee->facility->id]) }}">{{$facility_employee->facility->name}}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('facilities.index') }}">{{ trans('translation.facilities') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('root') }}">{{ trans('translation.home') }}</a></li>
                </ol>
            </div>  
        </div>
        
    </div>
</div>

<div class="row">
    <div class="card">
        <div class="card-header">
            <div class="col-md-6 col-12">
                @component('components.data-row', ['id'=>'user-name']){{$facility_employee->name}}@endcomponent
                @component('components.data-row', ['id'=>'national-id']){{$facility_employee->national_id}}@endcomponent
                @component('components.data-row', ['id'=>'position']){{$facility_employee->position_name}}@endcomponent
            </div>

            <div class="row">
                <div class="">
                    <h4 class="text-danger card-title">
                        {{ trans('translation.Remaining-of-attachments')}}
                    </h4>
                </div>
            
                <div class="col-md-6 col-12">
                    @foreach ($remaining_attachments as $attach)
                    <h6 class="flex-grow-1 mb-0 align-self-center">
                        @component('components.data-row', ['id'=>'attachment_label']){{$attach->placeholder}}@endcomponent
                        @component('components.data-row', ['id'=>'required'])
                        <i class="{{ $attach->is_required == 1 ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger'}}"></i>
                        @endcomponent
                    </h6>
                    @endforeach
                </div>
            </div>
            
        </div>
        <div class="card-body">
            <div class="row">
                @forelse ($attachments as $attachment)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-end">
                                    <h6 class="flex-grow-1 mb-0 align-self-center">
                                        {{ ++$loop->index . '- ' . (($attachment_placeholder = $attachment->attachment_label->placeholder) == ' '? trans('translation.attachment') : $attachment_placeholder)}}
                                    </h6>
                                    <a href="{{$attachment->url}}" download="{{$facility_employee->name . '_' . $attachment_placeholder}}">
                                        <button class="btn btn-outline-primary btn-sm on-default">
                                            <i class="mdi mdi-file-download-outline"></i>
                                        </button>
                                    </a>
                                    {{-- <button class="btn btn-outline-secondary btn-sm mx-2 on-default  edit-facility-attachment-button" > data-bs-target="#editQuestion" data-bs-toggle="modal" data-original-title="Edit" data-question-id="' . $question->id . '"> --}}
                                        {{-- <i class="mdi mdi-clipboard-edit-outline"></i>
                                        </button>

                                        <button class="btn btn-outline-danger btn-sm on-default delete-facility-attachment-button" data-question-id="' . $question->id . '">
                                            <i class="mdi mdi-delete"></i>
                                        </button> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{$attachment->url}}" title="{{$attachment->name}}" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ratio Video 16:9 -->
                @empty
                <div class="m-1">
                    {{trans('translation.no-data')}}
                </div>
                @endforelse
            </div>
        </div>
    
    </div>
</div>
    



@endsection
