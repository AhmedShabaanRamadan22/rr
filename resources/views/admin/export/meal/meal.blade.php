@component('admin.export.pdf', ['data' => $data])
@slot('content')
@php
    $foods = "";
    $total = count($data['body_content']->food_weights);
    foreach($data['body_content']->food_weights as $key => $food_weight){
        $foods .= $food_weight->food_name;
        if($key + 1 < count($data['body_content']->food_weights)){
            $foods .= ' | ';
        }
    }

    $stage = '';
    $current_stage = $data['body_content']->meal_organization_stages->whereNull('done_at')->sortBy('arrangement')->first();
    if ($current_stage) {
        $stage = $current_stage->organization_stage->stage_bank->name;
    } else {
        $stage = trans('translation.meal-finished');
    }


    $total_stages = $data['body_content']->meal_organization_stages->count();
    $total_done_stages = $data['body_content']->meal_organization_stages->whereNotNull('done_at')->count();
    $percentage = $total_stages == 0 ? "0.00" : number_format(($total_done_stages / $total_stages) * 100, 2);
    $percentage .= '%';
@endphp
@component('admin.export.order_details.facility-general-info', [
    'sector' => $data['sector'],
    'organization' => $data['organization_data'],
    'order_sector' => $data['order_sector'],
    'rows' => [
        'الوجبة' => 'وجبة ' . $data['body_content']->period->name . ' يوم ' . $data['body_content']->day_date,
        'حالة الوجبة' => ['color' => $data['body_content']->status->color, 'value' => $data['body_content']->status->name],
        'المراقب' => $data['body_content']->meal_organization_stage->creator_label ?? trans('translation.no-data'),
        'المشرف' => $data['body_content']->order_sector->sector->supervisor_label,
        'القائد التشغيلي' => $data['body_content']->order_sector->sector->boss_label,
        'مدير التشغيل الميداني' => $data['body_content']->order_sector->sector->classification->organization->operational_manager_name,
        ]
    ])@endcomponent
    <pagebreak/>
    <div>
        <h4 style="text-align: center">معلومات الوجبة</h4>
        <table class="body-table" style="width:100%">
            <tr>
                <th style="text-align:center">التعريف</th>
                <th style="text-align:center">المعلومة</th>
            </tr>
            <tr>
                <th style="text-align:center">قائمة الأطعمة</th>
                <td style="text-align:center">{{$foods}}</td>
            </tr>
            <tr>
                <th style="text-align:center">المرحلة الحالية</th>
                <td style="text-align:center">{{$stage}}</td>
            </tr>
            <tr>
                <th style="text-align:center">نسبة الإنجاز</th>
                <td style="text-align:center">{{$percentage}}</td>
            </tr>
        </table>
    </div>
    <div class="">
        <h4 style="text-align: center">أوقات الوجبة</h4>
        <table class="body-table" style="width:100%">
            <tr>
                <th style="text-align:center">التعريف</th>
                <th style="text-align:center">الوقت المتوقع</th>
                <th style="text-align:center">الوقت الفعلي</th>
                <th style="text-align:center">الحالة</th>
            </tr>
            <tr>
                <th>بداية الوقت</th>
                <td style="text-align:center">{{\Carbon\Carbon::parse($data['body_content']->start_time . ' ' . $data['body_content']->day_date)->format('h:i:sa | Y/m/d') }}</td>
                <td style="text-align:center">{{is_null($first_stage = $data['body_content']->meal_organization_stages->where('arrangement', '1')->first() ?? null) ? trans('translation.not-started-yet') : \Carbon\Carbon::parse($first_stage->done_at)->format('h:i:sa | Y/m/d') }}</td>
                <td style="text-align:center">
                    @php
                        $start_date_time = Carbon\Carbon::parse($data['body_content']->day_date . ' ' . $data['body_content']->start_time)->addMinutes($first_stage->duration);
                        $done_at = $first_stage?->done_at ?? null;
                        if ($done_at) {
                            if ($done_at > $start_date_time) {
                                echo trans('translation.started-late');
                            } else {
                                echo trans('translation.on-time');
                            }
                        } elseif (Carbon\Carbon::now() > $start_date_time) {
                            echo trans('translation.late');
                        } else {
                            echo trans('translation.not-started-yet');
                        }
                    @endphp
                </td>
            </tr>
            <tr>
                <th>نهاية الوقت</th>
                <td style="text-align:center">{{\Carbon\Carbon::parse($data['body_content']->end_time . ' ' . $data['body_content']->day_date)->format('h:i:sa | Y/m/d') }}</td>
                <td style="text-align:center">{{is_null($last_stage = $data['body_content']->meal_organization_stages->where('arrangement', $data['body_content']->meal_organization_stages->count())->first()->done_at ?? null) ? trans('translation.not-delivered') : \Carbon\Carbon::parse($last_stage)->format('h:i:sa | Y/m/d') }}</td>
                <td style="text-align:center">
                    @php
                        if ($data['body_content']->status_id == App\Models\Status::CLOSED_MEAL_FOR_SUPPORT){
                            echo trans('translation.closed-due-to-support');
                        }
                        elseif ($data['body_content']->status_id == App\Models\Status::CLOSED_MEAL){
                            echo trans('translation.closed');
                        }
                        else{
                            if ($last_stage) {
                                if ($last_stage > Carbon\Carbon::parse($data['body_content']->day_date . ' ' . $data['body_content']->end_time)) {
                                    echo trans('translation.late');
                                } else {
                                    echo trans('translation.on-time');
                                }
                            } elseif (Carbon\Carbon::now() > Carbon\Carbon::parse($data['body_content']->day_date . ' ' . $data['body_content']->end_time)) {
                                echo trans('translation.late-no-delivery') ;
                            } else {
                            echo trans('translation.not-delivered');
                            }
                        }
                    @endphp
                </td>
            </tr>
        </table>
    </div>
    @if (!($data['body_content']->meal_organization_stage->arrangement == 1 && $data['body_content']->meal_organization_stage->done_at == null))
        <pagebreak />
        <div>
            <h4 style="text-align: center;">تفاصيل مراحل الوجبة</h4>
            @foreach($data['body_content']->meal_organization_stages->sortBy('arrangement') as $index => $meal_organization_stage)
                @if ($meal_organization_stage->status_id != App\Models\Status::DONE_MEAL_STAGE)
                    @continue
                @endif
                @if(!$loop->first)
                    <pagebreak/>
                @endif
                <div style="">
                    <h4 style="text-align: center">مرحلة {{$meal_organization_stage->arrangement . ': ' . $meal_organization_stage->organization_stage->sortable_name}}</h4>
                    <div style="margin-top: 1%"></div>

                    <div class="hr-space">
                        <table class="body-table" style="width:100%">
                            <tr>
                                <th>تم بواسطة</th>
                                <th>وقت الانتهاء من المرحلة</th>
                                <th>المدة المتوقعة للمرحلة</th>
                                <th>المدة الفعلية للمرحلة</th>
                            </tr>
                            <tr>
                                <td>
                                    {{$meal_organization_stage->user->name ?? '-'}}
                                </td>
                                <td>
                                    {{$meal_organization_stage->done_at ?? '-'}}
                                </td>
                                <td>
                                    {{$meal_organization_stage->duration . ' ' . trans('translation.minutes')?? '-'}}
                                </td>
                                    @php
                                    $color = '';
                                    $value = '';
                                        if (!is_null($meal_organization_stage->calculate_duration())){
                                            $actual_duration = \Carbon\CarbonInterval::seconds($meal_organization_stage->calculate_duration())->cascade(); //->forHumans();
                                            $expected_duration = \Carbon\CarbonInterval::seconds($meal_organization_stage->duration * 60)->cascade(); //->forHumans();
                                            $value = $actual_duration->forHumans();
                                            if ($actual_duration->greaterThan($expected_duration)) {
                                                $color = '#EE4E4E'; //red
                                            } else {
                                                $color = '#A1DD70'; //Green
                                            }
                                        } else {
                                            $value = '-';
                                        }
                                    @endphp
                                <td style="background-color: {{$color}}">
                                    {{$value}}
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="">
                        <p class="note-title">الأسئلة</p>
                        <table class="body-table" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>السؤال</th>
                                <th>الاجابة</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($meal_organization_stage->answered_questions() as $question)
                                <tr>
                                    <th>{{$loop->iteration}}</th>
                                    <td>{{$question->question_bank_organization->question_bank->content}}</td>
                                    <td>
                                        @if (($answer = $question->meal_stage_answer($meal_organization_stage->id)->first())->actual_value == 'not-answered')
                                            {{trans('translation.not-answered')}}
                                        @else
                                            @php
                                                $type = $question->question_bank_organization->question_bank->question_type
                                            @endphp
                                            @if ($type->has_option)
                                                @foreach ($answer->actual_value as $value)
                                                    {{ $value->content }}
                                                @endforeach

                                            @elseif ($type->name == 'file' || $type->name == 'files' || $type->name == 'signature')
                                                {{-- files --}}

                                                @forelse ($question->answers->where('answerable_id', $meal_organization_stage->id)->where('answerable_type', 'App\Models\MealOrganizationStage') as $answer)
                                                    @component('admin.export.components.barcode-table', ['attachments' => $answer->actual_value, 'organization' => $data['organization_data'], 'new_line_at' => 4])@endcomponent
                                                @empty
                                                    {{ trans('translation.no-data') }}
                                                @endforelse

                                            @elseif(in_array($question->question_type_id, $answer?->specialQuestions()))
                                                {{  trans('translation.' . $answer?->actual_value ) }}

                                            @else
                                                {{-- string --}}
                                                {{ $answer?->actual_value }}
                                            @endif
                                        @endif
                                    </td>
                                @empty
                                    <div>
                                        {{ trans('translation.no-data') }}
                                    </div>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-bottom: 1%"></div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($data['body_content']->supports->isNotEmpty())
        <pagebreak/>
        <h4 style="text-align: center;">معلومات الإسناد للوجبة</h4>
        @component('admin.export.support.supports-template', ['supports' => $data['body_content']->supports, 'order_sector' => $data['order_sector'], 'organization' => $data['organization_data']])@endcomponent
    @endif

    @component('admin.export.components.color-keys-template')
        @component('admin.export.components.color-keys', ['items' => $data['meal_statuses'], 'description' => 'description'])@endcomponent
        @component('admin.export.components.color-keys', ['items' => [
            ['color' => '#EE4E4E', 'description' => 'تم الانتهاء من المرحلة في مدة أطول من المدة المجدولة'],
            ['color' => '#A1DD70', 'description' => 'تم الانتهاء من المرحلة خلال المدة المجدولة']
        ], 'description' => 'description'])@endcomponent
        @if ($data['body_content']->supports->isNotEmpty())
            @component('admin.export.components.color-keys', ['items' => $data['support_statuses'], 'description' => 'description'])@endcomponent
        @endif
    @endcomponent

    @endslot
@endcomponent
