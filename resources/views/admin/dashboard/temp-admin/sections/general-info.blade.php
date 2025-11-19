<div class="col-xl">
    <div class="card card-height-100 border-0 overflow-hidden">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">{{trans("translation.Statistics")}}</h4>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                @component('components.dashboard.statics-info-card', [
                    // // 'col' => 'col-md-2',
                    'data' => ($users->merge($providors)),
                    'icon' => 'ph-users',
                    'label' => 'translation.users',
                    'bgcolor' => 'bg-primary-subtle',
                    'iccolor' => 'text-primary',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $monitors,
                    'icon' => 'ph-police-car',
                    'label' => 'مراقبين',
                    'bgcolor' => 'bg-primary-subtle',
                    'iccolor' => 'text-primary',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $facilities,
                    'icon' => 'ph-buildings',
                    'label' => 'المنشآت',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $orders,
                    'icon' => 'mdi mdi-reorder-horizontal',
                    'label' => 'الطلبات',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $supports,
                    'icon' => 'ph-truck',
                    'label' => 'طلبات الاسناد',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success-emphasis',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => ($supports)->where('type',3),
                    'icon' => 'ph-drop-half-bottom',
                    'label' => 'اسناد المياه',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success-emphasis',
                    'end' => true,
                ])
                @endcomponent
            </div>
            <div class="row g-0">
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $providors,
                    'icon' => 'ph-users-four',
                    'label' => 'مزودي الخدمة',
                    'bgcolor' => 'bg-primary-subtle',
                    'iccolor' => 'text-primary',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $users,
                    'icon' => 'ph-users-three',
                    'label' => 'الموظفين',
                    'bgcolor' => 'bg-primary-subtle',
                    'iccolor' => 'text-primary',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $tickets,
                    'icon' => 'ph-ticket',
                    'label' => 'البلاغات',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $submitted_forms,
                    'icon' => 'ph-file-dotted',
                    'label' => 'الاستمارات',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $meals,
                    'icon' => 'ph-fork-knife',
                    'label' => 'الوجبات',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success-emphasis',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    // 'col' => 'col-md-2',
                    'data' => $supports->where('type',2),
                    'icon' => 'ph-cooking-pot',
                    'label' => 'اسناد الوجبات',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success-emphasis',
                    'end' => true,
                ])
                @endcomponent
            </div>
        </div>
    </div>
</div>
