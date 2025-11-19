@extends('layouts.master')
@section('title')
@lang('translation.home')
@endsection
@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col align-content-center">
                <h3 class="m-0 p-0">
                    {{trans('translation.topbar_welcome')}} {{ auth()->user()->name ?? trans('translation.not_found') }} !
                </h3>

            </div>
            <div class="col text-end">
                <a href="{{route('dashboard')}}" class="btn btn-primary">{{ trans('translation.move-to-page') }} {{ trans('translation.root') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection