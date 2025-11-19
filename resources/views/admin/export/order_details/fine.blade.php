@component('admin.export.pdf', ['data' => $data])
    @slot('content')
        @component('admin.export.order_details.facility-general-info', ['order_sector' => $data['order_sector'], 'organization' => $data['organization_data']])@endcomponent
        <pagebreak/>
        @if ($data['fines']->isNotEmpty())
            @foreach ($data['fines'] as $fine)
                <div class="">
                    <h4 style="text-align: center;padding-top: 5%">{{'مخالفة #' . ++$loop->index}}</h4>
                    <table class="body-table" style="width:100%">
                        <tr>
                            <th>التعريف</th>
                            <th style="text-align:center">المعلومة</th>
                        </tr>
                        <tr>
                            <th>رمز المراقب</th>
                            <td style="text-align:center">{{$fine->user->monitor->code}}</td>
                        </tr>
                        <tr>
                            <th>رقم المركز - اسم متعهد الإعاشة</th>
                            <td style="text-align:center">{{$data['order_sector']->sector->label . ' - ' . $data['order_sector']->order->facility->name}}</td>
                        </tr>
                        <tr>
                            <th>تاريخ وقت إنشاء المخالفة</th>
                            <td style="text-align:center">{{\Carbon\Carbon::parse($fine->created_at)->format('h:i:sa | Y/m/d')}}</td>
                        </tr>
                        <tr>
                            <th>تفاصيل المخالفة</th>
                            <td style="text-align:center">{{$fine->fine_organization->description}}</td>
                        </tr>
                        <tr>
                            <th>حالة المخالفة</th>
                            <td style="text-align:center; background-color: {{$fine->status->color}}">{{$fine->stauts->name}}</td>
                        </tr>
                        <tr>
                            <th>ملاحظات</th>
                            @component('admin.export.components.note-td', ['model' => $fine])@endcomponent
                        </tr>
                        <tr>
                            <th>المرفقات</th>
                            <td style="text-align:center">
                                @if ($fine->attachments->isNotEmpty())
                                    @component('admin.export.components.barcode-table', ['attachments' => $fine->attachments, 'organization' => $data['organization_data']])@endcomponent
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
        @else
        <div style="text-align:center; font-size:large; padding: 5rem">
            {{trans('translation.no-data')}}
        </div>
        @endif
    @endslot
@endcomponent
