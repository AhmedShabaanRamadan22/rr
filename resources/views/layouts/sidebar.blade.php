    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="{{ route('root') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ URL::asset('build/images/logos/icon-logo.png') }}" alt="" height="25">
                </span>
                <span class="logo-lg">
                    <img src="{{ URL::asset('build/images/logos/rakaya.png') }}" alt="" height="50">
                </span>
            </a>
            <a href="{{ route('root') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ URL::asset('build/images/logos/icon-white.png') }}" alt="" height="25">
                </span>
                <span class="logo-lg">
                    <img src="{{ URL::asset('build/images/logos/rakaya.png') }}" alt="" height="50">
                </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>
        @can('view_search_box_sidebar')
        <div class="row">
            <div class="col mx-2">
                <input class=" form-control" type="text" name="" id="sidebar-menu-search" placeholder="{{trans('translation.search')}}...">
            </div>
        </div>            
        @endcan
        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title"><span>@lang('translation.main')</span></li>
                    @hasrole(['organization admin'])
                        <li class="nav-item">
                            <a href="{{ route('root') }}"
                                class="nav-link menu-link {{ Request::routeIs('root') ? 'active' : '' }}"> <i
                                    class="mdi mdi-home"></i> <span
                                    data-key="t-root">@lang('translation.home')</span> </a>
                            <a href="{{ route('dashboard') }}"
                        class="nav-link menu-link {{ Request::routeIs('dashboard') ? 'active' : '' }}"> <i
                            class="mdi mdi-desktop-mac-dashboard"></i> <span
                            class="sidebar-label-span" data-key="t-root">@lang('translation.root')</span> </a>
                        </li>
                    @endhasrole
                    @hasrole(['superadmin','admin'])
                        <li class="nav-item">
                            <a href="{{ route('root') }}"
                                class="nav-link menu-link {{ Request::routeIs('root') ? 'active' : '' }}"> <i
                                    class="mdi mdi-home"></i> <span
                                    data-key="t-home">@lang('translation.home')</span> </a>
                            <a href="{{ route('dashboard') }}"
                                class="nav-link menu-link {{ Request::routeIs('dashboard') ? 'active' : '' }}"> <i
                                    class="mdi mdi-desktop-mac-dashboard"></i> <span
                                    class="sidebar-label-span" data-key="t-root">@lang('translation.root')</span> </a>
                            <!-- <a href="{{ route('dashboard_looker_studio') }}"
                                class="nav-link menu-link {{ Request::routeIs('dashboard_looker_studio') ? 'active' : '' }}"> <i
                                    class="mdi mdi-desktop-mac"></i> <span
                                    data-key="t-dashboard_looker_studio">@lang('translation.dashboard_looker_studio')</span> </a> -->
                        </li>
                        
                        @include('layouts.menu-links')

                    @endhasrole


                </ul>
            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>

    <script>
        const clearSessionStorage = () => {
            sessionStorage.clear();
        }
    </script>
