@extends('layouts.master')
@section('title', __('User Info'))

@push('styles')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endpush
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.users') }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ $user->name }}</li>
                        <li class="breadcrumb-item \"><a href="{{ route('users.index') }}">{{ trans('translation.users') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('root') }}">{{ trans('translation.home') }}</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->


    <!-- Edit User Info -->
    <div class="row justify-content-center">
        <div class="col-md-12  col-xl-12">
            <div class="card card-collapsed">
                <div class="card-header" class="card-options-collapse">
                    <div class="row justify-content-around">
                        <div class="col-lg-6">
                            <h3 class="card-title">{{ trans('translation.edit-user') }}</h3>
                        </div>
                        <div class="col-lg-6 text-end">
                            <!-- <a class="" href="{{ route('users.index') }}">{{trans('translation.back')}} > </a> -->
                        </div>
                    </div>
                    <!-- <a href="javascript:void(0)" class="card-options-remove" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a> -->

                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('users.update', $user->id) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="birthday_hj" id="birthday_hj" value="{{$user->birthday_hj}}">
                        <input type="hidden" name="national_id_expiration_hj" id="national_id_expiration_hj" value="{{$user->national_id_expiration_hj}}">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="">
                                    <div class="text-center">
                                        <div class="profile-user position-relative d-inline-block mx-auto">
                                            <img id="profile-img" src="{{ $user->profile_photo }}" alt="" class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image bg-primary">
                                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit position-absolute end-0 bottom-0">
                                                <input id="profile-img-file-input" name="profile_photo" type="file" accept="image/*" class="profile-img-file-input d-none" onchange="document.getElementById('profile-img').src = window.URL.createObjectURL(this.files[0])">
                                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-light text-body">
                                                        <i class="bi bi-camera"></i>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row col-lg-10 col-md-12 mx-auto">

                                @component('components.inputs.text-input',['columnName'=>'name','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user]) @endcomponent
                                @component('components.inputs.email-input',['columnName'=>'email','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user]) @endcomponent

                                @if( (auth()->user()->id ==  $user->id) || auth()->user()->can('edit_user_password'))
                                    @component('components.inputs.password-input',['columnName'=>'password','col'=>'4','margin'=>'mb-3', 'is_required'=> ' ']) @endcomponent
                                @endif

                                @component('components.inputs.number-input',['columnName'=>'phone','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user]) @endcomponent
                                @component('components.inputs.number-input',['columnName'=>'national_id','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user, 'disabled'=>auth()->user()->can('edit_user_nationalID') ? null : 'disabled']) @endcomponent
                                @component('components.inputs.select-input',['columnName'=>'national_source','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user, 'columnOptions'=>$columnOptions , 'is_required'=> ' ']) @endcomponent
                                @component('components.inputs.date-input',['columnName'=>'national_id_expired','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user])@endcomponent
                                @component('components.inputs.select-input',['columnName'=>'nationality','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user, 'columnOptions'=>$columnOptions]) @endcomponent
                                @component('components.inputs.date-input',['columnName'=>'birthday','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user])@endcomponent

                                <div class="col-md-4">
                                    <div class="row mb-4">
                                        <div class="col">
                                            <label for="inputOrganization" class=" form-label">{{ trans('translation.organization') }}</label>
                                            <select class="form-control selectpicker" name="organization" id="inputOrganization" data-actions-box="true" data-live-search="true" placeholder="{{trans('translation.choose-organization')}}">
                                                @foreach ($organizations as $organization)
                                                <option value="{{$organization->id}}" {{($user->organization_id == $organization->id)?'selected':''}}>{{$organization->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row mb-4">
                                        <div class="col">
                                            <label for="inputFavouriteOrganization" class=" form-label col-12">{{ trans('translation.favourite-organization') }}</label>
                                            @foreach ($organizations as $organization)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" name="favourite_organizations[]" type="checkbox" value="{{ $organization->id }}"
                                                    {{in_array($organization->id, $favourite_organizations) ? 'checked' : ''}} id="{{ $organization->id }}">
                                                <label class="form-check-label"
                                                    for="{{ $organization->id }}">{{ $organization->name }}</label>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h6>
                                        {{trans('translation.role-name')}}
                                    </h6>
                                    <!-- Select1 -->
                                    @if( count($roles) == 0 )
                                    @foreach ($user->roles as $role)
                                     <span class="badge bg-primary m-1">{{$role->name}}</span>
                                    @endforeach
                                    @else
                                    <input type="hidden" name="role[]" value="" >
                                    <select class="form-control selectpicker" name="role[]" id="role_name_filter" data-actions-box="true" {{ auth()->user()->can('edit_user_role') ? '' : 'disabled' }}
                                        placeholder="{{ trans('translation.choose-role') }}" data-live-search="true" multiple {{ count($roles) == 0 ? 'disabled' : '' }}>
                                        @foreach ($roles as $role)
                                            <option {{(in_array($role->name, $user_roles) ? 'selected' : '')}} value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                    <!-- End Select1 -->
                                </div>

                                @can('edit_users_permissions', auth()->user())
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h6>
                                        {{trans('translation.permissions')}}
                                    </h6>
                                    <!-- Select1 -->
                                    <select class="form-control selectpicker" multiple name="permission_filter[]" id="permission_filter" data-actions-box="true"
                                        placeholder="{{ trans('translation.choose-permission') }}" data-live-search="true">
                                        @foreach ($permissions as $permission)
                                            <option {{$user->hasPermissionTo($permission->name)  ? 'selected' : ''}}
                                           value="{{$permission->name}}">{{$permission->name}}</option>
                                        @endforeach
                                    </select>
                                    <!-- End Select1 -->
                                </div>
                                @endcan

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h6>{{ trans('translation.bravo_name') }}</h6>
                                    <!-- Select1 -->
                                    <select class="form-control selectpicker" name="bravo" id="bravo_filter" data-actions-box="true"
                                            placeholder="{{ trans('translation.choose_bravo') }}" data-live-search="true">
                                        @foreach ($bravos as $bravo)
                                        <option value="{{$bravo->id}}"
                                            {{ $user->bravo_id == $bravo->id ? 'selected' : '' }}
                                            data-content="{{ $bravo->name }} - {{$bravo->user_name_badge}}">
                                        {{ $bravo->name }}
                                    </option>
                                        @endforeach
                                    </select>
                                    <!-- End Select1 -->
                                </div>

                                @component('components.inputs.text-input',['columnName'=>'address','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user, 'is_required' => '']) @endcomponent
                                {{-- @component('components.inputs.select-input',['columnName'=>'scrub_size','col'=>'4','margin'=>'mb-3', 'modelItem'=>$user, 'columnOptions'=>$columnOptions, "is_required"=>""]) @endcomponent --}}
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h6>{{ trans('translation.scrub-size') }}</h6>
                                    <!-- Select1 -->
                                    <select class="form-control selectpicker" name="scrub_size" id="scrub_size_filter" data-actions-box="true"
                                            placeholder="{{ trans('translation.choose_size') }}" data-live-search="true">
                                        @foreach ($columnOptions['scrub_size'] as $key => $scrub_size)
                                        <option value="{{$key}}"
                                            {{ $user->scrub_size == $key ? 'selected' : '' }}
                                            data-content="{{ $scrub_size }}">
                                        {{ $scrub_size }}
                                    </option>
                                        @endforeach
                                    </select>
                                    <!-- End Select1 -->
                                </div>
                                @component('components.inputs.file-input',['columnName'=>'national_id_attachment', 'name'=>'national_id_attachment', 'col'=>'4','margin'=>'mb-3',"is_required"=>""]) @endcomponent

                                {{-- {{dd($user->national_id_attachment)}} --}}
                                @if (isset( $user->national_id_attachment))
                                <div class="card-body">
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ $user->national_id_attachment}}" title="user-national-id" id="user-national-id" allowfullscreen></iframe>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="row mt-3 mx-1">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-primary col-lg-1">{{ trans('translation.update') }}</button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>




    @push('after-scripts')
        <!-- SelectPicker -->
        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        {{-- <script src="{{ URL::asset('build/libs/@simonwep/pickr/pickr.min.js') }}"></script> --}}
        {{-- <script src="{{ URL::asset('build/js/pages/form-pickers.init.js') }}"></script> --}}

        <script>
            $(document).ready(function() {
                $('.selectPicker').selectpicker({
                    width: '100%',
                });
            }); // end document ready
        </script>
    @endpush
@endsection
