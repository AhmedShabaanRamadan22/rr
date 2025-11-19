@extends('layouts.master')
@section('title')
@lang('translation.home')
@endsection
@section('content')


<div class="card" style="min-height:550px; position: relative;">
    <div class="card-body p-2" style="position: relative; height: 450px;">
        @for ($i=1;$i<3;$i++)
            <div id="iframeDiv{{$i}}" style="position: absolute; inset: 0; z-index: {{3 - $i}}; padding: 0.5rem;">
                <iframe id="iframe{{$i}}"
                    src="{{$looker_studio_embded_url."?view=".$i}}"
                    frameborder="0"
                    style="width: 100%; height: 100%; border:0"
                    allowfullscreen
                    sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
                </iframe>
            </div>
        @endfor
    </div> 
    <div class="card-footer">
        <button onclick="refreshData()">Refresh Data</button>
    </div>
</div>

<!-- Iframes -->

<!-- Refresh Button -->
<!-- <button onclick="refreshData()">Refresh Data</button> -->

@endsection
@push('after-scripts')

<script>
    let activeFrame = 1;

    function refreshData() {
        const iframeToHide = document.getElementById(`iframe${activeFrame}`);
        const iframeToShow = document.getElementById(`iframe${3 - activeFrame}`);
        const divToHide = document.getElementById(`iframeDiv${activeFrame}`);
        const divToShow = document.getElementById(`iframeDiv${3 - activeFrame}`);

        // Load fresh iframe
        iframeToShow.src = `{{$looker_studio_embded_url}}?view=${activeFrame}_${Date.now()}`;

        // Wait until iframe is likely to be fully reloaded
        setTimeout(() => {
            // Swap z-index
            divToHide.style.zIndex = "0";
            divToShow.style.zIndex = "1";

            activeFrame = 3 - activeFrame;
        }, 8000); // Give it 8 seconds buffer
    }
</script>
@endpush