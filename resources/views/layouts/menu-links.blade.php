<li class="menu-title d-none"><span>@lang('translation.user')</span></li>
<x-menu-link route_name="organizations.index" icon_class="mdi-account-group-outline"></x-menu-link>
<x-menu-link route_name="users.index" icon_class="mdi-account-outline"></x-menu-link>
<x-menu-link route_name="providers.index" icon_class="mdi-account-tie" permission="view_providers"></x-menu-link>
<x-menu-link route_name="facilities.index" icon_class="mdi-office-building-outline" permission="view_facilities"></x-menu-link>

<li class="menu-title d-none"><span>@lang('translation.operation type before')</span></li>
<x-menu-link route_name="orders.index" icon_class="mdi-reorder-horizontal" permission="view_orders"></x-menu-link>
@can('view_customize_order_table',auth()->user())
<x-menu-link route_name="orders-customized.index" icon_class="mdi-reorder-horizontal"></x-menu-link>
@endcan
@can('view_order_assigns_table',auth()->user())
<x-menu-link route_name="admin.order-assigns.index" icon_class="mdi-account-multiple-plus-outline"></x-menu-link>
@endcan
    
<x-menu-link route_name="order-interviews.index" icon_class="mdi-reorder-vertical"></x-menu-link>
{{-- <x-menu-link route_name="order-reports.index" icon_class="mdi-reorder-vertical"></x-menu-link> --}}

<li class="menu-title d-none"><span>@lang('translation.operation type during')</span></li>
<x-menu-link route_name="tickets.index" permission="view_tickets" icon_class="mdi-ticket-confirmation-outline"></x-menu-link>
<x-menu-link route_name="supports.index" permission="view_tickets" icon_class="mdi-truck-delivery-outline"></x-menu-link>
<x-menu-link route_name="assists.index" permission="view_assists" icon_class="mdi-truck-delivery-outline"></x-menu-link>
<x-menu-link route_name="fines.index" permission="view_fines" icon_class="mdi-cash-multiple"></x-menu-link>

<li class="menu-title d-none"><span>@lang('translation.questions')</span></li>
<x-menu-link route_name="forms.index" icon_class="mdi-note-edit-outline"></x-menu-link>
<x-menu-link route_name="submitted-forms.index" icon_class="mdi-check-circle-outline"></x-menu-link>
<x-menu-link route_name="question-banks.index" icon_class="mdi-bank"></x-menu-link>
<x-menu-link route_name="question-types.index" icon_class="mdi-transition-masked"></x-menu-link>
<x-menu-link route_name="regexes.index" icon_class="mdi-quadcopter"></x-menu-link>

<li class="menu-title d-none"><span>@lang('translation.sectors')</span></li>
<x-menu-link route_name="sectors.index" icon_class="mdi-chart-timeline-variant"></x-menu-link>
<x-menu-link route_name="classifications.index" icon_class="mdi-tag-outline"></x-menu-link>
<x-menu-link route_name="nationalities.index" icon_class="mdi-flag-outline"></x-menu-link>
<x-menu-link route_name="monitors.index" icon_class="mdi-account-tie"></x-menu-link>

<li class="menu-title d-none"><span>@lang('translation.food')</span></li>
<x-menu-link route_name="food.index" icon_class="mdi-food-apple-outline"></x-menu-link>
<x-menu-link route_name="meals.index" icon_class="mdi-food-fork-drink"></x-menu-link>
<x-menu-link route_name="food-types.index" icon_class="mdi-food-outline"></x-menu-link>
<x-menu-link route_name="periods.index" icon_class="mdi-timetable"></x-menu-link>

<li class="menu-title d-none"><span>@lang('translation.settings')</span></li>
<x-menu-link route_name="services.index" icon_class="mdi-cog-outline"></x-menu-link>
<x-menu-link route_name="roles.index" icon_class="mdi-account-outline"></x-menu-link>
<x-menu-link route_name="categories.index" icon_class="mdi-book-outline"></x-menu-link>
<x-menu-link route_name="facility-employee-positions.index" icon_class="mdi-account-box-outline"></x-menu-link>
<x-menu-link route_name="statuses.index" icon_class="mdi-state-machine"></x-menu-link>
<x-menu-link route_name="attachment-labels.index" icon_class="mdi-pin-outline"></x-menu-link>
<x-menu-link route_name="operation-types.index" icon_class="mdi-account-cog-outline"></x-menu-link>
<x-menu-link route_name="reasons.index" icon_class="mdi-lightbulb-outline"></x-menu-link>
<x-menu-link route_name="dangers.index" icon_class="mdi-alert-decagram-outline"></x-menu-link>
<x-menu-link route_name="fine-banks.index" icon_class="mdi-cash-multiple"></x-menu-link>
<x-menu-link route_name="bravos.index" icon_class="mdi-radio-tower"></x-menu-link>

<!-- new menu-link -->
<x-menu-link route_name="facility-evaluations.index" icon_class="mdi-pen"></x-menu-link>
<x-menu-link route_name="mobile-infos.index" icon_class="mdi-cellphone"></x-menu-link>
<!-- <x-menu-link route_name="notifications.index" icon_class="mdi-chart-timeline-variant"></x-menu-link> -->
<x-menu-link route_name="interview-standards.index" icon_class="mdi-chart-timeline-variant"></x-menu-link>
{{-- <x-menu-link route_name="organization-stages.index" icon_class="mdi-chart-timeline-variant"></x-menu-link> --}}
<x-menu-link route_name="stage-banks.index" icon_class="mdi-clock-time-eight-outline"></x-menu-link>
<x-menu-link route_name="order-sectors.index" icon_class="mdi-monitor"></x-menu-link>

<x-menu-link route_name="ibans.index" icon_class="mdi-lock-outline"></x-menu-link>
<x-menu-link route_name="banks.index" icon_class="mdi-briefcase-account"></x-menu-link>

<li class="menu-title d-none"><span>@lang('translation.rakaya')</span></li>
<x-menu-link route_name="admin.gallery.index" icon_class="mdi-badge-account"></x-menu-link>
<x-menu-link route_name="candidates.index" icon_class="mdi-badge-account"></x-menu-link>
<x-menu-link route_name="contact-us.index" icon_class="mdi-help-circle"></x-menu-link>
<x-menu-link route_name="subjects.index" icon_class="mdi-clipboard-list"></x-menu-link>
<x-menu-link route_name="departments.index" icon_class="mdi-office-building"></x-menu-link>

<li class="menu-title d-none"><span>@lang('translation.message')</span></li>
<x-menu-link route_name="senders.index" icon_class="mdi-email-outline"></x-menu-link>
<x-menu-link route_name="messages.index" icon_class="mdi-message-reply-text-outline"></x-menu-link>
