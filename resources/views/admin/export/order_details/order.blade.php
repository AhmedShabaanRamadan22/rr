@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        <div class="">
            <h4 style="text-align: center;padding-top: 5%">معلومات عامة</h4>
            <table class="body-table" style="width:100%">

                <tr>
                    <th>#</th>
                    <th>التعريف</th>
                    <th style="text-align:center">المعلومة</th>
                </tr>
                <tr>
                    <th>1</th>
                    <td>رمز الطلب</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->code }}</td>
                </tr>
                <tr>
                    <th>2</th>
                    <td>حالة الطلب</td>
                    <td style="text-align:center" style="background: {{ $data['body_content']->status->color }}">
                        {{ $data['body_content']->status->name }}</td>
                </tr>
                <tr>
                    <th>3</th>
                    <td>مزود الخدمة</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->facility->name }}</td>
                </tr>
                <tr>
                    <th>4</th>
                    <td>مالك المنشأة</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->user->name }}</td>
                </tr>
                <tr>
                    <th>5</th>
                    <td>المنظمة</td>
                    <td style="text-align:center">
                        {{ $data['body_content']->organization_service->organization->name }}</td>
                </tr>
                <tr>
                    <th>6</th>
                    <td>ملاحظات</td>
                    @component('admin.export.components.note-td', ['model' => $data['body_content']])@endcomponent
                </tr>
                <tr>
                    <th>7</th>
                    <td>وقت إنشاء الطلب</td>
                    <td style="text-align:center">
                        {{ \Carbon\Carbon::parse($data['body_content']->created_at)->format('h:i:sa | Y/m/d') }}</td>
                </tr>

            </table>
        </div>
        {{-- <pagebreak /> --}}

        @component('admin.export.components.notes', [
            'notes' => $data['body_content']->notes,
        ])
        @endcomponent

        @if ($data['body_content']->interview_standard_orders->isNotEmpty())
            <pagebreak />
            <div class="">
                <h4 style="text-align: center;padding-top: 5%">المقابلة الشخصية</h4>
                <table class="body-table" style="width:100%">

                    <tr>
                        <th>#</th>
                        <th>معايير المقابلة الشخصية</th>
                        <th style="text-align:center">النقاط</th>
                    </tr>
                    
                    @forelse ($data['body_content']->interview_standard_orders as $interview_standard_order)
                    <tr>
                        <th>{{++$loop->index}}</th>
                        <td>{{$interview_standard_order->interview_standard->name}}</td>
                        <td style="text-align:center">{{$interview_standard_order->score}} / {{$interview_standard_order->max_score}}</td>
                    </tr>
                    @empty
                    
                    @endforelse
                    
                    <tr>
                        <th></th>
                        <td>نقاط اضافية</td>
                        <td style="text-align:center">{{$data['body_content']->bonus ?? 0}}</td>
                    </tr>
                </table>

            </div>
            <div class="">
                <h4 style="text-align: center">التقييم</h4>
                <table class="body-table" style="width:100%">
                    <tr>
                        <th>مجموع نقاط المقابلة قبل النقاط الاضافية</th>
                        <td style="text-align:center">{{ $data['body_content']->interview_total_score_before_bonus ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>مجموع نقاط المقابلة بعد النقاط الاضافية</th>
                        <td style="text-align:center">{{ $data['body_content']->interview_total_score_after_bonus ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        @endif
    @endslot
@endcomponent
