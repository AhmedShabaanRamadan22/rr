@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        @component('admin.export.order_details.facility-general-info', [
            'sector' => $data['body_content']->order_sector->sector,
            'facility' => $data['body_content']->order_sector->order->facility,
            'organization' => $data['organization_data'],
            'rows' => [
                'نوع الإسناد' => $data['body_content']->type_name,
                'حالة الإسناد' => ['color' => $data['body_content']->status->color, 'value' => $data['body_content']->status->name],
                'الفترة' => $data['body_content']->period->name,
                'سبب طلب الإسناد' => ['color' => $data['body_content']->reason_danger->danger->color, 'value' => $data['body_content']->reason_danger->reason->name],
                'الكمية المطلوبة' => $data['body_content']->quantity,
                'الكمية المسندة' => $data['body_content']->assists->where('status_id', App\Models\Status::DELIVERED_ASSIST)->sum('quantity')  ?? trans('translation.no-data'),
                // 'هل تم إيقاف طلب الدعم من قبل المراقب؟' => $data['body_content']->has_enough == 0 ? 'لا' : 'نعم',
                // 'رمز المراقب' => $data['body_content']->user->monitor->code,
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
                    <th>14</th>
                    <td>المراقب</td>
                    <td>{{$data['body_content']->creator_label}}</td>
                </tr>
                <tr>
                    <th>15</th>
                    <td>المشرف</td>
                    <td>{{$data['body_content']->order_sector->sector->supervisor_label}}</td>
                </tr>
                <tr>
                    <th>16</th>
                    <td>القائد التشغيلي</td>
                    <td>{{$data['body_content']->order_sector->sector->boss_label}}</td>
                </tr>
                <tr>
                    <th>17</th>
                    <td>مدير التشغيل الميداني</td>
                    <td>{{$data['body_content']->order_sector->sector->classification->organization->operational_manager_name}}</td>
                </tr>
            </table>
        </div>
        @component('admin.export.components.notes', [
            'notes' => $data['body_content']->notes,
        ])
        @endcomponent

        @component('admin.export.components.attachment', [
            'attachments' => $data['body_content']->attachments,
            'organization' => $data['organization_data'],
        ])
        @endcomponent

        <pagebreak />
        <div class="">
            <h4 style="text-align: center;">معلومات الدعم</h4>
            <div style="text-align: center">
                <div>
                    @php
                        $percentage = ($data['body_content']->assists->where('status_id', App\Models\Status::DELIVERED_ASSIST)->sum('quantity') / $data['body_content']->quantity) * 100
                    @endphp
                    <img height="100px"
                         src="https://quickchart.io/chart?c=%7Btype%3A'radialGauge'%2Cdata%3A%7Bdatasets%3A%5B%7Bdata%3A%5B{{ $percentage }}%5D%2CbackgroundColor%3A'%23{{ '9E8435' }}'%7D%5D%7D%7D">
                </div>
                <div style="margin-top: 1%">
                    <span>نسبة الوجبات المسلمة</span>
                </div>
            </div>
            @if ($data['body_content']->assists->count() > 0)
                <div style="margin-top: 1%;text-align:right">
                    <span>بيانات الدعم</span>
                    <table class="body-table" style="width:100%">

                        <tr>
                            <th>#</th>
                            <th>أسند بواسطة</th>
                            <th>مصدر الدعم</th>
                            <th>تم التوصيل بواسطة</th>
                            <th>الكمية</th>
                            <th>حالة الاستلام</th>
                            {{-- <th style="text-align:center">التوقيع</th>
                            <th>المرفقات</th> --}}
                        </tr>
                        @foreach ($data['body_content']->assists as $index => $assist)
                            <tr>
                                <th rowspan="{{2 + $assist->answers->count()}}">{{ $index + 1 }}</th>
                                <td>{{ $assist->assigner->name ?? '' }}</td>
                                <td>{{ $assist->assist_from ?? '' }}</td>
                                <td>{{ $assist->assistant->name ?? '' }}</td>
                                <td>{{ $assist->quantity ?? '' }}</td>
                                <td>{{ $assist->status->name_ar }}</td>
                            </tr>
                            <!-- <tr>
                                <td colspan="5">
                                    @if ($assist->assist_attachments->isNotEmpty())
                                        @component('admin.export.components.barcode-table', ['attachments' => $assist->assist_attachments, 'organization' => $data['organization_data'], 'new_line_at' => 7])@endcomponent
                                    @else
                                        <div>
                                            {{trans('translation.no-data')}}
                                        </div>
                                    @endif
                                </td>

                            </tr> -->
                            {{-- 
                            <tr>
                                <td colspan="5">
                                    @if (isset($assist->signature_attachment))
                                        <img height="50px"
                                             src="{{ App::environment('local') ? $data['header_default_logo'] : $assist->signature_attachment->url }}">
                                    @else
                                        لا يوجد
                                    @endif
                                </td>
                            </tr>
                            --}}
                            <tr> 
                                <th colspan="2">السؤال</th>
                                <th colspan="3" style="text-align:center">الإجابة</th>
                            </tr>
                            @foreach ( $assist->answers as $answer)
                                <tr>
                                    <td colspan="2">
                                        {{($question = $answer->question)->question_bank_organization->question_bank->content ?? '-'}}
                                    </td>
                                    <td colspan="3">
                                        {!! $data['answer_service']->generateAnswerValue($answer,$question,true) !!}
                                    </td>
                                </tr>
                                
                            @endforeach

                        @endforeach
                    </table>
                </div>
            @else
                <div style="text-align: center">
                    <h2 style="color: red">لا يوجد دعم حتى الآن</h2>
                </div>
            @endif
        </div>
        @component('admin.export.components.color-keys-template')
            @component('admin.export.components.color-keys', ['items' => $data['statuses'], 'description' => 'description'])@endcomponent
            @component('admin.export.components.color-keys', ['items' => $data['danger_levels'], 'description' => 'danger_description'])@endcomponent
        @endcomponent
    @endslot
@endcomponent
