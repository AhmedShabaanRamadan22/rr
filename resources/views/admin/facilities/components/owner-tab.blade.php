<div class="row">
    <div class="col-md-6 col-md-12">
                <div class="profile-user position-relative d-inline-block mx-auto">
                    <img id="profile-img" src="{{ $facility->user->profile_photo }}" alt="" class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image bg-primary">
                </div>
                <div class="mt-3"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-12">
        @component('components.data-row', ['id'=>'user-name']){{$facility->user->name}}@endcomponent
        @component('components.data-row', ['id'=>'phone'])
            <a href="{{'https://api.whatsapp.com/send?phone=966' . $facility->user->phone}}" dir="ltr" target="_blank">{{$facility->user->phone_code . ' ' . $facility->user->phone}}</a>
        @endcomponent
        @component('components.data-row', ['id'=>'email']){{$facility->user->email}}@endcomponent
        @component('admin.facilities.components.date-row', ['id'=>'birthday', 'date_ad'=>$facility->user->birthday, 'date_hj'=>$facility->user->birthday_hj])@endcomponent
        @component('components.data-row', ['id'=>'nationality']){{$facility->user->nationality_name}}@endcomponent
    </div>
    <div class="col-md-6 col-12">
        @component('components.data-row', ['id'=>'national-id']){{$facility->user->national_id}}@endcomponent
        @component('admin.facilities.components.date-row', ['id'=>'national-id-expired', 'date_ad'=>$facility->user->national_id_expired, 'date_hj'=>$facility->user->national_id_expired_hj])@endcomponent
        @component('components.data-row', ['id'=>'national-id-source']){{($facility->user->national_source_name)}}@endcomponent
        @component('components.data-row', ['id'=>'national-id-attachment'])
            @if(isset($facility->user->national_id_attachment))
                <a href="{{$facility->user->national_id_attachment}}" target="_blank">{{trans('translation.view')}} - </a>
                <a href="{{$facility->user->national_id_attachment}}" download="{{$facility->user->national_id_photo->label . ' - ' . $facility->user->name}}">{{trans('translation.download')}}</a>
            @else
                {{trans('translation.no-data')}}
            @endif
        @endcomponent
    </div>
</div>