@foreach ($tickets as $ticket)
    <div class="">
        <h4 style="text-align: center;">{{'بلاغ #' . ++$loop->index}}</h4>
        <table class="body-table" style="width:100%">
            <tr>
                <th>التعريف</th>
                <th style="text-align:center">المعلومة</th>
            </tr>
            @if(!isset($monitor_reports))
                <tr>
                    <th>رمز المراقب</th>
                    <td style="text-align:center">{{$ticket->user->monitor->code ?? '-'}}</td>
                </tr>
            @endisset
            <tr>
                <th>رقم المركز - اسم متعهد الإعاشة</th>
                <td style="text-align:center">{{$ticket->order_sector->sector->label . ' - ' . $ticket->order_sector->order->facility->name}}</td>
            </tr>
            <tr>
                <th>تاريخ وقت إنشاء البلاغ</th>
                <td style="text-align:center">{{\Carbon\Carbon::parse($ticket->created_at)->format('h:i:sa | Y/m/d');}}</td>
            </tr>
            <tr>
                <th>مستوى الخطورة</th>
                <td style="text-align:center; background-color: {{$ticket->reason_danger->danger->color}}">{{$ticket->reason_danger->danger->level}}</td>
            </tr>
            <tr>
                <th>سبب البلاغ</th>
                <td style="text-align:center">{{$ticket->reason_danger->reason->name}}</td>
            </tr>
            <tr>
                <th>حالة البلاغ</th>
                <td style="text-align:center; background-color: {{$ticket->status->color}}">{{$ticket->status->name}}</td>
            </tr>
            @if(!isset($monitor_reports))
                <tr>
                    <th>المراقب</th>
                    <td>{{$ticket->user->name}}</td>
                </tr>
            @endisset
            <tr>
                <th>المشرف</th>
                <td>{{$ticket->order_sector->sector->supervisor_label}}</td>
            </tr>
            <tr>
                <th>القائد التشغيلي</th>
                <td>{{$ticket->order_sector->sector->boss_label}}</td>
            </tr>
            <tr>
                <th>مدير التشغيل الميداني</th>
                <td>{{$ticket->order_sector->sector->classification->organization->operational_manager_label}}</td>
            </tr>
            <tr>
                <th>تاريخ وقت إغلاق البلاغ</th>
                <td style="text-align:center">{{is_null($ticket->closed_at) ? trans('translation.not-closed-yet') : \Carbon\Carbon::parse($ticket->closed_at)->format('h:i:sa | Y/m/d')}}</td>
            </tr>
            <tr>
                <th>ملاحظات</th>
                @component('admin.export.components.note-td', ['model' => $ticket])@endcomponent
            </tr>
            <tr>
                <th>المرفقات</th>
                <td style="text-align:center">
                    @if ($ticket->attachments->isNotEmpty())
                        @component('admin.export.components.barcode-table', ['attachments' => $ticket->attachments, 'organization' => $organization_data])@endcomponent
                    @else
                        <div>
                            {{trans('translation.no-data')}}
                        </div>
                    @endif
                </td>
            </tr>
            
        </table>
    </div>
    @if (!$loop->last)
        <pagebreak />
    @endif
@endforeach
