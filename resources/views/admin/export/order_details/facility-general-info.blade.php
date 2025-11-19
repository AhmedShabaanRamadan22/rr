@php
    $i = 1;
@endphp
<div class="">
    <h4 style="text-align: center">معلومات عامة</h4>
    <table class="body-table" style="width:100%">
        <tr>
            <th>#</th>
            <th>التعريف</th>
            <th>المعلومة</th>
        </tr>
        {{-- @dd($order_sector->toArray()) --}}
        @if(isset($order_sector) || isset($facility))
            <tr>
                <th>{{$i++}}</th>
                <td>المتعهد</td>
                <td>{{$order_sector?->order->facility->name ?? $facility->name}}</td>
            </tr>
            <tr>
                <th>{{$i++}}</th>
                <td>مالك المنشأة</td>
                <td>{{$order_sector?->order->facility->user->name ?? $facility->user->name}}</td>
            </tr>
        @endisset
        <tr>
            <th>{{$i++}}</th>
            <td>الشركة</td>
            <td>{{$organization->name ?? $organization->first()->name?? ''}}</td>
        </tr>
        @if(isset($order_sector) || isset($sector))
            <tr>
                <th>{{$i++}}</th>
                <td>رقم المركز</td>
                <td>{{$order_sector?->sector->label ?? $sector->label}}</td>
            </tr>
            <tr>
                <th>{{$i++}}</th>
                <td>عدد الحجاج</td>
                <td>{{$order_sector?->sector->guest_quantity ?? $sector->guest_quantity}}</td>
            </tr>
            <tr>
                <th>{{$i++}}</th>
                <td>جنسية الحجاج</td>
                <td>{{$order_sector?->sector->nationality_organization->nationality->name ?? $sector->nationality_organization->nationality->name}}</td>
            </tr>
            @endisset
        @isset($order_sector)
            <tr>
                <th>{{$i++}}</th>
                <td>مراقبي المركز</td>
                <td>{{ count($order_sector->monitors_label) != 0 ? implode(' - ', $order_sector->monitors_label) : trans('translation.no-data')}}</td>
            </tr>
        @endisset
        <tr>
            <th>{{$i++}}</th>
            <td>وقت إصدار التقرير</td>
            <td>{{\Carbon\Carbon::now()->format('h:i:sa | Y/m/d')}}</td>
        </tr>
        @isset($rows)
            @foreach ($rows as $key => $value)
                <tr>
                    <th>{{$i++}}</th>
                    <td>{{$key}}</td>
                    @if (is_array($value))
                        <td style="background-color: {{$value['color']}}">{{$value['value']}}</td>
                    @else
                        <td>{{$value}}</td>
                    @endif
                </tr>
            @endforeach
        @endisset
        @isset($model_notes)
            <tr>
                <th>{{$i++}}</th>
                <td>ملاحظات</td>
                @component('admin.export.components.note-td', ['model' => $model_notes])@endcomponent
            </tr>
        @endisset
    </table>
</div>