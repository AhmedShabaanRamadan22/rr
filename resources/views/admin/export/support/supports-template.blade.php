@foreach ($supports as $support)
    <div class="">
        <h4 style="text-align: center;">{{'إسناد #' . ++$loop->index}}</h4>
        <table class="body-table" style="width:100%">
            <tr>
                <th>التعريف</th>
                <th style="text-align:center">المعلومة</th>
            </tr>
            {{-- <tr>
                <th>رمز المراقب</th>
                <td style="text-align:center">{{$support->user->monitor->code ?? '-'}}</td>
            </tr> --}}
            <tr>
                <th>رقم المركز - اسم متعهد الإعاشة</th>
                <td style="text-align:center">{{$support->order_sector->sector->label . ' - ' . $support->order_sector->order->facility->name}}</td>
            </tr>
            <tr>
                <th>تاريخ وقت إنشاء الإسناد</th>
                <td style="text-align:center">{{\Carbon\Carbon::parse($support->created_at)->format('h:i:sa | Y/m/d');}}</td>
            </tr>
            <tr>
                <th>تاريخ وقت إغلاق الإسناد</th>
                <td style="text-align:center">{{\Carbon\Carbon::parse($support->updated_at)->format('h:i:sa | Y/m/d');}}</td>
            </tr>
            @if (!isset($monitor_report))
            <tr>
                <th>المراقب</th>
                <td>{{$support->creator_label}}</td>
            </tr>
            @endif
            <tr>
                <th>المشرف</th>
                <td>{{$support->order_sector->sector->supervisor_label}}</td>
            </tr>
            <tr>
                <th>القائد التشغيلي</th>
                <td>{{$support->order_sector->sector->boss_label}}</td>
            </tr>
            <tr>
                <th>مدير التشغيل الميداني</th>
                <td>{{$support->order_sector->sector->classification->organization->operational_manager_name}}</td>
            </tr>
            <tr>
                <th>الفترة - نوع الإسناد</th>
                <td style="text-align:center">{{$support->period->name . ' - ' . ($support->type == 2 ? 'وجبات' : 'ماء')}}</td>
            </tr>
            <tr>
                <th>سبب طلب الإسناد</th>
                <td style="text-align:center; background-color: {{$support->reason_danger->danger->color}}">{{$support->reason_danger->reason->name}}</td>
            </tr>
            <tr>
                <th>الكمية المطلوبة</th>
                <td style="text-align:center">{{$support->quantity}}</td>
            </tr>
            <tr>
                <th>الكمية المسلمة</th>
                <td style="text-align:center">{{$support->delivered_quantity}}</td>
            </tr>
            <tr>
                <th>حالة الإسناد</th>
                <td style="text-align:center; background-color: {{$support->status->color}}">{{$support->status->name}}</td>
            </tr>
            <tr>
                <th>تم الاكتفاء بـ</th>
                <td style="text-align:center">{{$support->has_enough_quantity ?? trans('translation.no-data')}}</td>
            </tr>
            <tr>
                <th>الدعم</th>
                <td>
                    @if ($support->assists->isNotEmpty())
                        <div style="text-align:start">
                            <table style="width: 100%" class="assist-table">
                            @foreach ($support->assists as $index => $assist)
                                    <tr>
                                        <td>
                                            <p>{{++$loop->index}} - 
                                                <span style="color: #999">مُسند من قِبل: </span>{{$assist->assist_from}}, 
                                                <span style="color: #999">الكمية: </span>{{$assist->quantity}},
                                                <span style="color: #999">الحالة: </span>{{$assist->status->name}},
                                                <span style="color: #999">تاريخ الدعم: </span>{{\Carbon\Carbon::parse($assist->created_at)->format('h:i:sa | Y/m/d');}}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <div style="text-align:center">
                            {{trans('translation.no-data')}}
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <th>ملاحظات</th>
                @component('admin.export.components.note-td', ['model' => $support])@endcomponent
            </tr>
            <tr>
                <th>المرفقات</th>
                <td style="text-align:center">
                    @if ($support->attachments->isNotEmpty())
                        @component('admin.export.components.barcode-table', ['attachments' => $support->attachments, 'organization' => $organization])@endcomponent
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
