@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        @component('admin.export.order_details.facility-general-info', [
            'sector' => $data['sector'],
            'organization' => $data['organization_data'],
            'rows' => [
                'رمز البلاغ' => $data['body_content']->code,
                'خطورة البلاغ' => ['color' => $data['body_content']->reason_danger->danger->color, 'value' => $data['body_content']->reason_danger->reason->name],
                // 'رمز المراقب' => $data['body_content']->user->monitor->code,
                'المراقب' => $data['body_content']->creator_label,
                'المشرف' => $data['body_content']->order_sector->sector->supervisor_label,
                'القائد التشغيلي' => $data['body_content']->order_sector->sector->boss_label,
                'مدير التشغيل الميداني' => $data['body_content']->order_sector->sector->classification->organization->operational_manager_name,
                // 'رقم برافو المراقب' => $data['body_content']->user->bravo?->number ?? trans('translation.no-data'),
                'حالة البلاغ' => ['color' => $data['body_content']->status->color, 'value' => $data['body_content']->status->name],
                'تاريخ إنشاء البلاغ' => \Carbon\Carbon::parse($data['body_content']->created_at)->format('h:i:sa | Y/m/d'),
                'تاريخ إغلاق البلاغ' => is_null($data['body_content']->closed_at) ? trans('translation.not-closed-yet') : \Carbon\Carbon::parse($data['body_content']->closed_at)->format('h:i:sa | Y/m/d'),
                'تاريخ آخر تحديث' => \Carbon\Carbon::parse($data['body_content']->updated_at)->format('h:i:sa | Y/m/d'),//! requested from Eng.Shaimaaaaaa as Eng.Omar said, Followed by Eng.Albaraa, Tested By Eng.Abeer,,,, after that, was changed by Abu Lara

            ]
        ])@endcomponent
        @component('admin.export.components.notes', [
            'notes' => $data['body_content']->notes,
        ])
        @endcomponent
        @component('admin.export.components.attachment', [
            'attachments' => $data['body_content']->attachments,
            'organization' => $data['organization_data'],
        ])
        @endcomponent

        @component('admin.export.components.color-keys-template')
            @component('admin.export.components.color-keys', ['items' => $data['statuses'], 'description' => 'description'])@endcomponent
            @component('admin.export.components.color-keys', ['items' => $data['danger_levels'], 'description' => 'danger_description'])@endcomponent
        @endcomponent
    @endslot
@endcomponent
