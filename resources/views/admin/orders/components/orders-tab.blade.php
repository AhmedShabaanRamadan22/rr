@php
    $i = 0;
@endphp

<div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show canceled-statues d-none" role="alert">
    <i class="ri-error-warning-line label-icon"></i>{{trans('translation.order-status')}}<strong> {{$order->status->name}}</strong>
</div>

<div class="position-relative mx-md-4 my-5 progress-status">    
    <div class="progress" style="height: 1px;">
        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex position-absolute translate-middle start-50 col-12 justify-content-between mb-4">
        @foreach($progress_statuses as $status)
        <div class="mt-4 text-center">
            <button class="btn btn-sm status-btn mb-2 rounded-pill {{ ($status->id < $order->status_id || ($status->id == $order->status_id && $status->name_ar == "تم القبول")) ? 'btn-primary' : 'btn-light'}} {{ $status->id == $order->status_id ? 'border border-primary border-2' : ''}}" style="width: 2rem; height:2rem;">{{++$loop->index}}</button>
            <div>{{$status->name}}</div>
        </div>
        @if ($status->id < $order->status_id)
        @php
                $i = $loop->index;
                @endphp
        @endif
        @endforeach
    </div>
</div>

<div class="row pt-2">
    <div class="col-md-6">
        @component('components.data-row', ['id'=>'order-id']){{$order->id}}@endcomponent
        @component('components.data-row', ['id'=>'order-type']){{$order->organization_service->service->name}}@endcomponent
        @component('components.data-row', ['id'=>'creation-date']){{$order->created_at->format('d/m/Y')}} ({{$order->created_at->diffForHumans()}})@endcomponent
    </div>
    <div class="col-md-6">
        @component('components.data-row', ['id'=>'facility'])
        {{$order->facility->name}}
            <a href="{{route('facilities.show',$order->facility_id)}}">
                <i class="mdi mdi-eye icon-bigger"></i>
            </a>
            <a href="{{route('facilities.edit',$order->facility_id)}}">
                <i class="mdi mdi-square-edit-outline icon-bigger"></i>
            </a>
        @endcomponent
        @component('components.data-row', ['id'=>'user']){{$order->user->name}}@endcomponent
        @component('components.data-row', ['id'=>'organization']){{$order->organization_service->organization->name}}@endcomponent
    </div>
</div>

@push('after-scripts')
    <script>
        $(document).ready(function() {
            $('.progress-bar').css('width',{{$i}} / ({{count($progress_statuses) - 1}}) * 100 + '%');

            let flag = {{$order->status_id}} > {{count($progress_statuses)}}
            if(flag){
                // $('.status-btn').removeClass('btn-primary').addClass('btn-light').attr('disabled', true)
                $('.progress-status').addClass('d-none')
                $('.canceled-statues').removeClass('d-none')
            }
            else{
                $('.progress-status').removeClass('d-none')
                $('.canceled-statues').addClass('d-none')
            }
        });
    </script>
@endpush