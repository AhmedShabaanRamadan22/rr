@foreach ($meals as $meal)
    <h4 style="text-align: center;font-size: 16px;">وجبة {{$meal->period->name}} يوم {{$meal->day_date}}</h4>
    <div class="">
        <table class="body-table" style="width:100%">

            <tr>
                <th>
                    حالة الوجبة
                </th>
                <td style="background-color: {{$meal->status->color}}">{{$meal->status->name}}</td>
            </tr>
            <tr>
                <th>
                    قائمة الاطعمة
                </th>
                <td style="text-align:center">
                    @foreach($meal->food_weights as $key => $food_weight)
                    {{ $food_weight->food_name }}
                    @if($key % 2 == 0 && $key + 1 < count($meal->food_weights))
                    |
                    @elseif($key % 2 != 0 && !$loop->last)
                    <br>
                    @endif
                    @endforeach
                </td>
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
                <td style="text-align:center">{{\Carbon\Carbon::parse($meal->start_time . ' ' . $meal->day_date)->format('h:i:sa | Y/m/d') }}</td>
                <td style="text-align:center">{{is_null(($first_meal_stage = $meal->meal_organization_stages->where('arrangement', '1')->first() ?? null)?->done_at ?? null) ?  trans('translation.not-started-yet') : \Carbon\Carbon::parse($first_meal_stage->done_at)->format('h:i:sa | Y/m/d')}}</td>
                <td style="text-align:center">
                    {{-- @dd($meal->meal_organization_stages->where('arrangement', '1')->first()); --}}
                    @php
                        $start_date_time = Carbon\Carbon::parse($meal->day_date . ' ' . $meal->start_time);
                        if($first_meal_stage){
                            $start_date_time_with_latency_buffer = $start_date_time->addMinutes($first_meal_stage->duration);
                            if ($first_meal_stage->done_at) {
                                if ($first_meal_stage->done_at > $start_date_time_with_latency_buffer) {
                                    echo trans('translation.started-late');
                                }
                                else{
                                    echo trans('translation.on-time');
                                }
                            }
                            else if ( Carbon\Carbon::now() > $start_date_time_with_latency_buffer ) {
                                echo trans('translation.late');
                            }
                            else{
                                echo trans('translation.not-started-yet');
                            }
                        }
                        else{
                            echo trans('translation.no-first-stage');
                        }
                    @endphp
                </td>
            </tr>
            <tr>
                <th>نهاية الوقت</th>
                <td style="text-align:center">{{\Carbon\Carbon::parse($meal->end_time . ' ' . $meal->day_date)->format('h:i:sa | Y/m/d') }}</td>
                <td style="text-align:center">{{is_null($last_stage = $meal->meal_organization_stages->where('arrangement', $meal->meal_organization_stages->count())->first()->done_at ?? null) ? trans('translation.not-delivered') : \Carbon\Carbon::parse($last_stage)->format('h:i:sa | Y/m/d') }}</td>
                <td style="text-align:center">
                    @php
                        if ($meal->status_id == App\Models\Status::CLOSED_MEAL_FOR_SUPPORT){
                            echo trans('translation.closed-due-to-support');
                        }
                        elseif ($meal->status_id == App\Models\Status::CLOSED_MEAL){
                            echo trans('translation.closed');
                        }
                        else{
                            if ($last_stage) {
                                if ($last_stage > Carbon\Carbon::parse($meal->day_date . ' ' . $meal->end_time)) {
                                    echo trans('translation.late');
                                } else {
                                    echo trans('translation.on-time');
                                }
                            } elseif (Carbon\Carbon::now() > Carbon\Carbon::parse($meal->day_date . ' ' . $meal->end_time)) {
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
    <div class="">
        <h4 style="text-align: center">مراحل الوجبة</h4>
        <table class="body-table" style="width:100%">
            <tr>
                <th style="text-align:center">المرحلة</th>
                <th style="text-align:center">المدة المتوقعة</th>
                <th style="text-align:center">بداية المرحلة الفعلية</th>
                <th style="text-align:center">نهاية المرحلة الفعلية</th>
            </tr>

            @foreach($meal->meal_organization_stages as $index => $meal_organization_stage)
                <tr>
                    <th>{{$meal_organization_stage->organization_stage->stage_bank->name}}</th>
                    <td style="text-align:center">{{($meal_organization_stage->duration ?? $meal_organization_stage->organization_stage->stage_bank->duration) . ' ' . trans('translation.minutes');}}</td>
                    <td style="text-align:center">
                        {{($meal_organization_stage->arrangement != 1 ? (is_null($meal_organization_stage->previous_stage()) ? '-' : \Carbon\Carbon::parse($meal_organization_stage->previous_stage()->done_at)->format('h:i:sa | Y/m/d')) : '-')}}
                    </td>
                    <td style="text-align:center">{{is_null($meal_organization_stage->done_at) ? '-' : \Carbon\Carbon::parse($meal_organization_stage->done_at)->format('h:i:sa | Y/m/d')}}</td>
                </tr>
            @endforeach
        </table>
    </div>
    @if (!$loop->last)
        <pagebreak />
    @endif
@endforeach