@extends('layouts.master')
@section('title', __('Facilities'))
@push('styles')
    <!-- SelectPicker -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ trans('translation.facilities') }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ trans('translation.facilities') }}</li>
                    <li class="breadcrumb-item"><a href="{{route('root')}}">{{ trans('translation.home') }}</a></li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">

    </div>
    <div class="card-body">
        @component('admin.facilities.components.facility-information', ['facility'=>$facility, 'columnOptions' => $columnOptions, 'districts' => $districts, 'hijriDateColumns'=>$hijriDateColumns])@endcomponent
    </div>
</div>

    @push('after-scripts')
        <!-- SelectPicker -->
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

        <script>
            $(document).ready(function() {

            
            }); //end ready
        </script>
    @endpush
@endsection
