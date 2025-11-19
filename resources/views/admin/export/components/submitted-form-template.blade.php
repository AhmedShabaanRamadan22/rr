 @foreach ($submitted_form->form->sections_has_question as $sectionIndex => $section)
    <h4 style="text-align: center;">{{'قسم ' . $loop->iteration . ': ' . $section->name}}</h4>
    @if (count($questions = $section->answered_questions($submitted_form->id)->get()) > 0)
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
                        @if(($answer = $question->answer($submitted_form->id)->first())->actual_value == 'not-answered')
                            {{trans('translation.not-answered')}}
                        @else
                            @php
                                $type = $question->question_bank_organization->question_bank->question_type
                            @endphp

                            @if($question->question_type_name == 'radio')
                                {!! $answer_service->generateRadioAnswerValue($answer) !!}
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
    {{-- @if($sectionIndex < count($data['body_content']->form->sections_has_question) - 1)
        <pagebreak />
    @endif --}}
    @if (!$loop->last)
        <pagebreak />
    @endif
@endforeach