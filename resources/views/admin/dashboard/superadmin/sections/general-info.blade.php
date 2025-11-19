{{-- <div class="col-xl-6">
    <div class="card card-height-100 border-0 overflow-hidden">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">الإحصائيات</h4>
        </div>
        <div class="card-body p-0">
            <div class="row row-cols-3 justify-content-center">
                @component('components.dashboard.statics-info-card', [
                    'data' => $users,
                    'icon' => 'ph-users',
                    'label' => 'translation.users',
                    'bgcolor' => 'bg-primary-subtle',
                    'iccolor' => 'text-primary',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-users-three',
                    'label' => 'مزودي الخدمة',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-police-car-light',
                    'label' => 'المراقبين',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders,
                    'icon' => 'ph-text-align-justify',
                    'label' => 'الطلبات',
                    'bgcolor' => 'bg-info-subtle',
                    'iccolor' => 'text-info',
                    'end' => true,
                    'bottom' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders,
                    'icon' => 'ph-cooking-pot',
                    'label' => 'طلبات إسناد الوجبات',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders,
                    'icon' => 'ph-drop',
                    'label' => 'طلبات إسناد المياه',
                    'bgcolor' => 'bg-info-subtle',
                    'iccolor' => 'text-info',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-fork-knife',
                    'label' => 'الوجبات',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-fork-knife',
                    'label' => 'البلاغات',
                    'bgcolor' => 'bg-danger-subtle',
                    'iccolor' => 'text-danger',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 4),
                    'icon' => 'ph-buildings',
                    'label' => 'عدد المنشآت',
                    'bgcolor' => 'bg-warning-subtle',
                    'iccolor' => 'text-warning',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
            </div>
        </div>
    </div>
</div> --}}


<div class="col-xl-6">
    <div class="card card-height-100 border-0 overflow-hidden">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">الإحصائيات</h4>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                @component('components.dashboard.statics-info-card', [
                    'data' => $users,
                    'icon' => 'ph-users',
                    'label' => 'translation.users',
                    'bgcolor' => 'bg-primary-subtle',
                    'iccolor' => 'text-primary',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-users-three',
                    'label' => 'مزودي الخدمة',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-police-car-light',
                    'label' => 'المراقبين',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success',
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders,
                    'icon' => 'ph-text-align-justify',
                    'label' => 'الطلبات',
                    'bgcolor' => 'bg-info-subtle',
                    'iccolor' => 'text-info',
                    'end' => true,
                ])
                @endcomponent

            </div>
            <div class="row g-0">
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders,
                    'icon' => 'ph-cooking-pot',
                    'label' => 'طلبات إسناد الوجبات',
                    'bgcolor' => 'bg-success-subtle',
                    'iccolor' => 'text-success',
                    'bottom' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders,
                    'icon' => 'ph-drop',
                    'label' => 'طلبات إسناد المياه',
                    'bgcolor' => 'bg-info-subtle',
                    'iccolor' => 'text-info',
                    'bottom' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-fork-knife',
                    'label' => 'الوجبات',
                    'bgcolor' => 'bg-light',
                    'iccolor' => 'text-body',
                    'bottom' => true,
                ])
                @endcomponent
                @component('components.dashboard.statics-info-card', [
                    'data' => $orders->whereIn('status_id', 5),
                    'icon' => 'ph-fork-knife',
                    'label' => 'البلاغات',
                    'bgcolor' => 'bg-danger-subtle',
                    'iccolor' => 'text-danger',
                    'bottom' => true,
                    'end' => true,
                ])
                @endcomponent
            </div>
        </div>
    </div>
</div>