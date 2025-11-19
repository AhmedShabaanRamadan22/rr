@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        {{-- @component('admin.export.order_details.facility-general-info', ['order_sector' => $data['order_sector'], 'organization' => $data['organization_data']])@endcomponent --}}
        <h3 style="text-align: center;">{{'استمارة: '. $data['submitted_form']->form->name}}</h3>
        <div class="">
            <h4 style="text-align: center">معلومات عامة</h4>
            <table class="body-table" style="width:100%">
                <tr>
                    <th>التعريف</th>
                    <th>المعلومة</th>
                </tr>
                <tr>
                    <th>وصف الاستمارة</th>
                    <td>{{$data['submitted_form']->form->description}}</td>
                </tr>
                <tr>
                    <th>وقت بدء الإجابة</th>
                    <td>{{\Carbon\Carbon::parse($data['submitted_form']->created_at)->format('h:i:sa | Y/m/d')}}</td>
                </tr>
                <tr>
                    <th>وقت الانتهاء من الإجابة</th>
                    <td>{{\Carbon\Carbon::parse($data['submitted_form']->created_at)->format('h:i:sa | Y/m/d')}}</td>
                </tr>
                <tr>
                    <th>المراقب</th>
                    <td>{{$data['submitted_form']->creator_label}}</td>
                </tr>
                <tr>
                    <th>المشرف</th>
                    <td>{{$data['submitted_form']->order_sector->sector->supervisor_label}}</td>
                </tr>
                <tr>
                    <th>القائد التشغيلي</th>
                    <td>{{$data['submitted_form']->order_sector->sector->boss_label}}</td>
                </tr>
                <tr>
                    <th>مدير التشغيل الميداني</th>
                    <td>{{$data['submitted_form']->order_sector->sector->classification->organization->operational_manager_name}}</td>
                </tr>
            </table>
        </div>
        <pagebreak/>
        @foreach ($data['submitted_form']->form->sections_has_question as $sectionIndex => $section)
            <h4 style="text-align: center;padding-top: 5%; ">{{'القسم ' . $loop->iteration . ': ' . $section->name}}</h4>
            @if (count($questions = $section->answered_questions($data['submitted_form']->id)->get()) > 0)
            <div class="">
                <table class="body-table" style="width:100%">
                    <tr>
                        <th>#</th>
                        <th>السؤال</th>
                        <th style="text-align:center">الإجابة</th>
                    </tr>
                    @foreach ($questions as $questionIndex => $question)
                    {{-- @dd($questionIndex , $question) --}}
                        <tr>
                            <th>{{ $questionIndex + 1 }}</th>
                            <td style="text-align:right">{{$question->content}}</td>
                            <td style="text-align:center">
                                @if(($answer = $question->answer($data['submitted_form']->id)->first())->actual_value == 'not-answered')
                                    {{trans('translation.not-answered')}}
                                @else
                                    @php
                                        $type = $question->question_bank_organization->question_bank->question_type
                                    @endphp

                                    @if($question->question_type_name == 'radio')
                                        {!! $data['answer_service']->generateRadioAnswerValue($answer) !!}
                                    @elseif ($type->has_option)
                                        @foreach ($answer->actual_value as $value)
                                            {{ $value->content }}
                                        @endforeach

                                    @elseif ($type->name == 'file' || $type->name == 'files' || $type->name == 'signature')
                                        {{-- files --}}

                                        <table class="barcode-table">
                                            <tr>
                                                @foreach ($answer->actual_value as $index => $answer)
                                                    @component('admin.export.components.barcode-td', ['url' => $answer['url']])@endcomponent
                                                    @if (++$loop->index % 3 == 0)
                                                        <br/>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        </table>

                                    @elseif(in_array($question->question_type_id, $answer?->specialQuestions()))
                                        {{  trans('translation.' . $answer?->actual_value ) }}

                                    @else
                                        {{-- string --}}
                                        {{ $answer?->actual_value }}
                                    @endif
                                    
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @else
                <h6 style="text-align: center; ">
                    لم يتم الإجابة على هذا القسم بعد    
                </h6>
            @endif
            @if($sectionIndex < count($data['submitted_form']->form->sections_has_question) - 1)
                <pagebreak />
            @endif
        @endforeach
        {{-- <pagebreak/>
        @if ($data['submitted_forms']->isNotEmpty())
            @foreach ($data['submitted_forms'] as $data['submitted_form'])
                @if(!$loop->last)
                    <pagebreak/>
                @endif
            @endforeach
        @else
        <div style="text-align:center; font-size:large; padding: 5rem">
            {{trans('translation.no-data')}}
        </div>
        @endif --}}
    @endslot
@endcomponent
