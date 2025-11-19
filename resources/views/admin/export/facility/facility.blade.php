@component('admin.export.pdf')
    @slot('content')
        @component('admin.export.facility.sections.info-facility',['data'=>['body_content'=>$data['body_content']]])@endcomponent
        @component('admin.export.facility.sections.owner-facility',['data'=>['body_content'=>$data['body_content']]])@endcomponent
        @component('admin.export.facility.sections.employee-table',['data'=>['body_content'=>$data['body_content'],'employee_attachment_lables'=>$data['employee_attachment_lables']]])@endcomponent
        @component('admin.export.components.attachment', [
            'label' => 'مرفقات المنشأة',
            'organization' => [],
            'attachments' => $data['body_content']->attachments,
        ])
        @endcomponent
    @endslot
@endcomponent
