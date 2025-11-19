<div class="row pt-2">
    <div class="col-md-6">
        @component('components.data-row', ['id'=>'order-user-name'])
        {{$order->user->name}}
        <a href="{{route('users.edit',$order->user_id)}}">
                <small>
                    {{trans('translation.edit-details')}}
                </small>
            </a>
        @endcomponent
        @component('components.data-row', ['id'=>'email']){{$order->user->email}}@endcomponent
        @component('components.data-row', ['id'=>'phone'])
            @if (isset($order->user->phone))
            <div class="row">
                <span dir="ltr" class="col-12 col-lg-auto my-auto">{{$phone_with_code = $order->user->phone_code . $order->user->phone}}</span>
                <div class="col-lg-auto my-auto">
                    <a href="tel:{{$phone_with_code}}" target="_blank" class="btn btn-outline-primary btn-sm m-1  on-default m-r-5 col-lg-auto">
                        <i class="mdi mdi-phone"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?phone={{ str_replace('+','',$phone_with_code) }}" target="_blank" class="btn btn-outline-success btn-sm m-1  on-default m-r-5 col-lg-auto">
                        <i class="mdi mdi-whatsapp"></i>
                    </a>
                </div>
            </div>
            @else
                {{trans('translation.not_found')}}
            @endif
        @endcomponent
        @component('components.data-row', ['id'=>'nationality']){{$order->user->nationality_name}}@endcomponent
    </div>
    <div class="col-md-6">
        @component('components.data-row', ['id'=>'national-id']){{$order->user->national_id}}@endcomponent
        @component('components.data-row', ['id'=>'national-id-attachment'])
            @if(isset($order->user->national_id_attachment))
                <a href="{{$order->user->national_id_attachment}}" target="_blank">{{trans('translation.view')}} - </a>
                <a href="{{$order->user->national_id_attachment}}" download="{{$order->user->name . '_national_id'}}">{{trans('translation.download')}}</a>
            @else
                {{trans('translation.no-data')}}
            @endif
        @endcomponent
        @component('admin.facilities.components.date-row', ['id'=>'national-id-expired', 'date_ad'=>$order->user->national_id_expired, 'date_hj'=>$order->user->national_id_expired_hj])@endcomponent
        @component('admin.facilities.components.date-row', ['id'=>'birthday', 'date_ad'=>$order->user->birthday, 'date_hj'=>$order->user->birthday_hj])@endcomponent
    </div>
</div>

@push('after-scripts')
    <script>
        $(document).ready(function() {
            
        });
    </script>
@endpush