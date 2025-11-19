@props(['route_name','icon_class','permission','star_route'=>0])
@can($permission??("view_".$route_name))
<li class="nav-item">
    <a 
        href="{{route($route_name)}}" 
        class="nav-link menu-link {{\Request::routeIs(($first_route_name = explode('.',$route_name)[0]).($first_route_name == "admin" ? ".".explode('.',$route_name)[1]:"").'.*')  ? 'active':''}}" 
        onclick="customClearTabSessionStorage();"
    > 
        <i class="mdi {{$icon_class}}"></i> 
        <span class="sidebar-label-span " data-key="t-service">{{trans('translation.'.$route_name)}}</span> 
    </a>
</li>
@endcan