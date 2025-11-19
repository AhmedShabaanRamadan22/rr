@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        @component('admin.export.order_details.facility-general-info', [
            'sector' => $data['sector'],
            'facility' => $data['facility'],
            'organization' => $data['organization_data'],
            'submitted_form' => $data['submitted_form_data'],
            'answer_service' => $data['answer_service'],
            'rows' => [
                'اسم الاستمارة' => $data['form_data']->name,
                'رمز الاستمارة' => $data['form_data']->code,
                'الوصف' => $data['form_data']->description,
                'خدمة المنظمة' => $data['body_content']->form->organization_service->service->name,
                // 'رمز المراقب' => $data['submitted_form_data']->user->monitor->code,
                'تصنيف المركز' => $data['body_content']->form->organization_category->category->name,
                'وقت بدء الإجابة' => \Carbon\Carbon::parse($data['body_content']->created_at)->format('h:i:sa | Y/m/d'),
                'وقت الانتهاء من الإجابة' => \Carbon\Carbon::parse($data['body_content']->updated_at)->format('h:i:sa | Y/m/d'),
            ]
        ])@endcomponent
        <pagebreak />
        <div class="">
            <h4 style="text-align: center">معلومات عامة</h4>
            <table class="body-table" style="width:100%">
                <tr>
                    <th>#</th>
                    <th>التعريف</th>
                    <th>المعلومة</th>
                </tr>
                <tr>
                    <th>15</th>
                    <td>المراقب</td>
                    <td>{{$data['body_content']->creator_label}}</td>
                </tr>
                <tr>
                    <th>16</th>
                    <td>المشرف</td>
                    <td>{{$data['body_content']->order_sector->sector->supervisor_label}}</td>
                </tr>
                <tr>
                    <th>17</th>
                    <td>القائد التشغيلي</td>
                    <td>{{$data['body_content']->order_sector->sector->boss_label}}</td>
                </tr>
                <tr>
                    <th>18</th>
                    <td>مدير التشغيل الميداني</td>
                    <td>{{$data['body_content']->order_sector->sector->classification->organization->operational_manager_name}}</td>
                </tr>
            </table>
        </div>
        {{-- @dd($data['body_content']->form->sections_has_question) --}}

        <pagebreak />
        @component('admin.export.components.submitted-form-template', ['submitted_form' => $data['submitted_form_data'], 'answer_service' => $data['answer_service']])@endcomponent
    @endslot
@endcomponent
