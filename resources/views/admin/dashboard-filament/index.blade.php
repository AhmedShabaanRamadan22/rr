@extends('layouts.master')
@section('title')
@lang('translation.Dashboard')
@endsection
@section('content')
<div class="card" style="min-height:90vh; height: 100vh; position: relative;">
    <div class="card-body p-2" style="position: relative; height: 80vh;">

        @if ($filament_embedded_url)
            <div id="iframeDiv" style="position: absolute; inset: 0; z-index: 1; padding: 0.5rem;">
                <iframe id="iframe"
                        src="{{ $filament_embedded_url }}"
                        frameborder="0"
                        style="width: 100%; height: 100%; border:0; border-radius: 6px;"
                        allowfullscreen
                        sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
                </iframe>
            </div>
        @else
            <div class="d-flex align-items-center justify-content-center h-100 w-100 text-center">
                <div>
                    <h3 class="text-danger mb-3">تعذر تحميل لوحة البيانات</h3>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
