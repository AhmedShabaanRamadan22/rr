@foreach ($items as $key => $data)
    <h4 style="text-align: center;font-size: 16px;">{{$key_label . ': ' . $key}}</h4>
    <div class="">
        <table class="body-table" style="width:100%">

            {{--    MEALS    --}}
            <tr>
                <td rowspan="{{count($data['meals']) > 0 ? count($data['meals']) : 1}}">الوجبات</td>
                @if(count($data['meals']) > 0)
                    @foreach($data['meals'] as $meal)
                        <td style="background-color: #d0e2f3">
                            <a href="{{ $meal['meal_url'] }}">
                                {{ $meal['period'] }}
                            </a>
                        </td>
                        <td colspan="2" style="background-color: {{ $meal['status_color'] }}">{{ $meal['status'] }}</td>
                        <td colspan="2" style="background-color: {{$meal_statuses[$meal['time_status']]['color']}}">{{ $meal_statuses[$meal['time_status']]['caption'] }}</td>
                        <td style="background-color: {{ $meal['has_support'] ? '#cc4125' : '#fff' }}">
                            {{ $meal['has_support'] ? 'يوجد إسناد' : 'لا يوجد إسناد' }}
                        </td>
            </tr>
            <tr>
                    @endforeach
                @else
                    <td colspan="6">لا يوجد وجبات</td>
                @endif
            </tr>

            {{--    TICKETS    --}}
            <tr>
                <td>البلاغات</td>
                @if(count($data['tickets']) > 0)
                    @foreach($dangers as $danger)
                        <td style="background-color: {{$danger->color}}">{{ $danger->level }}</td>
                        <td>{{ isset($data['tickets'][$danger->id]) ? $data['tickets'][$danger->id] : 0 }}</td>
                    @endforeach
                @else
                    <td colspan="6">لا يوجد بلاغات</td>
                @endif
            </tr>

            {{--    MEAL SUPPORT    --}}
            <tr>
                <td>إسناد الوجبات</td>
                @if($data['meal_supports']['support_count'] != 0)
                    <td>عدد الطلبات</td>
                    <td>{{$data['meal_supports']['support_count']}}</td>
                    <td>عدد الوجبات المطلوبة</td>
                    <td>{{$data['meal_supports']['needed_quantity']}}</td>
                    <td>عدد الوجبات المسلمة</td>
                    <td>{{$data['meal_supports']['delivered_quantity']}}</td>
                @else
                    <td colspan="6">لا يوجد إسناد وجبات</td>
                @endif
            </tr>

            {{--    WATER SUPPORT    --}}
            <tr>
                <td>إسناد المياه</td>
                @if($data['water_supports']['support_count'] != 0)
                    <td>عدد الطلبات</td>
                    <td>{{$data['water_supports']['support_count']}}</td>
                    <td>عدد العبوات المطلوبة</td>
                    <td>{{$data['water_supports']['needed_quantity']}}</td>
                    <td>عدد العبوات المسلمة</td>
                    <td>{{$data['water_supports']['delivered_quantity']}}</td>
                @else
                    <td colspan="6">لا يوجد إسناد مياه</td>
                @endif
            </tr>
        </table>
    </div>

    @if($loop->even && !$loop->last)
        <pagebreak/>
    @endif
@endforeach
