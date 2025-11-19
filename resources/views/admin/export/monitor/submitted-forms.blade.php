@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        {{-- ?? submitted forms --}}
        <div>
            <h3 style="text-align: center;">{{'استمارة: ' . $data['submitted_form']->form->name}}</h3>
            @component('admin.export.order_details.facility-general-info', [
                'sector' => $data['submitted_form']->order_sector->sector,
                'facility' => $data['submitted_form']->order_sector->order->facility,
                'organization' => $data['submitted_form']->order_sector->sector->classification->organization,
                'submitted_form' => $data['submitted_form'],
                'answer_service' => $data['answer_service'],
                'rows' => [
                    'الوصف' => $data['submitted_form']->form->description,
                    'المشرف' => $data['submitted_form']->order_sector->sector->supervisor_label,
                    'القائد التشغيلي' => $data['submitted_form']->order_sector->sector->boss_label,
                    'مدير التشغيل الميداني' => $data['submitted_form']->order_sector->sector->classification->organization->operational_manager_name,
                    'وقت بدء الإجابة' => \Carbon\Carbon::parse($data['submitted_form']->created_at)->format('h:i:sa | Y/m/d'),
                    'وقت الانتهاء من الإجابة' => \Carbon\Carbon::parse($data['submitted_form']->updated_at)->format('h:i:sa | Y/m/d'),
                ]
            ])@endcomponent
            <pagebreak/>
            @component('admin.export.components.submitted-form-template', ['submitted_form' => $data['submitted_form'], 'answer_service' => $data['answer_service']])@endcomponent
        </div>
    @endslot
@endcomponent
