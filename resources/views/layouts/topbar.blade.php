<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{route('root')}}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logos/icon-logo.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logos/rakaya.png') }}" alt="" height="55">
                            {{-- <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="22"> --}}
                        </span>
                    </a>

                    <a href="{{route('root')}}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logos/icon-gold.png') }}" alt=""
                                height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logos/rakaya.png') }}" alt="" height="55">
                            {{-- <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="22"> --}}
                        </span>
                    </a>
                </div>

                <button type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                {{-- <form class="app-search d-none d-md-inline-flex">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." autocomplete="off"
                            id="search-options" value="">
                        <span class="mdi mdi-magnify search-widget-icon"></span>
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                            id="search-close-options"></span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                        <div data-simplebar style="max-height: 320px;">
                            <!-- item-->
                            <div class="dropdown-header">
                                <h6 class="text-overflow text-muted mb-0 text-uppercase">Recent Searches</h6>
                            </div>

                            <div class="dropdown-item bg-transparent text-wrap">
                                <a href="index" class="btn btn-subtle-secondary btn-sm btn-rounded">how to setup <i
                                        class="mdi mdi-magnify ms-1"></i></a>
                                <a href="index" class="btn btn-subtle-secondary btn-sm btn-rounded">buttons <i
                                        class="mdi mdi-magnify ms-1"></i></a>
                            </div>
                            <!-- item-->
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow text-muted mb-1 text-uppercase">Pages</h6>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                                <span>Analytics Dashboard</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-lifebuoy-line align-middle fs-18 text-muted me-2"></i>
                                <span>Help Center</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                                <span>My account settings</span>
                            </a>

                            <!-- item-->
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow text-muted mb-2 text-uppercase">Members</h6>
                            </div>

                            <div class="notification-list">
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="m-0">Angela Bernier</h6>
                                            <span class="fs-2xs mb-0 text-muted">Manager</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="{{ URL::asset('build/images/users/avatar-3.jpg') }}"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="m-0">David Grasso</h6>
                                            <span class="fs-2xs mb-0 text-muted">Web Designer</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="{{ URL::asset('build/images/users/avatar-5.jpg') }}"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="m-0">Mike Bunch</h6>
                                            <span class="fs-2xs mb-0 text-muted">React Developer</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="text-center pt-3 pb-1">
                            <a href="#" class="btn btn-primary btn-sm">View All Results <i
                                    class="ri-arrow-right-line ms-1"></i></a>
                        </div>
                    </div>
                </form> --}}
            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown ms-1 topbar-head-dropdown header-item">
                    @hasrole(['superadmin','admin'])
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @switch(Session::get('lang'))
                            @case('en')
                                <img src="{{ URL::asset('build/images/flags/us.svg') }}" class="rounded" alt="Header Language"
                                    height="20">
                            @break

                            @default
                                <img src="{{ URL::asset('build/images/flags/sa.svg') }}" class="rounded" alt="Header Language"
                                    height="20">
                        @endswitch
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">

                        <a href="{{ url('index/en') }}" class="dropdown-item notify-item language py-2" data-lang="en"
                            title="English">
                            <img src="{{ URL::asset('build/images/flags/us.svg') }}" alt="user-image"
                                class="me-2 rounded" height="20">
                            <span class="align-middle">English</span>
                        </a>

                        <!-- item-->
                        <a href="{{ url('index/ar') }}" class="dropdown-item notify-item language" data-lang="ar"
                            title="Arabic">
                            <img src="{{ URL::asset('build/images/flags/sa.svg') }}" alt="user-image"
                                class="me-2 rounded" height="18">
                            <span class="align-middle">عربي</span>
                        </a>
                    </div>
                    @endhasrole
                </div>
                
                <!-- Notifications -->
                <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown"  data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <i class='bi bi-bell fs-2xl'></i>
                        @if(($notification_count = auth()->user()->unreadNotifications()->count()) > 0)
                            <span class="position-absolute topbar-badge fs-3xs translate-middle badge rounded-pill bg-danger">
                                <span class="notification-badge">{{ $notification_count > 5 ? '5+':$notification_count }}</span>
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                        
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">

                        <div class="dropdown-head rounded-top">
                            <div class="p-3 border-bottom border-bottom-dashed">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="mb-0 fs-lg fw-semibold"> {{trans("translation.notifications")}} </h6>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="py-2 ps-2" id="notificationItemsTabContent">
                            <div data-simplebar style="max-height: 300px;" class="pe-2">
                                @forelse ( auth()->user()->unreadNotificationsLimit as $notification)
                                    <div class="text-reset notification-item d-block dropdown-item position-relative unread-message">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3 flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-lg">
                                                    <i class="bx bx-badge-check"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <a href="{{route('readNotificationWithRedirect',$notification->id)}}" class="stretched-link">
                                                    <h6 class="mt-0 fs-md mb-2 lh-base">
                                                        {{$notification->data['message']??''}}
                                                    </h6>
                                                </a>
                                                <div class="fs-sm text-muted">
                                                    <p class="mb-1">
                                                        {{!isset($notification->data['assigned_by']) ? "" : trans('translation.by') . ' ' . $notification->data['assigned_by']}}
                                                    </p>
                                                </div>
                                                <p class="mb-0 fs-2xs fw-medium text-uppercase text-muted">
                                                    <span><i class="mdi mdi-clock-outline"></i> {{$notification->created_at->diffForHumans()}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                
                            </div>
                        </div>

                        <div class="dropdown-footer border-top border-top-dashed">
                            <div class="row m-3">
                                <div class="col text-center">
                                    <a href="{{route('notifications.index')}}">{{ trans('translation.all-notifications') }}</a>
                                    @if(($notification_count  > 0))
                                            <span class="badge bg-danger">{{ $notification_count }}</span>
                                    @endif
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Notification -->


                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle"
                        data-toggle="fullscreen">
                        <i class='bi bi-arrows-fullscreen fs-lg'></i>
                    </button>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle mode-layout"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-sun align-middle fs-3xl"></i>
                    </button>
                    <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                        <a href="#!" class="dropdown-item" data-mode="light">
                            <i class="bi bi-sun align-middle me-2"></i> {{ trans('translation.light_mode') }}
                        </a>
                        <a href="#!" class="dropdown-item" data-mode="dark">
                            <i class="bi bi-moon align-middle me-2"></i> {{ trans('translation.dark_mode') }}
                        </a>
                        <a href="#!" class="dropdown-item" data-mode="auto">
                            <i class="bi bi-moon-stars align-middle me-2"></i> {{ trans('translation.auto_mode') }}
                        </a>

                    </div>
                </div>



                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user bg-primary" src="{{ auth()->user()->profile_photo }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span
                                    class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ $user_name = auth()->user()->name ?? trans('translation.not_found') }}</span>
                                <span
                                    class="d-none d-xl-block ms-1 fs-sm user-name-sub-text">{{ auth()->user()->organization->name_ar ?? trans('translation.not_found') }}</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        {{-- <h6 class="dropdown-header">{{trans('translation.topbar_welcome')}} {{ $user_name }} !</h6> --}}
                        @hasrole(['superadmin','admin'])
                        <a class="dropdown-item" href="{{ route('profile') }}"><i
                                class="mdi mdi-account-circle text-muted fs-lg align-middle me-1"></i> <span
                                class="align-middle"> @lang('translation.profile')</span></a>
                        <a class="dropdown-item" href="{{ route('users.edit',auth()->user()->id) }}"><i
                                class="mdi mdi-square-edit-outline text-muted fs-lg align-middle me-1"></i> <span
                                class="align-middle"> @lang('translation.edit-personal-info')</span></a>
                        @endhasrole
                        <div class="dropdown-divider"></div>
                        <!-- <a class="dropdown-item" href="{{ url('users/profile') }}"><span
                                class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                                class="mdi mdi-cog-outline text-muted fs-lg align-middle me-1"></i> <span
                                class="align-middle">@lang('translation.settings')</span></a> -->
                        <!-- <a class="dropdown-item" href="{{-- route('password.confirm') --}}"><i
                                class="mdi mdi-lock text-muted fs-lg align-middle me-1"></i> <span
                                class="align-middle"> @lang('translation.lock-screen')</span></a> -->
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                class="mdi mdi-logout text-muted fs-lg align-middle me-1"></i> <span
                                class="align-middle" data-key="t-logout">@lang('translation.logout')</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
