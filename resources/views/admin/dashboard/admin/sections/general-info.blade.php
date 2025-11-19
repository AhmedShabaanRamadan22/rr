<div class="col-lg-12">
    <div class="card card-height-100 border-0 overflow-hidden">
        {{-- <div class="card-header">
            <h4 class="card-title mb-0 text-primary">الإحصائيات</h4>
        </div> --}}
        <div class="card-body p-0">
            <div class="row g-0">
                @component('components.dashboard.statics-info-card', [
                // // 'col' => 'col-md-2',
                // 'data' => ($users->merge($providors)),
                'icon' => 'ph-users',
                'label' => trans('translation.sector-guest-quantity'),
                'description' => 'users-dec-info-card',
                'bgcolor' => 'bg-primary-subtle',
                'iccolor' => 'text-primary',
                'target' => 'total_sector_guest_quantity_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $monitors,
                   'icon' => 'ph-police-car',
                    'description' => 'monitors-dec-info-card',
                    'label' => trans('translation.monitors'),
                    'bgcolor' => 'bg-primary-subtle',
                    'iccolor' => 'text-primary',
                    'target' => 'total_monitors_' . ($organization->id??0),
                    ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $facilities,
                'icon' => 'ph-buildings',
                'description' => 'facilities-dec-info-card',
                'label' => trans('translation.facilities'),
                'bgcolor' => 'bg-light',
                'iccolor' => 'text-body',
                'target' => 'total_facilities_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $orders,
                'icon' => 'mdi mdi-reorder-horizontal',
                'description' => 'orders-dec-info-card',
                'label' => trans('translation.orders'),
                'bgcolor' => 'bg-light',
                'iccolor' => 'text-body',
                'target' => 'total_orders_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $supports,
                'description' => 'supports-dec-info-card',
                'icon' => 'ph-truck',
                'label' => trans('translation.supports'),
                'bgcolor' => 'bg-success-subtle',
                'iccolor' => 'text-success-emphasis',
                'target' => 'total_supports_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => ($supports)->where('type',3),
                'icon' => 'ph-drop-half-bottom',
                'description' => 'water-supports-dec-info-card',
                'label' => trans('translation.water_supports'),
                'bgcolor' => 'bg-success-subtle',
                'iccolor' => 'text-success-emphasis',
                'target' => 'total_water_supports_' . ($organization->id??0),
                'end' => true,
                ])
                @endcomponent
            </div>
            <div class="row g-0">
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $providors,
                'icon' => 'ph-users-four',
                'description' => 'providers-dec-info-card',
                'label' => trans('translation.total_providors'),
                'bgcolor' => 'bg-primary-subtle',
                'iccolor' => 'text-primary',
                'target' => 'total_providors_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $users,
                'icon' => 'ph-users-three',
                'description' => 'employees-dec-info-card',
                'label' => trans('translation.employees'),
                'bgcolor' => 'bg-primary-subtle',
                'iccolor' => 'text-primary',
                'target' => 'total_employees_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $tickets,
                'icon' => 'ph-ticket',
                'description' => 'tickets-dec-info-card',
                'label' => trans('translation.total_tickets'),
                'bgcolor' => 'bg-light',
                'iccolor' => 'text-body',
                'target' => 'total_tickets_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $submitted_forms,
                'icon' => 'ph-file-dotted',
                'description' => 'submitted-forms-dec-info-card',
                'label' => trans('translation.submitted_forms'),
                'bgcolor' => 'bg-light',
                'iccolor' => 'text-body',
                'target' => 'total_submitted_forms_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $meals,
                'icon' => 'ph-fork-knife',
                'description' => 'meals-dec-info-card',
                'label' => trans('translation.meals'),
                'bgcolor' => 'bg-success-subtle',
                'iccolor' => 'text-success-emphasis',
                'target' => 'total_meals_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $supports->where('type',2),
                'icon' => 'ph-cooking-pot',
                'label' => trans('translation.meal_supports'),
                'description' => 'meals-supports-dec-info-card',
                'bgcolor' => 'bg-success-subtle',
                'iccolor' => 'text-success-emphasis',
                'target' => 'total_meal_supports_' . ($organization->id??0),
                'end' => true,
                ])
                @endcomponent
            </div>
            <div class="row g-0">
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $providors,
                'data' => $organization->food_weights_count,
                'icon' => 'ph-hamburger',
                'description' => 'foods-dec-info-card',
                'label' => trans('translation.Foods and accessories'),
                'bgcolor' => 'bg-primary-subtle',
                'iccolor' => 'text-primary',
                'target' => 'total_food_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $users,
                'icon' => 'ph-buildings',
                'label' => trans('translation.sectors'),
                'description' => 'sectors-dec-info-card',
                'bgcolor' => 'bg-primary-subtle',
                'iccolor' => 'text-primary',
                'target' => 'total_sectors_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                'data' => $organization->nationality_organizations_count,
                'icon' => 'ph-flag',
                'description' => 'nationalities-dec-info-card',
                'label' => trans('translation.nationalities'),
                'bgcolor' => 'bg-light',
                'iccolor' => 'text-body',
                'target' => 'total_nationalities_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $submitted_forms,
                'icon' => 'ph-truck',
                'label' => trans('translation.recieved_meals'),
                'description' => 'received-meals-dec-info-card',
                'bgcolor' => 'bg-light',
                'iccolor' => 'text-body',
                'target' => 'total_recieved_meals_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $meals,
                'icon' => 'ph-hamburger',
                'description' => 'quantity-food-supports-dec-info-card',
                'label' => trans('translation.quantity_food_supports'),
                'bgcolor' => 'bg-success-subtle',
                'iccolor' => 'text-success-emphasis',
                'target' => 'total_quantity_food_supports_' . ($organization->id??0),
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                // 'col' => 'col-md-2',
                // 'data' => $supports->where('type',2),
                'icon' => 'ph-drop',
                'description' => 'quantity-water-supports-dec-info-card',
                'label' => trans('translation.quantity_water_supports'),
                'bgcolor' => 'bg-success-subtle',
                'iccolor' => 'text-success-emphasis',
                'target' => 'total_quantity_water_supports_' . ($organization->id??0),
                'end' => true,
                ])
                @endcomponent
            </div>
        </div>
    </div>
</div>
